<?php

class Desenho { //estrutura para o desenho
	var $id = 0;
	var $userId = 0;
	var $turma = 0;
	var $titulo = '';
	var $descricao = '';
	var $dados = '';
	var $editavel = false;
	
	function Desenho($id,$uid,$turma,$titulo,$descricao,$dados, $editavel){
		$this->id = $id;
		$this->userId = $uid;
		$this->turma = $turma;
		$this->titulo = $titulo;
		$this->descricao = $descricao;
		$this->dados = $dados;
		$this->editavel = $editavel;
	}
}

class Arte {
	var galeria = new array();
	var erro = '';
	var turma = 0;
	var usuario = 0;
	var $contador = 0;
	var $BD_arte_tab = '';
	var $BD_user_tab = '';
	
	function Arte($aid, $uid){	// kind of self-explicativa.
		$this->turma = $aid;
		$this->usuario = $uid;
	}
	
	function configBD($BDarte,$BDuser){ // Auto-explicativa 
		$this->BD_arte_tab = $BDarte;
		$this->BD_user_tab = $BDuser;
	}
		
	
	// pega imagens do próprio usuario
	function getGaleria(){
		$this->erro = '';
		unset($this->galeria);
		
		$pesquisa1 = new conexao();
		if($pesquisa1->erro!= ""){ // Se tiver algum erro, copia da pesquisa pra classe:
			$this->erro = $pesquisa1->erro;
			return false;
		}

		$tabela_usuarios = $this->BD_user_tab;  // Precisa que tenha rodado a configBD() antes.
		$tabela_arte = $this->BD_arte_tab;
		$turma = $this->turma;
		$usuario = $this->usuario;

		//$this->erro = "tipo:$tipo - cons: $consulta"; // Se sobreviveu aos erros, põe infos de debug
		//pesquisa desenhos próprios
		$pesquisa1->solicitar("select * from $tabela_arte where usuarioId = '$usuario' and turmaId = '$turma'");
		for ($c=0; $c<$pesquisa1->registros; $c++){
			$id = $pesquisa1->resultado['id'];
			$uid = $pesquisa1->resultado['usuarioId'];
			$tid = $pesquisa1->resultado['turmaId'];
			$titulo = $pesquisa1->resultado['titulo'];
			$descricao = $pesquisa1->resultado['descricao'];
			$dados = $pesquisa1->resultado['dados'];
			$editavel = true;
			
			$this->galeria[] = new Desenho($id, $uid, $tid, $titulo, $descricao, $dados, $editavel);
		}
		$this->contador = count($this->galeria);
		return true;
	}
	
	// pega imagens dos colegas
	function getGaleriaTurma(){
		$this->erro = '';
		unset($this->galeria);
		
		$pesquisa1 = new conexao();
		if($pesquisa1->erro!= ""){ // Se tiver algum erro, copia da pesquisa pra classe:
			$this->erro = $pesquisa1->erro;
			return false;
		}

		$tabela_usuarios = $this->BD_user_tab;  // Precisa que tenha rodado a configBD() antes.
		$tabela_arte = $this->BD_arte_tab;
		$turma = $this->turma;
		$usuario = $this->usuario;

		//pesquisa desenhos doa colegas de turma
		$pesquisa1->solicitar("select * from $tabela_arte where usuarioId != '$usuario' and turmaId = '$turma'");
		for ($c=0; $c<$pesquisa1->registros; $c++){
			$id = $pesquisa1->resultado['id'];
			$uid = $pesquisa1->resultado['usuarioId'];
			$tid = $pesquisa1->resultado['turmaId'];
			$titulo = $pesquisa1->resultado['titulo'];
			$descricao = $pesquisa1->resultado['descricao'];
			$dados = $pesquisa1->resultado['dados'];
			$editavel = false;
			
			$this->galeria[] = new Desenho($id, $uid, $tid, $titulo, $descricao, $dados, $editavel);
		}
		$this->contador = count($this->galeria);
		return true;
	}	
	
	function salvaImagem($dados, $titulo = "", $descricao = ""){
		$this->erro = '';
		unset($this->galeria);
		
		$pesquisa1 = new conexao();
		if($pesquisa1->erro!= ""){ // Se tiver algum erro, copia da pesquisa pra classe:
			$this->erro = $pesquisa1->erro;
			return 0;
		}

		$tabela_usuarios = $this->BD_user_tab;  // Precisa que tenha rodado a configBD() antes.
		$tabela_arte = $this->BD_arte_tab;
		$turma = $this->turma;
		$usuario = $this->usuario;

		//salva o desenho do meliante
		$pesquisa1->solicitar("insert into $tabela_arte (usuarioId, turmaId, titulo, descricao, dados) VALUES ($usuario,$turma,$titulo,$descricao,$dados)");
		$idDoDesenho = mysql_insert_id();
		return $idDoDesenho;
	}
	
	function editaImagem($id, $dados, $titulo = "", $descricao = ""){
		$this->erro = '';
		unset($this->galeria);
		
		$pesquisa1 = new conexao();
		if($pesquisa1->erro!= ""){ // Se tiver algum erro, copia da pesquisa pra classe:
			$this->erro = $pesquisa1->erro;
			return 0;
		}

		$tabela_usuarios = $this->BD_user_tab;  // Precisa que tenha rodado a configBD() antes.
		$tabela_arte = $this->BD_arte_tab;
		$turma = $this->turma;
		$usuario = $this->usuario;

		//salva o desenho do meliante
		$pesquisa1->solicitar("UPDATE $tabela_arte SET titulo= '$titulo', descricao = '$descricao', dados= '$dados' WHERE id = '$id'");
		$idDoDesenho = mysql_insert_id();
		return true;
	}	
}
?>