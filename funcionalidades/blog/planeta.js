function inicia(){
	document.getElementById("balao").style.marginTop = (document.getElementById("ajudante").offsetHeight/2 - document.getElementById("balao").offsetHeight/2 ) - 3 + "px";
	if (navigator.appName == "Microsoft Internet Explorer"){
		document.getElementById("balao").style.behavior = "url(border-radius.htc)";
	}
}

function atualiza(str){ //função que atualiza os ajustes da tela sem interrupções como clearInterval
	window.setInterval(str, 10);
}

function ajusta(){
	document.getElementById("conteudo_meio").style.height = (window.document.getElementById("conteudo").offsetHeight) - 23 + "px";
	/*document.getElementById('light_box').style.marginTop = (pegaScroll()) - document.getElementById('light_box').offsetHeight/2 + 'px';*/
	//document.getElementById('fundo_lbox').style.height = document.getElementById('geral').offsetHeight + 30 + 'px';
}

function pegaScroll(){
	var ScrollTop = document.body.scrollTop;
	
	if (ScrollTop == 0){
		if (window.pageYOffset){
			ScrollTop = window.pageYOffset;
		}else{
			ScrollTop = (document.body.parentElement) ? document.body.parentElement.scrollTop : 0;
		}
	}
	return ScrollTop;
}

function abreFechaLB(){
	if ($('#light_box').css('display') == 'none'){
		$('#light_box').css('display','block');
		document.getElementById('light_box').style.marginTop = -(document.getElementById('light_box').offsetHeight/2) + 'px';
		document.getElementById('light_box').style.marginLeft = -(document.getElementById('light_box').offsetWidth/2) + 'px';
		$('#fundo_lbox').css('display','block');
	}
	else{
		$('#light_box').css('display','none');
		$('#fundo_lbox').css('display','none');
	}
}

$(document).ready(function(){
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
	$("#bt_ajuda").click(function(){
		$("#cabecalho").toggle('blind');
		$("#ajuda").toggle('slide',380);
		$(".troca").toggle();
	});
});
