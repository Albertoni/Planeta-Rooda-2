<?php 
// !SQLINJECTION
  session_start();
	require_once("biblioteca.inc.php");
	require_once("../cfg.php");		
	require_once("../db.inc.php");

	$codUsuario   = $_SESSION['SS_usuario_id'];
	$codTurma     = $_SESSION['SS_terreno_id'];
	$associacao   = "A";
	
	
 $codMaterial  = $_GET['m'];
 $texto        = $_POST['texto']; 
 
 
 if($codMaterial=='')
 		$codMaterial  = $_POST['codMaterial'];
 $data = date("Y-m-d H:i:s");
echo"<script>opener.location.reload();</script>";

 if ($texto!=''){	
	 	 
     $insere="INSERT INTO BibliotecaComentarios (codMaterial,codUsuario,comentario,data )  VALUES ('$codMaterial','$codUsuario','$texto','$data ')";
		 $faz = db_faz($insere);
		 echo"<script>opener.location.reload();</script>";
 }

  $images_path = $base_loc."/images/diario/";
?>


<html>
<head>
<title>Comentários</title>
<meta http-equiv="Content-Type" content="text/html;">
<!--Fireworks MX 2004 Dreamweaver MX 2004 target.  Created Mon Mar 27 17:12:04 GMT-0300 (Hora oficial do Brasil) 2006-->
</head>
<body bgcolor="#ffffff">


<table border="0" cellpadding="0" cellspacing="0" width="435">
<!-- fwtable fwsrc="comment.png" fwbase="diario_comment.gif" fwstyle="Dreamweaver" fwdocid = "149034191" fwnested="0" -->
  <tr>
   <td><img src="<?=$images_path; ?>spacer.gif" width="41" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="17" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="241" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="76" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="55" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="5" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="1" height="1" border="0" alt=""></td>
  </tr>

  <tr>
   <td colspan="6"><img name="diario_comment_r1_c1" src="<?=$images_path; ?>diario_comment_r1_c1.gif" width="435" height="30" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="1" height="30" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="6"><img name="diario_comment_r2_c1" src="<?=$images_path; ?>diario_comment_r2_c1.gif" width="41" height="655" border="0" alt=""></td>
   <td colspan="3" bgcolor="#CCCCCC">
    <FORM method="POST" action="comentarios.php">
    <INPUT type="hidden" name="codMaterial" value="<?=$codMaterial; ?>">
     <table align="center" valign="middle">
     		<tr><td><TEXTAREA name="texto" id="texto" rows="5" cols="37"></TEXTAREA></td></tr>
    </table>   
   </td>
   <td rowspan="2" colspan="2"><img name="diario_comment_r2_c5" src="<?=$images_path; ?>diario_comment_r2_c5.gif" width="60" height="170" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="1" height="139" border="0" alt=""></td>
  </tr>
  <tr>
   <td colspan="3"><img name="diario_comment_r3_c2" src="<?=$images_path; ?>diario_comment_r3_c2.gif" width="334" height="31" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="1" height="31" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="2" colspan="2"><img name="diario_comment_r4_c2" src="<?=$images_path; ?>diario_comment_r4_c2.gif" width="258" height="42" border="0" alt=""></td>
   <td colspan="2"><input type='image'  src='<?=$imagesPath;?>/planeta/images/diario/diario_comment_r4_c4.gif'></td>
   <td rowspan="4"><img name="diario_comment_r4_c6" src="<?=$images_path; ?>diario_comment_r4_c6.gif" width="5" height="485" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="1" height="30" border="0" alt=""></td>
  </tr>
  <tr>
   <td colspan="2"><img name="diario_comment_r5_c4" src="<?=$images_path; ?>diario_comment_r5_c4.gif" width="131" height="12" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="1" height="12" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="2"><img name="diario_comment_r6_c2" src="<?=$images_path; ?>diario_comment_r6_c2.gif" width="17" height="443" border="0" alt=""></td>
   <td colspan="2" bgcolor="#CCCCCC"><?php  listaComentarios($codMaterial,$codUsuario);  ?></td>
   <td rowspan="2"><img name="diario_comment_r6_c5" src="<?=$images_path; ?>diario_comment_r6_c5.gif" width="55" height="443" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="1" height="414" border="0" alt=""></td>
  </tr>
  <tr>
   <td colspan="2"><img name="diario_comment_r7_c3" src="<?=$images_path; ?>diario_comment_r7_c3.gif" width="317" height="29" border="0" alt=""></td>
   <td><img src="<?=$images_path; ?>spacer.gif" width="1" height="29" border="0" alt=""></td>
  </tr>
</table>
</form>
</body>
</html>
