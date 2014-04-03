<?php

function paginacao($turma, $pagina){
	$q = new conexao(); global $tabela_playerVideos;
	$q->solicitar("SELECT COUNT(*) FROM $tabela_playerVideos WHERE turma = $turma");
	
	$numVideos = $q->resultado['COUNT(*)'] - 1; // Sem o menos 1 ele buga em multiplos de 10
	$numPaginas = (int)($numVideos/10); // arredonda pra baixo
	
	
	if($pagina > 0){
		echo "					<th align=\"right\" class=\"bg1\"><a href=\"index.php?turma=$turma&page=".($pagina - 1)."\">Página Anterior</a></th>
					<th class=\"bg1\" align=\"center\">Páginas: ";
	}else{
		echo "					<th align=\"right\" class=\"bg1\"><span class=\"unclickable\">Página Anterior</span></th>
					<th class=\"bg1\" align=\"center\">Páginas: ";
	}
	
	for($i=0; $i <= $numPaginas; $i++){
		if ($i == $pagina){
			echo " <b>$i</b> ";
		}else{
			echo " <a href=\"index.php?turma=$turma&page=$i\">$i</a> ";
		}
	}
	
	if($pagina < $numPaginas){
		echo "					</th>
					<th align=\"left\" class=\"bg1\"><a href=\"index.php?turma=$turma&page=".($pagina + 1)."\">Página Seguinte</a></th>";
	}else{
		echo "					</th>
					<th align=\"left\" class=\"bg1\"><span class=\"unclickable\">Página Seguinte</span></th>";
	}
}

class video{
	private $id;
	private $nome;
	private $videoId;
	private $descricao;
	private $erro = "";
	private $usuario; // ID DO USUARIO
	private $turma;
	private $username; // NOME DO USUARIO
	public $comments;
	
	/*\
	 *	@param abrir: Se verdadeiro, abre um video com o ID passado.
	 *				Se falso, não usa o ID, mas os outros parametros tem que estar setados.
	 *				Favor não fazer gambiarra com as comparações do PHP, passe true ou false.
	 *	@param id: Passe qualquer coisa de 0 a INT_MAX se não for abrir um video, o valor é ignorado. Se for abrir passe o ID desejado.
	 *	@param nome: Nome do vídeo
	 *	@param link: Link num dos formatos suportados
	 *	@param descricao: Descrição do vídeo
	 *	@param turma: Id da turma na qual o video sera cadastrado
	\*/
	
	function __construct($abrir, $id, $nome="", $link="", $descricao="", $turma=0){
		global $tabela_playerVideos; global $tabela_usuarios;
		if($abrir){
			$q = new conexao();
			$q->solicitar("SELECT * FROM $tabela_playerVideos WHERE id = $id");
			if($q->registros == 0){
				$this->setErro("Não existe vídeo com esse ID.");
			}else{
				if($q->erro){
					$this->setErro($q->erro);
				}else{
					$this->setId($q->resultado['id']);
					$this->setTitulo($q->resultado['nome']);
					$this->setDescricao($q->resultado['descricao']);
					$this->setVideoId($q->resultado['videoId']);
					$this->setTurma($q->resultado['turma']);
					$this->setUsuario($q->resultado['usuario']);
					$this->setErro("");
					
					$q->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = ".$this->getUsuario());
					$this->setUsuarioNome($q->resultado['usuario_nome']);
				}
			}
		}else{
			if ($nome!="" and $this->validaLink($link)){
				$q = new conexao();
				// VideoId é setado na ValidaLink por ser mais fácil

				// SQL injection
				$nome = $q->sanitizaString($nome);
				$link = $q->sanitizaString($link);
				$descricao = $q->sanitizaString($descricao);

				// JavaScript injection
				$nome = str_replace("<", "&gt;", $nome);
				$link = str_replace("<", "&gt;", $link);
				$descricao = str_replace("<", "&gt;", $descricao);
				
				$user = usuario_sessao();
				$idUser = $user->getId();
				$nomeUsuario = $user->getName();

				$q->solicitar("INSERT INTO $tabela_playerVideos (nome, videoId, descricao, usuario, turma) VALUES ('$nome', '".$this->videoId."', '$descricao', '$idUser', '$turma')");
				if($q->erro){
					$this->setErro($q->erro);
				}else{
					$this->setId($q->ultimo_id());
					$this->setTitulo($nome);
					$this->setDescricao($descricao);
					$this->setUsuarioNome($nomeUsuario);
					$this->setErro("");
				}
			}else{
				$erro="";
				if($nome==""){$erro="O Nome não pode estar em branco.";}
				if(!$this->validaLink($link)){$erro.="O link não está em nenhum dos formatos aceitos, por favor modifique ele para um formato aceitável.";}
			}
		}
	}
	
