<?php
	session_start();

	//arquivos necessários para o funcionamento
	require_once("../cfg.php");
	require_once("../bd.php");
	require_once("../funcoes_aux.php");

	//ATENÇÃO: Todos os links à seguir devem ser iniciados por "http://"
	$linkServidor = "http://sideshowbob/asd/";
	
	$_SESSION['SS_link_pai'] = $_SERVER['REQUEST_URI'];		//Variável de sessão utilizada para guardar o caminho atual no ambiente, de forma a oreintar o sistema 1em relação as opções de voltar das funcionalidades - Guto - 09.04.10
	$personagem_id = $_SESSION['SS_personagem_id'];

	$pesquisa1 = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);
	$pesquisa1->solicitar("select * from $tabela_personagens where personagem_id='$personagem_id' limit 1");
	$terreno_id = $pesquisa1->resultado['personagem_terreno_id'];
	$pesquisa1->solicitar("select * from $tabela_terrenos where terreno_id='$terreno_id' limit 1");
	$grupo_id = $pesquisa1->resultado['terreno_grupo_id'];
	$_SESSION['SS_grupo_id']  = $grupo_id;  

	/*
	@author Yuri Pelz Gossmann
	@date 2012-08-09 -> 2012-08-20
	INÍCIO
	*/
	$acessoPlaneta=new conexao();
	$acessoPlaneta->solicitarSI('SELECT id_acesso,id_terreno,funcionalidade,data_hora
								 FROM '.$tabela_acessos_planeta.'
								 WHERE id_acesso=(SELECT MAX(id_acesso)
												  FROM '.$tabela_acessos_planeta.'
												  WHERE id_usuario='.intval($_SESSION['SS_usuario_id']).')');
	if(isset($_GET['funcionalidade'])&&(strtolower($_GET['funcionalidade'])==='true')&&isset($_GET['linkColorBox'])){
		/*
		@author Diogo
		INÍCIO
		*/
		if(isset($_GET['vem_de_casinha'])){
			$pesquisa1->solicitar("select * from $tabela_personagens where personagem_id='$personagem_id' limit 1");
			$pos_y = $pesquisa1->resultado['personagem_posicao_y']-=100;
			$pesquisa1->solicitar("UPDATE $tabela_personagens where personagem_id='$personagem_id' SET personagem_posicao_y=$pos_y");
		}
		/* FIM */
		
		$linkColorBox=strtolower($_GET['linkColorBox']);
		switch(substr($linkColorBox,0,31)){
			case 'funcionalidades/biblioteca/bibl': if(substr($linkColorBox,31,10)==='ioteca.php')
														$funcionalidade='biblioteca';
													else
														$funcionalidade='';
													break;
			case 'funcionalidades/blog/blog_inici': if(substr($linkColorBox,31,5)==='o.php')
														$funcionalidade='blog';
													else
														$funcionalidade='';
													break;
			case 'funcionalidades/forum/forum.php': $funcionalidade='forum';
													break;
			case 'funcionalidades/portfolio/portf': if(substr($linkColorBox,31,8)==='olio.php')
														$funcionalidade='portfolio';
													else
														$funcionalidade='';
													break;
			case 'funcionalidades/criar_personage': if(substr($linkColorBox,31,34)==='m/criar_personagem.php?id_char_as=')
														$funcionalidade='aparencia';
													else
														$funcionalidade='';
													break;
			case 'funcionalidades/arte/planeta_ar': if(substr($linkColorBox,31,7)==='te2.php')
														$funcionalidade='arte';
													else
														$funcionalidade='';
													break;
			case 'funcionalidades/pergunta/planet': if(substr($linkColorBox,31,14)==='a_pergunta.php')
														$funcionalidade='pergunta';
													else
														$funcionalidade='';
													break;
			case 'funcionalidades/aulas/planeta_a': if(substr($linkColorBox,31,8)==='ulas.php')
														$funcionalidade='aulas';
													else
														$funcionalidade='';
													break;
			case 'funcionalidades/roodaplayer/ind': if(substr($linkColorBox,31,6)==='ex.php')
														$funcionalidade='player';
													else
														$funcionalidade='';
													break;
			case 'funcionalidades/gerenciamento_f': if(substr($linkColorBox,31,31)==='uncionalidades_turmas/index.php')
														$funcionalidade='gerenc_funcio_turmas';
													else
														$funcionalidade='';
													break;
			default: $funcionalidade='';
					 break;
		}
	}
	else
		$funcionalidade='';
	$agora=date('Y-m-d H:i:s');
	$duracao=intval(strtotime($agora)-strtotime($acessoPlaneta->resultado['data_hora']));
	if($acessoPlaneta){
		$acessoPlaneta->solicitarSI('UPDATE '.$tabela_acessos_planeta.'
									 SET duracao='.$duracao.'
									 WHERE id_acesso='.$acessoPlaneta->resultado['id_acesso']);
		if(((intval($terreno_id)!=intval($acessoPlaneta->resultado['id_terreno']))||($funcionalidade!=$acessoPlaneta->resultado['funcionalidade']))&&($duracao>1)){
			$acessoPlaneta->solicitarSI('INSERT
										 INTO '.$tabela_acessos_planeta.' (id_usuario,id_terreno,funcionalidade,data_hora,duracao)
										 VALUES ('.intval($_SESSION['SS_usuario_id']).','.intval($terreno_id).',"'.$funcionalidade.'","'.$agora.'",0)');
		}
	}
	else
		$acessoPlaneta->solicitarSI('INSERT
									 INTO '.$tabela_acessos_planeta.' (id_usuario,id_terreno,funcionalidade,data_hora,duracao)
									 VALUES ('.intval($_SESSION['SS_usuario_id']).','.intval($terreno_id).',"'.$funcionalidade.'","'.$agora.'",0)');
	/*
	FIM
	*/

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<style type="text/css">
	body {height:100%; padding:0; margin:0}
	html {height:100%; padding:0; margin:0}
	#redimensionar {height:100%; width:100%; position:absolute; background-color:#abc}
	</style>
	<title>movimenta&ccedil;&atilde;o e conversa</title>
	<link type="text/css" media="screen" rel="stylesheet" href="colorbox/colorbox.css" />
	<script type="text/javascript" src="colorbox/jquery.js"></script>
	<script type="text/javascript" src="colorbox/jquery.colorbox.js"></script>
	<script type="text/javascript">	
		function chamaLink(link) {
			$.fn.colorbox({href: link, width:"80%", height:"85%", iframe:true, onCleanup:function(){ /*location.replace('index.php');*/ }});
		}
	</script>
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
	$personagem_id =      $_SESSION['SS_personagem_id'];

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
	
	//animação de entrada
	$conexao_aparencia_planeta = new conexao();
	$conexao_aparencia_planeta->solicitar("SELECT *
								FROM terrenos AS T JOIN Planetas AS P ON P.Id = T.terreno_grupo_id
								WHERE T.terreno_id = $terreno_id");
	
	$animacao = "";
	$direcaoAnimacao = '';
	$fundoQuarto = "";
	if($conexao_aparencia_planeta->resultado['Aparencia'] == '6'){
		$animacao = "predio";
		$direcaoAnimacao = '1';
		$conexao_personagem = new conexao();
		$conexao_personagem->solicitar("SELECT * 
										FROM $tabela_personagens 
										WHERE personagem_id=$personagem_id");
		$terreno_acesso_agora_id = $conexao_personagem->resultado['personagem_terreno_id'];
		if($terreno_id == $terreno_acesso_agora_id){
			$id_terreno_que_contem_ultimo_predio_acessado = $conexao_personagem->resultado['personagem_ultimo_terreno_id'];
		} else {
			$id_terreno_que_contem_ultimo_predio_acessado = $conexao_personagem->resultado['personagem_terreno_id'];
		}
		$conexao_aparencia_planeta->solicitar("SELECT *
								FROM terrenos AS T JOIN Planetas AS P ON P.Id = T.terreno_grupo_id
								WHERE T.terreno_id = $id_terreno_que_contem_ultimo_predio_acessado");
		$fundoQuarto = $conexao_aparencia_planeta->resultado['Aparencia'];
	} else {
		$conexao_personagem = new conexao();
		$conexao_personagem->solicitar("SELECT * 
										FROM $tabela_personagens 
										WHERE personagem_id=$personagem_id");
		$terreno_acesso_agora_id = $conexao_personagem->resultado['personagem_terreno_id'];
		$conexao_aparencia_planeta->solicitar("SELECT *
								FROM terrenos AS T JOIN Planetas AS P ON P.Id = T.terreno_grupo_id
								WHERE T.terreno_id = $terreno_acesso_agora_id");
		if($conexao_aparencia_planeta->resultado['Aparencia'] == '6'){
			$animacao = "predio";
			$direcaoAnimacao = '2';
			$fundoQuarto = $conexao_aparencia_planeta->resultado['Aparencia'];
		} else {
			$animacao = "foguete";
		}
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
			
				//funcionalidade
		if(isset($_GET['funcionalidade'])){
			$funcionalidade = $_GET['funcionalidade'];
		}
		else{
			$funcionalidade = false;
		}
				//linkExterno
		if(isset($_GET['linkExterno'])){
			$linkExterno = $_GET['linkExterno'];
		}
		else{
			$linkExterno = false;
		}
				//linkColorBox
		if(isset($_GET['linkColorBox'])){
			$linkColorBox = $_GET['linkColorBox'];
		}
		else{
			$linkColorBox = false;
		}
		
		?>			
		<div id="redimensionar">
			<object id="flash" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" 
			  codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" 
			  width="100%" height="100%" id="loader" align="middle">
			<param name="allowScriptAccess" value="sameDomain" />
			<param name="allowFullScreen" value="true" />
			<param name="movie" value="loader.swf" />
			<param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />
		<?php		
		if ($funcionalidade == "true") {	
		?>			
			<param name="wmode" value="opaque" />
			<param name= "flashvars" value="wmode=opaque&funcionalidade=true&linkColorBox=<?=$linkColorBox?>&linkServidor=<?=$linkServidor?>" />
			  <embed src="loader.swf" quality="high" bgcolor="#ffffff" wmode = "opaque" flashvars = "funcionalidade=true&linkColorBox=<?=$linkColorBox?>&linkServidor=<?=$linkServidor?>"
		<?php
		} else if ($linkExterno == "true") {
		?>			
			<param name="wmode" value="opaque" />
			<param name= "flashvars" value="wmode=opaque&linkExterno=true&linkColorBox=<?=$linkColorBox?>&linkServidor=<?=$linkServidor?>" />
			  <embed src="loader.swf" quality="high" bgcolor="#ffffff" wmode = "opaque" flashvars = "linkExterno=true&linkColorBox=<?=$linkColorBox?>&linkServidor=<?=$linkServidor?>"
		<?php
		} else {
		?>			
			<param name="wmode" value="window" />
			<param name= "flashvars" value="animacao=<?=$animacao?>&direcaoAnimacao=<?=$direcaoAnimacao?>&fundoQuarto=<?=$fundoQuarto?>" />
			<param name="movie" value="loader.swf" />
			  <embed src="loader.swf" quality="high" bgcolor="#ffffff" wmode = "window" flashvars = "animacao=<?=$animacao?>&direcaoAnimacao=<?=$direcaoAnimacao?>&fundoQuarto=<?=$fundoQuarto?>"
		<?php
		}
		?>
				width="100%" height="100%" name="loader" align="middle" 
				allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" 
				pluginspage="http://www.macromedia.com/go/getflashplayer" />
			</object> 
		</div>
		<?php
	}//if(($personagem_id   != "") || ($terreno_id != "")) {  
    ?>
</body>
</html>
