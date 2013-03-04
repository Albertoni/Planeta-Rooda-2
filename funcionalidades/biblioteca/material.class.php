<?php
class material { // Eu ia fazer uma piada sobre coisas imateriais, mas nada veio à mente.
	var $codMaterial	= 0;
	var $codTurma		= 0;
	var $titulo			= "";
	var $autor			= ""; // Não confundir com as duas abaixo, que são quem deu upload. Esse é o autor do material.
	var $codUsuario		= 0;
	var $nomeUsuario	= "";
	var $tipoMaterial	= ""; // a para arquivo, l para link
	var $material		= ""; // filename ou URL, bródisnêy. Sim, é redundante.
	var $refMaterial	= 0;
	var $data			= "";
	var $hora			= "";
	var $erro			= "";
	var $tags			= "";
	var $file			= NULL; // guarda uma estrutura de arquivo caso o material seja um arquivo
	
	function material($titulo="", $autor="", $tipo="", $dado="", $turma=0, $tags=""){
		global $tabela_Materiais;
		if ($titulo != "" and $autor != "" and ($tipo == "l" or $tipo == "a") and $dado != ""){
			$this->titulo = $titulo;
			$this->autor = $autor;
			$this->tags = $tags;
			$this->codUsuario = $_SESSION['SS_usuario_id'];
			$this->nomeUsuario = $_SESSION['SS_usuario_nome'];
			$this->codTurma = $turma == 0 ? $_SESSION['SS_turmas'][0] : $turma; // Passaram argumento, é o argumento.
			$this->material = $dado;
			
			if ($tipo == "l" or $tipo == "L"){
				
				// Tratamento do link:
				if (!(strpos($dado, "http://") === 0) and !(strpos($dado, "ftp://") === 0)){
					$dado = "http://" . $dado;
				}
				
				$zelda = new Link(str_replace(array("<", ">", "'", '"'), array("&lt;", "&gt;", "", ""), $dado), TIPOBIBLIOTECA, $_SESSION['SS_usuario_id']);
				
				if ($zelda->temErro()){
					foreach($zelda->getErrosArray() as $erro){
						$this->erro .= "!!LINK: ".$erro;
					}
				}else{
					$finalmente = new conexao();
					$finalmente->solicitar("INSERT INTO $tabela_Materiais
							(codTurma,				titulo,		autor,	palavras,	codUsuario,						tipoMaterial,	material,	data,		hora,		refMaterial)
					VALUES ( ".$this->codTurma.", '$titulo',	'$autor','$tags', ".$_SESSION['SS_usuario_id'].", '$tipo',			'$dado',	CURDATE(),	CURTIME(), ".$zelda->getId().")");
					
					
					if ($finalmente->erro != ""){
						$this->erro .= "!!SQL: ".$finalmente->erro;
					}
				}
			} else if($tipo == 'a' or $tipo == "A") {
				$fileName	= $this->material = $dado['name'];
				$tmpName	= $dado['tmp_name'];
				$fileSize	= $dado['size'];
				$fileType	= $dado['type'];
				
				$this->file = new File(TIPOBIBLIOTECA,$this->codTurma,$fileName, $fileType, $fileSize, $tmpName);
				
				$this->file->setTitulo($titulo);
				$this->file->setAutor($autor);
				$this->file->setTags($tags);
				
				$this->file->upload();
				
				if ($this->file->temErro()){
					$this->erro .= "!!FILE: ". $this->file->getErrosString();
				} else {
					$finalmente = new conexao();
					$finalmente->solicitar("INSERT INTO $tabela_Materiais
							(codTurma, titulo, autor, palavras, codUsuario, tipoMaterial, material, data, hora, refMaterial)
					VALUES ( $turma, '$titulo','$autor','$tags', ".$_SESSION['SS_usuario_id'].", '$tipo', '".$dado['name']."', CURDATE(), CURTIME(), ".$this->file->getId().")");
					
					if ($finalmente->erro != ""){
						$this->erro .= "!!SQL: ".$finalmente->erro;
					}
				}
			} else {
				$this->erro .= "Parametro \"Tipo\" errôneo passado para o construtor";
			}
			
		}else{ // Erros
			if ($titulo == ""){
				$this->erro .= "É necessário um título.\n";
			}
			if ($autor == ""){
				$this->erro .= "É necessário o nome do autor.\n";
			}
			if ($tipo != "l" and $tipo != "a"){
				$this->erro .= "Meu, o que tu quer fazer aqui? Sério, meu.\n"; // porra meu
			}
			if ($dado == ""){
				$this->erro .= "O link ou arquivo não foi enviado com sucesso.";
			}
		}
	}
	
	function open($id){
		global $tabela_Materiais;
		if (is_num($id)){
			global $tabela_Materiais;
			$c = new conexao();
			$c->solicitar("SELECT * FROM $tabela_Materiais WHERE codMaterial = $id");
			
			if ($c->registros != 1){
				if ($c->registros == 0){
					$this->erro = "Não existe material com esse ID no banco de dados.";
				}else{
					$this->erro = "Favor avisar um desenvolvedor que o erro 0xC0DEDBAD aconteceu no material.class.php";
					// Se você recebeu isso, significa que tem dois registros com o mesmo id. O id deveria ser único. Boa sorte. Mesmo.
				}
			}else{
				$this->codMaterial	= $id;
				$this->codTurma		= $c->resultado['codTurma'];
				$this->titulo		= $c->resultado['titulo'];
				$this->autor		= $c->resultado['autor'];
				$this->codUsuario	= $c->resultado['codUsuario'];
				$this->tipoMaterial	= $c->resultado['tipoMaterial'];
				$this->material		= $c->resultado['material'];
				$this->data			= $c->resultado['data'];
				$this->hora			= $c->resultado['hora'];
				$this->refMaterial	= $c->resultado['refMaterial'];
				
				// E agora se pega um que não tá na mesma tabela!
				$temp = new Usuario();
				$temp->openUsuario($this->codUsuario);
				$this->nomeUsuario = $temp->getName();
			}
		}else{ // falhou no is_num($id)
			$this->erro = "Você precisa passar um dígito para essa função.";
			return NULL;
		}
	}
	
	function temErro(){
		if ($this->erro != ""){
			return $this->erro;
		}else{
			return NULL;
		}
	}
}
/*
codMaterial
codTurma
titulo
autor
palavras
codUsuario
tipoMaterial - char1 - a XOR l
material - text
tamanhoMaterial
data
hora
numcomments
intergrupo
*/
?>
