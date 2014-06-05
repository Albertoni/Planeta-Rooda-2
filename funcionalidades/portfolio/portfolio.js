var guto = 0; //a variável guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'
var x = 0; y = 0;
var txt_font_size = new Array(1,2,4,6,8,12);
var txt_font_size_index = 0;
var modo = 1;
var id = false;

function checar(){
	document.getElementById('troca_img1').checked='checked';
	document.getElementById('troca_arq1').checked='checked';
}

function textoPost(){
	alert("bla");
	//edit.document.designMode="On";
}

function abre_topico(id) {
	($('#topico_oculto'+id).css('display') == 'none') ? $('#topico_oculto'+id).css('display','block') : $('#topico_oculto'+id).css('display','none');
};

function getMousePosition(e){
	try{
	return e.pageX ? {'x':e.pageX, 'y':e.pageY} : {'x':e.clientX + document.documentElement.scrollLeft + document.body.scrollLeft, 'y':e.clientY + document.documentElement.scrollTop + document.body.scrollTop};
	}catch(erro){
	return {'x':0, 'y':0};
	}
}

function showMousePos(e){
	if (!e) e = event;
	var mp = getMousePosition(e);
	x=mp.x;
	y=mp.y;
}
document.onmousemove = showMousePos; // ISSO É MUITO IMPORTANTE MUITISSIMO OBRIGADA

$(document).ready(function(){
	var cor_original = $(".bloco").css("border-top-color");
	var fala_original = document.getElementById("balao").innerHTML;
	
	//funes para trocar as falas do ajudante e pintar o bloco selecionado
	$("body").click(function(){ 
			if (guto != 1){
				$(".bloco").css("border-top-color",cor_original);
				$("#balao").html(fala_original);
			}
			guto = 0;
	});
	
	$(".bloco").click(function(){
			
			switch (this.id){
				case "projeto":
					$("#balao").html("No campo “Projeto” você encontra uma breve descrição do projeto que está sendo visualizado.");
					$("#perfil").css("border-top-color",cor_original);
					$("#postagens").css("border-top-color",cor_original);
					$("#links").css("border-top-color",cor_original);
					$("#posts").css("border-top-color",cor_original);
					$("#arquivos").css("border-top-color",cor_original);
				break;
				case "postagens":
					$("#balao").html("No campo “Postagens” você visualiza o histórico (por data) das postagens que foram realizadas nesse projeto.");
					$("#perfil").css("border-top-color",cor_original);
					$("#projeto").css("border-top-color",cor_original);
					$("#links").css("border-top-color",cor_original);
					$("#arquivos").css("border-top-color",cor_original);
					$("#posts").css("border-top-color",cor_original);
				break;
				case "links":
					$("#balao").html("No campo “links” você pode visualizar links externos relacionados com o projeto que está sendo desenvolvido.");
					$("#perfil").css("border-top-color",cor_original);
					$("#postagens").css("border-top-color",cor_original);
					$("#projeto").css("border-top-color",cor_original);
					$("#arquivos").css("border-top-color",cor_original);
					$("#posts").css("border-top-color",cor_original);
				break;
				case "arquivos":
					$("#balao").html("No campo “Arquivos” você pode visualizar os arquivos que foram inseridos nas postagem realizadas nesse projeto.");
					$("#posts").css("border-top-color",cor_original);
					$("#perfil").css("border-top-color",cor_original);
					$("#postagens").css("border-top-color",cor_original);
					$("#links").css("border-top-color",cor_original);
					$("#projeto").css("border-top-color",cor_original);
				break;
			}
			if (this.id != 'projetos'){
				$(this).css("border-top-color","#00A99D");
			}
			guto = 1;
	});
	
	$("#toggle_projeto").click(function(){
		$("#caixa_projeto").toggle('blind');
		$('#toggle_projeto').text($('#toggle_projeto').text() == '▼' ? '►' : '▼');
	});	
	$("#toggle_posts").click(function(){
		$("#caixa_posts").toggle('blind');
		$('#toggle_posts').text($('#toggle_posts').text() == '▼' ? '►' : '▼');
	});	
	$("#toggle_arq").click(function(){
		$("#caixa_arq").toggle('blind');
		$('#toggle_arq').text($('#toggle_arq').text() == '▼' ? '►' : '▼');
	});	
	$("#toggle_link").click(function(){
		$("#caixa_link").toggle('blind');
		$('#toggle_link').text($('#toggle_link').text() == '▼' ? '►' : '▼');
	});	
	
	$('.abas_port').click(function(){
		if (this.id == "aba_andamento"){
		
			$("#aba_andamento").removeClass('fechado').addClass('aberto');
			$("#aba_encerrado").removeClass('aberto').addClass('fechado');
			$("#proj_encerrados").css('display','none');
			$("#proj_andamento").css('display','block');
		}
		if (this.id == "aba_encerrado"){
			$("#aba_andamento").removeClass('aberto').addClass('fechado');
			$("#aba_encerrado").removeClass('fechado').addClass('aberto');
			$("#proj_encerrados").css('display','block');
			$("#proj_andamento").css('display','none');
		}
	});
	
	
	function mostraDescri(){
		$('#descricao').css('display','block');
		$('#descricao').css('left',x + 10 +'px');
		$('#descricao').css('top',y - 10 +'px');
	}
	$('.tool_bt').mouseover(function(){
		mostraDescri();
		switch(this.id){
			case 'alt_negrito':
				$('#descricao').html('Negrito');
				break;
			case 'alt_italico':
				$('#descricao').html('Itálico');
				break;
			case 'alt_sublinhado':
				$('#descricao').html('Sublinhado');
				break;
			case 'alt_tamanho':
				$('#descricao').html('Tamanho');
				break;
			case 'alt_imagem':
				$('#descricao').html('Inserir imagem');
				break;
			case 'alt_link':
				$('#descricao').html('Inserir link');
				break;
			case 'alt_arquivo':
				$('#descricao').html('Anexar arquivo');
				break;
		}
	});
	
	$('.tool_bt').mouseout(function(){
		$('#descricao').css('display','none');
	});
	
	function limpaLbox(){
		$('#imagem_lbox').css('display','none');
		$('#link_lbox').css('display','none');
		$('#arquivo_lbox').css('display','none');
	}
	function limpaContImg(){
		$('#cont_img1').css('display','none');
		$('#cont_img2').css('display','none');
		$('#cont_img3').css('display','none');
	}
	
	function limpaContArq(){
		$('#cont_arq1').css('display','none');
		$('#cont_arq2').css('display','none');
	}
	
	var marcado = true;
	var marcado2 = true;
});

