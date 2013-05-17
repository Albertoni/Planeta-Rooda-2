<?php
/*
*	Sistema do forum
*
*/



class itemMsg { //estrutura para o item post do forum, chamado de mensagem
	var $msgId = 0;
	var $msgPai = 0;
	var $msgData = '';
	var $msgUserId = 0;
	var $msgUserName = '';
	var $msgTitulo = '';
	var $msgTexto = '';
	var $msgQntFilhos = 0;
	var $msgLink = '';
	var $msgGrau = 0;
	
	function itemMsg($id, $uid, $uname, $pai, $qnt, $data, $conteudo, $grau,$titulo=''){
		$this->msgId = $id;
		$this->msgPai = $pai;
		$this->msgData = $data;
		$this->msgUserId = $uid;
		$this->msgUserName = $uname;
		$this->msgTitulo = $titulo;
		$this->msgTexto = $conteudo;
		$this->msgQntFilhos = $qnt;
		$this->msgGrau = $grau;
	}
}

function pegaData(){
	$today = getdate();
	$dia = $today['mday'];
	$mes = $today['mon'];
	$ano = $today['year'];
	$hora = $today['hours']."h";
	$minutos = $today['minutes']."min";
	$segundos = $today['seconds'];
	return "$dia/$mes/$ano,$hora $minutos";  // Esse return explica tudo.
}

function string2consulta($t, $str){ // Usado em pesquisa_forum pra repassar pro pesquisa da classe forum, constroi uma substring de pesquisa.
	if (trim($str) != ""){
		if ($t == '0'){
			$campo = 'msg_titulo';
		}else if ($t == '1'){
				$campo = 'usuario_nome';
			}else if ($t == '2'){
					$campo = 'msg_conteudo';
				}else{
					$campo = '';
				}
		
		$elementos = explode(" ", trim($str));
		$cel = count($elementos);
		for ($i=0; $i <$cel; $i++){
			if (empty($elementos[$i]))
				unset ($elementos[$i]);
			else
				$elementos[$i] = "$campo LIKE '%$elementos[$i]%'";
		}
		$consulta = implode(" AND ",$elementos);
		return "($consulta)";
	}else{
		return false;
	}
}

function troca(&$v1, &$v2){ // Eu acho que troca o valor de v1 com o de v2. Sinceramente espero que faça isso, pelo menos.
	$vaux = $v1;
	$v1 = $v2;
	$v2 = $vaux;
}


//ordena
function quicksort(&$vet, $ini, $fim){
	if ($ini < $fim){
		$k = divide($vet, $ini, $fim);
		quicksort($vet, $ini, $k-1);
		quicksort($vet, $k+1, $fim);
	}
}		//divide o array em dois
		function divide(&$vet, $ini, $fim){
			$i = $ini;
			$j = $fim;
			$dir = 1;

			while ($i < $j){
				if ($vet[$i]->msgId > $vet[$j]->msgId){
					troca($vet[$i], $vet[$j]);
					$dir = - $dir;
				}
				if ($dir == 1) {
					$j--;
				}else{
					$i++;
				}
			}
			return $i;
		}

class topico {
	
}


class dadosForum {
	/*\
	 * 
	 * Classe voltada SOMENTE a CARREGAR ou SALVAR os dados.
	 * Ver visualizacaoForum para onde o HTML é gerado.
	 * 
	 * Construtor requer a id da turma.
	 * 
	 * carregaTopicos: retorna a lista de topicos no forum daquela turma.
	 * carregaMensagems(int idTopico): Retorna a lista de mensagens do topico.
	 * 
	 * 
	 * 
	 * 
	 * 
	\*/
	
	public $idTurma;
	private $listaTopicos = array();
	
	function __construct($idTurma){
		$this->idTurma = (int) $idTurma;
		
	}
	
	function carregaTopicos(){
		if(empty($this->listaTopicos)){
			$idTurma = $this->idTurma;
			$q = new conexao();
			$q->solicitar("SELECT * FROM ForumTopico WHERE idTurma = $idTurma");
			
			print_r($q);
			
		}else{
			return $this->listaTopicos;
		}
	}
}


?>
