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

class topico{
	private $idTopico;
	private $idTurma;
	private $idUsuario;
	private $titulo;
	private $date;
	
	function __construct($idTopico, $idTurma = NULL, $idUsuario = NULL, $titulo = "NULL", $date = NULL){
		if($idTurma === NULL){// o cara que mandar a porra da id de turma que for === null que se vire
			$this->loadTopico($idTopico);
		}else{
			$this->idTopico	= $idTopico;
			$this->idTurma	= $idTurma;
			$this->idUsuario= $idUsuario;
			$this->titulo	= $titulo;
			$this->date		= $date;
		}
	}

	function loadTopico($id){
		$q = new conexao();
		$q->solicitar("SELECT * FROM ForumTopico WHERE idTopico = $idTopico");

		if($q->erro == ""){
			$this->idTopico	= $q->resultado['idTopico'];
			$this->idTurma	= $q->resultado['idTurma'];
			$this->idUsuario= $q->resultado['idUsuario'];
			$this->titulo	= $q->resultado['titulo'];
			$this->date		= $q->resultado['date'];
		}
	}
}


class forum {
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
	public $numPaginas;
	
	function __construct($idTurma){
		$this->idTurma = (int) $idTurma;
	}
	
	function carregaTopicos(){
		if(empty($this->listaTopicos)){
			$idTurma = $this->idTurma;
			$q = new conexao();
			$q->solicitar("SELECT * FROM ForumTopico WHERE idTurma = $idTurma");

			$numPaginas = floor($q->registros / 10); // 1 pagina por 10 topicos, COMEÇA NO ZERO
			
			for($i=0; $i < ($q->registros); $i+=1){
				$idTopico = $q->resultado['idTopico'];
				$idTurma = $q->resultado['idTurma'];
				$idUsuario = $q->resultado['idUsuario'];
				$titulo = $q->resultado['titulo'];
				$date = $q->resultado['date'];

				$topicoLoop = new topico($idTopico, $idTurma, $idUsuario, $titulo, $date);
				$listaTopicos[] = $topicoLoop; // appends
			}
			
			return $this->listaTopicos;

		}else{
			return $this->listaTopicos;
		}
	}
}


class visualizacaoForum extends forum{

	function imprimeTopicos(){
		if(empty($this->listaTopicos)){
			return "ops ops ops ops osp osposposp ospo pso sp opsopsospopsosp";
		}else{
			$html = "";
			foreach ($this->listaTopicos as $indice => $topico) {
				
			}
		}
	}

	function imprimePaginas(){

	}
}