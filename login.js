//funcoes para deixar o conteudo sempre centralizado, exceto quando ele encosta no topo da página
function entra(){
	var login = document.getElementById("sustenta_login");
	var cadastro = document.getElementById("sustenta_cadastro");
	if(document.body.clientHeight < login.clientHeight){
		login.style.top = 0;
		login.style.marginTop = 0;
		cadastro.style.top = 0;
		cadastro.style.marginTop = 0;
	}
	if (navigator.appName == "Microsoft Internet Explorer"){
		document.getElementById("caixa_cadastro").style.backgroundImage = "url(images/fundos/bloco_cadastro_ie6.png)";
	}
}
function ajusta(){
	var login = document.getElementById("sustenta_login");
	var cadastro = document.getElementById("sustenta_cadastro");
	if(document.body.clientHeight > login.clientHeight){
		login.style.top = "50%";
		login.style.marginTop = "-284px";
		cadastro.style.top = "50%";
		cadastro.style.marginTop = "-284px";
	}
	else{
		login.style.top = 0;
		login.style.marginTop = 0;
		cadastro.style.top = 0;
		cadastro.style.marginTop = 0;
	}
}

//funcoes para abertura da caixa de cadastro
var pos = 0;
var acel = 19;
var aparecer = 0;
var aparecerIE = 0;
var podeVoltar = false;
var intervalo;
var alerta_volta = '0'; //variavel para controlar a volta da tela de cadastro, depois de um alerta
var abreDnovo = 0;

function cadastro(opacidade){ //funcao que é atualizada a cada 20ms pela funcao inicia. Define as animacoes
	if (pos < 210){
		if (pos ++) { //se a posiçao aumenta, a aceleraçao dos objetos diminui e a opacidade da div  do cadastro muda
			acel --;
			if (podeVoltar == false){
				aparecer += 0.05;
				aparecerIE += 1;
			}
			else{
				aparecer -= 0.05;
				aparecerIE -= 1;
			}
		}
		pos += acel; //posicao aumenta a cada mudança da aceleracao
		document.getElementById("sustenta_cadastro").style.opacity = aparecer;
		document.getElementById("sustenta_cadastro").style.filter = 'alpha(opacity=' + opacidade*aparecerIE + ')';
		
	}
	if (podeVoltar == false){ //se as divs estiverem para abrir
		document.getElementById("sustenta_login").style.marginLeft = -177 - pos + "px";
		document.getElementById("sustenta_cadastro").style.marginLeft = -177 + pos/1.3 + "px";
	}
	else{ //se as divs estiverem para voltar
		document.getElementById("sustenta_login").style.marginLeft = -387 + pos + "px";
		document.getElementById("sustenta_cadastro").style.marginLeft = 0 - pos/1.3 + "px";	
	}
	if ((pos >= 210) && (podeVoltar == true)){ //se a div do cadastro tiver voltado, desaparece
		document.getElementById("sustenta_cadastro").style.display = 'none';
		clearInterval(intervalo);
		if (abreDnovo == 1){
			if (aberto == 'senha'){
				document.getElementById('tituloDir').innerHTML = titulo_sen;
				document.getElementById('caixa_cadastro').innerHTML = troca_senha;
			}else{
				document.getElementById('tituloDir').innerHTML = titulo_cad;
				document.getElementById('caixa_cadastro').innerHTML = c_cadastro;
				carregaNivel();
			}
			abreDnovo = 0;
			inicia('cadastro(5)');
		}else{
			aberto='';
		}
	}else 	if ((pos >= 210) && (podeVoltar == false)){
		clearInterval(intervalo);
		abreDnovo = 0;
	}else if (pos > 0){
		document.getElementById("sustenta_cadastro").style.display = 'block';
	}
}

function inicia(str){ //funcao que define eventos que ocorrem ao clicar no botao criar
	if (alerta_volta == '0'){
		clearInterval(intervalo); //limpa a atualizacao do evento ao ativar a funcao
		intervalo = window.setInterval(str, 20); //atualiza a funcao cadastro a cada 20ms para dar efeito de animaçao
		
		if ((pos >= 210) && (podeVoltar == false)){ //ao clicar, se as divs já estiverem abertas, reseta tudo e ativa o 'podeVoltar'
			pos = 0;
			acel = 19;
			podeVoltar = true;
		}
		if ((pos >= 210) && (podeVoltar == true)){ //ao clicar, se elas já tiverem sido fechadas, reseta denovo e desativa o 'podeVoltar'
			pos = 0;
			acel = 19;
			podeVoltar = false;	
		}
	}
}

