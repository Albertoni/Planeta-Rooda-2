<?php


function getListaAulas($turma){ // Pega a lista de aulas de uma turma, retorna um array() de objetos aula
	$retorno = array();
	
	$q = new conexao(); global $tabela_Aulas;
	if(is_numeric($turma)){
		$q->solicitar("SELECT id FROM $tabela_Aulas WHERE turma = $turma ORDER BY ordem");
		
		for($i=0; $i<$q->registros; $i++){
			$retorno[] = new aula();
			$retorno[$i]->abreAula($q->resultado['id']);
			$q->proximo();
		}
		
		return $retorno;
	} else {
		return NULL;
	}
}

class aula{
	//Constantes que representam ações que podem ter seu acesso gerenciado.
	const CRIAR_AULA=1;
	const EDITAR_AULA=2;
	const IMPORTAR_AULA=3;
	
	//Dados da aula
	private $id;
	private $turma;
	private $nomeTurma;
	private $titulo;
	private $data;
	private $desc;
	private $material;
	private $fundo;
	private $autor;
	private $tipo;
	private $erro = "";
	private $fileId = 0;
	
	// FALA COM O JAVA, ELE ME MANDOU FAZER ASSIM PORQUE DE ACORDO COM ELE É MAIS SEGURO E BONITO E DE ACORDO COM ELE ATÉ CONSTANTE DEVERIA TER GETTER E SETTER
	function getId()		{return $this->id;}
	function getTurma()		{return $this->turma;}
	function getTitulo()	{return $this->titulo;}
	function getData()		{return $this->data;}
	function getDesc()		{return $this->desc;}
	function getMaterial()	{return $this->material;}
	function getFundo()		{return $this->fundo;}
	function getAutor()		{return $this->autor;}
	function getTipo()		{return $this->tipo;}
	function getNomeTurma()	{return $this->nomeTurma;}
	
	function temErro()		{if($this->erro != "") return true; else return false;}
	function getErro()		{return $this->erro;}
	
	// Cria o objeto
	function aula($turma = "", $titulo = "", $data = "", $desc = "", $material = "", $fundo = "", $tipo = "", $autor = 0){
		// if dado == branco || 0 then erro else ok
		$this->turma	= $turma	!="" ? $turma		:$this->erro  = "Turma em branco;";
		$this->titulo	= $titulo	!="" ? $titulo		:$this->erro .= "Titulo em branco;";
		$this->data		= $data		!="" ? $data		:$this->erro .= "Data em branco;";
		$this->desc		= $desc		!="" ? $desc		:$this->erro .= "Descrição em branco;";
		$this->material	= $material	!="" ? $material	:$this->erro .= "Material em branco;";
		$this->fundo	= $fundo	!="" ? $fundo		:$this->erro .= "Fundo em branco;";
		$this->tipo		= $tipo		!="" ? $tipo		:$this->erro .= "Tipo em branco;";
		
		$this->autor	= $autor != 0 ? $autor : $_SESSION['SS_usuario_id'];
	}
	
	// Salva as mudanças
	function edita($id_da_aula){
		$titulo		= $this->titulo;
		$data		= $this->data;
		$desc		= $this->desc;
		$material	= isset($this->material['name']) ? $this->material['name'] : $this->material; // pega arquivos e não-arquivos numa tacada só
		$fundo		= $this->fundo;
		
		$q = new conexao(); global $tabela_Aulas;
		$q->solicitar("UPDATE $tabela_Aulas SET titulo='$titulo', data='$data', descricao='$desc', textoAula='$material', fundo=$fundo WHERE id=$id_da_aula");
	}
	
