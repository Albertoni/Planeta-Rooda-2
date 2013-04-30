var guto = 0; //a variável guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'
arquivoEmEdicao = new edFile("","","","",""); //variavel global que guarda as informações do arquivo que estah sendo editado no momento
var http_file = false;

/**
* As duas variáveis a seguir determinam que botões estavam habilitados antes de uma chamada a editarFile.
*/
var global_btEsqHabilitado;
var global_btDirHabilitado;

/**
* Armazena o evento onclick do botão da esquerda desde editarFile até edicaoCancel.
*/
var global_funcaoOnClickBtEsq;

if(navigator.appName == "Microsoft Internet Explorer") {
	http_file = new ActiveXObject("Microsoft.XMLHTTP");
} else {
	http_file = new XMLHttpRequest();
}

//funcao de teste.
function checkAndSubmit(){
	// alert("UHUUUUUUULL");
	var x;
	x = document.getElementById("texto");
	alert(x.value);
}

//objeto que guarda as informacoes do arquivo que estah sendo editado no momento
function edFile(id, autorNome, tituloNome, nomeFile, tagsNomes){
	this.id = id;
	this.autorNome = autorNome;
	this.tituloNome = tituloNome;
	this.nomeFile = nomeFile;
	this.tagsNomes = tagsNomes;
}

//entra no modo edicao de arquivo
function editarFile(idFile, autorNome , tituloNome, nomeFile, tagsNomes, tipo){
	var autor;
	var titulo;
	var nome;
	var tags;
	var bt_dir;
	var bt_esq;
	
	if (arquivoEmEdicao.id != ""){
		edicaoCancel(autorNome , tituloNome, nomeFile, tagsNomes);
	}
	else{
		arquivoEmEdicao = new edFile(idFile, autorNome, tituloNome, nomeFile, tagsNomes);
	}
	
	arquivoEmEdicao.id = idFile;
	
	
	
	autor=document.getElementById("autor"+idFile);
	autor.innerHTML = "<input type='text' id='autor_edit"+idFile+"' value='"+autorNome +"' />";
	arquivoEmEdicao.autorNome = autorNome;
	
	titulo = document.getElementById("titulo"+idFile);
	titulo.innerHTML = "<input type='text' id='titulo_edit"+idFile+"' value='"+tituloNome +"' />";
	arquivoEmEdicao.tituloNome = tituloNome;
	
	nome = document.getElementById("nome"+idFile);
	nome.innerHTML = "<input type='text' id='nome_edit"+idFile+"' value='"+nomeFile +"' />";
	arquivoEmEdicao.nomeFile = nomeFile;
	
	tags = document.getElementById("tags"+idFile);
	tags.innerHTML = "<input type='text' id='tags_edit"+idFile+"' value='"+tagsNomes +"' />";
	arquivoEmEdicao.tagsNomes = tagsNomes;
	
	bt_esq = document.getElementById("botao_esquerdo"+idFile);
	bt_esq.src = '../../images/botoes/bt_cancelar_pq.png';
	global_funcaoOnClickBtEsq = bt_esq.onclick;
	bt_esq.onclick = function(){edicaoCancel();};
	
	bt_dir = document.getElementById("botao_direito"+idFile);
	bt_dir.src = '../../images/botoes/bt_confirm.png';

	global_btDirHabilitado = !bt_dir.readOnly;
	global_btEsqHabilitado = !bt_esq.readOnly;
	
	if (tipo == 'l')
		bt_dir.onclick = function(){edicaoConfirm('l')};
	else
		bt_dir.onclick = function(){edicaoConfirm('a')};

}

