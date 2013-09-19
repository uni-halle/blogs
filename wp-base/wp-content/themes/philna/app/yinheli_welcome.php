<?
function yinheli_welcome(){
$referer=$_SERVER['HTTP_REFERER'];
global $referer;
$referer=$_SERVER['HTTP_REFERER'];
$hostinfo=parse_url($referer);
$host_h=$hostinfo["host"];
$host_p=$hostinfo["path"];
$host=array($host_h,$host_p);
if(substr($host_h, 0, 4) == 'www.')
		$host_h = substr($host_h, 4);
$divt='<div class="yinheli_welcome">';
$divw='</div>'."\n";

$host_h_url='<a href="http://'.$host_h.'/">$host_h</a>';


				//直接输入	没有东西
			if($referer==""):
				if($_COOKIE["comment_author_" . COOKIEHASH]!=""):
				echo $divt;
				printf(__('Howdy, <strong>%s</strong>, welcome back!','philna'), $_COOKIE["comment_author_" . COOKIEHASH]);
				echo $divw;
				else:
				echo $divt; _e('You direct access to my site! you remember my domain ? Thank you !','philna');echo $divw;
			endif;
		//没有东西
		
		//搜索引擎
			//baidu
		elseif(preg_match('/baidu.*/i',$host_h)):		
		echo $divt; _e('You found me through <strong>Baidu</strong>,Congratulations! If you can Subscribe to my blog that would be fine','philna');echo $divw;
			//google
		elseif(!preg_match('/www\.google\.com\/reader/i',$referer) && preg_match('/google\./i',$referer)):
		echo $divt; _e('You found me through <strong>Google</strong>,Congratulations! If you can Subscribe to my blog that would be fine','philna');echo $divw;
			//yahoo
		elseif(preg_match('/search\.yahoo.*/i',$referer) || preg_match('/yahoo.cn/i',$referer)):
		echo $divt; _e('You found me through <strong>Yahoo</strong>,Congratulations! If you can Subscribe to my blog that would be fine','philna');echo $divw;
		//阅读器
			//google
		elseif(preg_match('/google\.com\/reader/i',$referer)):
		echo $divt; _e('Thank you for feed me by <strong>Google</strong>','philna');echo $divw;
			//xianguo
		elseif(preg_match('/xianguo\.com\/reader/i', $referer)):
		echo $divt; _e('Thank you for feed me by <strong>XianGuo</strong>','philna');echo $divw;
			//zhuaxia
		elseif(preg_match('/zhuaxia\.com/i', $referer)):
		echo $divt; _e('Thank you for feed me by <strong>ZhuaXia</strong>','philna');echo $divw;
			//哪吒
		elseif(preg_match('/inezha\.com/i', $referer)):
		echo $divt; _e('Thank you for feed me by <strong>eZha</strong>','philna');echo $divw;
			//有道
		elseif(preg_match('/reader\.youdao/i', $referer)):
		echo $divt; _e('Thank you for feed me by <strong>YouDao</strong>','philna');echo $divw;
		
		//自己  
		elseif(self()):
		echo "<!--Welcome-->"."\n";
		

		//友情链接
		else:
			if($_COOKIE["comment_author_" . COOKIEHASH]!=""):
			echo $divt;
			printf(__('Howdy, <strong>%s</strong>,','philna'), $_COOKIE["comment_author_" . COOKIEHASH]);
			echo _e('welcome back from <strong>','philna').$host_h.'</strong>'.$divw;
			else:
			echo $divt;_e('welcome back from <strong>','philna');echo $host_h.'</strong>'.$divw;
			endif;
		endif;


}
//判断是自己的函数
function self(){
	$local_info = parse_url(get_option('siteurl'));
    $local_host = $local_info['host'];
	//check self
	if ( preg_match("/^http:\/\/(\w+\.)?($local_host)/",$_SERVER['HTTP_REFERER']) != 0) return true;
}
?>