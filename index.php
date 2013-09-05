<?php
//================================================
// Area de login teste
//================================================
	require_once("cfg.php");
	require_once("bd.php");
	
	session_start();

	if(isset($_POST["action"])){
		$action = $_POST["action"];
	}else if(isset($_GET["action"])){
		$action = $_GET["action"];
	}else{
		$action = false;
	}
	
//================================================
//Head
//================================================
	
	if($action == "log0001") {

		if(isset($_SESSION['SS_usuario_id'])){ // Caso o cara não esteja logado e acessa a página de logout
			/*
			@author Yuri Pelz Gossmann
			@date 2012-08-08 -> 2012-08-14
			INÍCIO
			*/
			$acessoPlaneta=new conexao(); global $tabela_acessos_planeta;
			$acessoPlaneta->solicitarSI('SELECT id_acesso,data_hora
										 FROM '.$tabela_acessos_planeta.'
										 WHERE id_acesso=(SELECT MAX(id_acesso)
														  FROM '.$tabela_acessos_planeta.'
														  WHERE id_usuario='.intval($_SESSION['SS_usuario_id']).')');
			if($acessoPlaneta->resultado){
				$agora=date('Y-m-d H:i:s');
				$acessoPlaneta->solicitarSI('UPDATE '.$tabela_acessos_planeta.'
											 SET duracao='.intval(strtotime($agora)-strtotime($acessoPlaneta->resultado['data_hora'])).'
											 WHERE id_acesso='.$acessoPlaneta->resultado['id_acesso']);
			}
			/*
			FIM
			*/
			session_destroy();
		}
	}

	$niveis = "";
	$pesquisa1 = new conexao();
	$pesquisa1->solicitar("select * from $tabela_nivel_permissoes ORDER BY nivel");
	for ($c=0; $c<$pesquisa1->registros; $c++){
		if ($pesquisa1->resultado['nivel'] != 1){
			$numero = $pesquisa1->resultado['nivel'];
			$nome = $pesquisa1->resultado['nivel_nome'];
			$niveis .= "				<option value=\"$numero\">$nome</option>\\\n";
		}
		$pesquisa1->proximo();
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="login.css" />
<script>
	var niveis = '<?php echo $niveis; ?>';
</script>
<script type="text/javascript" src="login.js"></script>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="http://www.cornify.com/js/cornify.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="login_ie6.js"></script>
<![endif]-->

</head>

<body onload="entra();" onresize="ajusta();">

<div id="fundo_ilust">
<img src="images/desenhos/fundo_ilust.png" />
</div>
<div id="ilust">
<span onclick="cornify_add();return false;"><div style="position:absolute;width:50px;height:100px"></div></span>
<img src="images/desenhos/capa_planeta.png" />
</div>

<!-- caixa de alerta que aparece ao confirmar o cadastro -->
<div id="mascara" onmousedown="fechar()"></div>
<div id="alerta" onmousedown="fechar();inicia('cadastro(5)');">
<p id="txt_alerta" align="center"></p>
</div>

<!-- estrutura para login -->
<div id="sustenta_login">
	<div id="logo"><img src="images/desenhos/logo_planeta.png" id="img_logo" /></div>
	<div id="caixa_login">
		<ul>
			<li> <label for="login1">APELIDO</label> </li>
			<li> <input id="login1" name="login1" type="text" class="campo_texto" autofocus /> </li>
			<li> <label for="password1">SENHA</label> </li>
			<li> <input id="password1" name="password1" type="password" class="campo_texto" onkeypress="return captureKeys(event);" /> </li>
			<li> <a href="#" onclick="abaDireita('senha');">Esqueci minha senha</a> </li>
			<li> <center><input type="button" value="" id="botao_entrar" onfocus="this.blur()" onClick="login();" /></center></li>
		</ul>
	</div> <!-- fim da caixa_login-->
	<div id="caixa_criar">
		<ul>
			<li>
				<center><label for="cadastro"><p>Esta é sua primeira vez?</p>
				Crie seu personagem ou veja o tutorial!</label></center>
			</li>
			<li>
				<center><input type="button" value="" class="botao_cadastro" 
				onfocus="this.blur()" onclick="abaDireita('cadastro');" />
				<input type="button" value="" class="botao_tutorial" 
				onfocus="this.blur()" onclick="alert('Em construção');" /></center>
			</li>
		</ul>
	</div> 
	<!-- fim da caixa_cadastro-->
</div><!-- fim do sustenta_login-->

<!-- estrutura para cadastro -->
<div id="sustenta_cadastro">
	<div id="cadastro_topo"><p align="center" id="tituloDir">CRIAR USUÁRIO</p></div>
	<div id="caixa_cadastro">
		<ul>
			<li> <label for="nome_completo">NOME COMPLETO</label> </li>
			<li> <input id="nome_completo" name="nome_completo" type="text" class="campo_texto" /> </li>
			<li> <label for="criar_apelido"><p style="width:48%; float:left">APELIDO</p></label> 
			<label for="sexo"><p style="width:48%; float:right">SEXO</p></label></li>
			<li> <input id="criar_apelido" name="criar_apelido" type="text" class="campo_texto" style="width:48%; float:left" /> 
			<select id="sexo" name="sexo" class="campo_texto" style="width:48%; float:right">
			<option>Masculino</option>
			<option>Feminino</option>
			</select></li>
			<li> <label for="criar_senha">SENHA</label> </li>
			<li> <input id="criar_senha" name="criar_senha" type="password" class="campo_texto" /> </li>
			<li> <label for="confirmar_senha">CONFIRMAR SENHA</label> </li>
			<li> <input id="confirmar_senha" name="confirmar_senha" type="password" class="campo_texto" /> </li>
			<li> <label for="email">E-MAIL</label> </li>
			<li> <input id="email" name="email" type="text" class="campo_texto" /> </li>
			<li> <label for="nivel">NÍVEL</label> </li>
			<li><select class="campo_texto" id="nivel" name="nivel">
<?php echo $niveis; ?>
				</select>
			</li>
			<li> <center><div id="botao_confirmar" onmousedown="criarPerson();">
			</div><div id="botao_tutorial" onmousedown="criarPerson();">
			</div></center></li>
		</ul>
	</div>
</div><!-- fim da sustenta_cadastro-->

</body>
</html>
