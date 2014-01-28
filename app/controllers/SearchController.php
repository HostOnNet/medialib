<?php

class SearchController extends BaseController
{
    public function search()
    {
        $media_list    = array();
        $is_search_result = 0;

        if(isset($_POST['submit']))
        {
            $is_search_result = 1;

            $search_string = Input::get('search_string');

            Cookie::forever('search_string', $search_string);


            if (strpos($search_string, 'file:') !== false)
            {
                $search_string_file = str_replace('file:', '', $search_string);
                $media_list = DB::table('medias')->where('file_name','LIKE',"%$search_string_file%")->get();
            }
            else
            {
                $order_by = Input::get('order_by');
                $sql_extra = '';

                switch ($order_by) {
                    case 'likes':
                        $sql_extra = ' ORDER BY likes DESC ';
                        break;
                    case 'random':
                        $sql_extra = ' ORDER BY RAND() ';
                        break;
                    default:
                        break;
                }

               if ( strpos($search_string , '+') === false && empty($sql_extra) ) {
                    $media_list = DB::select("select * from medias WHERE MATCH(description) AGAINST(?)", array( $search_string ) );
               } else {
                    $media_list = DB::select("select * from medias WHERE MATCH(description) AGAINST(? in boolean mode) $sql_extra", array($search_string) );
               }
            }

        }

        // add to watch table

        $search_action = Input::get('search_action');

        if (!empty($search_action)) {

            $playlist_id = Playlist::getId('search');

            if (!$playlist_id) {
                Playlist::add('search');
            }

            if ($search_action == 'add')
            {
                Playlist::emptyById($playlist_id);
            }

            foreach ($media_list as $row)
            {
                $media_id = $row->id;
                Playlist::addToPlaylist($media_id,$playlist_id);
            }

            $url = '/playlist/watch/' . $playlist_id;
            return Redirect::to($url);
        }

        $this->layout->title = 'Search';
        $this->layout->nest('content','search.search',array('media_list'=>$media_list, 'is_search_result' => "$is_search_result"));
    }
}