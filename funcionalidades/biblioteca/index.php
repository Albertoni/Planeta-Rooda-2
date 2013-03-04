<?  
	session_start();//adicionou

	require_once("biblioteca.inc.php");
	require_once("../cfg.php");//adicionou
	require_once("../db.inc.php");//adicionou

	$codUsuario   = $_SESSION['SS_usuario_id'];
	$codTurma     = $_SESSION['SS_terreno_id'];
	$associacao   = "A";
		
	$buscaTitulo   = $_POST['titulo'];
	$buscaQuem     = $_POST['quem'];
	$buscaPalavras = $_POST['palavras'];
	
	$images_path  = "../imagens/biblioteca/";//modificado o caminho	
	$autoriza = 1;
	$fundo    = "../imagens/figuras_fundo/naves.png";//background
	$corFundo = "#3366FF";//bgcolor
	
	$linkVolta = $_SESSION['SS_link_pai'];
	
?>
<?//para professores
if(($associacao=='P')or($associacao=='M')){?>
<html>
<head>
<title>Biblioteca0001.png</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!--Fireworks MX 2004 Dreamweaver MX 2004 target.  Created Wed Apr 26 09:53:25 GMT-0300 (Hora oficial do Brasil) 2006-->
<link href="biblioteca.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="<?=$corFundo; ?>">
<script>
function excluir() {
		if (window.confirm("Você tem certeza que deseja excluir a(s) mensagens(s) selecionada(s)?"))
			document.apaga.submit();
	}
</script>
<div style="position:absolute; width:760; height:335; z-index:1;">
	<table width="100%" height="100%" bgcolor="<?=$corFundo; ?>">
		<TR><TD><center><img src="<?=$fundo; ?>" border="0"></center></TD></TR>
	</table>
</div>
<div style="position:absolute; top:0; left:5; z-index:1;">
	<img src="<?=$images_path?>fundo_cinza.png" border="0"/>
</div>
<div style="position:absolute; top:0; left:480; z-index:1;">
	<img src="<?=$images_path?>fundo_cinza_enviar.png" border="0"/>
</div>
<div style="position:absolute; top:10; left:30; z-index:3;">
	<img src="<?=$images_path?>balao_azul.png" border="0"/>
</div>
<div style="position:absolute; top:50; left:45; z-index:2;">
	<img src="<?=$images_path?>balao_cinza.png" border="0"/>
</div>

<div style="position:absolute; top:20; left:60; z-index:3;">
	<form name='busca' method='post' action='index.php'>
	<table>
		<tr><td>
			<?parteCima($buscaTitulo,$buscaQuem,$buscaPalavras);?>
		</td></tr>
	</table>
	</form>
</div>
<div style="position:absolute; top:25; left:290; z-index:3;">
	<a href="#" onClick="document.busca.submit();"><img src="<?=$images_path?>botao_localizar.png" border="0"/></a>
</div>

<div style="position:absolute; top:70; left:500; z-index:3;">
 	<table>
	 	<tr><td>
		   <?enviaMaterial($codTurma,$codUsuario);?>   
		   </form>
   		</td></tr>
	</table>
</div>
<div style="position:absolute; top:210; left:625; z-index:2;">
	<a href="#" onClick="testaEnvia();"><img src="<?=$images_path?>botao_enviar.png" border="0"/></a>
</div>

<div style="position:absolute; top:110; left:50; z-index:3;">
   <form name='apaga' method='post' action='excluir.php'>
 	<table>
	 	<tr><td>
		   <?listaMateriais($codTurma,$codUsuario,$buscaTitulo,$buscaQuem,$buscaPalavras,1,0,$associacao);?>  
   		</td></tr>
	</table>
   </form>
</div>
<div style="position:absolute; top:215; left:430; z-index:2;">
	<a href="#" onClick="excluir();"><img src="<?=$images_path?>botao_excluir.png" border="0"/></a>
</div>

<div style="position:absolute; top:255; left:0; z-index:2;">
	<? if(($buscaTitulo != "") or ($buscaQuem  != "") or ($buscaPalavras != "")) { ?><a href=<?=$linkVolta?>><img src="<?=$images_path?>botao_voltar.png" border="0"/></a><? } else { ?><a href='<?=$linkVolta;?>'><img src="<?=$images_path?>botao_voltar.png" border="0"/></a><? } ?>
</div>

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
<body bgcolor="<?=$corFundo; ?>">
<script>corDeFundo("<?=$cor; ?>");</script>
<div style="position:absolute; width:760; height:335; z-index:1;">
	<table width="100%" height="100%" bgcolor="<?=$corFundo; ?>">
		<TR><TD><center><img src="<?=$fundo; ?>" border="0"></center></TD></TR>
	</table>
</div>

<div style="position:absolute; top:0; left:5; z-index:1;">
	<img src="<?=$images_path?>fundo_cinza.png" border="0"/>
</div>
<div style="position:absolute; top:0; left:480; z-index:1;">
	<img src="<?=$images_path?>fundo_cinza_enviar.png" border="0"/>
</div>
<div style="position:absolute; top:10; left:30; z-index:3;">
	<img src="<?=$images_path?>balao_azul.png" border="0"/>
</div>
<div style="position:absolute; top:50; left:45; z-index:2;">
	<img src="<?=$images_path?>balao_cinza.png" border="0"/>
</div>

<div style="position:absolute; top:20; left:60; z-index:3;">
	<form name='busca' method='post' action='index.php'>
	<table>
		<tr><td>
			<?parteCima($buscaTitulo,$buscaQuem,$buscaPalavras);?>
		</td></tr>
	</table>
	</form>
</div>
<div style="position:absolute; top:25; left:290; z-index:3;">
	<a href="#" onClick="document.busca.submit();"><img src="<?=$images_path?>botao_localizar.png" border="0"/></a>
</div>

<div style="position:absolute; top:70; left:500; z-index:3;">
 	<table>
	 	<tr><td>
		   <?enviaMaterial($codTurma,$codUsuario);?>   
		   </form>
   		</td></tr>
	</table>
</div>
<div style="position:absolute; top:210; left:625; z-index:2;">
	<a href="#" onClick="testaEnvia();"><img src="<?=$images_path?>botao_enviar.png" border="0"/></a>
</div>

<div style="position:absolute; top:110; left:50; z-index:3;">
   <form name='apaga' method='post' action='excluir.php'>
 	<table>
	 	<tr><td>
   			<?listaMateriais($codTurma,$codUsuario,$buscaTitulo,$buscaQuem,$buscaPalavras,0,0,$associacao);?>
   		</td></tr>
	</table>
   </form>
</div>

<div style="position:absolute; top:255; left:0; z-index:2;">
	<? if(($buscaTitulo != "") or ($buscaQuem  != "") or ($buscaPalavras != "")) { ?><a href=<?=$linkVolta?>><img src="<?=$images_path?>botao_voltar.png" border="0"/></a><? } else { ?><a href='<?=$linkVolta;?>'><img src="<?=$images_path?>botao_voltar.png" border="0"/></a><? } ?>
</div>

</body>
</html>

<? } ?>
<?
//para alunos em turmas q não está autorizada a inserção de material por alunos
if(($associacao=='A')and($autoriza!=1)){
?> 
<html>
<head>
<title>Biblioteca0002.png</title>
<meta http-equiv="Content-Type" content="text/html;">
<!--Fireworks MX 2004 Dreamweaver MX 2004 target.  Created Wed Apr 26 10:15:31 GMT-0300 (Hora oficial do Brasil) 2006-->
<link href="<?=$base_loc; ?>/pngfix.css" rel="stylesheet" type="text/css">
<link href="biblioteca.css" rel="stylesheet" type="text/css">
<script src="<?=$base_loc; ?>/config/corFundo.js"></script>
</head>
<body bgcolor="<?=$corFundo; ?>">
<script>corDeFundo("<?=$cor; ?>");</script>
<div style="position:absolute; width:760; height:335; z-index:1;">
	<table width="100%" height="100%" bgcolor="<?=$corFundo; ?>">
		<TR><TD><center><img src="<?=$fundo; ?>" border="0"></center></TD></TR>
	</table>
</div>

<div style="position:absolute; top:0; left:5; z-index:1;">
	<img src="<?=$images_path?>fundo_cinza.png" border="0"/>
</div>
<div style="position:absolute; top:10; left:30; z-index:3;">
	<img src="<?=$images_path?>balao_azul.png" border="0"/>
</div>
<div style="position:absolute; top:50; left:45; z-index:2;">
	<img src="<?=$images_path?>balao_cinza.png" border="0"/>
</div>

<div style="position:absolute; top:20; left:60; z-index:3;">
	<form name='busca' method='post' action='index.php'>
	<table>
		<tr><td>
			<?parteCima($buscaTitulo,$buscaQuem,$buscaPalavras);?>
		</td></tr>
	</table>
	</form>
</div>
<div style="position:absolute; top:25; left:290; z-index:3;">
	<a href="#" onClick="document.busca.submit();"><img src="<?=$images_path?>botao_localizar.png" border="0"/></a>
</div>

<div style="position:absolute; top:110; left:50; z-index:3;">
   <form name='apaga' method='post' action='excluir.php'>
 	<table>
	 	<tr><td>
   			<?listaMateriais($codTurma,$codUsuario,$buscaTitulo,$buscaQuem,$buscaPalavras,0,0,$associacao);?>
   		</td></tr>
	</table>
   </form>
</div>

<div style="position:absolute; top:255; left:0; z-index:2;">
	<? if(($buscaTitulo != "") or ($buscaQuem  != "") or ($buscaPalavras != "")) { ?><a href=<?=$linkVolta?>><img src="<?=$images_path?>botao_voltar.png" border="0"/></a><? } else { ?><a href='<?=$linkVolta;?>'><img src="<?=$images_path?>botao_voltar.png" border="0"/></a><? } ?>
</div>

</body>
</html>
<? } ?>