	function validaLink($link){ // ESSA FUNÇÃO TAMBÉM SETA A VARIÁVEL VIDEOID
		$pos=strpos($link,'/v/');
		
		if($pos==0 or $pos==FALSE){
			$pos=strpos($link,'=');
			
			if($pos==0 or $pos==FALSE){ // http://youtu.be/id_do_video
				$pos=strpos($link, "http://youtu.be/");
				if($pos==0){
					$vetor=explode('/',$link);
					$this->videoId=$vetor[3];
					
					return true;
				}else{ // o cara sempre pode tentar colar um video do google, sei lá
					return false;
				}
			}else{
				$pos=strpos($link,'?v=');
				if($pos==0 or $pos==FALSE){
					$pos=strpos($link,'&v=');
					
					if($pos==0 or $pos==FALSE){ // error f0f: format n0t found
						return false;
					}else{ // http://www.youtube.com/watch?algo=valor&v=id_do_video , já vi esse formato o suficiente pra considerar ele e suportar
						$vetor=explode('&v=',$link);
						$this->videoId=$vetor[1];
						
						return true;
					}
				}else{ // http://www.youtube.com/watch?v=id_do_video
					$vetor=explode('?v=',$link);
					$this->videoId=$vetor[1];
					
					return true;
				}
			}
		}else{ // http://www.youtube.com/v/id_do_video
			$vetor=explode('/v/', $link);
			$this->videoId=$vetor[1];
			return true;
		}
	}
	
	
	function getId()		{return $this->id;}
	function getTitulo()	{return $this->titulo;}
	function getLink()		{return 'http://www.youtube.com/v/'.$this->videoId;}
	function getDescricao()	{return $this->descricao;}
	function getVideoId()	{return $this->videoId;}
	function getErro()		{return $this->erro;}
	function getUsuario()	{return $this->usuario;}
	function getUsuarioNome(){return $this->username;} // TODO: FAZER FUNCIONAR QUANDO DÃO UPLOAD NO VIDEO, SÓ FUNCIONA PRA QUANDO ABRE O USER NO MOMENTO
	function getTurma()		{return $this->turma;}
	
	function setId($id)					{$this->id			= $id;}
	function setTitulo($tit)			{$this->titulo		= $tit;}
	function setVideoId($link)			{$this->videoId		= $link;}
	function setDescricao($desc)		{$this->descricao	= $desc;}
	function setErro($erro)				{$this->erro		= $erro;}
	function setUsuario($user)			{$this->usuario		= $user;}
	function setTurma($turma)			{$this->turma		= $turma;}
	function setUsuarioNome($username)	{$this->username	= $username;}
	
	function temErro()			{return $this->erro!="";}
}

function imprimeVideos($turma, $pagina, $turma){
	$q = new conexao(); global $tabela_playerVideos;

	// permissoes
	$user = usuario_sessao(); global $permissoes;

	$start = $pagina*10;
	
	$q->solicitar("SELECT id FROM $tabela_playerVideos WHERE turma = $turma LIMIT $start, 10");
	
	for ($i=0; $i < $q->registros; $i++){
		$vid = new video(true, $q->resultado['id']);
		
		$id				= $vid->getId();
		$url			= $vid->getLink();
		$videoNome		= $vid->getTitulo();
		$descricao		= $vid->getDescricao();
		$nomeDono		= $vid->getUsuarioNome();

		if($user->podeAcessar($permissoes['player_deletarVideos'], $turma)){
			$deletar = "<td class=\"bg1\">
						<img src=\"delete.png\" alt=\"deletar\" onclick=\"deleteVideo($id, $turma);\" style=\"cursor:pointer\" />
					</td>";
		}else{
			$deletar = "";
		}
		
		
		echo "				<tr id=\"linha$id\">
					<td class=\"bg1 small\" width=\"140px\">
						<a id=\"videoSelection$id\" href=\"#subindo\" onclick=\"selectVideo($id, $turma);\" name=\"$url\">$videoNome</a>
					</td>
					<td class=\"bg1 medium\" width=\"250px\" id=\"desc$id\">$descricao</td>
					<td class=\"bg1 small\" width=\"140px\" id=\"nome$id\">$nomeDono</td>
					<td style=\"display:none\" id=\"numcom$id\">$numComentarios</td>
					$deletar
				</tr>";
		
		$q->proximo();
	}
}