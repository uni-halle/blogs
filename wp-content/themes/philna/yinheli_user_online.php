<?php
$online_log="yinheli_user_online.txt";
$timeout=300;
$entries=file($online_log);
$temp=array();
for($i=0;$i<count($entries);$i++){
$entry=explode(",",trim($entries[$i]));
if(($entry[0]!=getenv('REMOTE_ADDR'))&&($entry[1]>time())){
array_push($temp,$entry[0].",".$entry[1]."\n");//取出其他浏览者的信息,并去掉超时者,保存进$temp
}
}
array_push($temp,getenv('REMOTE_ADDR').",".(time()+($timeout))."\n");
$users_online=count($temp);
$entries=implode("",$temp);
$fp=fopen($online_log,"w");
flock($fp,LOCK_EX);
fputs($fp,$entries);
flock($fp,LOCK_UN);
fclose($fp);
if($users_online==1){
echo "<strong>Now:</strong>"."Just you online!  O_o";
}else{
echo "<strong>Now:</strong>".$users_online."  Users Online";
}
?>