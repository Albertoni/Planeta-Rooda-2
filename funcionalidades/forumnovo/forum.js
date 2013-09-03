var guto = 0; //a variável guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'
var mensagemRespondida = 0; // usado pra guardar o id da mensagem sendo respondida
var turma = document.getElementById("idTurma").value;

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

var http = false;
var http_pesq = false;

if (navigator.appName == "Microsoft Internet Explorer"){
	http_pesq = new ActiveXObject("Microsoft.XMLhttp");
	http = new ActiveXObject("Microsoft.XMLHTTP");
}else{
	http_pesq = new XMLHttpRequest();
	http = new XMLHttpRequest();
}


function excluir(turma,idTopico){
	if (confirm("Tem certeza que deseja deletar este tópico? Essa ação não pode ser desfeita.")){
		var parametros = "turma=" + turma;
		parametros = parametros + "&idTopico=" + idTopico;
	
		http.abort();
		http.open("POST", "deltopico.php", true);
		http.onreadystatechange=function() {
			if ((http.readyState == 4)&& (http.status == 200 )) {

				document.getElementById("dinamica").innerHTML = http.responseText;
			}
		}
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http.setRequestHeader("Content-length", parametros.length);
		http.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
		http.send(parametros);
	}
}

function editarTopico(turma,idTopico){
	document.location = "forum_cria_topico.php?turma="+turma+"&idTopico="+idTopico;
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

function ordernar(elemento){
	var selecao = elemento.options[elemento.selectedIndex].value;
	if (selecao != ' -- Ordenar... --'){
		location.href = (location.href+'&ordem='+elemento.selectedIndex);
	}
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
}

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
}

var postDinamico = {
	geraPost: function (post, profundidadeArvore){
		var container = document.createElement("div");
		container.className = "cor3";
		var margem = ((profundidadeArvore == undefined) ? 0 : profundidadeArvore * );

		post.data = post.data.split(' ');

		if(post.mensagemRespondida != undefined){
			post.mensagemRespondida.data = post.mensagemRespondida.data.split(' ');

			var textoPost = "<div class=\"limite_resposta\">\
					<cite class=\"citacao\">Em resposta à mensagem de "+post.mensagemRespondida.nomeUsuario+", de "+post.mensagemRespondida.data[0]+" às "+post.mensagemRespondida.data[1]+":</cite>\
					<p class=\"texto_resposta\">"+post.texto+"</p>\
				</div>";
		}else{
			var textoPost = "<div class=\"limite_resposta\">\
					<p class=\"texto_resposta\">"+post.texto+"</p>\
				</div>";
		}

		container.innerHTML = "<ul>\
			<li class=\"tabela\">\
			<div class=\"info\">\
				<p class=\"nome\"><b>"+post.nomeUsuario+"</b></p>\
				<p class=\"data\"><span class=\"data\">"+post.data[0]+"</span> às <span class=\"data\">"+post.data[1]+"</span></p>\
			</div>\
				<div class=\"bts_msg\" align=\"right\">\
					<input type=\"image\" src=\"../../images/botoes/bt_editar.png\" onclick=\"editarMensagem("+turma+","+post.idPost+")\" "+ ((post.podeEditar || (post.idUsuario == userId)) ? "" : "style=\"display:none\"") +"/>\
					<input type=\"image\" src=\"../../images/botoes/bt_excluir.png\" onclick=\"excluirMensagem("+turma+","+post.idPost+",deltipo)\" "+ ((post.podeDeletar || (post.idUsuario == userId)) ? "" : "style=\"display:none\"") +"/>\
				</div>\
			</li>\
			<li>\
				<div class=\"imagem\"><img src=\"img_output.php?id="+post.idUsuario+"\"/></div>\
				"+textoPost
				+"</li>\
			<li>\
				<div align=\"right\">\
					<input type=\"image\" src=\"../../images/botoes/bt_responder_pq.png\" onclick=\"responder("+post.idPost+")\"/>\
				</div>\
			</li>\
			<li id=\"li_resposta_"+post.idPost+"\" style=\"display:none;\">\
				<textarea class=\"msg_dimensao\" rows=\"10\" id=\"msg_txt_"+post.idPost+"\"></textarea>\
				<div class=\"bts_msg\" align=\"right\">\
				<input type=\"image\" src=\"../../images/botoes/bt_enviar_pq.png\" onclick=\"enviaMensagem("+turma+","+post.idPost+")\"/>\
				<input type=\"image\" src=\"../../images/botoes/bt_cancelar_pq.png\" onclick=\"cancelaResposta("+post.idPost+")\"/>\
				</div>\
			</li>\
		</ul>";

		return container;
	},

	imprimePosts: function(array){
		var domArray = array.map(postDinamico.geraPost);

		domArray.forEach(
			function(objeto, indice, array){
				postDinamico.container.appendChild(objeto);
		})
	},

	container: document.getElementById("bloco_mensagens"),

	removeMensagemEspera: function(){
		document.getElementById("mensagem_espera").style.display = "none";
	},

	reordenar: function(select){
		var selecao = select.options[select.selectedIndex].value;
		if (selecao != 'Ordenar mensagens'){
			selecao == 'Por data e hora' ? this.imprimePosts(post) : this.imprimeArvore(post);
		}
	},

	imprimeArvore: function(array){
		function copiaArray(arr){
			return arr.filter(function(arg){return true;})
		}

		function pegaRespostas(arr, id){
			return arr.filter(function(post){return id == post.idPost;});
		}

		function pegaArraySemRespostas(arr, id){
			return arr.filter(function(post){return !(id == post.idPost);});
		}

		var arr = copiaArray(post); // vamos destruir, precisa copiar

		while(arr.length > 0){
			/*var post = arr.shift();

			postDinamico.container.appendChild(postDinamico.geraPost)

			pegaRespostas(arr, post.idPost).forEach*/

			alert('FUDEU');
		}
	}
};