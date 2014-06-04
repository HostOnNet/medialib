<?php

class TestController extends BaseController
{
    public function test()
    {
        Backup::db(1);
        return "hello, i am done";
    }


}