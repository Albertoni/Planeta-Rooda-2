<?php

/*\
 *
 * nova_turma.php
 *
\*/

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../reguaNavegacao.class.php");

$user = usuario_sessao();

/*if($user === false){
	die("Voce nao esta logado em sua conta. Por favor volte e logue.");
}*/

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Planeta ROODA 2.0</title>

	<link type="text/css" rel="stylesheet" href="../../planeta.css" />

	<script type="text/javascript" src="../../js/compatibility.js"></script>
	<script type="text/javascript" src="../../jquery.js"></script>
	<script type="text/javascript" src="../../planeta.js"></script>
	<script type="text/javascript" src="../lightbox.js"></script>

	<!--[if IE 6]>
	<script type="text/javascript" src="planeta_ie6.js"></script>
	<![endif]-->
	<style type="text/css">
	tr{
		background-color: #EEF5F5;
	}
	tr:nth-child(odd){
		background-color: #CCECF4;
	}
	</style>
</head>

<body onload="atualiza('ajusta()');inicia();ajusta_img();">
	<div id="descricao"></div>
	<div id="topo">
		<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Criar turma", "portfolio_inicio.php", false);
				$regua->imprimir();
			?>
			<p id="bt_ajuda"><span class="troca">OCULTAR AJUDANTE</span><span style="display:none" class="troca">CHAMAR AJUDANTE</span></p>
		</div>
	</div> 
	
	<div id="geral">
	
	<!-- **************************
				cabecalho
	***************************** -->
	<div id="cabecalho">
		<div id="ajuda">
			<div id="ajuda_meio">
				<div id="ajudante">
					<div id="personagem"><img src="../../images/desenhos/ajudante.png" height=145 align="left" alt="Ajudante" /></div>
					<div id="rel"><p id="balao">Para inserir uma nova turma, basta inserir o nome da turma, e selecionar os participantes.</p></div>
				</div>
			</div>
			<div id="ajuda_base"></div>
		</div>
	</div><!-- fim do cabecalho -->
	<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
	<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->
	
	<!-- **************************
				conteudo
	***************************** -->
	<div id="conteudo"><!-- tem que estar dentro da div 'conteudo_meio' -->
		
		
		<div id="info_post" class="bloco">
			<div class="bts_cima">
				<a href="portfolio.php?turma=<?=$turma?>" align="left" >
					<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
				</a>
				<input type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/>
			</div>
				<h1>NOVA TURMA</h1>
				<ul class="sem_estilo">
					<li>Turma <span class="exemplo">(Obrigatório)</span></li>
					<li><input name="turma" type="text"></li>
					<li>Descrição <span class="exemplo">(Obrigatório)</span></li>
					<li><input name="descricao" type="text"></li>
				</ul>
			<div id="add_colegas" class="bloco">
				<h1>ESCOLHER ALUNOS</h1>
				<ul class="sem_estilo">
					<div style="margin-bottom:20px">
						Pesquisar por
						<input type="radio" name="tipoPesquisa" value="nome" checked><span style="margin-right:2em;">Nome</span>
						<input type="radio" name="tipoPesquisa" value="email"><span style="margin-right:2em;">Email</span>
						<input type="radio" name="tipoPesquisa" value="login">Login
						<br><br>
						<input id="filtro" type="text" onkeyup="filtrar(this)">
					</div>

					<form name="fConteudo" id="postFormId" action="salvaTurma.php" onsubmit="return gravaConteudo()" method="post">
						<input type="hidden" name="owner_ids" id="owner_ids" value="" />
						<table>
							<colgroup span="3"></colgroup>
							<thead>
								<tr>
									<th>Nome</th>
									<th>Email</th>
									<th>Login</th>
								</tr>
							</thead>
							<tbody id="lista_usuarios">
								<tr class="cor1"><td>Só um instante, carregando os usuários...</td></tr>
							</tbody>
						</table>
					</form>
					<div class="bts_baixo">
						<a href="portfolio.php?turma=<?=$turma?>" align="left" >
							<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
						</a>
						<input type="image" src="../../images/botoes/bt_confirm.png" align="right"/>
					</div>
				</ul>
			</div>
			</div>
		</div><!-- Fecha Div conteudo -->
		</div><!-- Fecha Div conteudo_meio -->
		<div id="conteudo_base">
		</div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
</body>

<script type="text/javascript">
function ajusta_img() { 
	if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
		$('#cont_img3').css('width','436px');
		$('#cont_img3').css('padding-right','20px');
		$('#cont_img').css('height','170px');
	}
}

// setando o formulário
document.getElementById('postFormId').onsubmit = function(){
	var selected = new Array();
	$('#lista_usuarios input:checked').each(function(){
		selected.push($(this).attr('name'));
	});
	document.getElementById('owner_ids').value = selected.join(';');

	gravaConteudo();

	return true;
};

var listaUsuarios = [
<?php

$consulta = new conexao();
$consulta->solicitar("SELECT * FROM $tabela_usuarios"); // Pega a lista de pessoas da turma


for($i=0; $i < ($consulta->registros - 1); $i++){ // for precisa do -1 porque o ultimo não pode ter virgula
	echo "{idUsuario:\"".$consulta->resultado['usuario_id']."\", nome:\"".$consulta->resultado['usuario_nome']."\", email:\"".$consulta->resultado['usuario_email']."\", login:\"".$consulta->resultado['usuario_login']."\"},\n";
	$consulta->proximo();
}
echo "{idUsuario:\"".$consulta->resultado['usuario_id']."\", nome:\"".$consulta->resultado['usuario_nome']."\", email:\"".$consulta->resultado['usuario_email']."\", login:\"".$consulta->resultado['usuario_login']."\"}\n";
?>
];

function filtrar(input){ // TODO: FILTRAR POR NOME DE USUARIO, LOGIN E EMAIL
	var modoFiltragem = document.querySelector('input[name="tipoPesquisa"]:checked').value;
	// Caso não tenha nenhum marcado, a linha acima pode retornar null. Não remova a checagem sem remover a linha acima.
	if (modoFiltragem == null){modoFiltragem = 'nome';};

	var listaFiltrada = listaUsuarios.filter(function(usuario){
		return ((usuario[modoFiltragem].toLowerCase().indexOf(input.value.toLowerCase())) != -1);
	})

	setaListaDeUsuarios(listaFiltrada);
}

function setaListaDeUsuarios(lista){
	var tamanhoLista = lista.length;
	var elementoLista = document.getElementById('lista_usuarios');

	elementoLista.innerHTML = ""; // Precisa limpar ela pra inserir os dados atualizados

	function imprime(estruturaDados){
		function geraTd(textoLink, id){
			var link = document.createElement('a');
			link.href = "edita_usuario-Novo.php?id="+id;
			link.innerHTML = textoLink;

			var td = document.createElement('td');
			td.appendChild(link);

			return td;
		};
		var tr = document.createElement('tr');
		tr.className = 'trTabelaAlunos';


		var tdNome = geraTd(estruturaDados['nome'], estruturaDados['idUsuario']);
		var tdEmail = geraTd(estruturaDados['email'], estruturaDados['idUsuario']);
		var tdLogin = geraTd(estruturaDados['login'], estruturaDados['idUsuario']);
		
		
		tr.appendChild(tdNome);
		tr.appendChild(tdEmail);
		tr.appendChild(tdLogin);
		elementoLista.appendChild(tr);
	};

	for(var i=0; i < tamanhoLista; i++){
		imprime(lista[i]);
	};
}

setaListaDeUsuarios(listaUsuarios);
</script>

</html>
