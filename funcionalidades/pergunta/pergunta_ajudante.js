guto=0;

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
				case "criar_topico": // lasciate ogni speranza, voi che hanno che fare manutenzione
					$(".bloco").css("border-top-color",cor_original);
					switch (this.className){
						case "bloco multipla_escolha":
							$("#balao").html("Nesse tipo de exercício você pode criar uma pergunta com a resposta de múltipla escolha. Basta inserir as opções de respostas e selecionar ao lado, qual a resposta está correta.");
							break;
						case "bloco subjetiva":
							$("#balao").html("Nesse tipo de exercício você pode criar uma pergunta, colocando, também, uma breve resposta que seja mostrada quando o gabarito for liberado.");
							break;
						case "bloco pergunta_vf":
							$("#balao").html("Nesse tipo de exercício você pode criar uma pergunta inserindo opções de sentenças verdadeiras ou falsas. Basta preencher o número de opções que deseja e selecionar ao lado se aquela opção é verdadeira ou falsa.");
							break;
						default:
							$("#balao").html("Para criar um questionário, basta inserir o título e a descrição do que você pretende abordar nas questões. Se quiser, é possível inserir uma data limite para que as questões sejam respondidas e o gabarito seja liberado.");
					}
				break;
				case "bloco_mensagens":
					$("#balao").html("Nos questionários, você pode visualizar todos os questionários que foram criados até o momento.");
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
	
	$('#responder_topico').click(function(){
		$('#nova_mensagem').css('display','block');
	});
	$('#cancela_msg').click(function(){
		escondeNMsg();
	});
	$('#envia_msg').click(function(){
		enviaMens();
		escondeNMsg();
	});
});
