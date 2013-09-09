<?php
/*
*	Sistema do forum
*
*/



class mensagem { //estrutura para o item post do forum, chamado de mensagem
	private $id = 0; function getId(){return $this->id;}
	private $idTopico = 0; function getIdTopico(){return $this->idTopico;}
	private $idUsuario = 0;
	private $idMensagemRespondida = 0;
	private $texto = ''; function getTexto(){return $this->texto;}
	private $data = 0;
	private $salvo = false;
	private $nomeUsuario = '';
	
	function __construct($id = 0, $idTopico = NULL, $idUsuario = 0, $texto = '', $idMensagemRespondida = NULL){
		if($id != NULL){
			$this->carregar($id);
		}else{
			$this->idTopico = $idTopico;
			$this->idUsuario = $idUsuario;
			$this->idMensagemRespondida = $idMensagemRespondida;
			$this->texto = $texto;
		}
	}

	function loadFromSqlArray($a){
		$this->id = $a['idMensagem'];
		$this->idTopico = $a['idTopico'];
		$this->idUsuario = $a['idUsuario'];
		$this->idMensagemRespondida = $a['idMensagemRespondida'];
		$this->texto = $a['texto'];
		$this->data = $a['data'];
		$this->nomeUsuario = $a['usuario_nome'];
	}

	function salvar(){
		$q = new conexao();

		if($this->salvo == true){
			$textoSemHtml				= strip_tags($this->texto, "<a><img>");
			$textoSafe					= $q->sanitizaString($textoSemHtml);

			$q->solicitar("UPDATE ForumMensagem SET texto = '$textoSafe', data = NOW() WHERE idMensagem = '$this->id'");
		}else{
			$idTopicoSafe				= $q->sanitizaString($this->idTopico);
			$idUsuarioSafe				= $q->sanitizaString($this->idUsuario);
			$idMensagemRespondidaSafe	= $q->sanitizaString($this->idMensagemRespondida);
			$textoSemHtml				= strip_tags($this->texto, "<a><img>");
			$textoSafe					= $q->sanitizaString($textoSemHtml);

			$idMensagemRespondidaSafe = (($idMensagemRespondidaSafe == -1) ? "NULL" : $idMensagemRespondidaSafe);

			$query = "INSERT INTO ForumMensagem
				VALUES (NULL, $idTopicoSafe, $idUsuarioSafe, '$textoSafe', NOW(), $idMensagemRespondidaSafe)";

			$q->solicitar($query);

			if ($q->erro == "") {
				$this->id = $q->ultimo_id();

				// por favor me perdoem
				// ps.: se algum dia algum professor de algum colégio reclamar que a timestamp da mensagem postada mudou por 1 segundo eu pago uma cervejada pro nuted todo ~ João - 16/8/13 15:40
				$q->solicitar("SELECT NOW()");
				$this->data = $q->resultado['NOW()'];
			}
		}
		
		if ($q->erro != "") {
			die("Erro na salvar da mensagem1 - $q->erro - $query");
		}
	}

