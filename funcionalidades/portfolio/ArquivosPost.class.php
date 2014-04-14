<?php
require_once("../../arquivo.class.php");
class ArquivosPost extends Arquivo {
	public function __construct($idPost = false, $idArquivo = false) {
		if (is_integer($idPost) && is_integer($idArquivo)) {
			$this->abrir($idPost, $idArquivo);
		}
	}

	// Função chamada pelo __construct
	// precisa de ambos os parâmetros, abre arquivo $idArquivo se ele estiver
	// relacionado post $idPost pela tabela BlogArquivos (relação n para n).
	// Isso para que ninguém possa acessar qualquer arquivo a partir de qualquer post.
	// Quando usar, verifique antes se o usuario tem permissão para ver arquivos naquele post.
	protected function abrir($idPost = 0, $idArquivo = 0) {
		$bd = new conexao();
		$bd->solicitar(
			"SELECT
			TA.arquivo_id AS id,
			TA.titulo AS 'titulo',
			TA.nome AS 'nome',
			TA.tipo AS 'tipo',
			TA.tamanho AS 'tamanho',
			TA.arquivo AS 'conteudo',
			TA.md5 AS 'md5',
			TA.dataUpload AS 'data',
			TA.uploader_id AS 'idUsuario'
			FROM arquivos AS TA
			INNER JOIN PortfolioArquivos AS TPA
			ON TA.arquivo_id = TPA.idArquivo
			WHERE TPA.idPost = $idPost
			AND TPA.idArquivo = $idArquivo"
		);
		if ($bd->erro !== '') {
			throw new Exception('BD: ' . $bd->erro, 1);
			return false;
		}
		$this->popular($bd->resultado);
	}
	public function abrirPost($idPost) {
		global $tabela_arquivos;
		if (is_object($idPost)) {
			if (method_exists($idPost, 'getId')) {
				$idPost = $idPost->getId();
			}else{
				throw new Exception('Foi passado um objeto sem getId() para a abrir da ArquivosPost. Inteiro ou objeto com getId() definida esperado', 1);
			}
		}
		$idPost = (int) $idPost;
		$this->consulta = new conexao();
		$this->consulta->solicitar(
			"SELECT
			TA.arquivo_id AS id,
			TA.titulo AS 'titulo',
			TA.nome AS 'nome',
			TA.tipo AS 'tipo',
			TA.tamanho AS 'tamanho',
			TA.arquivo AS 'conteudo',
			TA.md5 AS 'md5',
			TA.dataUpload AS 'data',
			TA.uploader_id AS 'idUsuario'
			FROM $tabela_arquivos AS TA
			INNER JOIN PortfolioArquivos AS TPA
			ON TA.arquivo_id = TPA.idArquivo
			WHERE TPA.idPost = $idPost"
		);
		if ($this->consulta->erro !== '') {
			throw new Exception('BD: ' . $this->consulta->erro, 1);
			return false;
		}
		if (!($this->consulta->resultado)) {
			$this->limpar();
		} else {
			$this->popular($this->consulta->resultado);
		}
		return $this->consulta->registros;
	}
}