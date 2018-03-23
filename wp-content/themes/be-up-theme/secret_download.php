<?php
if (isset($_GET['file'])) {
    $param = $_GET['file'];
    $myhash= $_GET['hash'];
    $myfile = '/var/www/wp-prod/wp-content/themes/be-up-theme/assets/secret/'.$param;
	
	//$myfile = '/home/trojahn/Test/'.$param;
	//echo $myfile;
	//readfile($myfile);
	
	if ($file = fopen( $myfile, "r")) {
	header("Content-type:application/pdf");
    header("Content-Disposition:attachment;filename='.$param.'");	
	$cont=true;
    while(!feof($file)) {
        if($cont==false)
		echo fgets($file);
	$cont=false;
        # do same stuff with the $line
    }
    fclose($file);
    }
	else{echo 2;}
    /*if (isset($_COOKIE[$myhash])) {
       // header("Content-type:application/pdf");
       // header("Content-Disposition:attachment;filename='.$param.'");
       // readfile($myfile);
        
    }
    else{
        echo 'no auth';
    }*/

} else {
	
    // Uploadformular
 echo'  <form enctype="multipart/form-data" action="" method="POST">
    <!-- MAX_FILE_SIZE muss vor dem Dateiupload Input Feld stehen -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000000000000000" />
    <!-- Der Name des Input Felds bestimmt den Namen im $_FILES Array -->
    Diese Datei hochladen: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>';

if(isset($_FILES['userfile']['tmp_name'])){
$uploaddir = '/var/www/wp-prod/wp-content/themes/be-up-theme/assets/secret/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
//in php umbennen
$uploadfile  = substr($uploadfile, 0, strrpos($uploadfile , ".")).'.php';
echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "Datei ist valide und wurde erfolgreich hochgeladen.\n";
} else {
    echo "Möglicherweise eine Dateiupload-Attacke!\n";
	echo $_FILES["file"]["error"];
}
//chmod($uploadfile, 0600);


$content = file_get_contents($uploadfile);
$content = "<? \r\n" . $content;
file_put_contents($uploadfile, $content); 
echo 'Weitere Debugging Informationen:';
print_r($_FILES);

print "</pre>";
}
}