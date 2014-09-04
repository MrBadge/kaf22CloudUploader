<?php

require("config.php");
require("DropboxUploader.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cloud uploader</title>
<link href='http://fonts.googleapis.com/css?family=Exo+2:400,100,200,300,500,600,700,800,900&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
<?php

#require("config.php");

if ($_POST) {
    #require 'DropboxUploader.php';

    $keys = array_keys($data["directories"]);

    $directory = $keys[$_POST["folder"]];

    //echo "<h4 style=\"color: yellow;\">" . htmlspecialchars($directory) . "</h4>";

    try {
        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK)
            throw new Exception('File was not successfully uploaded from your computer.');

        $tmpDir = uniqid('/tmp/DropboxUploader-');
        if (!mkdir($tmpDir))
            throw new Exception('Cannot create temporary directory!');

        if ($_FILES['file']['name'] === "")
            throw new Exception('File name not supplied by the browser.');

        $tmpFile = $tmpDir.'/'.str_replace("/\0", '_', $_FILES['file']['name']);
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $tmpFile))
            throw new Exception('Cannot rename uploaded file!');

    		$uploader = new DropboxUploader($DropBoxMail, $DropBoxPassw);
        $uploader->upload($tmpFile, "kaf22Cloud");

        echo '<h3 align="center" style="color: green;font-weight:bold;">File successfully uploaded to the cloud!</h3>';
    } catch(Exception $e) {
        echo '<h3 align="center" style="color: red;font-weight:bold;">Error: ' . htmlspecialchars($e->getMessage()) . '</h3>';
    }

    // Clean up
    if (isset($tmpFile) && file_exists($tmpFile))
        unlink($tmpFile);

    if (isset($tmpDir) && file_exists($tmpDir))
        rmdir($tmpDir);
}
?>




<div class="header">
  <h1>Cloud uploader for kaf22 students</h1>
</div>

<div class="container">
  <form method="POST" enctype="multipart/form-data">

    <input type="file" name="file" /> 

    <div class="block">
      <img class="logo">
      <select class="select">
        <?php
          $i = 0;
          foreach($data["directories"] AS $output => $dirname){
            echo '<option value="'.$i.'">'.$dirname.' (/'.$output.')</option>';
            $i++;
          }
        ?>
      </select>
    </div>
    <div class="block dropzone"></div>
    <a class="upload-button">Upload</a>
  </form>
</div>
<a target="_blank" class="observe" href="https://www.dropbox.com/sh/lpxfgwzgebh1snx/AADRxppntPhhekrfKe94zo-ha?dl=0">Observe the cloud</a>

</body>
</html>
