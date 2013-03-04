var guto = 0; //a varivel guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'

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
				case "ident":
					$("#balao").html("Tu clicaste na tela de comentários =D");
					$("#perfil").css("border-top-color",cor_original);
					$("#post").css("border-top-color",cor_original);
					$("#link").css("border-top-color",cor_original);
					$("#arquivos").css("border-top-color",cor_original);
				break;
				case "perfil":
					$("#balao").html("Tu clicaste no Perfil =D");
					$("#ident").css("border-top-color",cor_original);
					$("#post").css("border-top-color",cor_original);
					$("#link").css("border-top-color",cor_original);
					$("#arquivos").css("border-top-color",cor_original);
				break;
				case "post":
					$("#balao").html("Tu clicaste nos Arquivos de postagem =D");
					$("#perfil").css("border-top-color",cor_original);
					$("#ident").css("border-top-color",cor_original);
					$("#link").css("border-top-color",cor_original);
					$("#arquivos").css("border-top-color",cor_original);
				break;
				case "link":
					$("#balao").html("Tu clicaste nos Links =D");
					$("#perfil").css("border-top-color",cor_original);
					$("#post").css("border-top-color",cor_original);
					$("#ident").css("border-top-color",cor_original);
					$("#arquivos").css("border-top-color",cor_original);
				break;
				case "arquivos":
					$("#balao").html("Tu clicaste na Biblioteca do blog =D");
					$("#perfil").css("border-top-color",cor_original);
					$("#post").css("border-top-color",cor_original);
					$("#ident").css("border-top-color",cor_original);
					$("#link").css("border-top-color",cor_original);
				break;
			}
			$(this).css("border-top-color","#00A99D");
			guto = 1;			
	});
	
	$("#toggle_post").click(function(){
		$("#caixa_post").toggle('blind');
		$('#toggle_post').text($('#toggle_post').text() == '▼' ? '►' : '▼');
	});	
	$("#toggle_perfil").click(function(){
		$("#caixa_perfil").toggle('blind');
		$('#toggle_perfil').text($('#toggle_perfil').text() == '▼' ? '►' : '▼');
	});	
	$("#toggle_arq").click(function(){
		$("#caixa_arq").toggle('blind');
		$('#toggle_arq').text($('#toggle_arq').text() == '▼' ? '►' : '▼');
	});	
	$("#toggle_link").click(function(){
		$("#caixa_link").toggle('blind');
		$('#toggle_link').text($('#toggle_link').text() == '▼' ? '►' : '▼');
	});	
	
	$("#dir").sortable({distance: 15, placeholder: 'move_bloco', handle: 'h1'});
	
});

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

function abreMes(){
	if ($('#mes_oculto').css('display') == 'none'){
		$('#mes_oculto').css('display' , 'block');
		$('.post_ano').css('background-image','url(images/botoes/seta_aberto.png)');
	}
	else{
		$('#mes_oculto').css('display','none');
		$('.post_ano').css('background-image','url(images/botoes/seta_fechado.png)');
	}
}
