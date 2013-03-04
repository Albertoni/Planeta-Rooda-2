<?php
$funcionalidade_id = $_GET['funcionalidade_id'];
$funcionalidade_tipo = $_GET['funcionalidade_tipo'];

if (is_numeric($funcionalidade_id) == false or is_numeric($funcionalidade_tipo) == false){
		die('RAAAAAAAAAA, pegadinha do Mallandro!'); // Sabe SQL injection?
	}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="planeta.css" />
<link type="text/css" rel="stylesheet" href="blog.css" />

<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<script language="javascript">
function fakeFile(image, input, pathfield) {
	var image = document.getElementById(image);
	var input = document.getElementById(input);
	var pathfield = document.getElementById(pathfield);
	
	var w = image.width, h = image.height, s = parseInt(w/4.2), wrapper = document.createElement('div');
	wrapper.style.cssText = "position:absolute; width:"+w+"px; height:"+h+"px; z-index:100; overflow:hidden;";
	input.style.cssText = "position:absolute; width:"+w+"px; height:"+h+"px; top:0; right:0; font-size:"+s+"px; filter:alpha(opacity=0); opacity:0; z-index:101;";
	image.parentNode.insertBefore(wrapper, image);
	wrapper.appendChild(image);
	wrapper.appendChild(input);
	return wrapper;
}
	
	function trocador(falso, original) {
	document.getElementById(falso).value = document.getElementById(original).value;
}
	
function addEvent(elm, evType, fn, useCapture) {
	if (elm.addEventListener) { 
		elm.addEventListener(evType, fn, useCapture); 
		return true; 
	}
	else if (elm.attachEvent) { 
		var r = elm.attachEvent('on' + evType, fn); 
		return r; 
	}
	else {
		elm['on' + evType] = fn;
	}
}

</script>

<style type="text/css">
body {
	background-color:transparent;
	background-image:none;
}
#falso_frame {
	width: 100px;
}
</style>
</head>
<body onload="fakeFile('botao_upload_frame', 'arquivo_frame', 'falso_frame')">
Adicionar novo arquivo:
	<form method="post" enctype="multipart/form-data" action='uploadFile.php?funcionalidade_id=<?=$_GET["funcionalidade_id"]?>&funcionalidade_tipo=<?=$_GET["funcionalidade_tipo"]?>' target="alvoAJAX">
		<input type="hidden" name="MAX_FILE_SIZE" value="2000000" /> 
		<input name="userfile" type="file" id="arquivo_frame" class="upload_file" style="" onchange="trocador('falso_frame', 'arquivo_frame')" />
		<input name="falso" type="text" id="falso_frame" />
		<img src="images/botoes/bt_procurar_arquivo.png" id="botao_upload_frame" />
		<input type="submit" name="upload" value="upload!" />
	</form>	
	<iframe id="alvoAJAX" name="alvoAJAX" style="display: none;" src=""></iframe>
</body>
</html>



