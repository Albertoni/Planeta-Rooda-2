<?php
/**
* Representação básica de um chat no banco de dados.
*/

	//BD
include_once(dirname(__FILE__)."/../../bd.php");
include_once(dirname(__FILE__)."/../../cfg.php");

	//interface
include_once("objetobd.php");

class ChatBD extends ObjetoBD{
//dados
		//dados
	protected $Id;
	protected $Nome;
	const NOME_TABELA = "Chats";

//métodos
	protected function ChatBD($id_param = self::ID_OBJETO_NAO_SALVO, $nome_param = "Chat sem nome"){
		$this->Id = $id_param;
		$this->Nome = $nome_param;
	}
	
	/**
	* "Inicializador".
	* @param Array<String,Object>		array_param		Array retornado por uma consulta SQL.
	* @return 
	*/
	protected static function deTupla($array_param){
		return new ChatBD(	$array_param['Id'], $array_param['Nome']);
	}
	
	/**
	* Salva este objeto no banco de dados.
	* Decide se deve usar inserir ou atualizar.
	* @param conexao		conexao_param		Conexão que será usada. Se não existir, criará nova conexão.
	* @return Booleano 	se conseguir salvar, true
	*					senão, false
	*/
	public function salvar($conexao_param = ""){
		if($this->Id == self::ID_OBJETO_NAO_SALVO){
			return $this->inserir($conexao_param);
		} else {
			return $this->salvar($conexao_param);
		}
	}

	/**
	* Insere este objeto no banco de dados.
	* @param conexao		conexao_param		Conexão que será usada. Se não existir, criará nova conexão.
	*/
	public function inserir($conexao_param = ""){
		$conexao = ($conexao_param == ""? new conexao() : $conexao_param);
		$conexao->solicitar("INSERT INTO ".self::NOME_TABELA." (Nome)
							VALUES ('".$this->Nome."')");
		if($conexao->erro != ""){
			throw new Exception($conexao->erro);
		} else {
			$this->Id = mysql_insert_id();
		}
	}
	
	/**
	* Atualiza este objeto no banco de dados.
	* @param conexao		conexao_param		Conexão que será usada. Se não existir, criará nova conexão.
	*/
	public function atualizar($conexao_param = ""){
		$conexao = ($conexao_param == ""? new conexao() : $conexao_param);
		$conexao->solicitar("UPDATE ".self::NOME_TABELA."
							SET Nome = ".$this->Nome."
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