
var numParagrafos = 0;
var numTitulos = 0;
var numImagens = 0;

function descCurta(textarea){
	document.getElementById("contador").innerHTML = textarea.value.length;
}

function validaForm(f){
	var erros = "";

	if(f['titulo'].value=="")	{erros+="O título não pode ser vazio!\n";}
	if(f['data'].value=="")		{erros+="A data não pode ser vazia!\n";}
	if(f['desc'].value=="")		{erros+="A descrição não pode ser vazia!\n";}
	
	switch(f['tipo'].value){
		case "1":
			if(f['aula'].value == ""){erros+="A aula não pode ser vazia!\n";}
			break;
		case "2":
			if(f['arqui'].value == ""){erros+="Selecione um arquivo!\n";}
			break;
		case "3":
			if(f['link'].value == ""){erros+="O link não pode estar em branco!\n";}
			break;
		default:
			erros+="Eu não sei o que você tentou fazer, mas não vai dar certo, é validado no PHP depois.\n";
	}
	
	if(erros!=""){alert("Existem um ou mais erros, por favor os corrija:\n"+erros);return false;}
	else return true;
}

function mudaInput(isso){
	monta = document.getElementById("bala_de_gambiarra"); //g for gambi
	arqui = document.getElementById("bala_de_arquivo");
	link  = document.getElementById("bala_de_internet");
	switch(isso.value){
		case "1":
			monta.style.display="inline";
			arqui.style.display="none";
			link.style.display ="none";
			break;
		case "2":
			monta.style.display="none";
			arqui.style.display="inline";
			link.style.display ="none";
			break;
		case "3":
			monta.style.display="none";
			arqui.style.display="none";
			link.style.display ="inline";
			break;
	}
}

function trocaPosicoes(novo, turma, anterior){
	if (navigator.appName == "Microsoft Internet Explorer"){
		http = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		http = new XMLHttpRequest(); // vou assumir que isso nunca vai falhar, ok?
	}
	
	http.abort();
	http.open("POST", "_troca.php", true);
	http.onreadystatechange=function() {
		if ((http.readyState == 4) && (http.status == 200))
			if (http.responseText != "314159265") // piiiiiiiiiiiiiiiiii
				alert(http.responseText);
			else
				window.location.reload();
	}
	parametros = "a1="+novo+"&a2="+anterior+"&turma="+turma;
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", parametros.length);
	http.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
	http.send(parametros);
}

function paragrafo(img){ // booleano indicando se uma imagem deve ser anexada, todo parágrafo tem título
	var id = numParagrafos;
	var titulo = new titulo();
	
	this.texto="Clique aqui para inserir seu texto";
	
	numParagrafos++;
	
	this.generate = function(){
		return "<p id=\""+id+"\">"+this.titulo.generate()+ "</p>";;
	}
}

function titulo(){
	var id = numTitulos;
	numTitulos++;
	
	this.conteudo = "Título";
	this.generate = function(){return "<span id=\"t"+numTitulos+"\" style=\"font-size:11px;\">"+conteudo+"</span>";};
}


function submitCustomHTML(){
	objContent.body.innerHTML = document.getElementById('customHTML').value;
	abreFechaLB();
}

function addTit(){
	objContent.execCommand('inserthtml', false, "<h2>Título</h2>");
}

function addPar(){
	objContent.execCommand('inserthtml', false, "<p>Parágrafo</p>");
}

function addParTit(){
	objContent.execCommand('inserthtml', false, "<h2>Título</h2><p>Parágrafo</p>");
}

function mudaFundo(valor){
	switch(valor){
		case "1":
			document.body.style.backgroundImage="url('../../images/fundos/fundo.png')";
			document.body.style.backgroundColor="#aaccca";
			break;
		case "2":
			document.body.style.backgroundImage="url('../../images/fundos/fundo2.png')";
			document.body.style.backgroundColor="#c0d7b6";
			break;
		case "3":
			document.body.style.backgroundImage="url('../../images/fundos/fundo3.png')";
			document.body.style.backgroundColor="#b5d5e6";
			break;
		case "5":
			document.body.style.backgroundImage="url('../../images/fundos/fundo5.png')";
			document.body.style.backgroundColor="#e4d5d7";
			break;
		case "6":
			document.body.style.backgroundImage="url('../../images/fundos/fundo6.png')";
			document.body.style.backgroundColor="#eebb85";
			break;
		case "7":
			document.body.style.backgroundImage="url('../../images/fundos/fundo7.png')";
			document.body.style.backgroundColor="#a9abc8";
			break;
	}
}
