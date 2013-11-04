var guto = 0; //a variável guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'
var mensagemRespondida = 0; // usado pra guardar o id da mensagem sendo respondida

var edicao = '\
	<div class="esq">\
	<div class="imagem"></div>\
		<ul>\
			<li><input type="text" id="ed_titulo" value="<?=$titulo?>" /></li>\
			<li class="mensagens">&nbsp;</li>\
		</ul>\
	</div>\
	<div class="dir">\
		<ul>\
			<li><textarea id="ed_mens" style="height:66px; overflow:hidden; width:100%;font-family:Trebuchet MS,Tahoma,Verdana; font-size:13px;"><?=$mensagem?></textarea></li>\
			<li class="criado_por">&nbsp;</li>\
			<li><div class="enviar" align="right">\
			<input type="image" src="../../images/botoes/bt_editar.png" />\
			<input type="image" src="../../images/botoes/bt_excluir.png" onclick=""/>\
			</div></li>\
		</ul>\
	</div>';

var amostra_topico = '';
var edicao_fid = 0;
var edicao_tid = 0;

$(document).ready(function(){
	var cor_original = $(".bloco").css("border-top-color");
	var fala_original = document.getElementById("balao").innerHTML;
	
	//funções para trocar as falas do ajudante e pintar o bloco selecionado
	$("body").click(function(){ 
			if (guto != 1){
				$(".bloco").css("border-top-color",cor_original);
				$("#balao").html(fala_original);
			}
			guto = 0;
	});

	$("#voltar").click(function(){
		history.go(-1);
	});

	$(".bloco").click(function(){
			switch (this.id){
				/*case "topicos":
					$("#balao").html("Tu clicaste no T\&#243;\picos =D");
				break;*/
				case "procurar_topico":
					$("#balao").html("Aqui você busca por discussões anteriores, é só clicar em “Procurar Tópico” e digitar o que procura (não se esqueça de selecionar se quer procurar por “Título”, “Nome” ou “Conteúdo”), após clique em “Procurar”.");
					$("#resultado_pesquisa").css("border-top-color",cor_original);
				break;
				case "resultado_pesquisa":
					$("#balao").html("Nos resultados da pesquisa, você pode ver tópicos relacionados com as palavras que foram preenchidas na busca.");
					$("#procurar_topico").css("border-top-color",cor_original);
				break;
				case "criar_topico":
					$("#balao").html("Criação de tópicos para discussões com os seus amigos, colegas e professores.");
				break;
				case "bloco_mensagens":
//					$("#balao").html("Tu clicaste no Mensagens =D");
					$("#nova_mensagem").css("border-top-color",cor_original);
				break;
				case "nova_mensagem":
//					$("#balao").html("Tu clicaste no Nova Mensagem =D");
					$("#bloco_mensagens").css("border-top-color",cor_original);
				break;
			}
			$(this).css("border-top-color","#00A99D");
			guto = 1;
	});
	
	$('#responder_topico').click(function(){
		$('.nova_mensagem').css('display','block');
	});
	$('#cancela_msg').click(function(){
		escondeNovaMensagem();
	});
	/*$('#envia_msg').click(function(){
		enviaMensagem();
		escondeNovaMensagem();
	});*/
});

var http = new XMLHttpRequest();


function excluirTopico(idTurma, idTopico){
	if (confirm("Tem certeza que deseja deletar este tópico? Essa ação não pode ser desfeita.")){
		var parametros = "turma=" + idTurma + "&idTopico=" + idTopico;
	
		http.abort();
		http.open("POST", "deltopico.php", true);
		http.onreadystatechange=function() {
			if((http.readyState == 4)&&(http.status == 200 )) {
				if(http.responseText == "ok"){
					document.getElementById("t"+idTopico).style.display = "none";
				}else{
					alert(http.responseText);
				}
			}
		}
		http.setRequestHeader("Content-length", parametros.length);
		http.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
		http.send(parametros);
	}
}

