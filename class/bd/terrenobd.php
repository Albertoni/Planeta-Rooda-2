<?php
/**
* Representação básica de um terreno no banco de dados.
*/

	//BD
include_once(dirname(__FILE__)."/../../bd.php");
include_once(dirname(__FILE__)."/../../cfg.php");

	//interface
include_once("objetobd.php");

	//class
include_once(dirname(__FILE__)."/../chat.php");

class TerrenoBD extends ObjetoBD{
//dados
		//Tipos
	const TIPO_PRINCIPAL=1;
	const TIPO_PATIO=2;
	
		//dados
	protected $Id;
	protected $Tipo;
	protected $StatusEdicao;
	protected $AutorEdicao;
	protected $Chat;
	const NOME_TABELA = "terrenos";

//métodos
	protected function TerrenoBD($id_param = self::ID_OBJETO_NAO_SALVO, $tipo_param = self::TIPO_PRINCIPAL, 
			$statusEdicao_param = false, $autorEdicao_param = self::ID_OBJETO_NAO_SALVO, $chat_param = self::ID_OBJETO_NAO_SALVO){
		$this->Id = $id_param;
		$this->Tipo = $tipo_param;
		$this->StatusEdicao = $statusEdicao_param;
		$this->AutorEdicao = $autorEdicao_param;
		$this->Chat = $chat_param;
	}
	
	/**
	* "Inicializador".
	* @param Array<String,Object>		array_param		Array retornado por uma consulta SQL.
	* @return 
	*/
	protected static function deTupla($array_param){
		return new TerrenoBD(	$array_param['Id'], $array_param['Tipo'], $array_param['StatusEdicao'], $array_param['AutorEdicao'], $array_param['Chat']);
	}
	
	/**
	* Salva este objeto no banco de dados.
	* Decide se deve usar inserir ou atualizar.
	* @param conexao		conexao_param		Conexão que será usada. Se não existir, criará nova conexão.
	*/
	public function salvar($conexao_param = ""){
		if($this->Id == self::ID_OBJETO_NAO_SALVO){
			$this->inserir($conexao_param);
		} else {
			$this->salvar($conexao_param);
		}
	}

	/**
	* Insere este objeto no banco de dados.
	* @param conexao		conexao_param		Conexão que será usada. Se não existir, criará nova conexão.
	*/
	public function inserir($conexao_param = ""){
		$conexao = ($conexao_param == ""? new conexao() : $conexao_param);
		if($this->Chat == self::ID_OBJETO_NAO_SALVO){
			$nomeChat = "Terreno";
			$this->Chat = Chat::getIdNovo($nomeChat);
		}
		$conexao->solicitar("INSERT INTO ".self::NOME_TABELA." (Tipo, StatusEdicao, AutorEdicao, Chat)
							VALUES (".$this->Tipo.", ".((int) $this->StatusEdicao).", ".$this->AutorEdicao.", ".$this->Chat.")");
		if($conexao->erro != ""){
			throw new Exception($conexao->erro);
		} else {
			$this->Id = $conexao->ultimoId();
		}
	}
	
	/**
	* Atualiza este objeto no banco de dados.
	* @param conexao		conexao_param		Conexão que será usada. Se não existir, criará nova conexão.
	*/
	public function atualizar($conexao_param = ""){
		$conexao = ($conexao_param == ""? new conexao() : $conexao_param);
		$conexao->solicitar("UPDATE ".self::NOME_TABELA."
							SET Tipo = ".$this->Tipo.", 
								StatusEdicao = ".$this->StatusEdicao.",
								AutorEdicao = ".$this->AutorEdicao.",
								Chat = ".$this->Chat."
							WHERE Id = ".$this->Id);
		if($conexao->erro != ""){
			throw new Exception($conexao->erro);
		}
	}

	/**
	* Deleta este objeto do banco de dados.
	* @param conexao		conexao_param		Conexão que será usada. Se não existir, criará nova conexão.
	*/
	public function deletar($conexao_param = ""){
		$conexao = ($conexao_param == ""? new conexao() : $conexao_param);
		
		$conexao->solicitar("DELETE FROM ".self::NOME_TABELA."
							WHERE Id = ".$this->Id);
		if($conexao->erro != ""){
			throw new Exception($conexao->erro);
		}
	}
	
	
}


?>
