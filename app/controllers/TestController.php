<?php

class TestController extends BaseController
{
    public function test()
    {
        $media_id = 580;
        $tag = 'breast';

        Tags::updateTagMediaLikesPerTag($media_id, $tag);
        return "updated";
    }


}