<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {
   	/**
   	 * [Joinbook 请求约车接口]
   	 *  1.客户端APP发送业务请求
   	 *  2.该接口简单校验加入任务队列
   	 *  3.入队成功返回入队成功标识 jobId
   	 */
    public function Joinbook()
    {
        $rid = I('rid',32); // 业务逻辑参数
        $subject = I('subject',3); // 业务逻辑参数
        $student_id = I('student_id',1); // 业务逻辑参数
        try{
            if(empty($rid)|| empty($subject))
                return $this->_return(0,"参数有误");
            $args = array(
                'student_id'=>$student_id , 
                'rid'=>$rid ,
                'subject'=>$subject,
                'date'=>date('Y-m-d')
            );
            // 缓存key
            $key = "requse".md5(json_encode($args));
            $yueyue = S($key);
            if($yueyue){
            	$this->ajaxReturn(['排队中,请稍后']);
            }
            $jobId = \Resque::enqueue('default', \Common\Job\Job::Booktrain, $args, true);
            // 入队成功标识,客户端使用此标识定时请求,队列状态查询接口
            $args['jobId'] = $jobId;
            S($key,$args ,60); // 60秒内禁止重复请求
            $this->ajaxReturn(['msg'=>'入队,预约成功','data'=>$args]);
        }catch (\Exception $e){
            $this->ajaxReturn(['异常']);
        }
    }

    //队列状态查询接口
    public function JoinStatus()
    {
        $jobid = I("jobid");
        $status = new \Resque\Job\Status($jobid);
        //执行完成告诉用户是否成功
        if (!$status->isTracking()) {
            $this->_return(0,"不存在的排队");
        }else{
        	// 缓存key
            $jobid = "Applet\Controller\V2\BooktrainControllerJoinbook_job".$jobid;
            $info = S($jobid);
            //队列没执行
            if(!$info){
                $info = [];
                $info['msg'] = "等待中...";
                $info['status'] =   100; // 收到该结果 前端继续轮询,一般限制次数轮询
                $this->ajaxReturn($info);
            }
            $this->ajaxReturn($info);  
        }
    }
}