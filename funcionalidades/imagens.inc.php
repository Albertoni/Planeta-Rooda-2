<?
function imagem($img,$width,$height){
	echo "<table border='0' cellspacing='0' cellpadding='0' width='$width' height='$height'><tr><td align='center' valign='center' width='$width' height='$height'>";
	echo"<img src='/planeta/redimensiona.php?img=$img&width=$width&height=$height' border='0'/>"; 
	echo "</td></tr></table>";
}
?>