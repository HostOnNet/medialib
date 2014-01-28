<?php

class Tags
{

	// this function is to  convert
	// time = keyword1, time2=keyword2 format to
	// keyword1, keyword2, etc...
	// to disply in media listing pages

	public static function get_keywords($description, $limit=1000)
	{
		$keywords = implode(',', Tags::get_keywords_array($description));

		if ($limit != 1000)
		{
			$keywords = substr($keywords, 0,$limit);
		}
		return $keywords;
	}

	// provdue array of unique keywords from string
	// time = keyword1 | time2=keyword2 | time3=kw3,kw6

	public static function get_keywords_array($description)
	{
		$bookmarks = explode('|',$description);

		$keywords = '';

		foreach ($bookmarks as $bookmark)
		{
			$bookmark_parts = explode('=',$bookmark);
			if (isset($bookmark_parts[1])) $keywords .= trim($bookmark_parts[1]) . ',';
		}

		$kw_array= explode(',',$keywords);
		$kw_array = array_unique($kw_array);

		return $kw_array;
	}


	// used in watch for bookmark

	public static function kw_short($keywords)
	{
		$kw_short_strings = Settings::get('kw_short_strings');
		$kw_short_strings_arr = preg_split("/\n/",$kw_short_strings);

		$string_src_array = array();
		$string_trans_array = array();

		foreach ($kw_short_strings_arr as $kw_short_strings_pair)
		{
			$found = strpos($kw_short_strings_pair,'=');

			if ($found)
			{
				$kw_str_kv = explode('=',$kw_short_strings_pair);
				$kw_str_s1 = trim($kw_str_kv[0]);
				$kw_str_s2 = trim($kw_str_kv[1]);

				if (!empty($kw_str_s1) && !empty($kw_str_s2))
				{
					$string_src_array[] = $kw_str_s1;
					$string_trans_array[] = $kw_str_s2;
				}

			}
		}

		$keywords_transformed = str_replace($string_src_array, $string_trans_array, $keywords);

		return $keywords_transformed;
	}

    public static function add($description, $media_id)
    {
		$keywords = Tags::get_keywords_array($description);

		foreach ($keywords as $tag)
		{
			$tag = trim($tag);

			if (!Tags::is_valid_tag($tag)) continue;

			$tag_info = DB::table('tags')->where('tag','=',$tag)->first();

			// tag not found
			if (empty($tag_info))
			{
				$tag_id = DB::table('tags')->insertGetId(array('tag'=>$tag, 'tag_count'=>0));
			}
			else
			{
				$tag_id = $tag_info->id;
			}

			$tag_found = DB::select('select * from tag_media WHERE tag_id=? AND media_id=?',array($tag_id, $media_id));
			if (empty($tag_found))
			{

				DB::update('update tags set tag_count=tag_count+1 WHERE tag=?',array($tag));
				DB::table('tag_media')->insert(array('tag_id' => $tag_id, 'media_id' => $media_id ));
			}
		}

        // update likes

        $description_array = explode('|', $description);

        foreach ($description_array as $bookmark)
        {


            $bookmark_array = explode('=', $bookmark);

            if (empty($bookmark_array))
            {
                dd('Fix description for media ' . $media_id);
            }


            $bookmark_time = trim($bookmark_array[0]);
            $bookmark_tags = trim($bookmark_array[1]);

            if (strlen($bookmark_tags) > 2)
            {
                $time_liked = DB::table('media_tag_time')->where('media_id','=',$media_id)->where('time_start','=',$bookmark_time)->first();

                if ($time_liked)
                {
                    $tag_array = array();

                    if (strpos($bookmark_tags, ',') !== false)
                    {
                        $tag_array = explode(',', $bookmark_tags);
                    }
                    else
                    {
                        $tag_array[] = $bookmark_tags;
                    }

                    foreach ($tag_array as $tag)
                    {
                        $tag = trim($tag);
                        $tag_id = Tags::getId($tag);

                        if ($tag_id)
                        {
                            DB::table('tag_media')->where('media_id','=',$media_id)->where('tag_id','=',$tag_id)->increment('likes', $time_liked->likes );
                        }
                    }

                }
            }

        }

    }

	// delete tags for a video

	public static function del($description, $media_id)
	{
		$keywords = Tags::get_keywords_array($description);

		foreach ($keywords as $tag)
		{
			$tag = trim($tag);

			if (!Tags::is_valid_tag($tag)) continue;

			$tag_info = DB::table('tags')->where('tag','=',$tag)->first();

			if (!empty($tag_info))
			{
				$tag_id = $tag_info->id;
				DB::delete('delete from tag_media WHERE tag_id=? AND media_id=?',array($tag_id,$media_id));
				DB::update('update tags set tag_count=tag_count-1 WHERE tag=?',array($tag));
			}
		}
	}

	// get id of tags by name

	public static function getId($tag)
	{
		$tag_info = DB::table('tags')->where('tag','=',$tag)->first();
		if (empty($tag_info)) return false;
		return $tag_info->id;
	}

	// if tag is ignored

	public static function is_valid_tag($tag)
	{
		if ( empty($tag) )
		{
			return false;
		}
		else if ($tag == '1' || $tag == '10')
		{
			return false;
		}

		return true;

	}

