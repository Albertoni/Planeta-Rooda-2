<?php
	require("cfg.php");
	require("bd.php");

	$data="";
	$pesquisa1 = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);
	if($pesquisa1->erro != "") 
	{ 
		$data .= '{ "valor":"0", "nivel":"erro"},';
	}else{
		$pesquisa1->solicitar("select * from $tabela_nivel_permissoes ORDER BY nivel");
		if($pesquisa1->erro!= "") 
		{ 
			$data .= '{ "valor":"1", "nivel":"erro"},';
		}else{
			for ($c=0; $c<$pesquisa1->registros; $c++){
				if ($pesquisa1->resultado['nivel'] != 0){
					$numero = $pesquisa1->resultado['nivel'];
					$nome = $pesquisa1->resultado['nivel_nome'];
					$data .= '{ "valor":"'.$numero.'", "nivel":"'.$nome.'"},';
				}
				$pesquisa1->proximo();
			}
			
		}
	}

	$data = '['.substr($data, 0, -1).']';
	header('Content-type: application/json; charset="utf-8"', true);
	echo '{"niveis":'.$data.'}';		
	
?>