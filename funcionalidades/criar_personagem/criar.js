$('#criar_personagem').ready(function(){
	var olhoSelec;
	var cabeloSelec;
	var botao = 0;
	var clicado;
	var posicao;
	var corCabeloSelecionada = "castanho";
	var vetorBotaluva=["bota_luva",118,138,{type:"path",path:"M0 8.631c3.518 1.859 6.837 3.01 10.356 5.608c-2.57 0.545-3.757-2.555-5.61-1.294c0.125 6.924-2.265 20.766 5.179 24.596c-1.723-12.873 4.379-19.89 3.452-34.089C10.109 1.83 4.72 2.329 2.157 0C1.966 2.627 0.735 5.417 0 8.631z","stroke-width":"0.172",stroke:"#000",fill:"#555"},
{type:"path",path:"M115.214 11.219c-1.868 0.589-4.568 1.147-6.042 1.295c1.688-3.06 6.483-3.01 8.199-6.041c-0.648-2.084-1.732-3.733-2.157-6.04c-3.143 1.314-7.27 1.648-10.356 3.02c0.987 12.596 10.639 27.488 2.158 37.974c-0.002 0.432 0.35 0.513 0.431 0.861C119.29 39.304 118.146 23.669 115.214 11.219z","stroke-width":"0.172",stroke:"#000",fill:"#555"},
{type:"path",path:"M20.525 70.033c0 0 4.271 8.767 20.678 8.316c16.408-0.449 18.431-3.596 20.005-5.394l4.72-0.45c0 0 7.642 6.743 16.857 5.619C92 77.001 94.697 78.35 98.519 72.956c0 0 4.271 0.674 4.495 4.495c0.225 3.82-0.225 9.89-5.844 13.485c0 0 0.449 11.238 11.014 16.408c10.563 5.169 6.293 23.825 2.247 26.747s-20.229 9.665-44.503-7.867c0 0 0.674-32.591 0-33.939c-0.675-1.349-3.372-6.068-3.372-6.068s-3.371 23.601-0.898 33.04c2.473 9.44-10.564 17.532-21.803 17.981s-28.545-1.573-29.219-10.114s5.619-18.431 13.485-23.151c0 0 3.597-8.99 0-13.935c0 0-6.068-3.372-6.293-9.44S20.525 70.033 20.525 70.033z","stroke-width":"0.172",stroke:"#000",fill:"#555"}];

	var vetorAcessorios=["acessorios",118,138,{type:"path",path:"M2.708 58.972c0 0-4.046 11.091-2.248 20.68c0 0 1.049 7.193 41.207 7.043C81.826 86.545 95.91 81.3 95.91 81.3s6.145-4.796 2.695-17.383c0 0-1.195-4.944-2.395-5.692c-1.199-0.749-29.969 3.297-34.615 3.896c-4.645 0.602-34.462 1.199-44.502-1.349C7.053 58.225 2.708 58.972 2.708 58.972z","stroke-width":"0.172",stroke:"#000",fill:"#555"},
						{type:"path",path:"M76.484 5.177c-1.121 0.472-10.111 1.601-14.24 0.434c0-1.87 0-3.74 0-5.61c5.063 0.88 9.617 0.669 14.672 0C77.102 2.668 77.145 4.789 76.484 5.177z","stroke-width":"0.172",stroke:"#000",fill:"#555"},
						{type:"path",path:"M64.4 14.672c0.293 1.575 0.604 3.137 0.434 5.178c6.508-1.234 8.182 0.45 13.809-0.862c-0.693-1.979 0.119-2.78 0-5.179L64.4 14.672z","stroke-width":"0.172",stroke:"#000",fill:"#555"},];
	
	var vetorPele = ["pele",133,110,{type:"path",path:"M127.72 50.745c5.133 2.697 5.799 11.75 3.021 17.694c-1.332 0.818-2.258 2.032-3.057 3.371l-1.285-0.107c0.402-0.692 0.52-1.672 0.457-2.832c-3.361-2.338-5.115-7.625-1.727-11.221c2.455-0.836 5.232-0.279 5.18 2.59c1.275-1.574-0.271-4.019-1.295-4.746c-2.85 0.025-4.379 1.375-6.465 2.156v-6.042C124.035 51.087 125.41 50.439 127.72 50.745","stroke-width":"0.172",stroke:"#000",fill:"#CCB27F"},
						{type:"path",path:"M126.4 71.703l1.285 0.107c-2.061 3.426-3.346 7.616-8.588 7.85c-0.934-4.514 2.607-7.93 2.59-12.946c1.771 0.241 2.518 1.501 3.875 2.157c-0.145 1.438-1.25 1.914-0.863 3.884C125.509 72.646 126.056 72.279 126.4 71.703","stroke-width":"0.172",stroke:"#000",fill:"#CCB27F"},
						{type:"path",path:"M110.035 17.526c6.779 8.855 12.084 25.91 10.789 44.44c-1.133 16.047-11.777 33.254-22.008 39.701c-10.33 6.518-31.135 9.242-46.176 7.338c-16.238-2.059-28.977-10.457-35.98-22.117c-1.295-2.168-2.393-4.434-3.281-6.797C8.55 67.253 8.136 46.925 12.865 33.574c0.162-0.468 0.334-0.926 0.514-1.376c1.141-2.895 3.389-5.565 4.736-8.2c1.467-2.849 2.545-6.329 4.316-8.63C29.373 6.395 43.47-0.456 59.544 0.263c0.279 0.01 0.584 0.018 0.863 0c8.838-0.566 21.057-0.386 30.127 2.239c0.01 0 0.018 0 0.027 0.009c2.455 0.863 4.918 1.933 7.344 3.255c0.01 0 0.02 0.009 0.02 0.009C102.464 8.724 106.791 13.282 110.035 17.526 M62.998 80.091c-0.588 2.293 4.756 3.155 4.746 0.862C65.73 81.097 64.335 80.621 62.998 80.091 M82.847 87.427c-10.6 3.119-26.152 2.662-35.818-0.862C54.302 92.677 75.269 92.867 82.847 87.427","stroke-width":"0.172",stroke:"#000",fill:"#CCB27F"},
						{type:"path",path:"M8.63 68.439c1.664 2.787 2.283 6.635 3.021 10.357c-1.941-0.359-3.498-1.106-4.766-2.141C3.074 73.564 1.789 67.944 0 62.83c1.313-4.783 1.15-12.66 8.631-9.927v3.884c-5.457-0.809-6.266 5.188-5.178 9.494c1.896-1.402-0.055-6.662 2.156-7.768c7.4 1.996-0.701 9.027 3.021 12.947C9.691 71.046 8.027 69.806 8.63 68.439","stroke-width":"0.172",stroke:"#000",fill:"#CCB27F"},];
	
	var corBotaluva = Raphael(vetorBotaluva);
	var corAcessorios = Raphael(vetorAcessorios);
	var corPele = Raphael(vetorPele);

	$('.amostras').css({'display':'none'});
	
	function botoesNormais(){
		$('#cabelo_bt').css({'background-position':'0 0'});
		$('#olhos_bt').css({'background-position':'0 -60px'});
		$('#pele_bt').css({'background-position':'0 -120px'});
		$('#acessorios_bt').css({'background-position':'0 -180px'});
		$('#botaeluva_bt').css({'background-position':'0 -240px'});
	}
	
	function pegaIdImagem(src) // Para pegar a id numérica dos cabelos e olhos
	{
		var pattern = new RegExp('\\d+', 'gi');
		var matchBackup;
		var matches;
		while((matches = pattern.exec(src)) != null){
			matchBackup = matches;
		}

		if (matchBackup){
			return matchBackup[0].trim();
		}
	}

	$('.troca_cabelo').click(function(){
		corCabeloSelecionada = (this.id).substring(3);
		document.getElementById('castanho').style.display = "none";
		document.getElementById('preto').style.display = "none";
		document.getElementById('loiro').style.display = "none";
		document.getElementById('ruivo').style.display = "none";
		document.getElementById(corCabeloSelecionada).style.display = "block";
		
		document.getElementById('corCabeloSelecionada_js').value = corCabeloSelecionada;
	});
	
	$('.botoes_custom_unidade').click(function(){
		$('.botoes_custom_unidade').css('margin-left',0);
		botoesNormais();
		
		switch(this.id){
			case 'cabelo_bt':
				$(this).css('background-position','-153px 0');
				posicao = "'-153px 0'";
				$(this).css('margin-left','15px');
				botao = 1;
				$('.amostras').animate({'opacity':'0'},100,function(){
					$('.amostras').css({'display':'none'});
					$('#cabelos').css({'display':'block'});
					$('#cabelos').animate({'opacity':'1'},100);
				});
				document.getElementById('castanho').style.display = "none";
				document.getElementById('preto').style.display = "none";
				document.getElementById('loiro').style.display = "none";
				document.getElementById('ruivo').style.display = "none";
				document.getElementById(corCabeloSelecionada).style.display = "block";
			break;
			case 'olhos_bt':
				$(this).css('background-position','-153px -60px');
				$(this).css('margin-left','15px');
				botao = 2;
				$('.amostras').animate({'opacity':'0'},100,function(){
					$('.amostras').css({'display':'none'});
					$('#olhos').css({'display':'block'});
					$('#olhos').animate({'opacity':'1'},100);
				});
			break;
			case 'pele_bt':
				$(this).css('background-position','-153px -120px');
				$(this).css('margin-left','15px');
				botao = 3;
				$('.amostras').animate({'opacity':'0'},100,function(){
					$('.amostras').css({'display':'none'});
					$('#cores_pele').css({'display':'block'});
					$('#cores_pele').animate({'opacity':'1'},100);
				});
			break;
			case 'acessorios_bt':
				$(this).css('background-position','-153px -180px');
				$(this).css('margin-left','15px');
				botao = 4;
				if($('#cores_roupas').css('display') != 'block'){
					$('.amostras').animate({'opacity':'0'},100,function(){
						$('.amostras').css({'display':'none'});
						$('#cores_roupas').css({'display':'block'});
						$('#cores_roupas').animate({'opacity':'1'},100);
					});
				}
			break;
			case 'botaeluva_bt':
				$(this).css('background-position','-153px -240px');
				$(this).css('margin-left','15px');
				botao = 5;
				if($('#cores_roupas').css('display') != 'block'){
					$('.amostras').animate({'opacity':'0'},100,function(){
						$('.amostras').css({'display':'none'});
						$('#cores_roupas').css({'display':'block'});
						$('#cores_roupas').animate({'opacity':'1'},100);
					});
				}
			break;
		}
	});
	/*
	$('.botoes_custom_unidade').mouseover(function(){
		if (this.offsetLeft == 250){
			if ($(this).css('background-position').length > 7) {
				$(this).css({'background-position': '-76px' + ' ' + $(this).css('background-position').substr(-6)});	
			}
			else{
				$(this).css({'background-position': '-76px' + ' ' + $(this).css('background-position').substr(-3)});
			}
		}
	});
	*/
	$('.amostra').click(function(){
		if(botao == 1){
			var proprio = this.parentNode.innerHTML;
			var ulFilhos = this.parentNode.parentNode.childNodes;
			var i_proprio = 0;
			for (i=0; i< ulFilhos.length; i++){
				if (ulFilhos[i].innerHTML == proprio)
					i_proprio = i;
			}
			i_proprio = (i_proprio - 1) /2 + 1;
			cabeloSelec = i_proprio; // a variável cabeloSelec guarda o número do frame que vai ser mandado para o movieclip dos cabelos do flash
			//document.getElementById('cabelo').value = cabeloSelec;
			$('#troca_cabelo').css({'background-image':'url('+ this.firstChild.src + ')'});
			$('#cabelo_js').val(pegaIdImagem(this.firstChild.src));
		}
		if(botao == 2){
			$('#troca_olho').css({'background-image':'url('+ this.firstChild.src + ')'}); //images/desenhos/olhos/olho8.png
			$('#olhos_js').val(pegaIdImagem(this.firstChild.src));
		}
		if(botao == 3){
			var corClicada = $(this).css('background-color');
			corPele.attr({fill: corClicada});
			$('#cor_pele_js').val(pegaIdImagem(this.parentNode.firstChild.id));
		}
		if(botao == 4){
			var corClicada = $(this).css('background-color');
			corAcessorios.attr({fill: corClicada});
			$('#cor_cinto_js').val(pegaIdImagem(this.parentNode.firstChild.id));
		}
		if(botao == 5){
			var corClicada = $(this).css('background-color');
			corBotaluva.attr({fill: corClicada});
			$('#cor_luvas_js').val(pegaIdImagem(this.parentNode.firstChild.id));
		}
	});
	
	$('#mudacor').click(function(){
		var substringue = $('.amostra_cabelo').children(0).attr('src');
		//alert(substringue);
		$('.amostra_cabelo').children(0).attr('src') = substringue.substring(0,substringue.length-4)+'_loiro.png';
	});

});

