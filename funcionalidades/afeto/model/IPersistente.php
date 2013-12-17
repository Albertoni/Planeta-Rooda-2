<?php


interface IPersistente{
	const ID_OBJETO_NAO_SALVO = -1;

	/**
	* @return Id deste objeto no banco de dados.
	*/
	public function getId();

	/**
	* Salva este objeto no banco de dados.
	* Decide se deve usar inserir ou atualizar.
	*
	* @return Booleano 	se conseguir salvar, true
	*					senão, false
	*/
	public function salvar();

	/**
	* Insere este objeto no banco de dados.
	*
	* @return Booleano 	se conseguir salvar, true
	*					senão, false
	*/
	public function inserir();
	
	/**
	* Atualiza este objeto no banco de dados.
	*
	* @return Booleano 	se conseguir salvar, true
	*					senão, false
	*/
	public function atualizar();

	/**
	* Deleta este objeto do banco de dados.
	*
	* @return Booleano 	se conseguir salvar, true
	*					senão, false
	*/
	public function deletar();

	/**
	* Busca no BD uma tupla com as propriedades do elemento passado.
	*
	* @return Array<Tipo> Array com todos os resultados encontrados no BD.
	*/
	public function busca();
}



?>