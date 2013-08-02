<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	require_once("../../cfg.php");
	require_once("../../bd.php");

	//$id = $_POST['dado_pesquisado'];
	//$posicao_dado_para_retorno = $_POST['pos_tupla_resultado_pesquisa'];
	
	$consulta = new conexao();
	$consulta->solicitar("SELECT * FROM Anos");
	
	$dados = '&dado_pesquisado='.$id;
	
	$numDadosEncontrados = 0;
	for ($i=1;$i<=count($consulta->itens);$i++){
		$numDadosEncontrados = $numDadosEncontrados + 1;
		
		$dados .= '&ano_nome'.$i.		        '='.$consulta->resultado['nome'];
		
		$consulta->proximo();
	}
	$operacaoRealizadaComSucesso = true;
	$mensagemDeErro = '';
	if($consulta->erro != ''){
		$operacaoRealizadaComSucesso = false;
		$mensagemDeErro = utf8_encode('Não foi possível encontrar os anos na base de dados.');
	}
	
	$erro = $consulta->erro;
	$dados .= '&erro='.$erro; 
	$dados .= '&numDadosEncontrados='.$numDadosEncontrados; 
	$dados .= '&operacaoRealizadaComSucesso='.$operacaoRealizadaComSucesso;
	$dados .= '&mensagemDeErro='.$mensagemDeErro; 

    echo $dados;	
//A partir do fim do php, não escrever absolutamente nada. Nem código. &numDadosEncontrados receberá TUDO o que for escrito.
?>
