var guto = 0; //a variável guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'

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
				
	$(".bloco").click(function(){
			
			switch (this.id){	
				case "topicos":
					$("#balao").html("Tu clicaste no T\&#243;\picos =D");
				break;				
				case "procurar_topico":
					$("#balao").html("Tu clicaste no Procurar T\&#243;\pico =D");
					$("#resultado_pesquisa").css("border-top-color",cor_original);
				break;				
				case "resultado_pesquisa":
					$("#balao").html("Tu clicaste no Resultado da Pesquisa =D");
					$("#procurar_topico").css("border-top-color",cor_original);
				break;				
				case "criar_topico":
					$("#balao").html("Tu clicaste no Criar T\&#243;\pico =D");
				break;			
				case "bloco_mensagens":
					$("#balao").html("Tu clicaste no Mensagens =D");
					$("#nova_mensagem").css("border-top-color",cor_original);
				break;				
				case "nova_mensagem":
					$("#balao").html("Tu clicaste no Nova Mensagem =D");
					$("#bloco_mensagens").css("border-top-color",cor_original);
				break;			
			}
			$(this).css("border-top-color","#00A99D");
			guto = 1;			
	});
	
	$('#responder_topico_cima').click(function(){
		$('#nova_mensagem').css('display','block');			  
	});
	$('#responder_topico_baixo').click(function(){
		$('#nova_mensagem').css('display','block');	
		window.location.hash="topo";
	});
	$('#cancela_msg').click(function(){
		$('#nova_mensagem').css('display','none');				  
	});
});
