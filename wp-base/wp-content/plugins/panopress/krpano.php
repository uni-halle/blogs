<?php $id  = 'pp_' . rand( 1000, 9999 ); ?>
<html>
<head>
<script type="text/javascript"  src="<?php echo $_GET['js'];?>"></script>
</head>
<body>
<div id="<?php echo $id; ?>" style="width:100%; height:100%; display:block"></div>
<script type="text/javascript">
embedpano({'target':'<?php echo $id; ?>','xml':'<?php echo $_GET['xml'];?>'});
document.getElementById('<?php echo $id; ?>').className = 'pp-embed-content';
</script>
</body>
</html>
