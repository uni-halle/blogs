<?php
$id   = 'pp_' . rand( 1000, 9999 );
$base = substr( $_GET['xml'], 0, strrpos($_GET['xml'], '/') + 1 );
?>
<html>
<head>
<base href="<?php echo $base; ?>" />
<script type="text/javascript" src="pano2vr_player.js"></script>
<script type="text/javascript" src="skin"></script>
</head>
<body>
<div id="<?php echo $id; ?>" style="width:100%; height:100%; display:block"></div>
<script type="text/javascript">
function updateOrientation() {
	switch(window.orientation) {
		case  90:
		case -90:
		if (window.pageYOffset==0) {
			window.scrollTo(0, 1);
		}
		break;
		default:
		if (window.pageYOffset==0) {
			window.scrollTo(0, 1);
		}
		break;		
	}
}
pano = new pano2vrPlayer('<?php echo $id; ?>'), skin = null;
/* fix 1.0b2 */
if( typeof pano2vrSkin !== 'undefined' ){
	skin=new pano2vrSkin(pano);
}
pano.readConfigUrl('<?php echo $_GET['xml'];?>');
updateOrientation();
setTimeout(function() { updateOrientation(); }, 10);
setTimeout(function() { updateOrientation(); }, 1000);
document.getElementById('<?php echo $id; ?>').className = 'pp-embed-content';
</script>
</body>
</html>
