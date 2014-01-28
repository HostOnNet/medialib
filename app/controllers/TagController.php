<?php

class TagController extends BaseController {

	public function index() {
		$tags = DB::table('tags')->orderBy('tag')->where('tag_count','>',1)->get();
		$max = DB::table('tags')->where('tag','!=','todo')->orderBy('tag_count','desc')->first();
		$max_tag_count = $max->tag_count;
		$data = array('tags' => $tags, 'max_tag_count'=>$max_tag_count);
		$this->layout->title = "Tag Cloud";
		$this->layout->nest('content', 'tag.index', $data);
	}

	public function search()
    {
		$tag = Route::input('tag_name');
		$tag_id = Tags::getId($tag);

        if ($tag == 'todo')
        {
            $media_list = DB::select('SELECT * FROM tag_media AS TM, medias AS MA WHERE TM.tag_id=? AND MA.id=TM.media_id ORDER BY MA.id ASC', array($tag_id) );
        }
        else
        {
            $media_list = DB::select('SELECT * FROM tag_media AS TM, medias AS MA WHERE TM.tag_id=? AND MA.id=TM.media_id ORDER BY TM.likes DESC', array($tag_id) );
        }

		$data = array('media_list' => $media_list, 'tag_id' => $tag_id, 'tag'=>$tag);

		if (empty($media_list)) {
			$this->layout->title = "Tag Videos";
			$this->layout->nest('content', 'tag.search_empty', $data);
			return;
		}

		$this->layout->title = "Tag Videos";
		$this->layout->nest('content', 'tag.search', $data);
	}

	public function rebuild() {
		DB::statement('drop table IF EXISTS `tags`');
		DB::statement('drop table IF EXISTS `tag_media`');
		DB::statement('CREATE TABLE `tags` (  `id` int(11) UNSIGNED NOT NULL auto_increment PRIMARY KEY, `tag` varchar(255) NOT NULL, `tag_count` int(11) NOT NULL default \'0\', UNIQUE KEY(tag)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
		DB::statement('CREATE TABLE `tag_media` ( `id` int(11) NOT NULL auto_increment, `tag_id` int(11) NOT NULL,`media_id` int(11) NOT NULL, `likes` int(11) NOT NULL DEFAULT \'0\', PRIMARY KEY  (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		$media_all = Media::get();
		$log = '';

		foreach ($media_all as $media) {
			$media_id = $media->id;
			$description = trim($media->description);
			if (empty($description)) continue;

			$log .= "Adding media id = $media_id, kw= $description<br>";
			Tags::add($description, $media_id);
		}

		$data = array('log'=>$log);
		$this->layout->title = "Rebuild Tags";
		$this->layout->nest('content', 'tag.rebuild', $data);
	}

	public function watch() {
		$tag_name  = Input::get('tag_name');
		$num_media = Input::get('num_media');
		$order_by = Input::get('order_by');
		$playlist_id = Playlist::getId($tag_name);

		$tag_id = Tags::getId($tag_name);

		$sql_extra = '';

		switch ($order_by)
        {
			case 'random':
				$sql_extra = ' ORDER BY RAND() ';
				break;
			case 'likes':
				$sql_extra = ' ORDER BY MA.likes DESC ';
				break;
            case 'tag_likes':
                $sql_extra = ' ORDER BY TM.likes DESC ';
                break;
			default:
				dd($order_by);
				break;
		}

		if ($num_media > 0) {
			$sql_extra = $sql_extra . ' LIMIT ' . $num_media;
		}

		$media_list = DB::select("SELECT * FROM tag_media AS TM, medias AS MA WHERE TM.tag_id=? AND MA.id=TM.media_id $sql_extra", array($tag_id));

		if (!$playlist_id) {
			$playlist_id = Playlist::add($tag_name);
		}

		if(!$playlist_id) {
			die('Playlist creation failed');
		}

        Playlist::emptyById($playlist_id);

		foreach ($media_list as $media) {
			Playlist::addToPlaylist($media->id, $playlist_id);
		}

		$url = '/playlist/' . $playlist_id;
        return Redirect::to($url);
	}
}