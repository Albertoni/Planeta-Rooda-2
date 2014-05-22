<?php

/*
									Notas sobre o funcionamento desta página.

As checkboxes são criadas via javascript, com base nas tags e suas classes deste HTML.
É feito desta forma para que possíveis alterações só afetem este arquivo (possivelmente não afetarão nem o banco de dados).
O procedimento é o seguinte:
	(1) Será criada uma tabela (do html, não bd) para cada div da classe gerenciamentoFuncionalidade, o nome da classe será o nome base das checkboxes desta tabela.
	(2) Todos os elementos th da tabela serão considerados colunas e as checkboxes nessas colunas terão no tipo o nome da classe do th ao qual pertencem.
	(3) Para cada tr é criada uma linha de checkboxes, cujos nomes serão o conteúdo das tds da classe opcaoGerenciamento dentro da tr.
Tudo isto é feito via javascript, na função criarCheckboxes que é chamada lá no fim deste arquivo.
*/

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../reguaNavegacao.class.php");

$user = usuario_sessao();

$codTurma = (isset($_GET['idTurma']) and is_numeric($_GET['idTurma'])) ? $_GET['idTurma'] : die("Ops, aparentemente n&atilde;o existe uma turma com esse idTurma, por favor tente de novo.");

$conexaoBuscaDadosGerenciamentoTurma = new conexao();
$conexaoBuscaDadosGerenciamentoTurma->solicitar("SELECT *
												FROM GerenciamentoTurma
												WHERE codTurma=$codTurma");

$conexaoBuscaDadosFuncionalidadesTurma = new conexao();
$conexaoBuscaDadosFuncionalidadesTurma->solicitar("SELECT *
												   FROM FuncionalidadesTurma
												   WHERE codTurma=$codTurma");

$buscaNomeTurma = new conexao();
$buscaNomeTurma->solicitar("SELECT nomeTurma from Turmas WHERE codTurma=$codTurma");
$nomeDaTurma = $buscaNomeTurma->resultado['nomeTurma'];

if(0 < $conexaoBuscaDadosFuncionalidadesTurma->registros){
	$turmaTem_comunicador = ($conexaoBuscaDadosFuncionalidadesTurma->resultado['batePapo']        == "h"? true : false);
	$turmaTem_biblioteca  = ($conexaoBuscaDadosFuncionalidadesTurma->resultado['biblioteca']      == "h"? true : false);
	$turmaTem_blog        = ($conexaoBuscaDadosFuncionalidadesTurma->resultado['blog']            == "h"? true : false);
	$turmaTem_portfolio   = ($conexaoBuscaDadosFuncionalidadesTurma->resultado['portfolio']       == "h"? true : false);
	$turmaTem_forum       = ($conexaoBuscaDadosFuncionalidadesTurma->resultado['forum']           == "h"? true : false);
	$turmaTem_arte        = ($conexaoBuscaDadosFuncionalidadesTurma->resultado['planetaArte']     == "h"? true : false);
	$turmaTem_pergunta    = ($conexaoBuscaDadosFuncionalidadesTurma->resultado['planetaPergunta'] == "h"? true : false);
	$turmaTem_player      = ($conexaoBuscaDadosFuncionalidadesTurma->resultado['planetaPlayer']   == "h"? true : false);
	$turmaTem_aulas       = ($conexaoBuscaDadosFuncionalidadesTurma->resultado['aulas']           == "h"? true : false);
} else {
	$turmaTem_comunicador = true;
	$turmaTem_biblioteca  = true;
	$turmaTem_blog        = true;
	$turmaTem_portfolio   = true;
	$turmaTem_forum       = true;
	$turmaTem_arte        = true;
	$turmaTem_pergunta    = true;
	$turmaTem_player      = true;
	$turmaTem_aulas       = true;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../planeta.css" rel="stylesheet" type="text/css">
<link href="planeta_pergunta.css" rel="stylesheet" type="text/css">
<script src="../../jquery.js" type="text/javascript"></script>
<script src="../../planeta.js" type="text/javascript"></script>
<script type="text/javascript" src="../lightbox.js"></script>
</head>

<body onload="atualiza('ajusta()');inicia();">

<div id="topo">
	<div id="centraliza_topo">
		<?php 
			$regua = new reguaNavegacao();
			$regua->adicionarNivel("Gerenciamento da turma ".$nomeDaTurma);
			$regua->imprimir();
		?>
		<p id="bt_ajuda">
			<span class="troca">OCULTAR AJUDANTE</span>
			<span class="troca" style="display:none">CHAMAR AJUDANTE</span>
		</p>
	</div>
</div>

<script src="gerenciamento_funcionalidades_turmas.js"></script>

<div id="geral">
	<div id="cabecalho">
		<div id="ajuda">
			<div id="ajuda_meio">
				<div id="ajudante">
					<div id="personagem">
						<img align="left" height="145" alt="Ajudante" src="../../images/desenhos/ajudante.png">
					</div>
					<div id="rel">
						<p id="balao" style="margin-top: 33.5px;">No Gerenciamento de Turmas você pode habilitar as ferramentas que deseja utilizar com seus alunos, configurando as possibilidades de acesso que cada usuário poderá ter.</p>
					</div>
				</div>
			</div>
			<div id="ajuda_base"></div>
		</div>
	</div>
	<a name="topo"></a>
	<div id="conteudo_topo"></div>
	<div id="conteudo_meio" style="height: 1719px;">
		<div id="conteudo">
		<div class="bts_cima" style="float:none">
			<a href="../administracao/listaFuncionalidadesAdministracao.php" align="left" >
				<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
			</a>
		</div>
			<big><b>Gerenciamento da Turma <?=$nomeDaTurma?></b></big><br><br><br>
			<form name="salvar_BD" method="post" action='gerenciamento_funcionalidades_turmas_gravacao.php?'>
			<ul>
			<!-- ********************************************* GERENCIAMENTO DO COMUNICADOR ******************************************************** -->
			<div class="bloco">
				<div class="gerenciamentoFuncionalidade" id="comunicador">
				<li><b><h1>Comunicador</h1></b><span class="margem_esquerda">Marque para habilitar esta funcionalidade para esta turma:</span>
						<input type="checkbox" class="habilitarFuncionalidade" name="comunicador" <?php if($turmaTem_comunicador){echo 'checked';}?> onclick="toggleVisibilidade('habilitarFuncionalidade_comunicador')"></li>
				<br><span class="margem_esquerda">Possibilita interação sí­ncrona entre os usuários.</span><br><br><br>
				<div <?php if(!$turmaTem_comunicador){echo 'style="display:none"';}?> id="habilitarFuncionalidade_comunicador">
<!-- Versão anterior a 13/05/2014
					<table border="0" width="100%" cellpadding="0px" cellspacing="0">
						<th></th>
						<th class="formador">Habilitado para Formadores</td>
						<th class="todos">Habilitado para Todos</td>
	<!-- Versão anterior a 08/05/2014
						<th class="monitor">Habilitado para Monitores</td>
						<th class="aluno">Habilitado para Alunos</td> 
					<tr>
						<td class="opcaoGerenciamento">Chat de Terreno</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Chat de Turma</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Chat Privado</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Chat Amigo</td>
					</tr>
					</table> -->
				</div></div>
			</div><br><br>
			
			<!-- ********************************************* GERENCIAMENTO DA BIBLIOTECA ******************************************************** -->
			<div class="bloco">
				<div class="gerenciamentoFuncionalidade" id="biblioteca">
				<li><b><h1>Biblioteca</h1></b><span class="margem_esquerda">Marque para habilitar esta funcionalidade para esta turma: </span>
						<input type="checkbox" class="habilitarFuncionalidade" name="biblioteca" <?php if($turmaTem_biblioteca){echo 'checked';}?> onclick="toggleVisibilidade('habilitarFuncionalidade_biblioteca')"></li>
				<br><span class="margem_esquerda">Oportuniza a publicação e organização de materiais e links.</span><br><br><br>
				<div <?php if(!$turmaTem_biblioteca){echo 'style="display:none"';}?> id="habilitarFuncionalidade_biblioteca">
					<table border="0">
						<th></th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
<!-- Versão anterior a 08/05/2014
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
-->
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="biblioteca_Enviar Materiais">Enviar Materiais</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="biblioteca_Editar Materiais">Editar Materiais</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="biblioteca_Excluir Arquivos">Excluir Arquivos</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="biblioteca_Aprovar Arquivos">Aprovar Arquivos</td>
					</tr>
					</table>
				</div></div>
			</div><br><br>
			
			<!-- ********************************************* GERENCIAMENTO DO BLOG ******************************************************** -->
			<div class="bloco">
				<div class="gerenciamentoFuncionalidade" id="blog">
				<li><b><h1>Blog da Turma</h1></b><span class="margem_esquerda">Marque para habilitar esta funcionalidade para esta turma: </span>
						<input type="checkbox" class="habilitarFuncionalidade" name="blog" <?php if($turmaTem_blog){echo 'checked';}?> onclick="toggleVisibilidade('habilitarFuncionalidade_blog')"></li>
				<br><span class="margem_esquerda">Permite que os usuários e professor possam inserir posts e comentários.</span><br><br><br>
				<div <?php if(!$turmaTem_blog){echo 'style="display:none"';}?> id="habilitarFuncionalidade_blog">
					<table border="0">
						<th><b></b></th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
<!--Versão anterior a 08/05/2014
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
-->
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="blog_Inserir Post">Inserir Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="blog_Editar Post">Editar Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="blog_Inserir Comentários">Inserir Comentários</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="blog_Excluir Post">Excluir Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="blog_Adicionar Links">Adicionar Links</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="blog_Adicionar Arquivos">Adicionar Arquivos</td>
					</tr>
					</table>
				</div></div>
			</div><br><br>
			
			<!-- ********************************************* GERENCIAMENTO DO PORTFÓLIO ******************************************************** -->
			<div class="bloco">
				<div class="gerenciamentoFuncionalidade" id="portfolio">
				<li><b><h1>Portfólio</h1></b><span class="margem_esquerda">Marque para habilitar esta funcionalidade para esta turma: </span>
						<input type="checkbox" class="habilitarFuncionalidade" name="portfolio" <?php if($turmaTem_portfolio){echo 'checked';}?> onclick="toggleVisibilidade('habilitarFuncionalidade_portfolio')"></li>
				<br><span class="margem_esquerda">Destinado aos professores e voltada à construção de um histórico da turma através do registro e da publicação de arquivos, possibilitando acompanhar os alunos e as práticas pedagógicas.</span><br><br><br>
				<div <?php if(!$turmaTem_portfolio){echo 'style="display:none"';}?> id="habilitarFuncionalidade_portfolio">
					<table border="0">
						<th><b></b></th>
                        <th class="monitor">Habilitado para Monitores</th>
                        <th class="aluno">Habilitado para Alunos</th>
<!--Versão anterior a 08/05/2014
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
-->					
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="portfolio_Visualizar Post">Visualizar Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="portfolio_Inserir Post">Inserir Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="portfolio_Editar Post">Editar Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="portfolio_Inserir Comentários">Inserir Comentários</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento"  data-nome-funcionalidade="portfolio_Excluir Post">Excluir Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento"  data-nome-funcionalidade="portfolio_Adicionar Links">Adicionar Links</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento"  data-nome-funcionalidade="portfolio_Enviar Arquivos">Enviar Arquivos</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento"  data-nome-funcionalidade="portfolio_Excluir Arquivos">Excluir Arquivos</td>
					</tr>
					</table>
				</div></div>
			</div><br><br>
			
			<!-- ********************************************* GERENCIAMENTO DO FÓRUM ******************************************************** -->
			<div class="bloco">
				<div class="gerenciamentoFuncionalidade" id="forum">
				<li><b><h1>Fórum</h1></b><span class="margem_esquerda">Marque para habilitar esta funcionalidade para esta turma: </span>
						<input type="checkbox" class="habilitarFuncionalidade" name="forum" <?php if($turmaTem_forum){echo 'checked';}?> onclick="toggleVisibilidade('habilitarFuncionalidade_forum')"></li>
				<br><span class="margem_esquerda">Possibilita a interação assínc­rona entre os usuários, com as mensagens organizadas de forma hierárquica.<span><br><br><br>
				<div <?php if(!$turmaTem_forum){echo 'style="display:none"';}?> id="habilitarFuncionalidade_forum">
					<table border="0">
						<th><b></b></th>
                        <th class="monitor">Habilitado para Monitores</th>
                        <th class="aluno">Habilitado para Alunos</th>
<!--Versão anterior a 08/05/2014					
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
-->
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="forum_Criar Tópico">Criar Tópico</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="forum_Editar Tópico">Editar Tópico</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="forum_Excluir Tópico">Excluir Tópico</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="forum_Responder Tópico">Responder Tópico</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="forum_Editar Resposta">Editar Resposta</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="forum_Excluir Resposta">Excluir Resposta</td>
					</tr>
<!--Legado de alguma era pré-joaniana
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="portfolio_Excluir Arquivos">Editar Anexo</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="portfolio_Excluir Arquivos">Excluir Anexo</td>
					</tr>
-->
					</table>
				</div></div>
			</div><br><br>
			
			<!-- ********************************************* GERENCIAMENTO DO PLANETA ARTE ******************************************************** -->
			<div class="bloco">
				<div class="gerenciamentoFuncionalidade" id="arte">
				<li><b><h1>Planeta Arte</h1></b><span class="margem_esquerda">Marque para habilitar esta funcionalidade para esta turma: </span>
						<input type="checkbox" class="habilitarFuncionalidade" name="arte" <?php if($turmaTem_arte){echo 'checked';}?> onclick="toggleVisibilidade('habilitarFuncionalidade_arte')"></li>
				<br><span class="margem_esquerda">Permite a montagem e a exposição de imagens, com a possibilidade de inserção de comentários.</span><br><br><br>
				<div <?php if(!$turmaTem_arte){echo 'style="display:none"';}?> id="habilitarFuncionalidade_arte">
					<table border="0">
						<td><b></b></td>
                        <th class="monitor">Habilitado para Monitores</th>
                        <th class="aluno">Habilitado para Alunos</th>
<!--						
						<th class="professor">Habilitado para Professores</td>
						<th class="monitor">Habilitado para Monitores</td>
						<th class="aluno">Habilitado para Alunos</td>
						<th class="todos">Habilitado para Todos</td>
-->						
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="arte_Criar Desenho">Criar Desenho</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="arte_Excluir Desenho">Excluir Desenho</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="arte_Ver Comentários">Ver Comentários</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="arte_Inserir Comentários">Inserir Comentários</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="arte_Excluir Comentários">Excluir Comentário</td>
					</tr>
					</table>
				</div></div>
			</div><br><br>
			
			<!-- ********************************************* GERENCIAMENTO DO PLANETA PERGUNTA ******************************************************** -->
			<div class="bloco">
				<div class="gerenciamentoFuncionalidade" id="pergunta">
				<li><b><h1>Planeta Pergunta</h1></b><span class="margem_esquerda">Marque para habilitar esta funcionalidade para esta turma: </span>
						<input type="checkbox" class="habilitarFuncionalidade" name="pergunta" <?php if($turmaTem_pergunta){echo 'checked';}?> onclick="toggleVisibilidade('habilitarFuncionalidade_pergunta')"></li>
				<br><span class="margem_esquerda">Permite a elaboração de jogos de perguntas e respostas.</span><br><br><br>
				<div <?php if(!$turmaTem_pergunta){echo 'style="display:none"';}?> id="habilitarFuncionalidade_pergunta">
					<table border="0">
						<th></th>
                        <th class="monitor">Habilitado para Monitores</th>
                        <th class="aluno">Habilitado para Alunos</th>
<!--Versão anterior a 08/05/2014
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
-->						
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="pergunta_Criar Questionário">Criar Questionário</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="pergunta_Criar Pergunta">Criar Pergunta</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="pergunta_Editar Questionário">Editar Questionário</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="pergunta_Editar Pergunta">Editar Pergunta</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="pergunta_Deletar Questionário">Deletar Questionário</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="pergunta_Deletar Pergunta">Deletar Pergunta</td>
					</tr>
					</table>
				</div></div>
			</div><br><br>
			
			<!-- ********************************************* GERENCIAMENTO DO PLANETA PLAYER ******************************************************** -->
			<div class="bloco">
				<div class="gerenciamentoFuncionalidade" id="player">
				<li><b><h1>Planeta Player</h1></b><span class="margem_esquerda">Marque para habilitar esta funcionalidade para esta turma: </span>
						<input type="checkbox" class="habilitarFuncionalidade" name="player" <?php if($turmaTem_player){echo 'checked';}?> onclick="toggleVisibilidade('habilitarFuncionalidade_player')"></li>
				<br><span class="margem_esquerda">[Descrição do player.]</span><br><br><br>
				<div <?php if(!$turmaTem_player){echo 'style="display:none"';}?> id="habilitarFuncionalidade_player">
					<table border="0">
						<th></th>
                        <th class="monitor">Habilitado para Monitores</th>
                        <th class="aluno">Habilitado para Alunos</th>
<!--Versão anterior a 08/05/2014						
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
-->						
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="player_Inserir Vídeos">Inserir Vídeos</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="player_Deletar Vídeos">Deletar Vídeos</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="player_Ver Comentários">Ver Comentários</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="player_Comentar Vídeos">Comentar Vídeos</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="player_Deletar Comentário">Deletar Comentário</td>
					</tr>
					</table>
				</div></div>
			</div><br><br>
			
			<!-- ********************************************* GERENCIAMENTO DO AULAS ******************************************************** -->
			<div class="bloco">
				<div class="gerenciamentoFuncionalidade" id="aulas">
				<li><b><h1>Aulas</h1></b><span class="margem_esquerda">Marque para habilitar esta funcionalidade para esta turma: </span>
						<input type="checkbox" class="habilitarFuncionalidade" name="aulas" <?php if($turmaTem_aulas){echo 'checked';}?> onclick="toggleVisibilidade('habilitarFuncionalidade_aulas')"></li>
				<br><span class="margem_esquerda">[Descrição do aulas.]</span><br><br><br>
				<div <?php if(!$turmaTem_aulas){echo 'style="display:none"';}?> id="habilitarFuncionalidade_aulas">
<!--Versão anterior a 21/05/2014
					<table border="0">
						<th></th>
                        <th class="monitor">Habilitado para Monitores</th>
                        <th class="aluno">Habilitado para Alunos</th>
    Versão anterior a 08/05/2014
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>

					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="aulas_Criar Aulas">Criar Aulas</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="aulas_Editar Aulas">Editar Aulas</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento" data-nome-funcionalidade="aulas_Importar Aulas">Importar Aulas</td>
					</tr>
					</table>-->
				</div></div>
			</div>
			</ul>
			
			<script>
                /*
                 * _checkboxesQuePodemExistir  Um objeto JavaScript que contém as regras de quais
                 *           elementos podem ou não ser oferecidos para o professor escolher. Vide ata
                 *           de 8-9 de maio de 2014.
                 */
                var checkboxesQuePodemExistir = {
                    //descreve qual cabeçalho da tabela pode ter a permissão habilitada para a funcionalidade biblioteca.
                    "biblioteca_Enviar Materiais":      ['monitor',     'aluno'],
                    "biblioteca_Editar Materiais":      ['monitor'             ],
                    "biblioteca_Excluir Arquivos":      ['monitor'             ],
                    "biblioteca_Aprovar Arquivos":      ['monitor'             ],
                    //descreve qual cabeçalho da tabela pode ter a permissão habilitada para a funcionalidade webfolio.
                    "blog_Inserir Post":                ['monitor',     'aluno'],
                    "blog_Editar Post":                 ['monitor',     'aluno'],
                    "blog_Inserir Comentários":         ['monitor',     'aluno'],
                    "blog_Excluir Post":                ['monitor',     'aluno'],
                    "blog_Adicionar Links":             ['monitor',     'aluno'],
                    "blog_Adicionar Arquivos":          ['monitor',     'aluno'],
                    //descreve qual cabeçalho da tabela pode ter a permissão habilitada para a funcionalidade projetos.
                    "portfolio_Visualizar Post":        ['monitor',     'aluno'],
                    "portfolio_Inserir Post":           ['monitor',     'aluno'],
                    "portfolio_Editar Post":            ['monitor',     'aluno'],
                    "portfolio_Inserir Comentários":    ['monitor',     'aluno'],
                    "portfolio_Excluir Post":           [                      ],
                    "portfolio_Adicionar Links":        ['monitor',     'aluno'],
                    "portfolio_Enviar Arquivos":        ['monitor',     'aluno'],
                    "portfolio_Excluir Arquivos":       [                      ],
                    //descreve qual cabeçalho da tabela pode ter a permissão habilitada para a funcionalidade forum.
                    "forum_Criar Tópico":               ['monitor',     'aluno'],
                    "forum_Editar Tópico":              ['monitor',     'aluno'],
                    "forum_Excluir Tópico":             ['monitor',            ],
                    "forum_Responder Tópico":           ['monitor',     'aluno'],
                    "forum_Editar Resposta":            ['monitor',     'aluno'],
                    "forum_Excluir Resposta":           ['monitor',     'aluno'],
                    //descreve qual cabeçalho da tabela pode ter a permissão habilitada para a funcionalidade planeta arte.
                    "arte_Criar Desenho":               ['monitor',     'aluno'],
                    "arte_Excluir Desenho":             ['monitor'             ],
                    "arte_Ver Comentários":             ['monitor',     'aluno'],
                    "arte_Inserir Comentários":         ['monitor',     'aluno'],
                    "arte_Excluir Comentário":          ['monitor'             ],
                    //descreve qual cabeçalho da tabela pode ter a permissão habilitada para a funcionalidade planeta pergunta.
                    "pergunta_Criar Questionário":      ['monitor',     'aluno'],
                    "pergunta_Criar Pergunta":          ['monitor',     'aluno'],
                    "pergunta_Editar Questionário":     ['monitor',     'aluno'],
                    "pergunta_Editar Pergunta":         ['monitor',     'aluno'],
                    "pergunta_Deletar Questionário":    ['monitor'             ],
                    "pergunta_Deletar Pergunta":        ['monitor'             ],
                    //descreve qual cabeçalho da tabela pode ter a permissão habilitada para a funcionalidade planeta player.
                    "player_Inserir Vídeos":            ['monitor',     'aluno'],
                    "player_Deletar Vídeos":            ['monitor'             ],
                    "player_Ver Comentários":           ['monitor',     'aluno'],
                    "player_Comentar Vídeos":           ['monitor',     'aluno'],
                    "player_Deletar Comentário":        ['monitor'             ],
                    //descreve qual cabeçalho da tabela pode ter a permissão habilitada para a funcionalidade planeta aulas.
                    "aulas_Criar Aulas":                [                      ],
                    "aulas_Editar Aulas":               [                      ],
                    "aulas_Importar Aulas":             [                      ]
                };

                criarCheckboxes(checkboxesQuePodemExistir);
			<?php
				if(0 < $conexaoBuscaDadosGerenciamentoTurma->registros){
					$estadosCheckboxes = $conexaoBuscaDadosGerenciamentoTurma->resultado['dadosGerenciamento'];
					
					echo "definirEstadosCheckboxes('$estadosCheckboxes');";
				}
			?>
			</script>
			<input type="hidden" name="codTurma" value="<?=$codTurma?>">
			<input type="button" class="botao_salvar" value="" onclick="criarInputComConteudoDasCheckboxes()">
			</form>
		</div>
	</div>
	<div id="conteudo_base"></div>
</div>
</body>
</html>
