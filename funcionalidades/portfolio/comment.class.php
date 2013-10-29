<?php
class Comentario {
	private $codPost;
	private $codComentario;
	private $codAutor;
	private $texto;
	private $data;
	private $erro = "";
	function __construct($cod = 0) 
	{
		if(is_int($cod))
		{
			if($cod > 0)
			{
				$bd = new conexao();
				$bd->solicitar(
					"SELECT C.codUsuario AS codUsuario, 
							U.usuario_nome AS nomeUsuario, 
							C.codComentario AS codComentario,
							C.data AS dataComentario,
							C.texto AS textoComentario
					FROM $tabela_portfolioComentarios AS C
						INNER JOIN $tabela_usuarios AS U
						ON C.codUsuario = U.usuario_id
					WHERE codComentario = '$cod'"
				);
				$this->erro = $bd->erro;
				if (!$this->erro) {
					$this->codComentario = $cod;
					$this->codPost	= $bd->resultado['codPost'];
					$this->codAutor	= $bd->resultado['codAutor'];
					$this->texto	= $bd->resultado['textoComentario'];
					$this->data		= $bd->resultado['dataComentario'];
				}
			}
		}
	}
	public function inserirComentario($codUsuario, $codPost, $texto)
	{
		if ($codUsuario >= 0 && $codPost >= 0) 
		{
			$q = new conexao();
		}
	}
	function carregarComentarios($postId)
	{
		if (is_int($post))
		{
			$q = new conexao();
			$q->solicitar(
				""
			);
			$this->error = $q->erro;
			if ($q->registros > 0)
			{
				$this->mensagens = $q->itens;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}