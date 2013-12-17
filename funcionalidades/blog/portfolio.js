var guto = 0; //a variável guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'

function checar(){
	document.getElementById('troca_img1').checked='checked';
	document.getElementById('troca_arq1').checked='checked';
}

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
	
	
	$('.tool_bt').mouseover(function(){
		mostraDescri();	
		if (this.id == 'alt_negrito'){
			$('#descricao').html('Negrito');
		}
		if (this.id == 'alt_italico'){
			$('#descricao').html('Itálico');
		}
		if (this.id == 'alt_sublinhado'){
			$('#descricao').html('Sublinhado');
		}
		if (this.id == 'alt_tamanho'){
			$('#descricao').html('Tamanho');
		}
		if (this.id == 'alt_imagem'){
			$('#descricao').html('Inserir imagem');
		}
		if (this.id == 'alt_link'){
			$('#descricao').html('Inserir link');
		}
		if (this.id == 'alt_arquivo'){
			$('#descricao').html('Anexar arquivo');
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
	
	$('.tool_bt').click(function(){
		$('#light_box').css('height','360px');
		$('#light_box').css('width','500px');
		if (this.id == 'alt_imagem'){
			limpaLbox();
			$('#imagem_lbox').css('display','block');
			abreFechaLB(); 
			if (marcado){
				limpaContImg();
				$('#cont_img1').css('display','block');
			}
			marcado = false;
		}
		if (this.id == 'alt_link'){
			limpaLbox()	;
			$('#link_lbox').css('display','block');
			abreFechaLB();
		}
		if (this.id == 'alt_arquivo'){
			limpaLbox();
			$('#arquivo_lbox').css('display','block');
			abreFechaLB();
			if (marcado2){
				limpaContArq();
				$('#cont_arq1').css('display','block');
			}
			marcado2 = false;
		}
	});
	
	$('.select_img').click(function(){
		if (this.id == 'troca_img1'){
			limpaContImg();
			$('#cont_img1').css('display','block');
		}
		if (this.id == 'troca_img2'){
			limpaContImg();
			$('#cont_img2').css('display','block');
		}
		if (this.id == 'troca_img3'){
			limpaContImg();
			$('#cont_img3').css('display','block');
		}
	});
	
	$('.select_arq').click(function(){
		if (this.id == 'troca_arq1'){
			limpaContArq();
			$('#cont_arq1').css('display','block');
		}
		if (this.id == 'troca_arq2'){
			limpaContArq();
			$('#cont_arq2').css('display','block');
		}
	});
});

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

