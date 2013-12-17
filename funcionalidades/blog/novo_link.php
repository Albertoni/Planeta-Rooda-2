<?php	
require_once("../../cfg.php");
require_once("../../bd.php");	
require_once("../../usuarios.class.php");
require_once("../../link.class.php");
require_once("blog.class.php");

$json = array();

$endereco = isset($_POST['novoLink']) ? $_POST['novoLink'] : '';
$funcionalidade_tipo = isset($_GET['funcionalidade_tipo']) ? (int) $_GET['funcionalidade_tipo'] : 0;
$funcionalidade_id = isset($_GET['funcionalidade_id']) ? (int) $_GET['funcionalidade_id'] : 0;

if ($funcionalidade_id > 0 && $funcionalidade_tipo > 0)
{
	$json['endereco'] = $endereco;
	$json['funcionalidade_tipo'] = $funcionalidade_tipo;
	$json['funcionalidade_id'] = $funcionalidade_id;

	if ($endereco != "") {
		$link = new Link($endereco, $funcionalidade_tipo, $funcionalidade_id);
		
		if ($link->temErro()){
			$json['errors'] = $link->getErrosArray();
		}
		else
		{
			$json['ok'] = true;
		}
	}
	else
	{
		$json['errors'][] = "Digite um link antes de enviar";
	}
}
else
{
	$json['errors'][] = "Link não enviado.";
}
echo json_encode($json);
