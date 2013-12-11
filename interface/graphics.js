;(function () {
	'use strict';
	var atualiza = function () {}, // função que desenha personagem (definida apos todas as imagems terem sido carregadas)
		dom = {}, form = {}, context,
		menuBotoes = [ 'cabelo', 'olhos', 'pele', 'cinto', 'luvas' ],
		cabeloCores = [ 'castanho', 'preto', 'loiro', 'ruivo' ],
		imgs = { 'cabelos' : {}, 'olhos' : [] },
		getImageContext = function (img) {
			 var canvas = document.createElement("canvas");
			 var context = canvas.getContext('2d');
			 canvas.width = img.width;
			 canvas.height = img.height;
			 context.drawImage(img,0,0);
			 return context;//.getImageData(0, 0, canvas.width, canvas.height);
		},
		colorAllPixels = function(context,hex) {
			var r = parseInt(hex.slice(0,2),16),
				g = parseInt(hex.slice(2,4),16),
				b = parseInt(hex.slice(4,6),16),
				imgData = context.getImageData(0, 0, context.canvas.width, context.canvas.height),
				i, j, n = imgData.data.length;
			for (i = 0; i < n; i+=4) {
				imgData.data[i + 0] = r;
				imgData.data[i + 1] = g;
				imgData.data[i + 2] = b;
			}
			context.putImageData(imgData, 0, 0);
		},
		drawContext = function (context, imgData, x, y){
			context.drawImage(imgData.canvas, x, y);
		};
	// DOM INTERFACE
	dom.canvas = document.getElementById('canvas_personagem');
	context = dom.canvas.getContext('2d');
	dom.menu = document.getElementById('menu_criar_personagem');
	dom.cabelo = document.getElementById('opcoes_cabelo');
	dom.cabeloEstilo = document.getElementById('cabelo_estilo');
	dom.cabeloCor = document.getElementById('cabelo_cor');
	dom.olhos = document.getElementById('opcoes_olhos');
	dom.pele = document.getElementById('opcoes_pele');
	dom.cinto = document.getElementById('opcoes_cinto');
	dom.luvas = document.getElementById('opcoes_luvas');
	// DATA FORM
	form.cabeloEstilo = document.getElementById('cabelo_js');
	form.cabeloCor = document.getElementById('cor_cabelo_js');
	form.olhos = document.getElementById('olhos_js');
	form.cor_pele = document.getElementById('cor_pele_js');
	form.cor_cinto = document.getElementById('cor_cinto_js');
	form.cor_luvas = document.getElementById('cor_luvas_js');
	form.form = document.getElementById('form_personagem');
	// Função que atualiza a visualização do personagem.
	// É atribuida a "atualiza" quando todas as imagens terminam de ser carregadas.
	function atualiza_c () {
		var corpo = imgs.corpo,
			olhos = imgs.olhos[parseInt(form.olhos.value, 10) - 1],
			cabeloCor = form.cabeloCor.value,
			cabeloEstilo = parseInt(form.cabeloEstilo.value, 10) - 1,
			cabelo = imgs.cabelos[cabeloCor][cabeloEstilo],
			peleCor = form.cor_pele.value,
			luvasCor = form.cor_luvas.value,
			cintoCor = form.cor_cinto.value;
		// limpa o canvas
		context.clearRect(0, 0, dom.canvas.width, dom.canvas.height);
		if (peleCor.length === 6) {
			drawContext(context, imgs.mapaPele,17,41);
		}
		if(luvasCor.length === 6) {
			drawContext(context, imgs.mapaLuvas,21,214);
		}
		if (cintoCor.length === 6) {
			drawContext(context, imgs.mapaCinto,30,173);
		}
		context.drawImage(corpo, 0, 0);
		if (olhos) {
			context.drawImage(olhos, 37, 84);
		}
		if (cabelo) {
			context.drawImage(cabelo, 5, 11);
		}
	}
	// CARREGANDO IMAGENS
	(function () {
		var i, j, 
			n_cores = cabeloCores.length, 
			n_estilos = 20, // 20 estilos de cabelo por cor
			n_olhos = 8,
			completo = 0,  // quantidade de imagens já carregadas
			total = (n_cores * n_estilos) + n_olhos + 4; // total de imagens p/ carregar (cabelos + olhos + corpo)
		// BARRA DE LOADING
		var desenhaBarra = function() {
			var margem = 40.5,
				border = 3,
				largura = dom.canvas.width-margem*2,
				altura = dom.canvas.height-margem*2,
				progresso = completo/total;
			context.clearRect(0, 0, dom.canvas.width, dom.canvas.height);
			context.fillStyle = "rgb(83, 104, 111)";
			context.fillRect(margem, margem, largura, altura);
			context.fillStyle = "rgb(238, 245, 245)";
			context.fillRect(margem, margem, largura, altura-altura*progresso);
			context.fillStyle = "rgb(5,5,5)";
			context.strokeRect(margem,margem,largura,altura);
		};
		desenhaBarra();
		var carregou = function () {
			// limpa o canvas
			completo += 1;
			desenhaBarra();
			if (completo === total) {
				imgs.mapaPele = getImageContext(imgs.mapaPeleImg);
				imgs.mapaLuvas = getImageContext(imgs.mapaLuvasImg);
				imgs.mapaCinto = getImageContext(imgs.mapaCintoImg);
				colorAllPixels(imgs.mapaPele, engine.mainChar.cor_pele);
				colorAllPixels(imgs.mapaCinto, engine.mainChar.cor_cinto);
				colorAllPixels(imgs.mapaLuvas, engine.mainChar.cor_luvas);
				atualiza = atualiza_c;
				atualiza();
			}
				this.onload = null;
		}
		for (i = 0; i < n_cores; i += 1) {
			// para cada cor de cabelo
			imgs.cabelos[cabeloCores[i]] = []; // new Array(m);
			for (j = 0; j < n_estilos; j += 1) {
				// carregar a imagem de cada um dos 20 estilos
				imgs.cabelos[cabeloCores[i]][j] = new Image();
				imgs.cabelos[cabeloCores[i]][j].onload = carregou;
				imgs.cabelos[cabeloCores[i]][j].src = "../images/desenhos/cabelos/" + cabeloCores[i] + "/cabelo" + (j + 1).toString(10) + ".png";
			}
		}
		// carregando olhos
		imgs.olhos = []; // new Array(n);
		for (i = 0; i < n_olhos; i += 1) {
			imgs.olhos[i] = new Image();
			imgs.olhos[i].onload = carregou;
			imgs.olhos[i].src = "../images/desenhos/olhos/olho" + (i + 1).toString(10) + ".png";
		}
		imgs.corpo = new Image();
		imgs.corpo.onload = carregou;
		imgs.mapaPeleImg = new Image();
		imgs.mapaPeleImg.onload = carregou;
		imgs.mapaLuvasImg = new Image();
		imgs.mapaLuvasImg.onload = carregou;
		imgs.mapaCintoImg = new Image();
		imgs.mapaCintoImg.onload = carregou;
		imgs.corpo.src = "../images/desenhos/personagem_limpo.png";
		imgs.mapaPeleImg.src = "../images/desenhos/mapa_pele.png";
		imgs.mapaLuvasImg.src = "../images/desenhos/mapa_luvas.png";
		imgs.mapaCintoImg.src = "../images/desenhos/mapa_cinto.png";
	}());
	// FIM CARREGAR IMAGENS
}());
// vim: ts=4 sts=4 sw=4 expandtab
