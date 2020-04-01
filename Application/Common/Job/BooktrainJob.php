<?php
namespace Common\Job;
class BooktrainJob
{
    public function perform()
    {
        $args = $this->args;
        $rid = $args['rid'];
        $subject = $args['subject'];
        $args = array(
            'student_id'=>$args['student_id'] , 
            'rid'=>$rid ,
            'subject'=>$subject
        );
        // 数据库业务逻辑处理-start
        // $deal_info = D('User')->deal($args);
        $deal_info=[];
        $status =$deal_info['status']?:'200';
        $msg =$deal_info['msg']?:'预约成功';
        // 数据库业务逻辑处理-end
        $margs = array(
            'student_id'=>$args['student_id'] , 
            'rid'=>$args['rid'] ,
            'subject'=>$args['subject'],
            'date'=>date('Y-m-d')
        );
        // 终端打印参数
        fwrite(STDOUT,json_encode($args)."\n");

        // 获取当前业务的缓存参数,app请求入队时写入
        $key = "requse".md5(json_encode($margs)); 
        $result = S($key);

        //将业务逻辑处理结果
        $result["status"] = $status;
        $result["msg"] = $msg;

        // 将参数及约车结果放入缓存
        S($key,$result ,1200); 
        
        // 将约车的结果以工作任务标识 jobID为key放入缓存,等待客户端轮询获取
        $jobid = "Applet\Controller\V2\BooktrainControllerJoinbook_job".$result['jobId'];
        S($jobid,$result ,300);
    }

}