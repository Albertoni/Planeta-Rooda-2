<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");

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
	table{
		width: 100%;
	}

	#containerPesquisa{
		margin-bottom: 20px;
		border: 1px solid gray;
		padding:3px;
	}
	</style>
</head>

<body onload="atualiza('ajusta()');inicia();ajusta_img();">
	<div id="descricao"></div>
	<div id="topo">
		<div id="centraliza_topo">
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
					<div id="rel"><p id="balao">Para criar uma nova turma, basta escrever o nome da turma, dar uma descrição a ela e selecionar os participantes.</p></div>
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
				<input form="postFormId" type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/>
			</div>
				<h1>NOVA TURMA</h1>
				<ul class="sem_estilo">
					<li>Turma <span class="exemplo">(Obrigatório)</span></li>
					<li><input form="postFormId" name="turma" type="text"></li>
					<li>Descrição <span class="exemplo">(Obrigatório)</span></li>
					<li><input form="postFormId" name="descricao" type="text"></li>
				</ul>
			<div id="add_colegas" class="bloco">
				<h1>ESCOLHER ALUNOS</h1>
				<ul class="sem_estilo">
					<div id="containerPesquisa">
						Pesquisar por
						<input type="radio" name="tipoPesquisa" onclick="filtrar()" value="nome" checked><span style="margin-right:2em;">Nome</span>
						<input type="radio" name="tipoPesquisa" onclick="filtrar()" value="email"><span style="margin-right:2em;">Email</span>
						<input type="radio" name="tipoPesquisa" onclick="filtrar()" value="login">Login
						<br><br>
						<input id="filtro" type="text" onkeyup="filtrar()">
					</div>

					<form name="fConteudo" id="postFormId" action="salvaTurma.php" method="post">
						<input type="hidden" name="ids_alunos" id="ids_alunos" value="" />
						<input type="hidden" name="idProfResponsavel" value="<?= $user->getId(); ?>" />
						<table>
							<colgroup span="4"></colgroup>
							<thead>
								<tr>
									<th></th>
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
						<input form="postFormId" type="image" src="../../images/botoes/bt_confirm.png" align="right"/>
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
	var selected = [];
	for (var i = 0; i < listaUsuariosSelecionados.length; i++) {
		if(listaUsuariosSelecionados[i] == true){
			selected.push(i); // o indice é o id
		};
	};
	
	document.getElementById('ids_alunos').value = selected.join(';');

	return true;
};

var listaUsuarios = [
<?php

$consulta = new conexao();
$consulta->solicitar("SELECT * FROM $tabela_usuarios");


for($i=0; $i < ($consulta->registros - 1); $i++){ // for precisa do -1 porque o ultimo não pode ter virgula
	echo "{idUsuario:\"".$consulta->resultado['usuario_id']."\", nome:\"".$consulta->resultado['usuario_nome']."\", email:\"".$consulta->resultado['usuario_email']."\", login:\"".$consulta->resultado['usuario_login']."\"},\n";
	$consulta->proximo();
}
echo "{idUsuario:\"".$consulta->resultado['usuario_id']."\", nome:\"".$consulta->resultado['usuario_nome']."\", email:\"".$consulta->resultado['usuario_email']."\", login:\"".$consulta->resultado['usuario_login']."\"}\n";
?>
];
var listaUsuariosSelecionados = [];

function filtrar(){
	var modoFiltragem = document.querySelector('input[name="tipoPesquisa"]:checked');
	modoFiltragem = (modoFiltragem == null) ? "nome" : modoFiltragem.value;
	// Caso não tenha nenhum marcado retorna null e a linha acima conserta.

	var input = document.getElementById("filtro");

	var listaFiltrada = listaUsuarios.filter(function(usuario){
		/*
			Vamos por partes:
			Primeiro, a função filter loopa por todos os elementos, chamando essa função anonima que declarei agora uma vez sobre cada elemento do array. Se e somente se retornar true, o elemento vai pro array filtrado.

			usuario[modoFiltragem] quer dizer acessar a propriedade do objeto usuario contida em modoFiltragem.
			Exemplo:modoFiltragem = "nome";
					usuario[modoFiltragem] == usuario["nome"] == usuario.nome;

			Passa-se tudo para minúscula para facilitar a vida do usuário.

			E por fim, um indexOf para ver se a string sendo buscada é contida na propriedade.
		*/
		return ((usuario[modoFiltragem].toLowerCase().indexOf(input.value.toLowerCase())) != -1);
	});

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

		var tdCheckbox = document.createElement('td');
			var checkbox = document.createElement('input');
				checkbox.type = "checkbox";
				checkbox.value = estruturaDados['idUsuario'];
				checkbox.addEventListener("click", function(){
					listaUsuariosSelecionados[this.value] = this.checked;
				}, false);
				checkbox.checked = (((listaUsuariosSelecionados[estruturaDados['idUsuario']] == false) ||
									((listaUsuariosSelecionados[estruturaDados['idUsuario']] == undefined))) // undefined para caso nunca tenha sido clicado, e não, undefined não é igual a false
				 					? false : true);
			tdCheckbox.appendChild(checkbox);

		var tdNome = geraTd(estruturaDados['nome'], estruturaDados['idUsuario']);
		var tdEmail = geraTd(estruturaDados['email'], estruturaDados['idUsuario']);
		var tdLogin = geraTd(estruturaDados['login'], estruturaDados['idUsuario']);
		
		
		tr.appendChild(tdCheckbox);
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