function excluirMensagem(idTurma, idMensagem){
	if (confirm("Tem certeza que deseja deletar essa mensagem? Essa ação não pode ser desfeita.")){
		var parametros = "turma=" + turma + "&idMensagem=" + idMensagem;
	
		http.abort();
		http.open("POST", "delmensagem.php", true);
		http.onreadystatechange=function() {
			if((http.readyState == 4)&&(http.status == 200 )) {
				if(http.responseText == "ok"){
					document.getElementById("m"+idMensagem).style.display = "none";
				}else{
					alert(http.responseText);
				}
			}
		}
		http.setRequestHeader("Content-length", parametros.length);
		http.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
		http.send(parametros);
	}
}

function editarTopico(turma,idTopico){
	document.location = "forum_cria_topico.php?turma="+turma+"&idTopico="+idTopico;
}
function editarMensagem (turma,idMensagem) {
	document.location = "forum_edita_mensagem.php?turma="+turma+"&idMensagem="+idMensagem;
}

function confirmaEditarMensagem(idTurma, idMensagem, idTopico){
	var parametros = "turma=" + turma + "&idMensagem=" + idMensagem + "&idTopico=" + idTopico + "&msg_conteudo="+ document.getElementById("textarea").value;

	http.abort();
	http.open("POST", "forum_salva_mensagem.php", true);
	http.onreadystatechange=function() {
		if((http.readyState == 4)&&(http.status == 200 )) {
			try{
				JSON.parse(http.responseText);
			}
			catch(e){
				if (e instanceof SyntaxError){
					/*alert("Erro desconhecido, por favor tente editar a mensagem novamente.")
					document.location = "forum.php?turma="+turma;*/

					console.log(e);
				}
			}

			document.location = "forum_topico.php?turma="+turma+"&topico="+idTopico;
		}
	}
	http.setRequestHeader("Content-length", parametros.length);
	http.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
	http.send(parametros);
}

function colore(elemento){
	quantidade = document.getElementById(elemento).getElementsByTagName('span').length -1;
	var cor = new Array();
	cor[0] = '#EEF5F5';
	cor[1] = '#ccecf4';
	for (i=0; i < quantidade; i++){
		document.getElementById('topicos').getElementsByTagName('span')[i].getElementsByTagName('div')[0].style.backgroundColor = cor[i%2];
	}
}

function pesquisar(pg, novaPesq){
	var aux_consulta = document.getElementById('consulta').value;
	for (i=0;i<document.radio.tipo.length;i++){
		if (document.radio.tipo[i].checked){
			aux_tipo = document.radio.tipo[i].value;
			break;
		}
	}

	var parametros = "pagina=" + encodeURI(pg);
	
	if (novaPesq){
		parametros = parametros + "&consulta=" + encodeURI(aux_consulta);
		parametros = parametros + "&tipo=" + encodeURI(aux_tipo);
	}
	
	http_pesq.abort();
	http_pesq.open("POST", "pesquisa_forum.php", true);
	http_pesq.onreadystatechange=function() {
		if ((http_pesq.readyState == 4)&& (http_pesq.status == 200 )) {
			document.getElementById("dinamica").innerHTML = http_pesq.responseText;
		}
	}
	http_pesq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_pesq.setRequestHeader("Content-length", parametros.length);
	http_pesq.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
	http_pesq.send(parametros);

}

function escondeNovaMensagem(){
	document.getElementById('msg_txt').value = "";
	document.getElementById('msg_txt').innerHTML = "";
	document.getElementById('nova_mensagem').style.display = "none";
}

function responder(id){
	if (document.getElementById('li_resposta_'+id).style.display == "none"){
		document.getElementById('li_resposta_'+id).style.display = "block";
		mensagemRespondida = id;
	}else{
		document.getElementById('li_resposta_'+id).style.display = "none";
	}
}