	function carregar($id){
		$q = new conexao();

		$idSafe = $q->sanitizaString($id);
		//$q->solicitar("SELECT * FROM ForumMensagem WHERE idMensagem = '$idSafe'");
		
		$q->solicitar("SELECT * FROM ForumMensagem
						INNER JOIN usuarios ON usuarios.usuario_id = ForumMensagem.idUsuario
						WHERE idMensagem = $idSafe");

		if($q->erro == ""){
			$this->loadFromSqlArray($q->resultado);

			$this->salvo = true;
		}else{
			die("Erro na 'carregar' da mensagem -$idSafe- $q->erro");
		}
	}

	function toJson(){
		global $user; global $permissoes; global $turma;

		$podeEditar = $user->podeAcessar($permissoes['forum_editarResposta'], $turma);
		$podeDeletar = $user->podeAcessar($permissoes['forum_excluirResposta'], $turma);

		$arr = array(
			'idPost' => $this->id,
			'idTopico' => $this->idTopico,
			'idUsuario' => $this->idUsuario,
			'nomeUsuario' => $this->nomeUsuario,
			'texto' => $this->texto,
			'data' => $this->data,
			'podeEditar' => $podeEditar,
			'podeDeletar' => $podeDeletar
			);

		if($this->idMensagemRespondida != NULL){
			$mens = new mensagem($this->idMensagemRespondida);
			$arr['mensagemRespondida'] = $mens->toJson();
		}

		return $arr;
	}

	function setTexto($texto){
		$this->texto = $texto;
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
	private $nomeUsuario;function getNomeUsuario(){return $this->nomeUsuario;}
	private $mensagens;	function getMensagens(){return $this->mensagens;}
	private $salvo = false;

function setIdTopico($arg){$this->idTopico = $arg;}
function setIdTurma($arg){$this->idTurma = $arg;}
function setIdUsuario($arg){$this->idUsuario = $arg;}
function setTitulo($arg){$this->titulo = $arg;}
function setDate($arg){$this->date = $arg;}

function setMensagem($indice, $mensagem){
	$objetoMensagem = NULL;

	if(is_string($mensagem)){
		$objetoMensagem = new mensagem(NULL, $this->idTopico, $this->idUsuario, $mensagem, -1);
	}else{
		$objetoMensagem = $mensagem; // só renomeando
	}
	
	$this->mensagens[$indice] = $objetoMensagem;
}

	
	function __construct($idTopico, $idTurma = NULL, $idUsuario = NULL, $titulo = "NULL", $date = "", $nomeUsuario = "ERRO 43"){
		if($idTurma === NULL){// se mandar só a id do topico, abre ele
			$this->loadTopico($idTopico);
		}else{
			$this->idTopico		= $idTopico;
			$this->idTurma		= $idTurma;
			$this->idUsuario	= $idUsuario;
			$this->titulo		= $titulo;
			$this->date			= $date;
			$this->nomeUsuario	= $nomeUsuario;
		}
	}

	function loadTopico($id){
		$q = new conexao();
		$idSafe = $q->sanitizaString($id);
		$q->solicitar("SELECT * FROM ForumTopico
						INNER JOIN usuarios ON usuarios.usuario_id = ForumTopico.idUsuario
						WHERE idTopico = $idSafe");

		if($q->erro == ""){
			$this->idTopico	= $q->resultado['idTopico'];
			$this->idTurma	= $q->resultado['idTurma'];
			$this->idUsuario= $q->resultado['idUsuario'];
			$this->titulo	= $q->resultado['titulo'];
			$this->date		= $q->resultado['data'];
			$this->nomeUsuario = $q->resultado['usuario_nome'];
			$this->salvo	= true;

			$q->solicitar("SELECT * FROM ForumMensagem
							INNER JOIN usuarios ON usuarios.usuario_id = ForumMensagem.idUsuario
							 WHERE idTopico = $idSafe
							 ORDER BY idMensagem");
			if($q->erro == ""){
				$this->mensagens = array();
				for ($i=0; $i < $q->registros; $i++){
					$mensagem = new mensagem();
					$mensagem->loadFromSqlArray($q->resultado);

					array_push($this->mensagens, $mensagem);

					$q->proximo();
				}
			}else{
				die("Erro na loadTopico do topico - ".$q->erro);
			}
		}
	}

	function salvar(){

		if ($this->salvo === true){// atualizar
			$q = new conexao();

			$tituloSemHtml	= strip_tags($this->titulo, "<a><img>");
			$titulo			= $q->sanitizaString($tituloSemHtml);

			$q->solicitar("UPDATE ForumTopico SET titulo='$titulo', data=NOW() WHERE idTopico = $this->idTopico");
		}else{// inserir
			$q = new conexao();

			$idTurma = $q->sanitizaString($this->idTurma);
			$tituloSemHtml	= strip_tags($this->titulo, "<a><img>");
			$titulo			= $q->sanitizaString($tituloSemHtml);
			$q->solicitar("INSERT INTO ForumTopico
				VALUES (NULL, '$idTurma', '$this->idUsuario', '$titulo', NOW())");

			$this->idTopico = ($q->erro == "") ? $q->ultimo_id() : NULL;
		}

		if($q->erro != ""){
			die("Erro na salvar do topico");
		}
	}

	function getPrintableMessageNumber(){
		$mensagens = count($this->mensagens);

		if ($mensagens === 1) {
			return "1 mensagem";
		} else {
			return "$mensagens mensagens";
		}
		
	}

	function insereMensagem($texto){
		$mensagem = new mensagem(NULL, $this->idTopico, $this->idUsuario, $texto, -1);
		$mensagem->salvar();
	}
}

class visualizacaoTopico extends topico{

	function __construct($idTopico, $idTurma = NULL, $idUsuario = NULL, $titulo = "NULL", $date = "", $nomeUsuario = "ERRO 43"){
		parent::__construct($idTopico, $idTurma, $idUsuario, $titulo, $date, $nomeUsuario);
	}

	function imprimeMensagens(){
		$mensagens = $this->getMensagens();
		$arrJson = array();
		
		foreach ($mensagens as $indice => $mensagem){
			$arrJson[] = $mensagem->toJson();
		}
		echo json_encode($arrJson);
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
	protected $listaTopicos = array();
	public $numPaginas;
	
	function __construct($idTurma){
		$this->idTurma = (int) $idTurma;
	}
	
	function carregaTopicos(){
		if(empty($this->listaTopicos)){
			$idTurma = $this->idTurma;
			$q = new conexao();
			$q->solicitar("SELECT idTopico FROM ForumTopico WHERE idTurma = $idTurma ORDER BY data DESC");

			$this->numTopicos = $q->registros;
			for($i=0; $i < ($q->registros); $i+=1){
				$idTopico = $q->resultado['idTopico'];

				$topicoLoop = new topico($idTopico);
				$this->listaTopicos[] = $topicoLoop; // appends
				$q->proximo();
			}
			
			return $this->listaTopicos;

		}else{
			return $this->listaTopicos;
		}
	}
}


class visualizacaoForum extends forum{

	function imprimeTopicos($user, $permissoes){

		$this->carregaTopicos();

		if(empty($this->listaTopicos)){
			echo "Não existem tópicos nessa turma.";
		}else{
			$html = "";

			foreach ($this->listaTopicos as $indice => $topico) {
				$idTopico = $topico->getIdTopico();
				$idTurma = $topico->getIdTurma();
				$idUsuario = $topico->getIdUsuario();
				$titulo = $topico->getTitulo();
				$nomeUsuario = $topico->getNomeUsuario();
				$data = explode(' ', $topico->getDate());
				$link = "forum_topico.php?turma=$idTurma&amp;topico=$idTopico";

				$acoesPermitidas = "";
				if($user->podeAcessar($permissoes['forum_excluirTopico'], $idTurma) or $user->podeAcessar($permissoes['forum_editarTopico'], $idTurma)){
					$acoesPermitidas .= "<div class=\"enviar\" align=\"right\">";

					if ($user->podeAcessar($permissoes['forum_editarTopico'], $idTurma)) {
						$acoesPermitidas .= "<img src=\"../../images/botoes/bt_editar.png\" onclick=\"editarTopico($idTurma,$idTopico)\" class=\"clicavel\"/>";
					}

					if ($user->podeAcessar($permissoes['forum_excluirTopico'], $idTurma)) {
						$acoesPermitidas .= "<input type=\"image\" src=\"../../images/botoes/bt_excluir.png\" onclick=\"excluirTopico($idTurma,$idTopico)\" class=\"clicavel\"/>";
					}

					$acoesPermitidas .= "</div></li>";
				}

				echo "
<div class=\"alterna\" id=\"t$idTopico\">
	<div class=\"esq\">
		<li><a href=\"$link\" id=\"ta$idTopico\">$titulo</a></li>
		<li class=\"mensagens\">".$topico->getPrintableMessageNumber()."</li>
	</div>
		<div class=\"dir\">
		<ul>
		<li class=\"criado_por\">Por: <span style=\"color:#C60;\">$nomeUsuario</span> em <span style=\"color:#C60;\">$data[0]</span> às <span style=\"color:#C60;\">$data[1]</span></li>
		$acoesPermitidas
	</ul>
	</div>
</div>
";
			}
		}
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