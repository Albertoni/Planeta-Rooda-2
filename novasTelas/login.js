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
	
	$('input[type=text]').focus(function(){
		$(this).css('background-color','white');	
		$(this).mouseover(function(){
			$(this).css('background-color','white');				
		});
		$(this).mouseout(function(){
			$(this).css('background-color','white');				
		});						
	});
	$('input[type=text]').blur(function(){
		$(this).mouseover(function(){
			$(this).css('background-color','#74d3ed');				
		});
		$(this).mouseout(function(){
			$(this).css('background-color','white');				
		});
	});
	
	$('input[type=password]').focus(function(){
		$(this).css('background-color','white');	
		$(this).mouseover(function(){
			$(this).css('background-color','white');				
		});
		$(this).mouseout(function(){
			$(this).css('background-color','white');				
		});						
	});
	$('input[type=password]').blur(function(){
		$(this).mouseover(function(){
			$(this).css('background-color','#74d3ed');				
		});
		$(this).mouseout(function(){
			$(this).css('background-color','white');				
		});
	});
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
	}		
	else if (pos > 0){
		document.getElementById("sustenta_cadastro").style.display = 'block';
	}
}

function inicia(str){ //funcao que define eventos que ocorrem ao clicar no botao criar
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

function alerta(){
	document.getElementById("txt_alerta").innerHTML = 'Lorem Ipsum';
	$('#mascara').css('display','block');
	$('#alerta').css('display','block');
	document.getElementById("txt_alerta").style.marginTop = document.getElementById("alerta").offsetHeight/2 - document.getElementById("txt_alerta").offsetHeight + "px";
}

function fechar(){
	$('#mascara').css('display','none');
	$('#alerta').css('display','none');		
}
