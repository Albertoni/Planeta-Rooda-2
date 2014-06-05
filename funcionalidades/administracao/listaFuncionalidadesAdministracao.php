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
							 <div id="rel"><p id="balao">Aqui você pode editar usuários, criar ou editar turmas, inserir alunos nas mesmas e gerenciar as funcionalidades.</p></div>
						</div>
				  </div>
				  <div id="ajuda_base"></div>
			 </div>
		</div><!-- fim do cabecalho -->
		<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
		<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->
			<div id="conteudo" style="position:relative;margin-top:0;"><!-- tem que estar dentro da div 'conteudo_meio' -->
                <div class="bts_cima" style="float:none">
                    <a href="../../tela_inicial_geral.php" align="left" > <!-- o link está errado porque não se sabe para onder retornaremos ainda-->
                        <img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
                    </a></div>
<!--FOI POSTERGADO A IMPLEMENTACAO DA VERIFICAO SOBRE O NIVEL DO USUARIO PARA SABER SE ELE PODE ADMINISTRAR-->				
<!--Criar Turma-->
                 <div class="bloco">
                         <h1>Funcionalidades que não dependem de turma</h1>
                         <ul class="sem_estilo">
					        <li><a href="nova_turma.php">Criar Nova Turma</a></li>
                            <!--Editar Usuário-->
                            <li><a href="lista_usuarios.php">Editar Usuário</a></li>
                         </ul>
                 </div>
                <hr>
                    <div class="bloco">
                        <h1>ESCOLHA A TURMA QUE QUER GERENCIAR</h1>
                        <ul class="sem_estilo">
                            <li>Turma <span class="exemplo">(Obrigatório)</span></li>
                            <li><select name="codTurma" onchange="preparaOpcoes(this)"><!--para cada turma retornada na consulta ao BD, uma option deverá ser retornada-->
                                    <option value="" selected>Selecione uma turma</option>
                                    <?php
                                    $idProfessor = $user->getId();
                                    $q->solicitar("SELECT * FROM Turmas JOIN TurmasUsuario ON Turmas.codTurma = TurmasUsuario.codTurma WHERE TurmasUsuario.codUsuario = '$idProfessor' AND associacao=".NIVELPROFESSOR);

                                    for($i=0;$i<$q->registros;$i++){
                                        $nomeTurma = $q->resultado['nomeTurma'];
                                        $codTurma = $q->resultado['codTurma'];
                                        echo "<option value=\"$codTurma\">$nomeTurma</option>";
                                        $q->proximo();
                                    }
                                    ?>
                            </select></li>
                            <!--Editar Turma-->
                            <div id="escondeOpcoes" style="display:none">
                            <li><a class="precisaPreparar" href="editaTurma.php?turma=">Editar Turma</a></li>
                            <!--Inserir Usuarios na Turma -->
                            <li><a class="precisaPreparar" href="insereUsuario.php?turma=">Inserir Usuarios</a></li>
                            <!--Gerenciamento-->
                            <li><a class="precisaPreparar" href="../gerenciamento_funcionalidades_turmas/gerenciamento_funcionalidades_turmas.php?idTurma=">Gerenciamento de Funcionalidades</a></li>
                            <!--Por enquanto, direciona o usuario para a pagina de gerenciamento de funcionalidades -->
                            </div>
                        </ul>
                    </div>
			</div>
		</div>
		<div id="conteudo_base">
		</div><!-- para a imagem de fundo da base -->
	</div>
	</body>
    <script>
        function preparaOpcoes(select){
            var codTurma = select.options[select.selectedIndex].value;

            if(codTurma){
                document.getElementById("escondeOpcoes").style.display = "block";
            }else{
                document.getElementById("escondeOpcoes").style.display = "none";
            }

            var links = document.getElementsByClassName("precisaPreparar");
            var codTurma = select.options[select.selectedIndex].value;

            for(i=0; i < links.length; i++){
                var link = links[i].href;
                var posCodTurma = link.indexOf('=');
                link = link.substr(0,posCodTurma+1) + codTurma;
                links[i].href = link;
            }
        }
    </script>
</html>
