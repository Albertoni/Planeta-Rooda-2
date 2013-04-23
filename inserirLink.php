<?php   
/* 
 * inserirLink.php
 */
require("cfg.php");
require("bd.php");    
require("usuarios.class.php");
require("link.class.php");
$json = array();
$endereco = isset($_POST['novoLink']) ? trim($_POST['novoLink']) : '';
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
				$json['id'] = $link->getId();
				$json['endereco'] = $link->getLink();
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
