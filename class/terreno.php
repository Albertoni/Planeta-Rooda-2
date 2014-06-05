<?php
/**
* Classe para representar objetos terreno.
* Torna transparente o uso do banco de dados.
*/

include_once("bd/terrenobd.php");

class Terreno extends TerrenoBD{
//dados
	
//métodos
	
	/**
	* Construtor de objetos que não estão no BD.
	* @param int		tipo_param		TerrenoBD::TIPO_PRINCIPAL ou TerrenoBD::TIPO_PATIO.
	* @return int		Id do terreno criado, que já estará no BD. 
	*					Se retornar IObjetoBD::ID_OBJETO_NAO_SALVO, não conseguiu salvar.
	*/
	public static function getIdNovo($tipo_param){
		$terreno = new TerrenoBD();
		$terreno->Tipo = $tipo_param;
		$terreno->inserir();
		return $terreno->Id;
	}

	/**
	* Construtor de objetos que estão no BD.
	* @param int	id_param		Id no BD do terreno procurado.
	* @return Terreno		O terreno procurado, caso haja.
	*						Caso contrário, retornará null.
	*/
	public static function getPorId($id_param){
		$conexao = new conexao();
		$conexao->solicitar("SELECT *
							FROM ".TerrenoBD::NOME_TABELA."
							WHERE Id = ".$id_param);
		$resultado = (0 < $conexao->registros? TerrenoBD::deTupla($conexao->resultado) : null);
		return $resultado;
	}
	
		/*
		* Setters.
		* @return Booleano 	se conseguir salvar, true
		*					senão, false
		*/
	/**
	* @param int		autor_param		Id do usuário que editou o terreno.
	*/
	public function setEdicao($autor_param){
		$this->StatusEdicao = true;
		$this->AutorEdicao = $autor_param;
		return $this->atualizar();
	}

		/*
		* Getters.
		* @param String		propriedade_param		A propriedade que deseja-se consultar.
		*/
	public function __get($propriedade_param){
		return $this->$propriedade_param;
	}
	
	
}
?>