function alerta(texto){
	window.addEventListener('keydown',removeAlerta,true);
	document.getElementById("txt_alerta").innerHTML = texto;
	$('#mascara').css('display','block');
	$('#alerta').css('display','block');
	document.getElementById("txt_alerta").style.marginTop = document.getElementById("alerta").offsetHeight/2 - document.getElementById("txt_alerta").offsetHeight + "px";
}

function removeAlerta(evt){
	if (evt.keyCode == 13){
		$('#password1').blur();
		$('#mascara').css('display','none');
		$('#alerta').css('display','none');
		window.removeEventListener('keydown',removeAlerta,true);
	}
}

function fechar(){
	$('#mascara').css('display','none');
	$('#alerta').css('display','none');
}

//envio de cadastro
var http = false;
var http_ns = false;
var http_nivel = false;
var http_login = false;

if (navigator.appName == "Microsoft Internet Explorer"){
	http = new ActiveXObject("Microsoft.XMLHTTP");
	http_ns = new ActiveXObject("Microsoft.XMLHTTP");
	http_nivel = new ActiveXObject("Microsoft.XMLHTTP");
	http_login = new ActiveXObject("Microsoft.XMLHTTP");
}else{
	http = new XMLHttpRequest();
	http_ns = new XMLHttpRequest();
	http_nivel = new XMLHttpRequest();
	http_login = new XMLHttpRequest();
}

function criarPerson(){
	var nomeA = document.getElementById('nome_completo').value;
	var loginA = document.getElementById('criar_apelido').value;
	var emailA = document.getElementById('email').value;
	var passwordA = document.getElementById('criar_senha').value;
	var passwordB = document.getElementById('confirmar_senha').value;
	
	if (validar_cadastro(nomeA,loginA,emailA,passwordA,passwordB)){
		enviaCad();
	}
}

function enviaCad(){
	var parametros = "nome_completo=" + encodeURI(document.getElementById('nome_completo').value);
	parametros += "&criar_apelido=" + encodeURI(document.getElementById('criar_apelido').value);
	parametros += "&criar_senha=" + encodeURI(document.getElementById('criar_senha').value);
	parametros += "&confirmar_senha=" + encodeURI(document.getElementById('confirmar_senha').value);
	parametros += "&email=" + encodeURI(document.getElementById('email').value);
	parametros += "&nivel=" + encodeURI(document.getElementById('nivel').value);
	parametros += "&sexo=" + encodeURI(document.getElementById('sexo').value);
	
	http.abort();
	http.open("POST", "cadastro.php", true);
	http.onreadystatechange=function() {
		if ((http.readyState == 4)&& (http.status == 200 )) {
			dados = eval('(' + http.responseText + ')');

			alerta_volta = dados.mensagem.valor;
			alerta(dados.mensagem.texto);
		}
	}
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", parametros.length);
	http.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
	http.send(parametros);
}

function novaSenha(){
	var parametros = "email=" + encodeURI(document.getElementById('email').value);
	
	http_ns.abort();
	http_ns.open("POST", "novasenha.php", true);
	http_ns.onreadystatechange=function() {
		if ((http_ns.readyState == 4)&& (http_ns.status == 200 )) {
			dados = eval('(' + http_ns.responseText + ')');
			alerta(dados.mensagem.texto);
		}
	}
	http_ns.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_ns.setRequestHeader("Content-length", parametros.length);
	http_ns.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
	http_ns.send(parametros);
}


