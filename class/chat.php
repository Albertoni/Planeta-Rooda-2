<?php
/**
* Classe para representar objetos chat.
* Torna transparente o uso do banco de dados.
*/

include_once("bd/chatbd.php");

class Chat extends ChatBD{
//dados
	
//m�todos
	
	/**
	* Construtor de objetos que n�o est�o no BD.
	* @param int		nome_param		Nome do chat.
	* @return int		Id do chat criado, que j� estar� no BD. 
	*					Se retornar IObjetoBD::ID_OBJETO_NAO_SALVO, n�o conseguiu salvar.
	*/
	public static function getIdNovo($nome_param){
		$chat = new ChatBD();
		$chat->Nome = $nome_param;
		$chat->inserir();
		return $chat->Id;
	}

	/**
	* Construtor de objetos que est�o no BD.
	* @param int	id_param		Id no BD do terreno procurado.
	* @return Terreno		O terreno procurado, caso haja.
	*						Caso contr�rio, retornar� null.
	*/
	public static function getPorId($id_param){
		$conexao = new conexao();
		$conexao->solicitar("SELECT *
							FROM ".ChatBD::NOME_TABELA."
							WHERE Id = ".$id_param);
		$resultado = (0 < $conexao->registros? ChatBD::deTupla($conexao->resultado) : null);
		return $resultado;
	}

		/*
		* Setters.
		* @return Booleano 	se conseguir salvar, true
		*					sen�o, false
		*/

		/*
		* Getters.
		* @param String		propriedade_param		A propriedade que deseja-se consultar.
		*/
	public function __get($propriedade_param){
		return $this->$propriedade_param;
	}
	
	
}
?>