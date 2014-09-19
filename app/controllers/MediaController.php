<?php

class MediaController extends BaseController {

	public function listMedia() {
        $sort_by = Input::get('sort_by');
		$per_page = 20;
        if (empty($sort_by)) {
            $sort_by = Settings::get('sort_by');
        } else {
            Settings::put('sort_by',$sort_by);
        }
        if ($sort_by == 'view_time') {
            $media_list = Media::orderBy('view_time', 'desc')->paginate($per_page);
        } else if ($sort_by == 'views') {
            $media_list = Media::orderBy('views', 'desc')->paginate($per_page);
        } else if ($sort_by == 'likes') {
            $media_list = Media::orderBy('likes', 'desc')->paginate($per_page);
        } else if ($sort_by == 'id') {
            $media_list = Media::orderBy('id', 'desc')->paginate($per_page);
        }
        $pagination_links = $media_list->appends(array('sort_by' => $sort_by))->links();
		$data = array('media_list' => $media_list, 'page_links' => $pagination_links );
		$this->layout->title = "Watch";
		$this->layout->nest('content','media.list',$data);
	}

    function save()
    {
        $media_id = (int) Input::get('media_id');
        $description = Input::get('description');

        $volume = (int) Input::get('volume');
        $media_info = Media::find($media_id);

        if (empty($media_info)) {
            die('Media not found with id = ' + $media_info->id);
        }

        if (($volume > 20) && ($volume != $media_info->volume) ) {
            Media::where('id', '=', $media_id)->update(['volume' => $volume]);
        }

        $redirect_back = 0;

        if ($description != null) {

            $description = trim($description);

            // update description

            $len_des_org = strlen($media_info->description);
            $len_des_new = strlen($description);

            if ( ($len_des_org * 0.80) > $len_des_new) {
                die('new description is smaller. original = ' . $len_des_org . ' new =' . $len_des_new);
            }

            $description = Tags::sort_bookmark($description);
            $description_original = $media_info->description;

            if ($description_original != $description) {
                Tags::del($description_original, $media_id);
                Tags::add($description, $media_id);
                Media::where('id', '=', $media_id)->update(array('description' => "$description"));
                $redirect_back = 1;
            }
        }

        // if not edit, update views

        if (! $redirect_back) {
            $dataUpdate = array();
            $dataUpdate['view_time'] = time();
            $dataUpdate['views'] = $media_info->views + 1;
            $dataUpdate['view_again'] = Media::getViewAgain($media_info->views, $media_info->likes);
            Media::where('id', '=', $media_id)->update($dataUpdate);
        }

        if ($redirect_back) {
            $url_redirect =  '/watch/' . $media_id . '/' . $_POST['ref_page'];
        } else {
            if (strpos($_POST['ref_page'],'-')) {
                $ref_page_parts = explode('-',$_POST['ref_page']);
                $action_name = $ref_page_parts[0];
                $action_value = $ref_page_parts[1];

                if ($action_name == 'media') {
                    $url_redirect =  '/media?page=' . $action_value;
                } else if ($action_name == 'search') {
                    $url_redirect =  '/';
                } else if ($action_name == 'tag') {
                    $url_redirect =  '/tag/' . $action_value;
                } else if ($action_name == 'playlist') {
                    if (strpos($action_value, 'x')) {
                        $playlist_parts = explode('x', $action_value);
                        $playlist_id = $playlist_parts[0];
                        $pm_id = $playlist_parts[1];
                    } else {
                        $playlist_id = $action_value;
                        $pm_id = 0;
                    }
                    Playlist::deleteFromPlaylist($media_id,$playlist_id, $pm_id);
                    $url_redirect =  '/playlist/watch/' . $playlist_id;
                }
            } else {
                dd($_POST['ref_page']);
            }
        }
        Backup::db();
        return Redirect::to($url_redirect);
    }

    function info($media_id) {
       $media_info = DB::table('medias')->find($media_id);
       $description = $media_info->description;
       if (Actor::haveName($description)) {
            $name = Actor::getName($description);
            echo "<a href='/tag/" . $name . "'>" . $name . "</a>";
            $name_en = Actor::getNameEn($name);
            $name_jp = Actor::getNameJp($name);
            echo Actor::getLinks($name_en);
            echo Actor::getLinks($name_jp);
       } else {
            echo 'Performer name not found';
       }
       return '';
    }
}
