<?php

class TagController extends BaseController {

	public function index()
    {
		$tags = DB::table('tags')->orderBy('tag')->where('tag_count','>',1)->get();
		$max = DB::table('tags')->where('tag','!=','todo')->orderBy('tag_count','desc')->first();
		$max_tag_count = $max->tag_count;
		$data = array('tags' => $tags, 'max_tag_count'=>$max_tag_count);
        return View::make('tag.index', $data);
	}

	public function search()
    {
		$tag_id = Route::input('tag_id');
		$tag = Tags::getTagById($tag_id);

        if ($tag == 'todo') {
            $media_list = DB::select('SELECT MA.id, MA.description FROM tag_media AS TM, medias AS MA WHERE TM.tag_id=? AND MA.id=TM.media_id ORDER BY MA.id ASC', array($tag_id) );
        } else {
            $media_list = DB::select('SELECT MA.id, MA.description FROM tag_media AS TM, medias AS MA WHERE TM.tag_id=? AND MA.id=TM.media_id ORDER BY TM.likes_per_tag DESC', array($tag_id) );
        }

		$data = array('media_list' => $media_list, 'tag_id' => $tag_id, 'tag'=>$tag);

		if (empty($media_list)) {
			return View::make('tag.search_empty', $data);
		}

        return View::make('tag.search', $data);
	}

	public function rebuild() {

        ini_set('max_execution_time', 60000);

		DB::statement('drop table IF EXISTS `tags`');
		DB::statement('drop table IF EXISTS `tag_media`');
		DB::statement('CREATE TABLE `tags` (  `id` int(11) UNSIGNED NOT NULL auto_increment PRIMARY KEY, `tag` varchar(255) NOT NULL, `tag_count` int(11) NOT NULL default \'0\', UNIQUE KEY(tag)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
        DB::statement('CREATE TABLE `tag_media` ( `id` int(11) NOT NULL auto_increment, `tag_id` int(11) NOT NULL,`media_id` int(11) NOT NULL, `likes` int(11) NOT NULL DEFAULT \'0\', `likes_per_tag` int(11) NOT NULL DEFAULT \'0\', PRIMARY KEY  (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

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
        return View::make('tag.rebuild', $data);
	}

	public function watch() {
		$tag_name  = Input::get('tag_name');
        $num_media  = Input::get('num_media');
		$playlist_id = Playlist::getId($tag_name);
		$tag_id = Tags::getId($tag_name);

		$media_list = DB::select("SELECT * FROM tag_media AS TM, medias AS MA WHERE TM.tag_id=? AND MA.id=TM.media_id ORDER BY TM.likes_per_tag DESC LIMIT ?", array($tag_id, $num_media));

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
