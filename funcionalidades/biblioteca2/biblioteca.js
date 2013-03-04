var guto = 0; //a variável guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'

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
					$("#balao").html("Tu clicaste no Procurar Material =D");
					$("#enviar_material").css("border-top-color",cor_original);
					$("#arquivos_enviados").css("border-top-color",cor_original);
				break;
				case "enviar_material":
					$("#balao").html("Tu clicaste no Enviar Material =D");
					$("#procurar_material").css("border-top-color",cor_original);
					$("#arquivos_enviados").css("border-top-color",cor_original);
				break;
				case "arquivos_enviados":
					$("#balao").html("Tu clicaste no Arquivos Enviados =D");
					$("#procurar_material").css("border-top-color",cor_original);
					$("#enviar_material").css("border-top-color",cor_original);
				break;
				
			}
			$(this).css("border-top-color","#00A99D");
			guto = 1;
	});
	
	
	$("#tipo_link").val("digite o endere\u00e7\o aqui");
	$("#tipo_link").css("color","#aaaaaa");
	
	$("#tipo_link").blur(function(){
		if(document.getElementById("tipo_link").value == ""){
			$("#tipo_link").val("digite o endere\u00e7\o aqui");
			$("#tipo_link").css({ 'color': '#aaaaaa', 'width': '100%' });
		}
	});
	$("#tipo_link").click(function(){
		if (document.getElementById("tipo_link").value == "digite o endere\u00e7\o aqui"){
			$("#tipo_link").val("");		
			$("#tipo_link").css("color","black");	
		}
	});
	
});

function tipoMaterial(tipo){
	if (tipo == "tipoLink"){
		document.getElementById("tipo_link").style.display = 'block';	
		document.getElementById("tipo_arquivo").style.display = 'none';	
	}
	if (tipo ==  "tipoArquivo"){
		document.getElementById("tipo_link").style.display = 'none';	
		document.getElementById("tipo_arquivo").style.display = 'block';	
	}
}

function procurar(){
	document.getElementById("falso_path").value = document.getElementById("file_real").value;
}
