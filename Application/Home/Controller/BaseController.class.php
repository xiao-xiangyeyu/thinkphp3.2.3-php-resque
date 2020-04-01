<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
     public function __construct()
    {
        $this->initQueue();
        parent::__construct();
    }


    protected function initQueue(){
        $config = C('QUEUE');
        vendor('php-resque.autoload');
        \Resque::setBackend(['redis' => $config], 1);
        \Resque\Redis::prefix($config['prefix']);
    }
}