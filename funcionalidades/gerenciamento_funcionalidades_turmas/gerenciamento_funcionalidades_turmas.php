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

session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../reguaNavegacao.class.php");


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
					<table border="0" width="100%" cellpadding="0px" cellspacing="0">
						<th></th>
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
					</table>
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
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
					<tr>
						<td class="opcaoGerenciamento">Enviar Materias</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Editar Materias</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Excluir Arquivos</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Aprovar Arquivos</td>
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
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
					<tr>
						<td class="opcaoGerenciamento">Inserir Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Editar Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Inserir Comentários</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Excluir Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Adicionar Links</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Adicionar Arquivos</td>
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
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
					<tr>
						<td class="opcaoGerenciamento">Visualizar Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Inserir Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Editar Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Inserir Comentários</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Excluir Post</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Adicionar Links</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Adicionar Arquivos</td>
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
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
					<tr>
						<td class="opcaoGerenciamento">Criar Tópico</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Editar Tópico</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Excluir Tópico</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Responder Tópico</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Editar Resposta</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Excluir Resposta</td>
					</tr>
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
						<th class="professor">Habilitado para Professores</td>
						<th class="monitor">Habilitado para Monitores</td>
						<th class="aluno">Habilitado para Alunos</td>
						<th class="todos">Habilitado para Todos</td>
					<tr>
						<td class="opcaoGerenciamento">Criar Desenho</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Comentar Desenho</td>
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
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
					<tr>
						<td class="opcaoGerenciamento">Criar Questionário</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Criar Pergunta</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Editar Questionário</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Editar Pergunta</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Deletar Questionário</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Deletar Pergunta</td>
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
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
					<tr>
						<td class="opcaoGerenciamento">Inserir Vídeos</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Comentar Vídeos</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Deletar Vídeos</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Deletar Comentário</td>
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
					<table border="0">
						<th></th>
						<th class="professor">Habilitado para Professores</th>
						<th class="monitor">Habilitado para Monitores</th>
						<th class="aluno">Habilitado para Alunos</th>
						<th class="todos">Habilitado para Todos</th>
					<tr>
						<td class="opcaoGerenciamento">Criar Aulas</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Editar Aulas</td>
					</tr>
					<tr>
						<td class="opcaoGerenciamento">Importar Aulas</td>
					</tr>
					</table>
				</div></div>
			</div>
			</ul>
			
			<script>criarCheckboxes();
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