function abreMes(){
	if ($('#mes_oculto').css('display') == 'none'){
		$('#mes_oculto').css('display' , 'block');
		$('.post_ano').css('background-image','url(../../images/botoes/seta_aberto.png)');
	}
	else{
		$('#mes_oculto').css('display','none');
		$('.post_ano').css('background-image','url(../../images/botoes/seta_fechado.png)');
	}
}

function abreArquivos(){
	if ($('#arquivos_ocultos').css('display') == 'none'){
		$('#arquivos_ocultos').css('display','block');
		$('#abre_arquivos').html('Ocultar');
	}
	else{
		$('#arquivos_ocultos').css('display','none');
		$('#abre_arquivos').html('Ver mais');
	}
}

function abreLinks(){
	if ($('#links_ocultos').css('display') == 'none'){
		$('#links_ocultos').css('display','block');
		$('#abre_links').html('Ocultar');
	}
	else{
		$('#links_ocultos').css('display','none');
		$('#abre_links').html('Ver mais')
	}
}


function validaForm(){
	var erros = new Array();
	if (document.getElementsByName("titulo_projeto")[0].value == ""){erros.push("O projeto precisa ter um título.\n");}
	if (document.getElementsByName("objetivos_projeto")[0].value == ""){erros.push("O projeto precisa ter objetivos.\n");}
	if (document.getElementsByName("autor_projeto")[0].value == ""){erros.push("O projeto precisa ter um autor.");}
	// Ok, erros adicionados caso existam.
	gravaConteudo();
	if (erros.length != 0) {alert(erros); return false;} else return true;
}

/* function deleteBd(id, tabela, coluna, turma) {
	window.frames["deletante"].location = "deleteBd.php?id="+id+"&tabela="+tabela+"&coluna="+coluna+"&turma="+turma;
}*/

function fechaProjeto(id, indiceFeedback){
	var a = newAjax();
	if(a){
		a.open('GET','fechaProjeto.php?id='+id,true);
		a.setRequestHeader('Content-Type','text/html');
		a.send(null);
		a.onreadystatechange = function() {
			if(a.readyState == 4) {
				if(a.status == 200) {
					if (a.responseText != "ok"){
						alert('Erro com a solicitação! ('+a.responseText+')');
					}else{
						document.getElementById("proj_id"+indiceFeedback).style.display = "none";
					}
				}
			}
		}
	}
}

function mataPost(postDiv){
	post = document.getElementById(postDiv);
	pai = document.getElementById("posts");
	if (post != false) // Sei lá, vai que não rolou pegar o elemento, é melhor garantir
		pai.removeChild(post); // MATE O FILHO! MATE SEU PRÓPRIO FILHO! MUAHAHAHAHAHAHAHAHAHAHA!!!
}

function unhideDiv(div){document.getElementById(div).style.display = "block";}
function hideDiv(div){document.getElementById(div).style.display = "none";}
function botaoAdicionar(id){
	var div = document.getElementById(id);
	if (div.style.display == "none")
	{
		div.style.display = "block";
	}
	else
	{
		div.style.display = "none";
	}
};


function newAjax() {
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		aux_ajax = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) { // IE
		try {
			aux_ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				aux_ajax = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}

	if (!aux_ajax) {
		alert('Você está usando um navegador não suportado. Por favor, utilize Chrome ou Firefox.');
		return false;
	}
	return aux_ajax;
}

function carregaHTML(obj_id,script_url,pars) {
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