	// sort bookmark by time

	public static function sort_bookmark($description)
	{
		$bookmarks_str = explode('|',$description);

		$s_array = array();

		foreach($bookmarks_str as $keyval_str)
		{
			if (strpos($keyval_str, '=') !== false)
            {
				$keyval_array = explode('=',$keyval_str);
				$key = trim($keyval_array[0]);
				$value = trim($keyval_array[1]);

                if (strpos($value, ':') !== false)
                {
				   dd($keyval_str);
				}

                if (isset($s_array[$key]))
                {
                    $s_array[$key] .= ', ' . $value;
                }
                else
                {
                    $s_array[$key] = $value;
                }
			}
		}

		ksort($s_array);

		$bookmark_final = '';

		foreach ($s_array as $key => $val)
        {
            $key = trim($key);
            $val = trim($val);
			$bookmark_final .= "$key = $val | ";
		}

		$bookmark_final = trim($bookmark_final);
		$bookmark_final = preg_replace('/\|$/','',$bookmark_final);
		return trim($bookmark_final);
	}

	/*
	 Used in watch page, bookmark links below player
	 Generate clickable bookmark links from description
	*/

	public static function get_bookmarks($description, $media_id = 0, $time_start = '')
	{
		$description = Tags::sort_bookmark($description);
		$bookmarks = explode('|',$description);
		$bookmark_links = '';

		foreach ($bookmarks as $bookmark)
		{
			$bookmark = trim($bookmark);

			if (strlen($bookmark) > 4)
			{
				$bookmark_str_val = explode('=',$bookmark);

				$bookmark_time = trim($bookmark_str_val[0]);
				if ($bookmark_time == "00:00:00") continue;

				$bookmark_name = '';

				if (isset($bookmark_str_val[1]))
				{
					$bookmark_name = trim($bookmark_str_val[1]);
				}

				if ($bookmark_name == '')
				{
					$bookmark_name = $bookmark_time;
				}

                $record = DB::table('media_tag_time')->where('media_id','=',$media_id)->where('time_start','=',$bookmark_time)->get(array('likes'));

                $likes = 0;

                if ($record)
                {
                    $likes = $record[0]->likes;
                }

                if ($likes < 1)
                {
                    $css_class = 'bookmark bookmark_0';
                }
                else if ($likes < 5)
                {
                    $css_class = 'bookmark bookmark_5';
                }
                else if ($likes < 10)
                {
                    $css_class = 'bookmark bookmark_10';
                }
                else
                {
                    $css_class = 'bookmark bookmark_15';
                }

                if ($likes > 0)
                {
                     $likes_name = ' (' . $likes . ')';
                }
                else
                {
                    $likes_name = '';
                }

                if ( $time_start == $bookmark_time)
                {
                    $css_class .= ' bookmark_auto';
                }

                $bookmark_links .=  "<a class='$css_class' href='#' alt=\"$bookmark_time\">$bookmark_name$likes_name</a> &nbsp; <img class='fav' src='/img/fav.png' alt=\"$bookmark_time\"> &nbsp;";
    		}
		}

		return $bookmark_links;
	}

	public static function getTagTime($description, $tag, $media_id)
    {
		$bookmarks_array = explode('|',$description);

        $valid_times = array();

		foreach($bookmarks_array as $bookmark)
        {
			$keyval_array = explode('=',$bookmark);
			$time = trim($keyval_array[0]);
			$keywords = $keyval_array[1];

			if (strpos($keywords, $tag) !== false)
            {
                $valid_times[] = $time;
			}
		}

        if (empty($valid_times))
        {
            return false;
        }

        $best_time = '00:00:00';
        $best_rate = -1;

        foreach ($valid_times as $my_time)
        {
            $record = DB::table('media_tag_time')->where('media_id','=',$media_id)->where('time_start','=',$my_time)->get(array('likes'));

            $likes = 0;

            if ($record)
            {
                $likes = $record[0]->likes;
            }

            if ($likes > $best_rate)
            {
                $best_rate = $likes;
                $best_time = $my_time;
            }
        }

        return $best_time;
	}

    public static function getTagByTime($media_id, $time_to_find)
    {
        $time_to_find = trim($time_to_find);
        $media = Media::find($media_id);
        $description = $media->description;

        $bookmarks_array = explode('|',$description);

        foreach($bookmarks_array as $bookmark)
        {
            $keyval_array = explode('=',$bookmark);

            if (trim($keyval_array[0]) == $time_to_find)
            {
                return $keyval_array[1];
            }
        }

        return false;
    }

    public static function getPerformer($tags) {
        if (strpos($tags, ']') !== false ) {
            $pattern = '/[=|,]([^,]*)\[(.*)\]/';
            if (preg_match($pattern, $tags, $match)) {
                if (isset($match[2])) {
                    $performer_name_en = trim($match[1]);
                    $performer_name_jp = urlencode(trim($match[2]));

                    $meta_link =  trim(Settings::get('meta_links'));


                    if (!empty( $meta_link)) {
                        $meta_link_parts = explode("|", $meta_link);
                        $link =  $meta_link_parts[0] . $performer_name_jp;
                        return  "<a class=bookmark2 href='$link'>x</a> &nbsp; ";
                    }

                }
            }
        }
    }


}