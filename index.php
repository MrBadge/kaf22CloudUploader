<?php

require("config.php");
require("DropboxUploader.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cloud uploader</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/style.css">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/fileinput.min.js" type="text/javascript"></script>
<script src="js/utils.js" type="text/javascript"></script>
</head>

<body>
<?php

#require("config.php");

if ($_POST) {
    #require 'DropboxUploader.php';
    $file = $_FILES['fl'];
    $keys = array_keys($data["directories"]);
    $directory = $keys[$_POST["fld"]];

    //echo "<script type='text/javascript'>alert(tag:" . $directory . ");</script>";

    try {
        if ($file['error'] !== UPLOAD_ERR_OK)
            throw new Exception('File was not successfully uploaded from your computer.');

        $tmpDir = uniqid('/tmp/DropboxUploader-');
        if (!mkdir($tmpDir))
            throw new Exception('Cannot create temporary directory!');

        if ($file['name'] === "")
            throw new Exception('File name not supplied by the browser.');

        $tmpFile = $tmpDir.'/'.str_replace("/\0", '_', $file['name']);
        if (!move_uploaded_file($file['tmp_name'], $tmpFile))
            throw new Exception('Cannot rename uploaded file!');

    		$uploader = new DropboxUploader($DropBoxMail, $DropBoxPassw);
        $uploader->upload($tmpFile, $directory);

        //echo '<h3 align="center" style="color: green;font-weight:bold;">File successfully uploaded to the cloud!</h3>';
        echo '<div class="alert alert-success" width="940" align="center" role="alert">File successfully uploaded to the cloud!</div>';
    } catch(Exception $e) {
        //echo '<h3 align="center" style="color: red;font-weight:bold;">Error: ' . htmlspecialchars($e->getMessage()) . '</h3>';
        echo '<div class="alert alert-danger" width="940" align="center" role="alert"><strong>Error: </strong>' . htmlspecialchars($e->getMessage()) . ' </div>';
    }
    //echo '<script type="text/javascript">createAutoClosingAlert(".alert", 2000);</script>';

    // Clean up
    if (isset($tmpFile) && file_exists($tmpFile))
        unlink($tmpFile);

    if (isset($tmpDir) && file_exists($tmpDir))
        rmdir($tmpDir);
}
?>

<div class="container">
<div class="hero-unit">
  <img src="images/box-img.png" style="float: left; margin-top: 25px; margin-left: -20px" hspace="20" width="90" height="87" alt="" />
  <h1>Cloud uploader for kaf22 students</h1>
    <hr>
    <p>Destination: 
    <form method="POST" enctype="multipart/form-data">
    <select name="fld">
      <?php
        $i = 0;
        foreach($data["directories"] AS $output => $dirname){
          echo '<option value="'.$i.'">'.$dirname.' (/'.$output.')</option>';
          $i++;
        }
      ?>
    </select>
    </p>
    <script>$("#input-id").fileinput({'showUpload':false, 'showPreview':false});</script>
    <form method="POST" enctype="multipart/form-data">
    <input id="input-id" type="file" class="file" name="fl" value="">
    <hr>
  <p>
    <a href="https://www.dropbox.com/sh/lpxfgwzgebh1snx/AADRxppntPhhekrfKe94zo-ha?dl=0" target="_blank" class="btn btn-primary btn-large">Observe the cloud</a>
  </p>
</div>
</div>

</body>
</html>
