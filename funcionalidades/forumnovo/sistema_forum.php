<?php
/*
*	Sistema do forum
*
*/



class mensagem { //estrutura para o item post do forum, chamado de mensagem
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
	
	function __construct($id, $uid, $uname, $pai, $qnt, $data, $conteudo, $grau,$titulo=''){
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
	private $idTopico;	function getIdTopico(){return $this->idTopico;}
	private $idTurma;	function getIdTurma(){return $this->idTurma;}
	private $idUsuario;	function getIdUsuario(){return $this->idUsuario;}
	private $titulo;	function getTitulo(){return $this->titulo;}
	private $date;		function getDate(){return $this->date;}
	private $mensagens;	function getMensagens(){return $this->mensagens;}
	
	function __construct($idTopico, $idTurma = NULL, $idUsuario = NULL, $titulo = "NULL", $date = NULL){
		if($idTurma === NULL){// se mandar só a id do topico, abre ele
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

			$q->solicitar("SELECT * FROM ForumMensagens WHERE idTopico = $this->idTopico ORDER BY idMensagem");
			if($q->erro == ""){
				$this->mensagens = array();
				for ($i=0; $i < $q->registros; $i++){
					$mensagem = new mensagem();
					array_push($this->mensagem, $mensagem);
					$q->proximo();
				}
			}
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

			$this->numTopicos = $q->registros;
			
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
			return "Não existem tópicos nessa turma.";
		}else{
			$html = "";
			foreach ($this->listaTopicos as $indice => $topico) {
				$idTopico = $topico->getIdTopico();
				$idTurma = $topico->getIdTurma();
				$idUsuario = $topico->getIdUsuario();
				$date = $topico->getDate();
				$titulo = $topico->getTitulo();
				$link = "forum_topico.php?turma=$idTurma&amp;topico=$idTopico";

				$html .= "
<span><div class=\"cor1\" id=\"t$idTopico\">
	<div class=\"esq\">
	<div class=\"imagem\"><img src=\"img_output.php?id=$idUsuario\"></div>

	<ul>
		<li><a href=\"$link\" id=\"ta$idTopico\">$titulo</a></li>
		<li class=\"mensagens\">".$topico->getPrintableMessageNumber()."</li>
		</ul>
		</div>
		<div class=\"dir\">
		<ul>
		<li>
		<div class=\"limite_topico\">
		<div style=\"height:70px; overflow:hidden;\"><a href=\"$link\" id=\"tm518\">hue</a></div>
		</div>
		</li>
		<li class=\"criado_por\">Por: <span style=\"color:#C60;\">joao teste</span> em <span style=\"color:#C60;\">29/4/2013</span> às  <span style=\"color:#C60;\">17h 4min</span></li>
		<li><div align=\"right\" class=\"enviar\">
		</div></li>
	</ul>
	</div>
</div></span>
";
			}
		}
	}

	function imprimePaginas(){

	}

	function imprimeNumTopicos(){
		$frase = ($this->numTopicos == 1) ? "Existe 1 tópico nesse forum." : "Existem $this->numTopicos tópicos nesse forum.";
		
		echo "		<div class=\"troca_paginas\">
			<div class=\"paginas_padding\">
				$frase
			</div>
		</div>";

		
	}
}