function cancelaResposta(id){
		document.getElementById('li_resposta_'+id).style.display = "none";
		document.getElementById('msg_txt_'+id).value = "";
}
// enviarMensagem(formulario)
var enviaMensagem = (function () {
	function sucesso() {
		var resposta;
		var novoPost;
		console.log(this.responseText);
		try {
			resposta = JSON.parse(this.responseText);
		}
		catch (e) {
			console.dir(e);
		}
		console.dir(resposta);
		novoPost = postDinamico.geraPost(resposta);
		document.getElementById("bloco_mensagens").appendChild(novoPost);
		console.dir(novoPost);
		cancelaResposta(resposta.mensagemRespondida.idPost);
	}
	function falha() {
		console.log(this.responseText);
	}
	return function (formulario) {
		AJAXSubmit(formulario, sucesso, falha);
	}
}());
/*
function enviaMensagem(forumId,id){
	var parametros = "idTopico=" + encodeURI(document.getElementById("topico").value);
	parametros += "&mensagemRespondida=" + encodeURI(id);
	parametros += "&turma=" + encodeURI(forumId);
	parametros += "&msg_conteudo=" + encodeURI(document.getElementById('msg_txt_'+id).value);
	http.abort();
	http.open("POST", "forum_salva_mensagem.php", true);
	http.onreadystatechange=function() {
		if ((http.readyState == 4)&& (http.status == 200 )) {
			cancelaResposta(id);

			var novoPost = postDinamico.geraPost(JSON.parse(http.responseText));

			document.getElementById("bloco_mensagens").appendChild(novoPost);
		}
	}
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", parametros.length);
	http.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
	http.send(parametros);
}*/