function validar_cadastro(nomeA,loginA,emailA,passwordA,passwordB) {

	var Caracteres_Email = /^[\w-]+(\.[\w-]+)*@(([A-Za-z\d][A-Za-z\d-]{0,61}[A-Za-z\d]\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;
	var Caracteres_Login = /^[A-Za-z\d-_]{0,65}$/;

	errors='';

	if (emailA == null) {
		errors='Email inválido.<br />';
	} else if (emailA == "") {
		errors='Email inválido.<br />';
	} else if (!Caracteres_Email.test(emailA)) {
		errors='Email inválido.<br />';
	}

	if (passwordA == null) {
		errors='Password inválido.<br />';
	} else if (passwordA == "") {
		errors='Password inválido.<br />';
	} else if(passwordA != passwordB) {
		errors='Os passwords nos campos destinados aos mesmos devem ser iguais.<br />';
	}

	if (loginA == null) {
		errors='Apelido inválido.<br />';
	} else if (loginA == "") {
		errors='Apelido inválido.<br />';
	} else if (!Caracteres_Login.test(loginA)) {
		errors='Apelido inválido.<br />';
	}
	
	if (nomeA == null) {
		errors='Nome inválido.';
	} else if (nomeA == "") {
		errors='Nome inválido.';
	}
	
	alerta_volta = 1;
	
	if (errors) alerta(errors);
	return (errors == '');
}

function login(){
	var parametros = "login1=" + encodeURI(document.getElementById('login1').value);
	parametros = parametros + "&password1=" + encodeURI(document.getElementById('password1').value);
	
	http_login.abort();
	http_login.open("POST", "login.php", true);
	http_login.onreadystatechange=function() {
		if ((http_login.readyState == 4)&& (http_login.status == 200 )) {
			//alert(http_login.responseText);
			try{
				dados = eval('(' + http_login.responseText + ')');
				if (dados.login.valor == '1'){
					alerta_volta = '1';
					alerta(dados.login.texto);
				}else{
					document.location = dados.login.texto;
					//window.open(dados.login.texto, "", "fullscreen");
				}
			}
			catch(erro){
				console.log(erro.message); // Foda-se que isso pode dar merda no firefox, só cai aqui se deu merda de qualquer jeito.
			}
		}
	}
	http_login.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_login.setRequestHeader("Content-length", parametros.length);
	http_login.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
	http_login.send(parametros);
}

var titulo_cad = 'CRIAR USUÁRIO';
var titulo_sen = 'NOVA SENHA';

var c_cadastro = '<ul>\
			<li> <label for="nome_completo">NOME COMPLETO</label> </li>\
			<li> <input id="nome_completo" name="nome_completo" type="text" class="campo_texto" required /> </li>\
			<li> <label for="criar_apelido"><p style="width:48%; float:left">APELIDO</p></label>\
			<label for="sexo"><p style="width:48%; float:right">SEXO</p></label></li>\
			<li> <input id="criar_apelido" name="criar_apelido" type="text" class="campo_texto" style="width:48%; float:left" required/>\
			<select id="sexo" name="sexo" class="campo_texto" style="width:48%; float:right">\
			<option value="1">Feminino</option>\
			<option value="2">Masculino</option>\
			</select></li>\
			<li> <label for="criar_senha">SENHA</label> </li>\
			<li> <input id="criar_senha" name="criar_senha" type="password" class="campo_texto" required/> </li>\
			<li> <label for="confirmar_senha">CONFIRMAR SENHA</label> </li>\
			<li> <input id="confirmar_senha" name="confirmar_senha" type="password" class="campo_texto" required/> </li>\
			<li> <label for="email">E-MAIL</label> </li>\
			<li> <input id="email" name="email" type="email" class="campo_texto" required/> </li>\
			<li> <label for="nivel">NÍVEL</label> </li>\
			<li><select class="campo_texto" id="nivel" name="nivel">\
				'+niveis+'\
				</select>\
			</li>\
			<li> <center><div id="botao_confirmar" onmousedown="criarPerson();" >\
			</div></center></li></ul>';

var troca_senha = '<ul>\
			<li> <label for="email">E-MAIL</label> </li>\
			<li> <input id="email" name="email" type="text" class="campo_texto" /> </li>\
			<li> <center><div id="botao_confirmar" onmousedown="novaSenha();" >\
			</div></center></li></ul>';

var aberto = '';

function abaDireita(objeto){
	alerta_volta = '0';
	abreDnovo = ((podeVoltar) || (aberto == objeto))? 0: 1;
	if (objeto == 'senha'){
			if ((aberto == 'senha')||(aberto == '')){
				document.getElementById('tituloDir').innerHTML = titulo_sen;
				document.getElementById('caixa_cadastro').innerHTML = troca_senha;
			}
	}else{
			if ((aberto == 'cadastro')||(aberto == '')){
				document.getElementById('tituloDir').innerHTML = titulo_cad;
				document.getElementById('caixa_cadastro').innerHTML = c_cadastro;
			}
			//carregaNivel();
	}
	aberto = objeto;
	inicia('cadastro(5)');
}

function captureKeys (evt) {
	var tamanho;
	var ctexto = document.getElementById('texto');
	var keyCode = evt.keyCode ? evt.keyCode : (evt.charCode ? evt.charCode : evt.which);

	if (keyCode == 13){
		login();
		return false;
	}
	return true;
}

function balanca(){ // call with setInterval('balanca()', 50)
	document.body.setAttribute("style", 'margin:0; height:100%; -moz-transform: rotate('+ ((Math.random()*21)-10) +'deg)');
	document.body.style.cssText = 'margin:0; height:100%; -moz-transform: rotate('+ ((Math.random()*21)-10) +'deg)';
}