//confirma as mudancas no modo edicao de arquivo, depois sai do modo edicao de arquivos
function edicaoConfirm(tipo){
	
	var autor;
	var titulo;
	var nome;
	var tags;
	autor = document.getElementById("autor_edit"+arquivoEmEdicao.id);
	titulo = document.getElementById("titulo_edit"+arquivoEmEdicao.id);
	nome = document.getElementById("nome_edit"+arquivoEmEdicao.id);
	tags = document.getElementById("tags_edit"+arquivoEmEdicao.id);
	
	
	http_file.abort();
	var r=confirm("Voce tem certeza que deseja modificar esse arquivo?");
	if (r==true){
		http_file.open("GET", "editarFile.php?idFile=" + arquivoEmEdicao.id+"&titulo="+titulo.value+"&autor="+autor.value+"&nome="+nome.value+"&tags="+tags.value+"&t="+tipo, true);
		http_file.onreadystatechange=function() {
			if ((http_file.readyState == 4) && (http_file.status == 200 )) {
				var retorno = http_file.responseText;
				if (retorno == '1'){
					alert('Arquivo editado com sucesso');
					//deu certo
				}
				else alert(retorno);
				
			}
		}	
		http_file.send(null);
		
		arquivoEmEdicao.autorNome = autor.value;
		arquivoEmEdicao.tituloNome = titulo.value;
		arquivoEmEdicao.nomeFile = nome.value;
		arquivoEmEdicao.tagsNomes = tags.value;
		edicaoCancel();
		
	}
}

//cancela o modo edicao de arquivo.
function edicaoCancel(){
	autor=document.getElementById("autor"+arquivoEmEdicao.id);
	autor.innerHTML = arquivoEmEdicao.autorNome;
	
	titulo = document.getElementById("titulo"+arquivoEmEdicao.id);
	titulo.innerHTML = arquivoEmEdicao.tituloNome;
	
	nome = document.getElementById("nome"+arquivoEmEdicao.id);
	nome.innerHTML = arquivoEmEdicao.nomeFile;
	
	tags = document.getElementById("tags"+arquivoEmEdicao.id);
	tags.innerHTML = arquivoEmEdicao.tagsNomes;
	
	bt_dir = document.getElementById("botao_direito"+arquivoEmEdicao.id);
	if(global_btDirHabilitado){
		bt_dir.src = '../../images/botoes/bt_editar.png';
		bt_dir.onclick = function(){editarFile(arquivoEmEdicao.id,arquivoEmEdicao.autorNome,arquivoEmEdicao.tituloNome, arquivoEmEdicao.nomeFile, arquivoEmEdicao.tagsNomes);};
		bt_dir.readOnly = false;
	} else {
		bt_dir.src = '';
		bt_dir.readOnly = true;
		bt_dir.onclick = function(){};
	}
	
	bt_esq = document.getElementById("botao_esquerdo"+arquivoEmEdicao.id);
	if(global_btEsqHabilitado){
		bt_esq.src = '../../images/botoes/bt_excluir.png';
		bt_esq.onclick = global_funcaoOnClickBtEsq;
		bt_esq.readOnly = false;
	} else {
		bt_esq.src = '';
		bt_esq.readOnly = true;
		bt_esq.onclick = function(){};
	}

}


function excluirFile(idFile, tipo){
	http_file.abort();
	var r=confirm("Voce tem certeza que deseja excluir esse arquivo?");
	if (r==true){
		http_file.open("GET", "excluirFile.php?idFile="+idFile+"&t="+tipo, true);
		http_file.onreadystatechange=function() {
			if ((http_file.readyState == 4) && (http_file.status == 200 )) {
				var retorno = http_file.responseText;
				if (retorno == '1'){
					alert('Arquivo excluido com sucesso');
					document.getElementById('arquivos_enviados').removeChild(document.getElementById('file'+idFile));
				}
				else alert(retorno);
			}
		}
		http_file.send(null);
	}
}



