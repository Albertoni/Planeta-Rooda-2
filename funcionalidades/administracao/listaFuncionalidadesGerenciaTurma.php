<?php
header('Content-type: text/html; charset=utf-8');
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("verificaPermissoesAdministracao.php");

$user = usuario_sessao();

validaPermissaoAcesso($user->getid());

if ($user===false){ // Se isso não estiver setado, o usuario não está logado
    die('<a href="../../index.php">Por favor volte e entre em sua conta.</a>');
}

$q = new conexao();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Planeta Rooda - Funcionalidades</title>
    <link href="../../planeta.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="geral">
    <!-- **************************
                    cabecalho
    ***************************** -->
    <div id="cabecalho">
        <div id="ajuda">
            <div id="ajuda_meio">
                <div id="ajudante">
                    <div id="personagem"><img src="../../images/desenhos/ajudante.png" height=145 align="left" alt="Ajudante" /></div>
                    <div id="rel"><p id="balao">Aqui você pode editar turmas, inserir alunos e gerenciar as funcionalidades da turma.</p></div>
                </div>
            </div>
            <div id="ajuda_base"></div>
        </div>
    </div><!-- fim do cabecalho -->
    <div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
    <div id="conteudo_meio"><!-- para a imagem de fundo do meio -->
        <div id="conteudo" style="position:relative;margin-top:0;"><!-- tem que estar dentro da div 'conteudo_meio' -->
            <div class="bts_cima" style="float:none">
                <a href="../../listaFuncionalidades.php?turma=<?=$_GET['turma']?>" align="left" > <!-- o link está errado porque não se sabe para onder retornaremos ainda-->
                    <img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
                </a></div>
            <div class="bloco">
                <?php
                    $codigoTurma = $_GET['turma'];
                    $q->solicitar("SELECT nomeTurma FROM Turmas WHERE codTurma=$codigoTurma");
                    $nomeTurma = $q->resultado['nomeTurma'];
                ?>
                <h1>Gerenciamento da turma <?=$nomeTurma?></h1>
                <ul class="sem_estilo">
                    <!--Editar Turma-->
                    <div>
                        <li><a href="editaTurma.php?turma=<?=$_GET['turma']?>">Editar Turma</a></li>
                        <!--Inserir Usuarios na Turma -->
                        <li><a href="insereUsuario.php?turma=<?=$_GET['turma']?>">Inserir Usuarios</a></li>
                        <!--Gerenciamento-->
                        <li><a href="../gerenciamento_funcionalidades_turmas/gerenciamento_funcionalidades_turmas.php?idTurma=<?=$_GET['turma']?>">Gerenciamento de Funcionalidades</a></li>
                        <!--Por enquanto, direciona o usuario para a pagina de gerenciamento de funcionalidades -->
                        <li><a href="importarAlunosTurma.php?turma=<?=$_GET['turma']?>">Importar alunos de uma turma</a></li>
                    </div>
                </ul>
            </div>
        </div>
        <input type="hidden" name="deOndeVem" value="listaFuncionalidadesGerenciaTurma.php">
    </div>
    <div id="conteudo_base">
    </div><!-- para a imagem de fundo da base -->
</div>
</body>
</html>
