<?php

class AjaxController extends BaseController
{
	public function like($media_id)
    {
        $allow_unlimited_likes = 1;

        $time_now = time();
        $time_past = $time_now - 1800;

        $media_info = Media::find($media_id);

        if (empty($media_info)) {
            return 'No Media';
        }

        $likes = $media_info->likes;

        DB::table('logs')->where('log_time', '<', $time_now - 86400 )->delete();

        if ($allow_unlimited_likes == 0)
        {
            $records = DB::table('logs')->where('log_type', '=', 'likes')->where('log_mid','=', $media_id)->where('log_time','>', $time_past)->first();
        }
        else
        {
            $records = '';
        }

        if (empty($records))
        {
            $records = DB::table('logs')->insert(array('log_mid' => $media_id, 'log_time' => $time_now, 'log_type' => 'likes' ));
            DB::query('update medias SET likes=likes+1 where id=?', $media_id);
            $likes ++;
            return "Like (" . $likes . ")+";
        }
        else
        {
            return "Like (" . $likes . ")=";
        }
	}

	public function tag_suggest()
    {
		$query = Input::get('query','no_query_string_entered');
		$tags = DB::table('tags')->where('tag','LIKE', $query . '%')->where('tag_count','>','1')->get();
		$tag_array_from_db = array();

		if (!empty($tags))
        {
			foreach($tags as $tag)
            {
				$tag_array_from_db[] = $tag->tag;
			}
		}

		$jason_object = array();
		$jason_object['query'] = $query;
		$jason_object['suggestions'] = $tag_array_from_db;
		return json_encode($jason_object);
	}

    public function media_tag_time_like()
    {
        $media_id = Input::get('media_id');
        $time_start = Input::get('time_start');
        $like = Input::get('like');

        $record = DB::table('media_tag_time')->where('media_id','=',$media_id)->where('time_start','=',$time_start)->get();

        if ($record)
        {
            if ($like == 1)
            {
                DB::table('media_tag_time')->where('media_id','=',$media_id)->where('time_start','=',$time_start)->increment('likes');
            }
            else
            {
                DB::table('media_tag_time')->where('media_id','=',$media_id)->where('time_start','=',$time_start)->decrement('likes');
            }
        }
        else
        {
           DB::table('media_tag_time')->insert(array('media_id' => $media_id, 'time_start' => $time_start, 'likes' => 1));
        }

        if ($like == 1)
        {
            DB::table('medias')->where('id','=',$media_id)->increment('likes');
        }
        else
        {
            DB::table('medias')->where('id','=',$media_id)->decrement('likes');
        }

        // vote for this medias tag.

        $tags = Tags::getTagByTime($media_id, $time_start);

        if ($tags)
        {
            $tag_array = array();

            if (strpos($tags, ',') !== false)
            {
                $tag_array = explode(',', $tags);
            }
            else
            {
                $tag_array[] = $tags;
            }

            foreach ($tag_array as $tag)
            {
                $tag = trim($tag);
                $tag_id = Tags::getId($tag);

                if ($tag_id)
                {
                    if ($like == 1)
                    {
                        DB::table('tag_media')->where('media_id','=',$media_id)->where('tag_id','=',$tag_id)->increment('likes');
                    }
                    else
                    {
                        DB::table('tag_media')->where('media_id','=',$media_id)->where('tag_id','=',$tag_id)->decrement('likes');
                    }

                }
            }
        }

        return "OK";
    }
}