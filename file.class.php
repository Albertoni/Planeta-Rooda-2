<?php
require_once("cfg.php");
require_once("bd.php");



//UPGRADE: TRANSFORMAR FUNCIONALIDADE_TIPO EM FUNCIONALIDADE_TIPOS E FUNCIONALIDADE_ID PARA FUNCIONALIDADE_IDS (OBJETIVO: EVITAR DUPLICAÇÃO DE ARQUIVOS)
class File {
	private $id;
	private $link;				//string com o endereco para o arquivo no servidor
	private $nome;				//nome do arquivo. Deve conter a extensao também 
	private $tipo = "";
	private $tamanho;
	private $fileContent = "";	//conteudo do arquivo
	private $funcionalidade_tipo;	//tipo da funcionalidade a qual o arquivo pertence.
									//Possibilidades: 1-blog, 2-portfolio, 3-biblioteca

	private $funcionalidade_id;		//id da funcionalidade a qual o arquivo pertence
	private $erros = array();		//array de strings que guarda os erros, caso aja algum
	private $download = false;		//variavel que diz se se tem os dados necessarios para se fazer o download
	private $upload = false;		//variavel que diz se se tem os dados necessarios para se fazer o upload
	private $blog_id = 0;			// Para minimizar a necessidade de gambiarras e facilitar a vida do banco de dados.
	private $titulo = 0;			// Opcional, usado na biblioteca
	private $autor = "";			// ^^^^^^^^^^^^^^^^^^^^^^^
	private $tags = "";				// ^^^^^^^^^^^^^^^^^^^^^^^

 //aceita como parametros funcionalidade_tipo(integer > 0), funcionalidade_id(integer > 0) e 
 //nome(string), o que eh suficiente para efetuar download do BD, ou entao
 //aceita funcionalidade_tipo, funcionalidade_id, nome, tipo, tamanho e nome temporario do arquivo no servidor,
 //que eh o necessario para efetuar o upload
	public function File($funcionalidade_tipo=-2, $funcionalidade_id=-2, $nome="", $tipo="", $tamanho=0, $fileNameServ="", $blog_id=0, $manual_id=0){
	global $tabela_arquivos;
	if(($funcionalidade_tipo!==-2) and ($funcionalidade_id!==-2) and ($nome!=="") and ($tipo==="") and ($tamanho===0) and ($fileNameServ==="")){
		$this->nome = $nome;
		$this->funcionalidade_tipo = $funcionalidade_tipo;
		$this->funcionalidade_id = $funcionalidade_id;
		$this->download = true;
	
	}
	else if (($funcionalidade_tipo>0) and ($funcionalidade_id>0) and ($nome!=="") and ($tipo!=="") and ($tamanho>0) and ($fileNameServ!=="")){
		$this->nome = $nome;
		$this->tipo = $tipo;
		$this->tamanho = $tamanho;
		$this->funcionalidade_tipo	= $funcionalidade_tipo;
		$this->funcionalidade_id	= $funcionalidade_id;

		$file	= fopen($fileNameServ, 'r');
		$fileContent = fread($file, filesize($fileNameServ));
		$fileContent = addslashes($fileContent);
		$this->fileContent = $fileContent;
		fclose($file);
		$this->upload = true;
	}
	elseif($manual_id != 0){ // usado na biblioteca, principalmente no material.class.php
		$gambi = new conexao();
		$manual_id = (int) $manual_id;
		$gambi->solicitar("SELECT nome, funcionalidade_tipo, funcionalidade_id FROM $tabela_arquivos WHERE arquivo_id = $manual_id");
		
		$this->nome = $gambi->resultado['nome'];
		$this->funcionalidade_tipo = $gambi->resultado['funcionalidade_tipo'];
		$this->funcionalidade_id = $gambi->resultado['funcionalidade_id'];
		$this->download = true;
	}else{
		$this->erros[] = "ERRO - Parametros errados em File Constructor";
	}
	}

