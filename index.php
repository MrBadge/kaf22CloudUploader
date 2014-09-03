<?php

require("config.php");
require("DropboxUploader.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cloud uploader</title>
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

<h1 align="center" class="heading-txt">Cloud uploader for kaf22 students</h1>
<table width="812" border="0" align="center" style="margin-top:-50px;" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50">&nbsp;</td>
  </tr>
</table>
<table width="812" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" valign="top" class="blue-box"><table width="415" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="84">&nbsp;</td>
      </tr>
      <tr>
        <td><table width="415" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="115" align="left" valign="top">
              <img src="images/box-img.png" width="90" height="87" alt="" />
            </td>
            <td>
              <strong>Destination:</strong> 
                <select name="folder">
                  <?php
                    $i = 0;
                    foreach($data["directories"] AS $output => $dirname){
                      echo '<option value="'.$i.'">'.$dirname.' (/'.$output.')</option>';
                      $i++;
                    }
                  ?>
                </select>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        
      </tr>
      <tr>
        <td>
          <table width="415" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center"><form method="POST" enctype="multipart/form-data">
  		          <input type="file" name="file" /> 
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center">
          <input name="Button" type="submit" value="" width="69" height="35" border="none" style="background-image:url(images/upload-btn.png)" class="upload" />  
        </td>
      </tr>
      <tr>
	<td align="center"><a target="_blank" style="color: #FF9900" href="https://www.dropbox.com/sh/lpxfgwzgebh1snx/AADRxppntPhhekrfKe94zo-ha?dl=0">Observe the cloud</a></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
