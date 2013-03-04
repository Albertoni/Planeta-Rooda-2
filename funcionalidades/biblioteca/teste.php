 
<form method="post" enctype="multipart/form-data">
<table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
<tr>
    <td width="20">
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000"> 
        <input name="link" type="text" size="0" id="link"> 
    </td>
    <td width="80">
        <input name="inverter" type="submit" class="box" id="inverter" value=" Inverter ">
	</td>
</tr>
</table>
</form>


<?php



if(isset($_POST['inverter'])){
    $link = $_POST['link'];
	$link = strrev($link);
	echo $link;
	
}
/*
$nome = "AUT94.pdf";
$file2 = new File(3,4,$nome);
$file2->download();
echo ("yep  ".$file2->toString() );*/
//echo("AUHHUSASAHUHSAHUSAHUS");

/*
if(isset($_FILES)){


print_r($_FILES);




}

*/

?>