<?php
require_once './KalonDaemon.php'; 
declare(ticks = 1); 
$toDo = $_SERVER['argv'][1]; 
$daemonConf = array('pidFileName' => 'mydaemon.pid', 
                    'verbose'     => true); 
$stopFlag = 0;

function stopHandler()
{
    global $stopFlag;
    $stopFlag = 1;  
    return;
}


try {
    $daemon = new KalonDaemon($daemonConf);
    if ($toDo == 'start') {
        $daemon->setGracefulStopHandler('stopHandler');
        
        $daemon->start();
        
        $i = 0;
        $badVideos = array();
        for (;;) {
            echo "Stop flag is $stopFlag\n";
            //如果设置了结束标签则退出循环
            if ($stopFlag) {
                break;
            }
            sleep(5);
        }
    } elseif ($toDo == 'stop') {
        $daemon->stop();// graceful stop , call stopHandler()
        //$daemon->stop(true);//force stop, kill -9
    } else {
        echo "Unknow action.";
        exit;
    }  
} catch (KalonDaemonException $e) { 
    echo $e->getMessage(); 
    echo "/n"; 
} 
?>