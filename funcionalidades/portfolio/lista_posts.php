<?php
class lista_posts{
	var $lista = array();
	var $tamanho_lista = 0;
	function __construct($id, $tabela_posts){
		$consulta = new conexao();
		$this->lista = array();
		$lista_interna = array();
		
		$consulta->solicitar("SELECT id, titulo, dataCriacao FROM $tabela_posts WHERE projeto_id=$id ORDER BY dataCriacao DESC"); // Pega só os posts publicos.
		//print_r($consulta);
		
		$this->tamanho_lista = count($consulta->itens); // Seta o valor pro for ali embaixo
		
		$ano_atual = 9999999; // I do believe this code will last more than humanity in a working status.
		$mes_atual = 13;
		
		for ($i=0; $i < $this->tamanho_lista; $i++){
			$elemento['ano'] = substr($consulta->resultado['dataCriacao'], 0, 4); // Pega os 4 chars do ano.
			$elemento['mes'] = substr($consulta->resultado['dataCriacao'], 5, 2); // See above
			$elemento['titulo'] = $consulta->resultado['titulo'];
			$elemento['post_id'] = $consulta->resultado['id'];
			
			if ($elemento['ano'] != $ano_atual) {
				if ($ano_atual != 9999999){
					$lista_interna[] = "\nend_month"; // pusha as coisas
					$lista_interna[] = "\nend_year"; // se não é o ano inicial, acaba o anterior
				}
				$ano_atual = $elemento['ano'];
				$lista_interna[] = "\nnew_year";
				$lista_interna[] = $elemento['ano'];
				$mes_atual = 13;
			}
			
			
			
			if ($elemento['mes'] < $mes_atual) {
				if ($mes_atual != 13){
					if ($lista_interna[$i-1] == "\nend_year"){ // NÃO PEÇA. FUNCIONA. DÁ UM PRINT_R PRA VER.
						$lista_interna[] = "\nend_year";
						$lista_interna[$i-1] = "\nend_month";
					} else {
						$lista_interna[] = "\nend_month"; // se não é o mes inicial, acaba o anterior
					}
				}
				$lista_interna[] = "\nnew_month";
				$lista_interna[] = $elemento['mes'];
				$mes_atual = $elemento['mes'];
			}
			
			$lista_interna[] = $elemento['titulo'] ."\n". $elemento['post_id']; // O título SEMPRE vai estar lá.
				// Esse \n aí serve pra separar, já que teoricamente o usuario não conseguiria enviar um \n via input.
				// Sem falar que tem um str_replace que tira \n do título dos posts. Pra GARANTIR GARANTIDO. ~ João, 01 set. 2011
			
			$consulta->proximo();
		}
		$this->lista = $lista_interna;
		$this->tamanho_lista = count($lista_interna); // Seta o valor pra ser utilizado fora do construtor.
	}
}

function getMonth($number){ // usada no portfolio_projeto.php
	switch ($number){
		case 1:
			return "Janeiro";
			break;
		case 2:
			return "Fevereiro";
			break; // Esses breaks tão aí pra garantir a pegada.
		case 3:
			return "Março";
			break;
		case 4:
			return "Abril";
			break;
		case 5:
			return "Maio";
			break;
		case 6: 
			return "Junho";
			break;
		case 7:
			return "Julho";
			break;
		case 8:
			return "Agosto";
			break;
		case 9:
			return "Setembro";
			break;
		case 10:
			return "Outubro";
			break;
		case 11:
			return "Novembro";
			break;
		case 12:
			return "Dezembro";
			break;
	}
	return "Alguma coisa deu muito errado no lista_posts.php. Avise um desenvolvedor disso.";
}
?>
