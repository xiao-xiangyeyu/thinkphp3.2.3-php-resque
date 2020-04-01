<?php
namespace Common\Job;
class TestJob
{
    public function perform()
    {
        fwrite(STDOUT,json_encode($this->args)."\n");
    }



}