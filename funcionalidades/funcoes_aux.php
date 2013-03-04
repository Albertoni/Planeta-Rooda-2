<?php
//---------------------------------------------------------------
//Funções Comuns
//---------------------------------------------------------------

	function resolucao($screen_res) {
		
		if($screen_res != "") { 
			$_SESSION['resolucao'] = $screen_res;
		}
		
		if(isset($_SESSION["resolucao"])){
			$screen_res = intval($_SESSION["resolucao"]);
		}else{
		?>
			<script language="javascript">
			<!--
			resolucao_encaminhar();
			function resolucao_encaminhar(){
				location.href = 'index.php?screen_res='+ screen.width;
			}
			//-->
			</script>
		<?php
		}
		return $screen_res;
	}
	//função para arrumar data vinda do banco de dados
	function comum_arrumar_data($data) {
		$nova_data_array = explode("-",$data);
		$nova_data = $nova_data_array[2]."-".$nova_data_array[1]."-".$nova_data_array[0];
		return $nova_data;
	}
	
	//função para arrumar data vinda do banco de dados 2
	function comum_arrumar_data_hora($data) {
		$nova_data_array = explode(" ",$data);
		$nova_data_array_dia = explode("-",$nova_data_array[0]);
		$nova_data = $nova_data_array_dia[2]."-".$nova_data_array_dia[1]."-".$nova_data_array_dia[0]." ".$nova_data_array[1];
		return $nova_data;
	}
	
	// função usada para validar data
	function comum_conferir_data ($data) {
		$data_array = explode("-",$data);
		$dia = $data_array[2];
		$mes = $data_array[1];
		$ano = $data_array[0];
		
		if ( (($ano % 4) == 0) && ($mes == 2) && ($dia > 29) )
		// se o mês for fevereiro e o ano for bissexto, dia não pode
		// ser maior que 29
		return 0;
		else if ( (($ano % 4) > 0) && ($mes == 2) && ($dia > 28) )
		// se o mês for fevereiro e o ano não for bissexto, dia não pode
		// ser maior que 28
		return 0;
		else if( (($mes == 4) || ($mes == 6) || ($mes == 9) || ($mes == 11) ) && ($dia == 31))
		// se o mês for Abril, Junho, Setembro ou Novembro, dia não pode ser 31
		return 0;
		else
		return 1;
	}
	
	//enviar email
	function comum_enviar_email ($destinatario,$assunto,$mensagem,$remetente) {

		$sucesso = mail($destinatario, $assunto, $mensagem, "From: $remetente");
		//echo $mensagem;
	
		if($destinatario=="" || $assunto=="" || $mensagem=="" || $remetente=="" || $sucesso==false) {
			$status=0;
		} else {
			$status=1;
		}
		
		return $status;
		
	}
	

?>
