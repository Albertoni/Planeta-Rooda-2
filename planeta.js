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
	console.log((window.document.getElementById("conteudo").offsetHeight) - 23 + "px");
	document.getElementById("conteudo_meio").style.height = (window.document.getElementById("conteudo").offsetHeight) - 23 + "px";
		//document.getElementById('comentarios').style.marginTop = (pegaScroll()) - document.getElementById('comentarios').offsetHeight/2 + 'px';
		//document.getElementById('light_box').style.marginTop = (pegaScroll()) + 'px';
}

function pegaScroll(){
	var ScrollTop = document.body.scrollTop;
	
	if (ScrollTop == 0){
		if (window.pageYOffset){
			ScrollTop = window.pageYOffset;
		}
		else{
			ScrollTop = (document.body.parentElement) ? document.body.parentElement.scrollTop : 0;
		}
	}
	return ScrollTop;
}

function abreComents(){
	if ($('#comentarios').css('display') == 'none'){
		$('#comentarios').css('display','block');
		document.getElementById('comentarios').style.marginTop = -(document.getElementById('comentarios').offsetHeight/2) + 'px';
		document.getElementById('comentarios').style.marginLeft = -(document.getElementById('comentarios').offsetWidth/2) + 'px';
		$('#light_box').css('display','block');
	}
	else{
		$('#comentarios').css('display','none');
		$('#light_box').css('display','none');
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
	$('textarea').focus(function(){
		if ((this).id != "texto_b"){
			$(this).css('background-color','white');
			$(this).mouseover(function(){
				$(this).css('background-color','white');
			});
			$(this).mouseout(function(){
				$(this).css('background-color','white');
			});
		}
	});
	$('textarea').blur(function(){
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

function abreFechaLB(){ // Sometimes I wonder if this couldn't be done better
	if ($('#light_box').css('display') == 'none'){
		$('#light_box').css('display','block');
		document.getElementById('light_box').style.marginTop = -(document.getElementById('light_box').offsetHeight/2) + 'px';
		document.getElementById('light_box').style.marginLeft = -(document.getElementById('light_box').offsetWidth/2) + 'px';
		document.getElementById('light_box').style.marginTop = 0;
		document.getElementById('light_box').style.marginLeft = 0;
		document.getElementById('light_box').style.opacity = 1;
		$('#light_box').css('left','30%');
		$('#light_box').css('top','10%');
		$('#light_box').css('height','80%');
		$('#light_box').css('width','40%');
		$('#light_box').css('z-index','501');
		$('#light_box').css('position','fixed');
		
		document.getElementById('fundo_lbox').style.height = "100%";
		document.getElementById('fundo_lbox').style.width = "100%";
		document.getElementById('fundo_lbox').style.opacity = 0.7;
		
		$('#fundo_lbox').css('display','block');
		$('#fundo_lbox').css('position','fixed');
		$('#fundo_lbox').css('z-index','500');
		$('#fundo_lbox').css('background-color','#FFF');
		$('#fundo_lbox').css('left','0%');
		$('#fundo_lbox').css('top','0%');
	}else{
		$('#light_box').css('display','none');
		$('#fundo_lbox').css('display','none');
	}
}
