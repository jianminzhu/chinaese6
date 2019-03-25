<?php
/* PHP中如何增加一个系统用户
下面是一段例程，增加一个名字为james的用户,
root密码是 verygood。仅供参考
*/

function getFilesFromFolder($dir,$exceptFolders,$exceptFiles){
    foreach ($exceptFolders as $k1=>$v1){
        $except_fo[] = $dir.'/'.$v1;
    }
    foreach ($exceptFiles as $k2=>$v2){
        $except_fi[] = $dir.'/'.$v2;
    }

    global $file_list;
    if(is_dir($dir)&&file_exists($dir)){
        $ob=scandir($dir);
        foreach($ob as $file){
            if($file=="."||$file==".."){
                continue;
            }
            $file=$dir."/".$file;
            if(is_file($file)){
                if(pathinfo($file)['extension'] == 'php' && !in_array($file, $except_fi)){
                    $file_list[] = $file;
                }
            }elseif(is_dir($file)){
                if(!in_array($file, $except_fo)){
                    getFilesFromFolder($file,$exceptFolders,$exceptFiles);
                }
            }
        }
    }

    return $file_list;
}

function backupMysql()
{
    $fileName = "dbbackup/chinese6_companion_" . date("Ymd__h_i_s", time());
    $backupmysql = "mysqldump -h az1-ls7.a2hosting.com -u  chinese6_test2 -p  chinese6_companion | gzip > ~//travelling.chinesecompanion.com/public/chinese6_companion_`date '+%m-%d-%Y'`.sql.gz";

    $fp = @popen($backupmysql,"w");
    $rootpasswd ="xAGfF&WzdLP$";
    @fputs($fp,$rootpasswd);
    @pclose($fp);
    return "<a href ='http://travelling.chinesecompanion.com/$fileName'>$fileName</a>";
}
echo backupMysql();



?>





