<?
	require_once("../sistema.inc.php");	
	require_once("biblioteca.inc.php");		

	$usuario      = sessaoUsuario();
	$codUsuario   = $usuario["codUsuario"];

	$turma        = sessaoTurma();
	$codTurma     = $turma["codTurma"];
	$associacao   = $turma["associacao"];
	$cor          = $turma["cor"];	
	
	$buscaTitulo   = $_POST['titulo'];
	$buscaQuem     = $_POST['quem'];
	$buscaPalavras = $_POST['palavras'];
	
	$images_path  = $base_loc."/images/biblioteca/";	
	
	hierarquia('biblioteca');	
	
	$select = "SELECT materialBiblioteca FROM Turmas WHERE codTurma=$codTurma";
	$result = db_busca($select);	
	$autoriza = $result[0][materialBiblioteca];
	//echo "<<<$autoriza>>>";
	//echo "$associacao";

?>
<?//para professores
if(($associacao=='P')or($associacao=='M')){?>
<html>
<head>
<title>Biblioteca0001.png</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!--Fireworks MX 2004 Dreamweaver MX 2004 target.  Created Wed Apr 26 09:53:25 GMT-0300 (Hora oficial do Brasil) 2006-->
<link href="<?=$base_loc; ?>/pngfix.css" rel="stylesheet" type="text/css">
<link href="biblioteca.css" rel="stylesheet" type="text/css">
<script src="<?=$base_loc; ?>/config/corFundo.js"></script>
</head>
<body>
<script>
function excluir() {
		if (window.confirm("Você tem certeza que deseja excluir a(s) mensagens(s) selecionada(s)?"))
			document.apaga.submit();
	}
</script>
<script>corDeFundo("<?=$cor; ?>");</script>
<? include("$base_dir/config/fundo.php"); ?>
<table border="0" cellpadding="0" cellspacing="0" width="759">
<!-- fwtable fwsrc="Biblioteca_enviarmaterial-mais largo.png" fwbase="Biblioteca0001.png" fwstyle="Dreamweaver" fwdocid = "1493755985" fwnested="0" -->
  <tr>
   <td><img src="<?=$images_path?>spacer.gif" width="9" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="33" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="13" height="1" border="0" alt=""></td>

   <td><img src="<?=$images_path?>spacer.gif" width="64" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="225" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="13" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="57" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="11" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="54" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="28" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="17" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="103" height="1" border="0" alt=""></td>

   <td><img src="<?=$images_path?>spacer.gif" width="117" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="15" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="1" border="0" alt=""></td>
  </tr>

  <tr>
   <td colspan="14"><img name="Biblioteca0001_r1_c1" src="<?=$images_path?>Biblioteca0001_r1_c1.png" width="759" height="13" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="13" border="0" alt=""></td>
  </tr>

  <tr>
   <td rowspan="9" colspan="2"><img name="Biblioteca0001_r2_c1" src="<?=$images_path?>Biblioteca0001_r2_c1.png" width="42" height="243" border="0" alt=""></td>
   <td colspan="3" rowspan="3" bgcolor="#00CCFF">
   <form name='busca' method='post' action='index.php'>
   <?parteCima($buscaTitulo,$buscaQuem,$buscaPalavras);?>
   </form>
   </td>
   <td colspan="9"><img name="Biblioteca0001_r2_c6" src="<?=$images_path?>Biblioteca0001_r2_c6.png" width="415" height="16" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="16" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="3"><img name="Biblioteca0001_r3_c6" src="<?=$images_path?>Biblioteca0001_r3_c6.png" width="13" height="78" border="0" alt=""></td>
   <td colspan="3"><a href="#" onClick='document.busca.submit();'><img name="Biblioteca0001_r3_c7" src="<?=$images_path?>Biblioteca0001_r3_c7.png" width="122" height="29" border="0" alt=""></a></td>
   <td colspan="5"><img name="Biblioteca0001_r3_c10" src="<?=$images_path?>Biblioteca0001_r3_c10.png" width="280" height="29" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="29" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="2" colspan="4"><img name="Biblioteca0001_r4_c7" src="<?=$images_path?>Biblioteca0001_r4_c7.png" width="150" height="49" border="0" alt=""></td>
   <td colspan="3" rowspan="3" bgcolor="#CCCCCC">   
   <?enviaMaterial($codTurma,$codUsuario);?>   
   </form>
   </td>
   <td rowspan="9"><img name="Biblioteca0001_r4_c14" src="<?=$images_path?>Biblioteca0001_r4_c14.png" width="15" height="241" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="37" border="0" alt=""></td>
  </tr>

  <tr>
   <td colspan="3"><img name="Biblioteca0001_r5_c3" src="<?=$images_path?>Biblioteca0001_r5_c3.png" width="302" height="12" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="12" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="5"><img name="Biblioteca0001_r6_c3" src="<?=$images_path?>Biblioteca0001_r6_c3.png" width="13" height="149" border="0" alt=""></td>
   <td colspan="4" rowspan="3" bgcolor="#999999">
   <form name='apaga' method='post' action='excluir.php'>
   <?
   listaMateriais($codTurma,$codUsuario,$buscaTitulo,$buscaQuem,$buscaPalavras,1,0,$associacao);
   ?>  
   </form>
   </td>
   <td rowspan="2" colspan="3"><img name="Biblioteca0001_r6_c8" src="<?=$images_path?>Biblioteca0001_r6_c8.png" width="93" height="112" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="85" border="0" alt=""></td>

  </tr>
  <tr>
   <td colspan="3"><img name="Biblioteca0001_r7_c11" src="<?=$images_path?>Biblioteca0001_r7_c11.png" width="237" height="27" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="27" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="5"><img name="Biblioteca0001_r8_c8" src="<?=$images_path?>Biblioteca0001_r8_c8.png" width="11" height="80" border="0" alt=""></td>
   <td rowspan="2" colspan="3"><a href="#" onClick="excluir();"><img name="Biblioteca0001_r8_c9" src="<?=$images_path?>Biblioteca0001_r8_c9.png" width="99" height="28" border="0" alt=""></a></td>
   <td rowspan="5"><img name="Biblioteca0001_r8_c12" src="<?=$images_path?>Biblioteca0001_r8_c12.png" width="103" height="80" border="0" alt=""></td>

   <td rowspan="2"><a href='#' onClick='testaEnvia();'><img name="Biblioteca0001_r8_c13" src="<?=$images_path?>Biblioteca0001_r8_c13.png" width="117" height="28" border="0" alt=""></a></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="8" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="2" colspan="4"><img name="Biblioteca0001_r9_c4" src="<?=$images_path?>Biblioteca0001_r9_c4.png" width="359" height="29" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="20" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="3" colspan="3"><img name="Biblioteca0001_r10_c9" src="<?=$images_path?>Biblioteca0001_r10_c9.png" width="99" height="52" border="0" alt=""></td>

   <td rowspan="3"><img name="Biblioteca0001_r10_c13" src="<?=$images_path?>Biblioteca0001_r10_c13.png" width="117" height="52" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="9" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="2"><img name="Biblioteca0001_r11_c1" src="<?=$images_path?>Biblioteca0001_r11_c1.png" width="9" height="43" border="0" alt=""></td>
   <td colspan="3"><? if(($buscaTitulo != "") or ($buscaQuem  != "") or ($buscaPalavras != "")) { ?><a href='index.php'><img name="Biblioteca0001_r11_c2" src="<?=$images_path?>Biblioteca0001_r11_c2.png" width="110" height="38" border="0" alt=""></a><? } else { ?><a href='../turmas'><img name="Biblioteca0001_r11_c2" src="<?=$images_path?>Biblioteca0001_r11_c2.png" width="110" height="38" border="0" alt=""></a><? } ?></td>
   <td rowspan="2" colspan="3"><img name="Biblioteca0001_r11_c5" src="<?=$images_path?>Biblioteca0001_r11_c5.png" width="295" height="43" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="38" border="0" alt=""></td>
  </tr>

  <tr>
   <td colspan="3"><img name="Biblioteca0001_r12_c2" src="<?=$images_path?>Biblioteca0001_r12_c2.png" width="110" height="5" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="5" border="0" alt=""></td>
  </tr>
</table>
</body>
</html>



<?//para alunos em turmas q está autorizada a inserção de material por alunos
}if(($associacao=='A')and($autoriza==1)){?>
<html>
<head>
<title>Biblioteca0002.png</title>
<meta http-equiv="Content-Type" content="text/html;">
<!--Fireworks MX 2004 Dreamweaver MX 2004 target.  Created Wed Apr 26 10:15:31 GMT-0300 (Hora oficial do Brasil) 2006-->
<link href="<?=$base_loc; ?>/pngfix.css" rel="stylesheet" type="text/css">
<link href="biblioteca.css" rel="stylesheet" type="text/css">
<script src="<?=$base_loc; ?>/config/corFundo.js"></script>
</head>
<body>
<script>corDeFundo("<?=$cor; ?>");</script>
<? include("$base_dir/config/fundo.php"); ?>
<table border="0" cellpadding="0" cellspacing="0" width="759">
<!-- fwtable fwsrc="Biblioteca_editar.png" fwbase="Biblioteca0002.png" fwstyle="Dreamweaver" fwdocid = "1833895096" fwnested="0" -->
  <tr>
   <td><img src="<?=$images_path?>spacer.gif" width="44" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="14" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="62" height="1" border="0" alt=""></td>

   <td><img src="<?=$images_path?>spacer.gif" width="229" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="65" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="69" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="26" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="16" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="104" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="14" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="102" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="3" height="1" border="0" alt=""></td>

   <td><img src="<?=$images_path?>spacer.gif" width="11" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="1" border="0" alt=""></td>
  </tr>

  <tr>
   <td colspan="13"><img name="Biblioteca0002_r1_c1" src="<?=$images_path?>Biblioteca0002_r1_c1.png" width="759" height="13" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="13" border="0" alt=""></td>
  </tr>
  <tr>

   <td rowspan="9"><img name="Biblioteca0002_r2_c1" src="<?=$images_path?>Biblioteca0002_r2_c1.png" width="44" height="242" border="0" alt=""></td>
   <td colspan="3" rowspan="4" bgcolor="#00CCFF">
   <form name='busca' method='post' action='index.php'>
   <?parteCima($buscaTitulo,$buscaQuem,$buscaPalavras);?>
   </td>
   <td colspan="2"><img name="Biblioteca0002_r2_c5" src="<?=$images_path?>Biblioteca0002_r2_c5.png" width="134" height="17" border="0" alt=""></td>
   <td rowspan="3" colspan="7"><img name="Biblioteca0002_r2_c7" src="<?=$images_path?>Biblioteca0002_r2_c7.png" width="276" height="55" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="17" border="0" alt=""></td>
  </tr>
  <tr>
   <td colspan="2"><input type='image' name="Biblioteca0002_r3_c5" src="<?=$images_path?>Biblioteca0002_r3_c5.png" width="134" height="27" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="27" border="0" alt=""></td>
	</form>
  </tr>
  <tr>
   <td colspan="2"><img name="Biblioteca0002_r4_c5" src="<?=$images_path?>Biblioteca0002_r4_c5.png" width="134" height="11" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="11" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="2" colspan="3"><img name="Biblioteca0002_r5_c5" src="<?=$images_path?>Biblioteca0002_r5_c5.png" width="160" height="40" border="0" alt=""></td>
   <td colspan="4" rowspan="3" bgcolor="#CCCCCC" valign='top'>
   <?enviaMaterial($codTurma,$codUsuario);?>
   </td>
   <td rowspan="5" colspan="2"><img name="Biblioteca0002_r5_c12" src="<?=$images_path?>Biblioteca0002_r5_c12.png" width="14" height="162" border="0" alt=""></td>

   <td><img src="<?=$images_path?>spacer.gif" width="1" height="32" border="0" alt=""></td>
  </tr>
  <tr>
   <td colspan="3"><img name="Biblioteca0002_r6_c2" src="<?=$images_path?>Biblioteca0002_r6_c2.png" width="305" height="8" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="8" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="4"><img name="Biblioteca0002_r7_c2" src="<?=$images_path?>Biblioteca0002_r7_c2.png" width="14" height="147" border="0" alt=""></td>
   <td colspan="3" rowspan="2" bgcolor="#999999" valign='top'>
   <? listaMateriais($codTurma,$codUsuario,$buscaTitulo,$buscaQuem,$buscaPalavras,0,0,$associacao); ?>  
   </td>

   <td rowspan="6" colspan="2"><img name="Biblioteca0002_r7_c6" src="<?=$images_path?>Biblioteca0002_r7_c6.png" width="95" height="191" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="96" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="2" colspan="4"><img name="Biblioteca0002_r8_c8" src="<?=$images_path?>Biblioteca0002_r8_c8.png" width="236" height="26" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="22" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="2" colspan="3"><img name="Biblioteca0002_r9_c3" src="<?=$images_path?>Biblioteca0002_r9_c3.png" width="356" height="29" border="0" alt=""></td>

   <td><img src="<?=$images_path?>spacer.gif" width="1" height="4" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="3"><img name="Biblioteca0002_r10_c8" src="<?=$images_path?>Biblioteca0002_r10_c8.png" width="16" height="69" border="0" alt=""></td>
   <td><a href='#' onClick='testaEnvia();'><img src="<?=$images_path?>Biblioteca0002_r10_c9.png" width="104" height="25" border="0"></a></td>
   <td rowspan="3"><img name="Biblioteca0002_r10_c10" src="<?=$images_path?>Biblioteca0002_r10_c10.png" width="14" height="69" border="0" alt=""></td>
   <td rowspan="2" colspan="2"><? if(($buscaTitulo != "") or ($buscaQuem  != "") or ($buscaPalavras != "")) { ?><a href='index.php'><img name="Biblioteca0002_r10_c11" src="<?=$images_path?>Biblioteca0002_r10_c11.png" width="105" height="30" border="0" alt=""></a><? } else { ?><a href='../turmas'><img name="Biblioteca0002_r10_c11" src="<?=$images_path?>Biblioteca0002_r10_c11.png" width="105" height="30" border="0" alt=""></a><? } ?></td>
   <td rowspan="3"><img name="Biblioteca0002_r10_c13" src="<?=$images_path?>Biblioteca0002_r10_c13.png" width="11" height="69" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="25" border="0" alt=""></td>
	
  </form> 
   
  </tr>
  <tr>
   <td rowspan="2" colspan="3"><? if(($buscaTitulo != "") or ($buscaQuem  != "") or ($buscaPalavras != "")) { ?><a href='index.php'><img name="Biblioteca0002_r11_c1" src="<?=$images_path?>Biblioteca0002_r11_c1.png" width="120" height="44" border="0" alt=""></a><? } else { ?><a href='../turmas'><img name="Biblioteca0002_r11_c1" src="<?=$images_path?>Biblioteca0002_r11_c1.png" width="120" height="44" border="0" alt=""></a><? } ?></td>
   <td rowspan="2" colspan="2"><img name="Biblioteca0002_r11_c4" src="<?=$images_path?>Biblioteca0002_r11_c4.png" width="294" height="44" border="0" alt=""></td>
   <td rowspan="2"><img name="Biblioteca0002_r11_c9" src="<?=$images_path?>Biblioteca0002_r11_c9.png" width="104" height="44" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="5" border="0" alt=""></td>
  </tr>
  <tr>
   <td colspan="2"><img name="Biblioteca0002_r12_c11" src="<?=$images_path?>Biblioteca0002_r12_c11.png" width="105" height="39" border="0" alt=""></td>

   <td><img src="<?=$images_path?>spacer.gif" width="1" height="39" border="0" alt=""></td>
  </tr>
</table>
</body>
</html>


<?//para alunos em turmas q não está autorizada a inserção de material por alunos
}if(($associacao=='A')and($autoriza!=1)){?> 
<html>
<head>
<title>biblioteca0003.png</title>
<meta http-equiv="Content-Type" content="text/html;">
<!--Fireworks MX 2004 Dreamweaver MX 2004 target.  Created Wed Apr 26 10:27:23 GMT-0300 (Hora oficial do Brasil) 2006-->
<link href="<?=$base_loc; ?>/pngfix.css" rel="stylesheet" type="text/css">
<link href="biblioteca.css" rel="stylesheet" type="text/css">
<script src="<?=$base_loc; ?>/config/corFundo.js"></script>
</head>
<body>
<script>corDeFundo("<?=$cor; ?>");</script>
<? include("$base_dir/config/fundo.php"); ?>

<table border="0" cellpadding="0" cellspacing="0" width="612">
<!-- fwtable fwsrc="biblioteca0003.png" fwbase="biblioteca0003.png" fwstyle="Dreamweaver" fwdocid = "1712804484" fwnested="0" -->
  <tr>
   <td><img src="<?=$images_path?>spacer.gif" width="11" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="111" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="42" height="1" border="0" alt=""></td>

   <td><img src="<?=$images_path?>spacer.gif" width="7" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="304" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="6" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="50" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="81" height="1" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="1" border="0" alt=""></td>
  </tr>

  <tr>

   <td colspan="8"><img name="biblioteca0003_r1_c1" src="<?=$images_path?>biblioteca0003_r1_c1.png" width="612" height="14" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="14" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="6" colspan="3"><img name="biblioteca0003_r2_c1" src="<?=$images_path?>biblioteca0003_r2_c1.png" width="164" height="239" border="0" alt=""></td>
   <td colspan="2" rowspan="3" bgcolor="#00CCFF">
   <form name='busca' method='post' action='index.php'>
   <?parteCima($buscaTitulo,$buscaQuem,$buscaPalavras);?>
   </form>
   </td>
   <td colspan="3"><img name="biblioteca0003_r2_c6" src="<?=$images_path?>biblioteca0003_r2_c6.png" width="137" height="23" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="23" border="0" alt=""></td>
  </tr>

  <tr>
   <td rowspan="3"><img name="biblioteca0003_r3_c6" src="<?=$images_path?>biblioteca0003_r3_c6.png" width="6" height="75" border="0" alt=""></td>
   <td colspan="2"><a href="#" onClick='document.busca.submit();'><img name="biblioteca0003_r3_c7" src="<?=$images_path?>biblioteca0003_r3_c7.png" width="131" height="32" border="0" alt=""></a></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="32" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="2" colspan="2"><img name="biblioteca0003_r4_c7" src="<?=$images_path?>biblioteca0003_r4_c7.png" width="131" height="43" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="32" border="0" alt=""></td>
  </tr>

  <tr>
   <td colspan="2"><img name="biblioteca0003_r5_c4" src="<?=$images_path?>biblioteca0003_r5_c4.png" width="311" height="11" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="11" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="4"><img name="biblioteca0003_r6_c4" src="<?=$images_path?>biblioteca0003_r6_c4.png" width="7" height="185" border="0" alt=""></td>
   <td colspan="3" bgcolor="#999999">
   <? listaMateriais($codTurma,$codUsuario,$buscaTitulo,$buscaQuem,$buscaPalavras,0,0,$associacao); ?>  
   </td>
   <td rowspan="4"><img name="biblioteca0003_r6_c8" src="<?=$images_path?>biblioteca0003_r6_c8.png" width="81" height="185" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="114" border="0" alt=""></td>

  </tr>
  <tr>
   <td rowspan="3" colspan="3"><img name="biblioteca0003_r7_c5" src="<?=$images_path?>biblioteca0003_r7_c5.png" width="360" height="71" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="27" border="0" alt=""></td>
  </tr>
  <tr>
   <td rowspan="2"><img name="biblioteca0003_r8_c1" src="<?=$images_path?>biblioteca0003_r8_c1.png" width="11" height="44" border="0" alt=""></td>
   <td><? if(($buscaTitulo != "") or ($buscaQuem  != "") or ($buscaPalavras != "")) { ?><a href='index.php'><img name="biblioteca0003_r8_c2" src="<?=$images_path?>biblioteca0003_r8_c2.png" width="111" height="40" border="0" alt=""></a><? } else { ?><a href='../turmas'><img name="biblioteca0003_r8_c2" src="<?=$images_path?>biblioteca0003_r8_c2.png" width="111" height="40" border="0" alt=""></a><? } ?></td>
   <td rowspan="2"><img name="biblioteca0003_r8_c3" src="<?=$images_path?>biblioteca0003_r8_c3.png" width="42" height="44" border="0" alt=""></td>

   <td><img src="<?=$images_path?>spacer.gif" width="1" height="40" border="0" alt=""></td>
  </tr>
  <tr>
   <td><img name="biblioteca0003_r9_c2" src="<?=$images_path?>biblioteca0003_r9_c2.png" width="111" height="4" border="0" alt=""></td>
   <td><img src="<?=$images_path?>spacer.gif" width="1" height="4" border="0" alt=""></td>
  </tr>
</table>
</body>
</html>

<? } ?>