var postDinamico = {
	geraPost: function (post, profundidadeArvore){
		var container = document.createElement("div");
		container.className = "cor3";
		var margem = ((profundidadeArvore == undefined) ? 0 : profundidadeArvore * 10);

		var data = post.data.split(' ');

		if((post.mensagemRespondida != undefined) && profundidadeArvore == 0){
			try{
				var respondidaData = post.mensagemRespondida.data.split(' ');
			}catch(e){
				var respondidaData = ['Ocorreu um erro', ''];
				console.log(e);
				console.log(post.mensagemRespondida);
			}

			var textoPost = "<div class=\"limite_resposta\">\
					<cite class=\"citacao\">Em resposta à mensagem de "+post.mensagemRespondida.nomeUsuario+", de "+respondidaData[0]+" às "+respondidaData[1]+":</cite>\
					<p class=\"texto_resposta\">"+post.texto+"</p>\
				</div>";
		}else{
			var textoPost = "<div class=\"limite_resposta\">\
					<p class=\"texto_resposta\">"+post.texto+"</p>\
				</div>";
		}
		var anexos = '';
		if (post.anexos.length > 0) {
			anexos = '<ul class="anexos">';
			for (var i = 0; i < post.anexos.length; i+=1) {
				var classes = post.anexos[i].tipo.split('/');
				classes = classes.map(function(e) { return e.split(".").join("-"); });
				classes.push('arquivo');
				anexos += '<li class="'+classes.join(' ')+'"><a href="abre_anexo.php?m='+post.idPost+'&amp;a='+post.anexos[i].id+'">'+post.anexos[i].nome+'<span class="icon">&nbsp</span></a></li>';
			}
			anexos += '</ul>';
		}

		container.innerHTML = "<ul>\
			<li class=\"tabela\">\
			<div class=\"info\">\
				<p class=\"nome\"><b>"+post.nomeUsuario+"</b></p>\
				<p class=\"data\"><span class=\"data\">"+data[0]+"</span> às <span class=\"data\">"+data[1]+"</span></p>\
			</div>\
				<div class=\"bts_msg\" align=\"right\">\
					<input type=\"image\" src=\"../../images/botoes/bt_editar.png\" onclick=\"editarMensagem("+turma+","+post.idPost+")\" "+ ((post.podeEditar && post.podeResponder) ? "" : "style=\"display:none\"") +"/>\
					<input type=\"image\" src=\"../../images/botoes/bt_excluir.png\" onclick=\"excluirMensagem("+turma+","+post.idPost+")\" "+ ((post.podeDeletar) ? "" : "style=\"display:none\"") +"/>\
				</div>\
			</li>\
			<li>\
				<div class=\"imagem\"><img src=\"img_output.php?id="+post.idUsuario+"\"/></div>\
				"+textoPost
				+"</li>\
				<li>\
				"+anexos+"\
				</li>\
				" + (post.podeResponder ? ("\
			<li>\
				<div align=\"right\">\
					<input type=\"image\" src=\"../../images/botoes/bt_responder_pq.png\" onclick=\"responder("+post.idPost+")\"/>\
				</div>\
			</li>\
			<li id=\"li_resposta_"+post.idPost+"\" style=\"display:none;\">\
				<form action=\"forum_salva_mensagem.php\" enctype=\"multipart/form-data\" method=\"post\" onsubmit=\"enviaMensagem(this); event.preventDefault ? event.preventDefault() : event.returnValue = false; return false;\" id=\"form_resposta_"+post.idPost+"\">\
					<input type=\"hidden\" name=\"idTopico\" value=\""+document.getElementById("topico").value+"\">\
					<input type=\"hidden\" name=\"idTurma\" value=\""+turma+"\">\
					<input type=\"hidden\" name=\"mensagemRespondida\" value=\""+post.idPost+"\">\
					<textarea class=\"msg_dimensao\" rows=\"10\" name=\"msg_conteudo\" id=\"msg_txt_"+post.idPost+"\"></textarea>\
					Incluir anexo: <input type=\"file\" name=\"arquivo\">\
					<div class=\"bts_msg\" align=\"right\">\
					<button type=\"submit\" class=\"responder\">Responder</button>\
					<input type=\"image\" src=\"../../images/botoes/bt_cancelar_pq.png\" onclick=\"cancelaResposta("+post.idPost+")\"/>\
					</div>\
				</form>\
			</li>\
			") : "") + "\
		</ul>";

		container.style.marginLeft = margem+'px';
		container.style.width = '';
		container.id = 'm'+post.idPost;

		return container;
	},

	imprimePosts: function(array){
		var domArray = array.map(function(post, indice, array){return postDinamico.geraPost(post, 0);});
		// antes de mexer no codigo acima leia a documentação de map, ela passa 3 parametros e só precisamos do primeiro

		domArray.forEach(
			function(objeto, indice, array){
				postDinamico.container.appendChild(objeto);
		})
	},

	container: document.getElementById("areaMensagens"),

	removeMensagemEspera: function(){
		document.getElementById("mensagem_espera").style.display = "none";
	},

	reordenar: function(select){
		var selecao = select.options[select.selectedIndex].value;
		if (selecao != 'Ordenar mensagens'){
			$('#areaMensagens').empty();
			selecao == 'Por data e hora' ? this.imprimePosts(post) : this.imprimeArvore(post);
		}
	},

	imprimeArvore: function(posts){
		function copiaArray(arr){
			return arr.filter(function(arg){return true;})
		}

		function pegaRespostas(arr, id){
			return arr.filter(function(post){return ((post.mensagemRespondida != undefined) ? (id == post.mensagemRespondida.idPost) : false);});
		}

		function pegaArraySemRespostas(arr, id){
			return arr.filter(function(post){return !((post.mensagemRespondida != undefined) ? (id == post.mensagemRespondida.idPost) : false);});
		}

		function processaPost(post, arrayNovo, profundidade){
			postDinamico.container.appendChild(postDinamico.geraPost(post, profundidade));
			var respostas = pegaRespostas(arrayNovo, post.idPost);
			var semRespostas = pegaArraySemRespostas(arrayNovo, post.idPost);
			console.log(respostas);
			console.log(semRespostas);

			respostas.forEach(function(post, index, array){
				semRespostas = processaPost(post, semRespostas, profundidade+1);
			});

			return semRespostas;
		}

		var arr = copiaArray(posts); // vamos destruir, precisa copiar

		while(arr.length > 0){
			var post = arr.shift();
			arr = processaPost(post, arr, 0);
		}
	}
};

var turma = document.getElementById("idTurma").value;