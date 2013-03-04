 
<form method="post" enctype="multipart/form-data">
<table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
<tr>
    <td width="246">
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
        <input name="userfile" type="file" id="userfile">
    </td>
    <td width="80">
        <input name="upload" type="submit" class="box" id="upload" value=" Upload ">
	</td>
</tr>
</table>
</form>


<?php

require_once("cfg.php");
require_once("bd.php");
require_once("file.class.php");

if(isset($_POST['upload']) && $_FILES['userfile']['size'] > 0){
    $fileName = $_FILES['userfile']['name'];
    $tmpName  = $_FILES['userfile']['tmp_name'];
    $fileSize = $_FILES['userfile']['size'];
    $fileType = $_FILES['userfile']['type'];
	$file = new File(3,5,$fileName, $fileType, $fileSize, $tmpName);
	$file->upload();
	if ($file->temErro()){
	    echo($file->getErrosString());
	}
	else{
	    echo("upload com sucesso".NL);
	
	}
	
}

$nome = "AUT94.pdf";
$file2 = new File(3,4,$nome);
$file2->download();
echo ("yep  ".$file2->toString() );
//echo("AUHHUSASAHUHSAHUSAHUS");

/*
if(isset($_FILES)){


print_r($_FILES);




}

*/

?>