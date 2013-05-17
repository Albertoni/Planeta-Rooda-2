<?php
require_once('../../cfg.php');
require_once('../../bd.php');
class Comentarios {
    private $_nComentarios = 0;
    private $_postId;
    private $mensagens;

    function __construct($postId = 0) {
        if ($post > 0) {
            $q = new conexao();
            $q->solicitar(
                "SELECT C.codUsuario as codUsuario, 
                        U.usuario_nome as nomeUsuario, 
                        C.codComentario as codComentario,
                        C.data as dataComentario,
                        C.texto as textoComentario
                FROM $tabela_portfolioComentarios AS C
                    INNER JOIN $tabela_usuarios AS U
                    ON C.codUsuario = U.usuario_id
                WHERE codPost = '$postId'
                ORDER BY C.codComentario ASC"
            );
        }
    }
    function inserirComentario($codUsuario, $codPost, $texto) {
        if ($codUsuario >= 0 && $codPost >= 0) {
            $q = new conexao();
        }
    }
}