	// Cadastra no BD
	function registra(){
		$q = new conexao(); global $tabela_Aulas;
		$q->solicitar("SELECT MAX(ordem) AS maximal FROM $tabela_Aulas WHERE turma = ".$this->turma); // Pega o maior número de ordenamento de aulas
		$maxTurma = $q->registros != 0 ? $q->resultado['maximal'] + 1 : 1; // Se existirem aulas, adiciona um ao número, senão seta como 1.
		// Isso é feito pra aula ser inserida como se fosse a ultima aula


		switch($this->tipo){
			case 1:
				$turma		= $this->turma;
				$titulo		= $this->titulo;
				$data		= $this->data;
				$desc		= $this->desc;
				$material	= $this->material;
				$fundo		= $this->fundo;
				$user		= $this->autor;
				
				$q->solicitar("INSERT INTO $tabela_Aulas
		(turma,		titulo,		data,		descricao,	textoAula,		fundo, idPoster, ordem, tipoAula) VALUES
		($turma,	'$titulo',	'$data',	'$desc',	'$material',	$fundo, $user, $maxTurma, 1)");
				
				
				break;



			case 2:
				$fileName	= $this->material['name'];
				$tmpName	= $this->material['tmp_name'];
				$fileSize	= $this->material['size'];
				$fileType	= $this->material['type'];
				
				$turma		= $this->turma;
				$titulo		= $this->titulo;
				$data		= $this->data;
				$desc		= $this->desc;
				$fundo		= $this->fundo;
				$user		= $this->autor;
				
				$q->solicitar("INSERT INTO $tabela_Aulas
		(turma,		titulo,		data,		descricao,	textoAula,		fundo, idPoster, ordem, tipoAula) VALUES
		($turma,	'$titulo',	'$data',	'$desc',	'$fileName',	$fundo, $user, $maxTurma, 2)");
				
				$aulaId = $q->ultimo_id(); // PEga o ID da aula pra usar como chave única do filé
				
				$file = new File(TIPOAULA,$aulaId,$fileName, $fileType, $fileSize, $tmpName);
				$file->upload();
				$this->fileId = $file->getId(); // Pra jogar o link de download no fórum com menos hacks
				
				if ($file->temErro()){
					$_SESSION['erroAulas'] = $file->getErrosString();
				}
				
				break;




			case 3:
				$turma		= $this->turma;
				$titulo		= $this->titulo;
				$data		= $this->data;
				$desc		= $this->desc;
				$material	= $this->material;
				$fundo		= $this->fundo;
				$user		= $this->autor;
				
				$q->solicitar("INSERT INTO $tabela_Aulas
		(turma,		titulo,		data,		descricao,	textoAula,		fundo, idPoster, ordem, tipoAula) VALUES
		($turma,	'$titulo',	'$data',	'$desc',	'$material',	$fundo, $user, $maxTurma, 3)");
				
				$aulaId = $q->ultimo_id(); // PEga o ID da aula pra usar como chave única dos links
				
				$zelda = new Link(str_replace(array("<", ">", "'", '"'), array("&lt;", "&gt;", "", ""), $material), TIPOAULA, $aulaId);
				
				if ($zelda->temErro()){
					$_SESSION['erroAulas'] = $zelda->getErrosArray();
				}
				break;
		}
	}
	
	// Baixa uma aula do BD!
	function abreAula($id){
		if (is_numeric($id)){
			$q = new conexao(); global $tabela_Aulas; global $tabela_turmas;
			$nome_da_turma = new conexao();
			$q->solicitar("SELECT * FROM $tabela_Aulas WHERE id = $id");
			
			$nome_da_turma->solicitar("SELECT nomeTurma FROM $tabela_turmas WHERE codTurma = ".$q->resultado['id']);
			
			$this->id		= $q->resultado['id'];
			$this->turma	= $q->resultado['turma'];
			$this->titulo	= $q->resultado['titulo'];
			$this->data		= $q->resultado['data'];
			$this->desc		= $q->resultado['descricao'];
			$this->material	= $q->resultado['textoAula'];
			$this->fundo	= $q->resultado['fundo'];
			$this->autor	= $q->resultado['idPoster'];
			$this->tipo		= $q->resultado['tipoAula'];
			$this->nomeTurma= $nome_da_turma->resultado['nomeTurma'];
		
			if ($q->erro != "")
				$this->erro = $q->erro; // bota o erro
			else
				$this->erro = ""; // limpa o erro que teria se o objeto fosse criado com dados vazios para abrir uma aula
		}else{
			$this->erro = "Argumento passado para a abreAula não é um número";
		}
	}
	
	function deletaAula($id){
		$q = new conexao(); global $tabela_Aulas; global $nivelProfessor; global $tabela_arquivos; global $tabela_links;
		
		if (is_numeric($id)){
			$q->solicitar("SELECT turma FROM $tabela_Aulas WHERE id = $id");
			// Pertence à turma e é professor
			if (in_array($q->resultado['turma'], $_SESSION['SS_turmas']) and checa_nivel($_SESSION['SS_usuario_nivel_sistema'], $nivelProfessor)){ // Se ele pertence à turma que tá tentando apagar
				$q->solicitar("DELETE FROM $tabela_Aulas WHERE id = $id");
				if ($q->erro != ""){
					$this->erro = "Não foi possível apagar a aula. SQL -> " . $q->erro;
					return false;
				}
				
				// REMOVENDO DADOS AUXILIARES
				if($this->tipo == 2){
					$q->solicitar("DELETE FROM $tabela_arquivos WHERE funcionalidade_tipo=".TIPOAULA." AND funcionalidade_id=$id");
					
					if ($q->erro != ""){
						$this->erro = "Não foi possível apagar o arquivo da aula. SQL -> " . $q->erro;
						return false;
					}
				} else if ($this->tipo == 3){
					$q->solicitar("DELETE FROM $tabela_links WHERE funcionalidade_tipo=".TIPOAULA." AND funcionalidade_id=$id");
					
					if ($q->erro != ""){
						$this->erro = "Não foi possível apagar o link da aula. SQL -> " . $q->erro;
						return false;
					}
				}
			}else{
				$this->erro = "Você está tentando apagar uma aula de uma turma a qual não pertence ou não tem permissões.";
				return false;
			}
		}else{
			$this->erro = "O id passado para a função deletaAula da classe de aulas não é um número.";
			return false;
		}
		// Não deu erro algum? Ótimo!
		return true;
	}
	
	function trocaPosicoes($trocaEssa, $comEssa){ // SÃO OS IDS DAS AULAS, IDIOTA
		global $nivelProfessor; // favor incluir o cfg.php antes obrigado
		// Sanity check basicona
		if (is_numeric($trocaEssa) and is_numeric($comEssa)){
			$q = new conexao(); $s = new conexao(); global $tabela_Aulas;
			
			$q->solicitar("SELECT turma, ordem FROM $tabela_Aulas WHERE id = $trocaEssa");
			$s->solicitar("SELECT turma, ordem FROM $tabela_Aulas WHERE id = $comEssa");
			
			if ($q->erro != "")
			{	$this->erro = "Erro SQL ao trocar a aula $trocaEssa com $comEssa -> " . $q->erro;
				return false;}
			if ($s->erro != "")
			{	$this->erro .= "Erro SQL durante a troca da aula $trocaEssa com $comEssa -> " . $s->erro;
				return false;}
			
			// checa se pertence a ambas as turmas e é professor de ambas.
			if (in_array($q->resultado['turma'], $_SESSION['SS_turmas']) and in_array($s->resultado['turma'], $_SESSION['SS_turmas']) and checa_nivel($_SESSION['SS_usuario_nivel_sistema'], $nivelProfessor)){
				$posDessa = $q->resultado['ordem'];
				$posDaOutra = $s->resultado['ordem'];
				
				$q->solicitar("UPDATE $tabela_Aulas SET ordem=$posDaOutra WHERE id=$trocaEssa");
				$s->solicitar("UPDATE $tabela_Aulas SET ordem=$posDessa WHERE id=$comEssa");
				
				if (($q->erro != "") or ($s->erro != "")){ // ERRO NO UPDATE
					$this->erro = "Ocorreu um problema na atualização, é possível que a ordem que as aulas são mostradas esteja em um estado inconsistente.";
					return false;
				}
				
				return true; // TUDO OK
			}else{
				$this->erro = "VOCÊ NÃO PODE BRINCAR COM TURMAS ÀS QUAIS NÃO PERTENCE"; // 0x00BABACA
				return false;
			}
		}else{
			$this->erro = "O id passado para a função trocaPosicoes da classe de aulas não é um número.";
			return false;
		}
	}
	
	function getFriendlyAndPrintableClassText(){
		$gambi = new conexao(); global $tabela_arquivos; global $tabela_links;
		
		switch($this->tipo){
			case 1: // text-only
				return $this->material;
				break;
			case 2: // file
				$gambi->solicitar("SELECT arquivo_id FROM $tabela_arquivos WHERE funcionalidade_tipo=".TIPOAULA." AND funcionalidade_id=$id");
				return "<a href=\"../../downloadFile.php?fileId=".$gambi->resultado['arquivo_id']."\">".$this->material."</a>";
				break;
			case 3: // link
				return "<a href \"".$this->material."\">".$this->material."</a>";
				break;
			default:
				$this->erro = "A AULA DE ID ".$this->id." ESTÁ COM OS DADOS DELA CORROMPIDOS!";
		}
	}
	

	
	
	
}
?>
