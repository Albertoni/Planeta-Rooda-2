<?php	
	session_start();
	
	//$atualizacao = date("Y-m-j H:i:s");

	//arquivos necessários para o funcionamento
	require_once("cfg.php");
	require_once("bd.php");
	require_once("funcoes_aux.php");

			$bd = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);	
		  	$bd->solicitar("SELECT * FROM `erros_db` ORDER BY `erros_db_id` DESC");
				
			if($bd->erro == "") 
			{ 
				for($j=0;$j<$bd->registros;$j++) {

	

						$erros_db_id	  = $bd->resultado['erros_db_id'];
						$erros_db_texto   = $bd->resultado['erros_db_texto'];
						
						echo $erros_db_id." @ ".$erros_db_texto."<br>";


							
					$bd->proximo();
				} //for($i=0;$i<$pesquisa1->registros;$i++)	
			} //if($bd->erro== "")	
			
			$bd->primeiro ();
			$ultimo_registro = $bd->resultado['erros_db_id'];
			$ultimo_registro = $ultimo_registro - 14;
			
			$bd->solicitar("DELETE FROM `erros_db` WHERE `erros_db_id` < $ultimo_registro");
			
?>