	public function download(){
		if ($this->download === true){
			global $tabela_arquivos;
			$consulta = new conexao();
			$nome = mysql_real_escape_string($this->nome);
			$funcionalidade_tipo = (int) $this->funcionalidade_tipo;
			$funcionalidade_id = (int) $this->funcionalidade_id;
			
			$consulta->connect();
			$consulta->solicitar("SELECT * FROM $tabela_arquivos	WHERE nome = '$nome' 
																AND funcionalidade_tipo = '$funcionalidade_tipo' 
																AND funcionalidade_id = '$funcionalidade_id';");
			
			$this->id = $consulta->resultado["arquivo_id"];
			$this->nome = $consulta->resultado["nome"];
			$this->tipo = $consulta->resultado["tipo"];
			$this->tamanho = $consulta->resultado["tamanho"];
			$this->fileContent = $consulta->resultado["arquivo"];
			$this->funcionalidade_tipo = $consulta->resultado["funcionalidade_tipo"];
			$this->funcionalidade_id = $consulta->resultado["funcionalidade_id"];
			$nome = $consulta->resultado["nome"];
			$tipo = $consulta->resultado["tipo"];
			$tamanho = $consulta->resultado["tamanho"];
			
			if ($consulta->erro !== ""){
			$this->erros[] = "ERRO - \"".$consulta->erro."\"";
			}
			else {
				header("Content-length: $tamanho");
				header("Content-type: $tipo");
				header("Content-Disposition: attachment; filename=$nome");
				echo $this->fileContent;
			}
			
		}
		else {
			$this->erros[] = "ERRO - dados incorretos ou insuficientes para efetuar download";
		}
		
	}
	
	/*
	public function getFileListArray($funcionalidade_tipo, $funcionalidade_id){
		$consulta = new conexao();
		$consulta->connect();
		
		
		$consulta->solicitar("SELECT nome
								FROM $tabela_arquivos 
								WHERE funcionalidade_tipo='$funcionalidade_tipo' 
								AND funcionalidade_id='$funcionalidade_id'");
		$retorno = new Array();
		for($i=0 ; $i<count($consulta->itens);$i++) {
			$retorno[] = $consulta->resultado['nome'];
			$consulta->proximo();
		}
		return $retorno;
	
	}*/
	
	
	//exclui o arquivo do bd
	public function excluir(){
		global $tabela_arquivos;
		$consulta = new conexao();
		$file_name = mysql_real_escape_string($this->nome);
		$funcionalidade_tipo = (int) $this->funcionalidade_tipo;
		$funcionalidade_id = (int) $this->funcionalidade_id;
		$consulta->connect();
		$consulta->solicitar("DELETE FROM $tabela_arquivos 
								WHERE nome = '$file_name'
								AND funcionalidade_tipo = '$funcionalidade_tipo'
								AND funcionalidade_id	 = '$funcionalidade_id'");
		
	
	}
	
	//funcao que retorna true se aconteceu algum erro
	//retorna false caso contrario
	public function temErro(){
		if (count($this->erros) == 0){
			return false;
		}
		else return true;
	}
	
	//funcao que retorna os erros que aconteceram
	public function getErrosArray(){
		return $this->erros;
	}
	
	public function getErrosString(){
		$erros = "";
		for ($i = 0 ; $i < count($this->erros) ; $i++){
			if ($i < (count($this->erros) - 1) ){
				$erros .= $this->erros[$i]."<BR />";
			}
			else {
				$erros .= $this->erros[$i];
			}
		}
		return $erros;
	}
	
	
	public function getNome()				{return $this->nome;}
	public function getConteudoArquivo()	{return $this->fileContent;}
	public function getTamanho()			{return $this->tamanho;}
	public function getTipo()				{return $this->tipo;}
	public function getFuncionalidadeTipo()	{return $this->funcionalidade_tipo;}
	public function getFuncionalidadeId()	{return $this->funcionalidade_id;}
	public function getId()					{return $this->id;}
	public function getBlogId()				{return $this->blog_id;}
	public function getTitulo()				{return $this->titulo;}
	public function getAutor()				{return $this->autor;}
	public function getTags()				{return $this->tags;}
	public function setTitulo($t)			{$this->titulo = $t;}
	public function setAutor($a)			{$this->autor = $a;}
	public function setTags($t)				{$this->tags = $t;}
	
	//manda os meta-dados do arquivo pro Bd
	//obs: retorna erro caso jah tenha no bd um arquivo de mesmo nome, funcionalidade_tipo e funcionalidade_id
	public function upload(){
	if ($this->upload === true){
		global $tabela_arquivos;
		session_start();
		$consulta = new conexao();
		$nome 					= mysql_real_escape_string($this->getNome());
		$tipo					= mysql_real_escape_string($this->getTipo());
		$tamanho				= (int) $this->getTamanho();
		$ConteudoArquivo		= mysql_real_escape_string($this->getConteudoArquivo());
		$funcionalidade_tipo	= (int) $this->getFuncionalidadeTipo();
		$funcionalidade_id		= (int) $this->getFuncionalidadeId();
		$tit					= mysql_real_escape_string($this->getTitulo());
		$aut					= mysql_real_escape_string($this->getAutor());
		$tag					= mysql_real_escape_string($this->getTags());
		$uploader_id			= (int) $_SESSION['SS_usuario_id'];
		
		$consulta->solicitar("SELECT * FROM $tabela_arquivos WHERE nome = '$nome'
															 AND funcionalidade_tipo = '$funcionalidade_tipo'
															 AND funcionalidade_id = '$funcionalidade_id';");
		if ($consulta->registros === 0){
			$consulta->solicitar("INSERT INTO $tabela_arquivos
								(nome , tipo, tamanho, arquivo, funcionalidade_tipo , funcionalidade_id, titulo, autor, tags, uploader_id)
						VALUES ('$nome', '$tipo', '$tamanho', '$ConteudoArquivo', '$funcionalidade_tipo','$funcionalidade_id', '$tit', '$aut', '$tag', '$uploader_id');");
			if ($consulta->erro !== ""){
				$this->erros[] = "ERRO - \"".$consulta->erro."\"";
			}
			$this->id = $consulta->ultimo_id(); // Pega o id do arquivo!
		}
		else {
			$this->erros[] = "ERRO - Arquivo ja existe no banco de dados";
		}
	}
	else {
		$this->erros[] = "ERRO - dados incorretos ou insuficientes para efetuar upload";
	}
	}
	//comentario_id	,	post_id	,	comentario_msg	,	comentario_data	,	owner_id
	//funcao que retorna true se alguma propriedade (que nao seja o id) do File esta vazia
	public function isAnyPropEmpty(){
	if (($this->getNome() === "") or ($this->getTamanho()===0) or ($this->getArquivo()==="") or ($this->getFuncionalidadeTipo()===-2) or ($this->getFuncionalidadeId()===-2)){
		return true;
	}
	else return false;
	}
	
	private function limparFile(){
		$this->id = -1;
		$this->nome = "";
		$this->tamanho = 0;
		$this->tipo = "";
		$this->funcionalidade_tipo = 0;
		$this->funcionalidade_id = 0;
	}
	
	public function toString(){
		$saida  = "id=".$this->getId();
		$saida .= ", nome=".$this->getNome();
		$saida .= ", tamanho=".$this->getTamanho();
		$saida .= ", tipo=".$this->getTipo();
		$saida .= ", funcionalidade_tipo=".$this->getFuncionalidadeTipo();
		$saida .= ", funcionalidade_id=".$this->getFuncionalidadeId();
		return $saida;
	}
	
}

?>
