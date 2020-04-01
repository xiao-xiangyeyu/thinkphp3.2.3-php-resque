# 启动redis服务
windows下运行 redis-server.exe

# 启动队列
# --queue=default 启动队列的名称  default(队列名称)
# --debug=1  啰嗦模式启动,会打印详细调试信息
# --interval=2 在队列中循环的间隔时间，即完成一个任务后的等待时间，默认是5秒
# --count=5 需要创建的Worker的进程数量
# --pid=/tmp/resque.pid 手动指定PID文件的位置，适用于单Worker运行方式

php resque start --queue=default --debug=1 --interval=2 --count=5


# 浏览器访问模拟app请求入队
	http://localhost/thinkphp-queue/index.php/home/Index/Joinbook
	
# 浏览器模拟轮询结果
	http://localhost/thinkphp-queue/index.php/home/Index/Joinbook/JoinStatus/jobid/e0f04586abe1eef6f4b00b2d36c79958
