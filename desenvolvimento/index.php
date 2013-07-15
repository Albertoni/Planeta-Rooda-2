<?php
	session_start();

	//arquivos necessários para o funcionamento
	require("../cfg.php");
	require("../bd.php");
	require("../funcoes_aux.php");
	require("flash.class.php");

	//ATENÇÃO: Todos os links à seguir devem ser iniciados por "http://"
	global $linkServidor;
	
	$_SESSION['SS_link_pai'] = $_SERVER['REQUEST_URI'];		//Variável de sessão utilizada para guardar o caminho atual no ambiente, de forma a oreintar o sistema 1em relação as opções de voltar das funcionalidades - Guto - 09.04.10
	$personagem_id = $_SESSION['SS_personagem_id'];

	$pesquisa1 = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);
	$pesquisa1->solicitar("select * from $tabela_personagens where personagem_id='$personagem_id' limit 1");
	$terreno_id = $pesquisa1->resultado['personagem_terreno_id'];
	$pesquisa1->solicitar("select * from $tabela_terrenos where terreno_id='$terreno_id' limit 1");
	$grupo_id = $pesquisa1->resultado['terreno_grupo_id'];
	$_SESSION['SS_grupo_id']  = $grupo_id;  

	
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<style type="text/css">
	body {height:100%; padding:0; margin:0}
	html {height:100%; padding:0; margin:0}
	#conteudoFlash {height:100%; width:100%; position:absolute; background-color:#abc}
	</style>
	<title>movimenta&ccedil;&atilde;o e conversa</title>
	<!--<link type="text/css" media="screen" rel="stylesheet" href="colorbox/colorbox.css" />
	<script type="text/javascript" src="colorbox/jquery.js"></script>
	<script type="text/javascript" src="colorbox/jquery.colorbox.js"></script>-->
	<link media="screen" rel="stylesheet" href="colorbox.css" />
	<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
	<script src="/../jquery.js"></script>
	<script src="colorbox/jquery.colorbox.js"></script>
<?php
	//ajuste da resolução					
	if(isset($_GET["screen_res"])){
		$screen_res = $_GET["screen_res"];
	}
	else{
		$screen_res = false;
	}
	$screen_res = resolucao($screen_res);
	/*
	$screen_height = $_GET["screen_height"];
	$screen_width = $_GET["screen_width"];
	if(!isset($_GET["screen_height"]) or !isset($_GET["screen_width"])){		//Só agora ele chyama a função para não entrar num looping infinito - Guto - 26.05.10
		resolucao();
	}
	*/
	?>
</head>
<body>
    <?php     
	//procurando variavel $personagem_id      
	$personagem_id = $_SESSION['SS_personagem_id'];

	//procurando variavel $terreno_id 
	$terreno_id = "";
	
	if(isset($_GET['terreno_id_tela_inicial_geral'])){
		$terreno_id = $_GET['terreno_id_tela_inicial_geral'];
	} else if(isset($_POST['terreno_id'])){
		$terreno_id = $_POST['terreno_id'];
	} else if(isset($_GET['terreno_id'])){
		$terreno_id = $_GET['terreno_id'];
	} else {
		$terreno_id = false;
	}
	
	//Se $terreno_id não foi enviado nem por POST nem por GET, pega do banco de dados, na coluna referente ao referente $personagem_id - Guto - 08.09.08   
	if ($terreno_id == "") {
		$pesquisa0 = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);
		$pesquisa0->solicitar("SELECT * FROM `$tabela_personagens` WHERE personagem_id='$personagem_id'");
		$terreno_id = $pesquisa0->resultado['personagem_terreno_id'];
	}
		
	//com $terreno_id e $personagem_id inicia
	if(($personagem_id   != "") || ($terreno_id != "")) { 

		//atualizando dados da sessao
		$_SESSION['SS_terreno_id'] = $terreno_id;
		
		//Equação para calcular o coeficiente de ajuste de dimensões da tela para as diferente resoluções, conforme dados estabelecido pelo Dani - Guto 17.10.08
		$screenAju = ($screen_res*0.0011458) - 0.1664;
	  
		$height = 600*$screenAju;
		$width  = 800*$screenAju;
		/*
		$height = 580*($screen_height/768);
		$width  = 950*($screen_width/1024);
		*/
		
		$flash = new Flash();
		$flash->chamar($terreno_id, $linkServidor);
		
	}//if(($personagem_id   != "") || ($terreno_id != "")) {  
    ?>
	<script type="text/javascript">	
		var colorBoxAberta = false;
	
		function esconderFlash() {
			//var screenshotData = document.getElementById('flashObject').exportScreenshot();
			//document.getElementById('objetoScreenshot').src = 'data:image/jpeg;base64,' + screenshotData;
			//document.getElementById('objetoScreenshot').style.width = flashObject.width;
			//document.getElementById('objetoScreenshot').style.height = flashObject.height;
			document.getElementById('conteudoFlash').style.top = '-10000px';
			document.getElementById('conteudoScreenshot').style.top = '';
		}
		
		function mostrarFlash() {
			document.getElementById('conteudoFlash').style.top = '';
			document.getElementById('conteudoScreenshot').style.top = '-10000px';
		}
		
		function chamaLink(link) {
			esconderFlash();
			if(!colorBoxAberta){
				colorBoxAberta = true;
				$.fn.colorbox({href: link, width:"80%", height:"85%", iframe:true, onCleanup:function(){ 
					mostrarFlash();
					colorBoxAberta = false;
				}});
			}
		}
		
		function colorBoxEstaAberta(){
			return colorBoxAberta;
		}
	</script>
</body>
</html>
