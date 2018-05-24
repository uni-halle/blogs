<?php


function sd_rel_to_abs_filename($rel_filename) {
    return '/var/www/wp-prod/wp-content/themes/be-up-theme/assets/secret/' . $rel_filename;
}


function sd_pdf_to_php_filename($pdf_filename) {
    return substr_replace($pdf_filename, 'php', strrpos($pdf_filename, '.') + 1);
}


function sd_php_to_pdf_filename($pdf_filename) {
    return substr_replace($pdf_filename, 'pdf', strrpos($pdf_filename, '.') + 1);
}


function sd_echo_file($file, $client_filename) {
    if ($fd = fopen( $file, "r")) {
        fseek($fd, 5); /* Skip pseudo header. 8 == length("<? \r\n") */
        header("Content-type:application/pdf");
        header("Content-Disposition:attachment;filename=" . $client_filename . "");
        $cont = true;
        while(!feof($fd)) {
            if($cont == false)
                echo fgets($fd);
            $cont = false;
            # do same stuff with the $line
        }
        fclose($fd);
    } else {
        echo "File not found";
    }
}


function sd_echo_file_by_get($file) {
    sd_echo_file(sd_rel_to_abs_filename($file),
                 sd_php_to_pdf_filename($file));
}


function sd_upload_file() {
    echo '<form enctype="multipart/form-data" action="" method="POST">
    <!-- MAX_FILE_SIZE muss vor dem Dateiupload Input Feld stehen -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000000000000000" />
    <label for="password">Passwort: </label>
    <input id ="password" type="password" name="password" />
    <!-- Der Name des Input Felds bestimmt den Namen im $_FILES Array -->
    Diese Datei hochladen: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>';


    if (isset($_FILES['userfile']['tmp_name'])) {
        $uploaddir  = '/var/www/wp-prod/wp-content/themes/be-up-theme/assets/secret/';
        $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
        //in php umbennen
        $uploadfile = substr($uploadfile, 0, strrpos($uploadfile , ".")).'.php';
        echo '<pre>';
        if ($_POST['password'] == 'Aktive*Geburt2020') {
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
            // echo 'Weitere Debugging Informationen:';
            // print_r($_FILES);

        } else {
            echo "Ung&uumlltiges Passwort. Datei-Upload ist fehlgeschlagen.";
        }
        print "</pre>";
    }
}

?>
