function mostraLista(qualLista){
	if(listaAtual === qualLista){
		return 0; // a lista sumiria se tu clicar nela duas vezes seguidas sem isso
	}
	
	switch(listaAtual){
		case 1:
			sumindo = listaProfessores;
			break;
		case 2:
			sumindo = listaMonitores;
			break;
		case 3:
			sumindo = listaAlunos;
			break;
	}
	switch(qualLista){
		case 1:
			aparecendo = listaProfessores;
			listaAtual = 1;
			break;
		case 2:
			aparecendo = listaMonitores;
			listaAtual = 2;
			break;
		case 3:
			aparecendo = listaAlunos;
			listaAtual = 3;
			break;
	}
	
	clearInterval(idIntervalo);
	aparecendo.style.display = "block";
	idIntervalo = window.setInterval(transicionaOpacidade, 10);
}

function transicionaOpacidade(){
	aparecendo.style.opacity = 1.0 - opacidade;
	sumindo.style.opacity = opacidade;

	if(opacidade < 0){
		window.clearInterval(idIntervalo);
		opacidade = 1.0;
		sumindo.style.display = "none";
	}else{
		opacidade -= 0.05;
	}
}

function trocaNivel(userId, turma){
	
	abreFechaLB('#light_box_troca');
}

function removeUsuario(userId, turma){
	
}

function mostraCarteira(userId){
	carregaHTML("getCarteira.php", "userId="+userId, handlerCarteira);
	abreFechaLB('#light_box_carteira');
}


var idIntervalo = 0;
var opacidade = 1.0;
var sumindo = null;
var aparecendo = null;
var listaAtual = 1;


document.onLoad= function(){
	var listaProfessores = document.getElementById("listaProfessores");
	var listaMonitores = document.getElementById("listaMonitores");
	var listaAlunos = document.getElementById("listaAlunos");
}

function abreFechaLB(qualLB){
	if ($(qualLB).css('display') == 'none'){
		$(qualLB).css('display','block');
		$('#fundo_lbox').css('display','block');
	}else{
		$(qualLB).css('display','none');
		$('#fundo_lbox').css('display','none');
	}
}

// FUNÇÕES CARTEIRA

function trocaFoto(){
	um.style.opacity = 1.0 - opacidade;
	dois.style.opacity = opacidade;

	if(opacidade < 0){
		window.clearInterval(idIntervaloFoto);
		temp = um;
		um = dois;
		dois = temp;
		opacidade = 1.0;
	}else{
		opacidade -= 0.01;
	}
}

var idIntervaloFoto = null;
var um = null;
var dois  = null;
var opacidade = 1.0;

var handlerCarteira = function(){
	if(this.readyState==4){
		if(this.status==200){
			document.getElementById("light_box_carteira").innerHTML=this.responseText;
			um = document.getElementById("img-cart-1");
			dois = document.getElementById("img-cart-2");
		}else{
			document.getElementById("light_box").innerHTML = 'Erro ao tentar carregar os dados! Favor tentar novamente. Erro:'+a.statusText;
		}
	}
}

// FIM FUNÇÕES CARTEIRA
// TROCA DE NIVEL
function dadosNivel(userId, turma){
	this.userId = userid;
	this.turma = turma;
}
var dados = null; // usado para guardar os dados enquanto o usuário seleciona o nivel pretendido

function preparaTrocaNivel(userId,turma){
	dados = new dadosNivel(userId, Turma);
	abreFechaLB("light_box_troca");
}
function efetuaTrocaNivel(nivel){
	if(dados == null){
		alert('Desculpe, aconteceu um erro ao tentar trocar o nivel do aluno, por favor recarregue a página e tente novamente.');
	}else{
		var nivel = 0;
		switch(nivel){
			case 'profe':
			case 'aluno':
			case 'monit':
			default:
				alert('Favor recarregar a página e tentar novamente, algum erro ocorreu.');
		}
		argumentos = "userId="+dados.userId+"&turma="+dados.turma+"&nivel="+nivel;
		carregaHTML('trocaNivel.php',argumentos, handlerTrocaNivel);
	}
	abreFechaLB('light_box_troca');
}

var handlerTrocaNivel = function(){
	if(this.readyState==4){
		if(this.status==200){
			if(this.responseText === "OK"){
				alert("Troca efetuada com sucesso.");
			}else{
				alert(this.responseText);
			}
		}else{
			alert("Algum erro imprevisto ocorreu. Por favor, tente novamente.")
		}
		dados = null;
	}
}
// FIM TROCA DE NIVEL
// REMOÇÃO USUARIO
function removeUsuario(userId, idTurma){
	var handlerRemocaoUsuario = function(){
		if(this.readyState==4){
			if(this.status==200){
				if(this.responseText == 'OK'){
					document.getElementById("user"+userId).style.display = "none";
					alert("Aluno removido com sucesso.");
				}else{
					alert(this.responseText);
				}
			}else{
				alert("Algum erro imprevisto ocorreu. Por favor, tente novamente.")
			}
		}
	}

	argumentos = "userId="+userId+"&turma="+idTurma;
	carregaHTML('removeUser.php',argumentos, handlerRemocaoUsuario);
}
// FIM REMOÇÃO
