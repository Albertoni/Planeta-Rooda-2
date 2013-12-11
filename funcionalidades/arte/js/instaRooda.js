function Ponto(x,y,tamanho, rigidez){
	this.x = x;
	this.y = y;
	this.tamanho = Math.max(tamanho,1)*1024;
	this.rigidez = Math.max(rigidez,1);
}

function Pincel(){

	var camadas = new Array();

	this.adiciona_camada = function(){
		var p = new Array();
		camadas.push(p);
	}

	this.adiciona_ponto = function(x,y,tamanho, rigidez){
		var cam = camadas.length -1;
		var p = new Ponto(x,y,tamanho, rigidez);
		camadas[cam].push(p);
	}

	this.limpa_pincel = function(){
		Array.clear(camadas);
	}

	this.intensidade = function(x,y){
		var e = Math.E;
		var m = camadas.length;
//		window.document.write("e:"+Math.E+"<br />");
		var resultado = 0;
		for (var c = 0; c < m; c++){
			var n = camadas[c].length;
			for (var i = 0; i<n; i++){
					var influencia = 1/camadas[c][i].tamanho;
					var rigidez = camadas[c][i].rigidez*2;
					var rigidezX = Math.pow((x- camadas[c][i].x),rigidez);
					var rigidezY = Math.pow((y- camadas[c][i].y),rigidez);
					var normal_x = Math.pow(e,-(influencia)*rigidezX);
					var normal_y = Math.pow(e,-(influencia)*rigidezY);

					resultado = resultado + normal_x*normal_y;
	//			window.document.write("e^(-("+influencia+")*("+x+"- "+pontos[i].x+")^"+rigidez+")");

			}
			resultado = Math.min(resultado, 1);
		}
//		resultado = (x%10)/10;
//		window.document.write("r:"+resultado+"<br />");
		return resultado;
	}

	//this.adiciona_camada;
}

function contrasteTrans(contraste, Pixel, j){
	Pixel.data[j] = Pixel.data[j] * contraste;
	Pixel.data[j+1] = Pixel.data[j+1] * contraste;
	Pixel.data[j+2] = Pixel.data[j+2] * contraste;
	return Pixel;
}

function mascaraTrans(cor, Pixel, j){
	Pixel.data[j] = Math.round(Pixel.data[j]*(1-cor.opacidade) + cor.r*cor.opacidade);
	Pixel.data[j+1] = Math.round(Pixel.data[j+1]*(1-cor.opacidade) + cor.g*cor.opacidade);
	Pixel.data[j+2] = Math.round(Pixel.data[j+2]*(1-cor.opacidade) + cor.b*cor.opacidade);
	return Pixel;
}
function cinzaTrans(Pixel,j){
	var r = Pixel.data[j];
	var g = Pixel.data[j+1];
	var b = Pixel.data[j+2];
	var media = Math.floor((r + g + b)/ 3);
	Pixel.data[j] = media;
	Pixel.data[j+1] = media;
	Pixel.data[j+2] = media;
	return Pixel;
}

function brilhoTrans(brilho,Pixel,j){
	Pixel.data[j] = Math.min(Pixel.data[j] + brilho,255);
	Pixel.data[j+1] = Math.min(Pixel.data[j+1] + brilho,255);
	Pixel.data[j+2] = Math.min(Pixel.data[j+2] + brilho,255);
	return Pixel;
}

ferramenta1 = new Pincel();
ferramenta1.adiciona_camada();
ferramenta1.adiciona_ponto(0, 0, 15, 1);
ferramenta1.adiciona_ponto(0, telaHeight, 15, 1);
ferramenta1.adiciona_ponto(telaWidth, 0, 15, 1);
ferramenta1.adiciona_ponto(telaWidth, telaHeight, 15, 1);
ferramenta2 = new Pincel();
ferramenta2.adiciona_camada();
ferramenta2.adiciona_ponto( Math.floor(telaWidth / 2), Math.floor(telaHeight / 2), 15, 1 );
console.log (Math.floor(telaWidth / 2) + " " + Math.floor(telaHeight / 2));


function aplicaFiltro(ctx, cor){
	var Pixel = ctx.getImageData(0, 0, telaWidth, telaHeight);
	var tamanho = (Pixel.data.length /4);
	for (i=0; i<tamanho; i++){
		var j = i*4;

		var r = Pixel.data[j];
		var g = Pixel.data[j+1];
		var b = Pixel.data[j+2];

		var media = Math.floor((r + g + b)/ 3);
		var contraste = 1.2;
		var brilho = 30;

		Pixel = mascaraTrans(cor, Pixel, j);
		Pixel = brilhoTrans(-30, Pixel, j);
		Pixel = contrasteTrans(1.2, Pixel, j);

		var y = Math.floor( i / telaWidth );
		var x = i - y * telaWidth;
//				console.log("i:"+i+" x:"+x+" y:"+y);

		var intensidade = ferramenta1.intensidade(x,y);
		Pixel = brilhoTrans(-60*intensidade, Pixel, j);
		Pixel = contrasteTrans(1+0.4*(intensidade), Pixel, j);

		intensidade = ferramenta2.intensidade(x,y);
		Pixel = contrasteTrans(1+0.2*intensidade, Pixel, j);
		Pixel = brilhoTrans(10*intensidade, Pixel, j);
	}
	ctx.putImageData(Pixel, 0, 0);
}

function aplicaFiltro1(ctx){
	var cor = {	r: 182,
				g: 168,
				b: 105,
				opacidade: 0.42};

	aplicaFiltro(ctx,cor);
}

function aplicaFiltro2(ctx){
	var cor = {	r: 168,
				g: 105,
				b: 182,
				opacidade: 0.42};

	aplicaFiltro(ctx,cor);
}

function aplicaFiltro3(ctx){
	var cor = {	r: 105,
				g: 182,
				b: 168,
				opacidade: 0.42};

	aplicaFiltro(ctx,cor);
}