function aprovarMaterial(idFile){
	http_file.abort();
	var r=confirm("Voce tem certeza que deseja aprovar esse arquivo?");
	if (r==true){
		http_file.open("GET", "aprovarMaterial.php?idFile="+idFile, true);
		http_file.onreadystatechange=function() {
			if ((http_file.readyState == 4) && (http_file.status == 200 )) {
				var retorno = http_file.responseText;
				if (retorno == '1'){
					alert('Arquivo aprovado com sucesso.');
					ulAprovado = document.getElementById("file"+idFile);
					ulAprovado.className = "";
					botao = document.getElementById("botao_aprovar"+idFile);
					botao.style.display = "none";
				}
				else alert(retorno);
			}
		}
		http_file.send(null);
	}
}



$(document).ready(function(){
	document.getElementById("arquivos_enviados").style.minHeight = document.getElementById("esq").offsetHeight - 25 + "px";
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
	$(".bloco").click(function(){
			
			switch (this.id){
				case "procurar_material":
					$("#balao").html("Aqui você busca por materiais já publicados na biblioteca, é só clicar em “Procurar Material” e digitar o que procura (não se esqueça de selecionar se quer procurar por “Título”, “Autor” ou “Palavras do Material”), após clique em “Procurar”.");
					$("#enviar_material").css("border-top-color",cor_original);
					$("#arquivos_enviados").css("border-top-color",cor_original);
				break;
				case "enviar_material":
					$("#balao").html("Aqui você pode enviar materiais (links ou arquivos, deve-se selecionar a opção) para a biblioteca. Digite o título do material, o autor e as palavras deste material. Depois clique em “Enviar”.");
					$("#procurar_material").css("border-top-color",cor_original);
					$("#arquivos_enviados").css("border-top-color",cor_original);
				break;
				case "arquivos_enviados":
					$("#balao").html("Aqui você visualiza os arquivos enviados por outra pessoa ou por você mesmo.");
					$("#procurar_material").css("border-top-color",cor_original);
					$("#enviar_material").css("border-top-color",cor_original);
				break;
				
			}
			$(this).css("border-top-color","#00A99D");
			guto = 1;
	});
	
	$("#tipo_link").css("display", "none");
	$("#tipo_arquivo").css("display", "none");
	
	$("#tipo_link").val("digite o endere\u00e7\o aqui");
	$("#tipo_link").css("color","#aaaaaa");
	
	$("#tipo_link").blur(function(){
		if(document.getElementById("tipo_link").value == ""){
			$("#tipo_link").val("digite o endere\u00e7\o aqui");
			$("#tipo_link").css({ 'color': '#aaaaaa'});
		}
	});
	$("#tipo_link").click(function(){
		if (document.getElementById("tipo_link").value == "digite o endere\u00e7\o aqui"){
			$("#tipo_link").val("");
			$("#tipo_link").css({"color": "black"});
		}
	});
	
});



function tipoMaterial(tipo){
	$("#tipo_mensagem").css("display", "none");
	if (tipo == "tipoLink"){
		document.getElementById("tipo_link").style.display = 'block';
		document.getElementById("tipo_arquivo").style.display = 'none';
	}else{
		document.getElementById("tipo_link").style.display = 'none';
		document.getElementById("tipo_arquivo").style.display = 'block';
	}
}


function procurar(){
	document.getElementById("falso_path").value = document.getElementById("file_real").value;
}

function newAjax() {
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		aux_ajax = new XMLHttpRequest();
		if (aux_ajax.overrideMimeType) {
			aux_ajax.overrideMimeType('text/xml');
		}
	} else if (window.ActiveXObject) { // IE
		try {
			aux_ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				aux_ajax = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!aux_ajax) {
		alert('Desisto :( Não consegui criar uma instância XMLHTTP');
		return false;
	}
	return aux_ajax;
}

function loadComentarios(obj_id,script_url,pars) {
	var a = newAjax();
	var obj = document.getElementById(obj_id);
	if(a) {
		a.open('POST',script_url,true);
		a.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		a.send(pars);
		a.onreadystatechange = function() {
			if(a.readyState == 4) {
				if(a.status == 200) {
					obj.innerHTML = a.responseText;
				} else {
					alert('Erro com a solicitação! (AJAX)');
				}
			}
		}
	}
}
