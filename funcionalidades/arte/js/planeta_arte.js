var contexto;
var canvas;
var tela_svg;
var telaWidth = 500;
var telaHeight = 300;
var reta_preview;
var elipse_preview;
var retangulo_preview;
var mao_livre_preview;
var path = Array();
var lista_pontos = Array();
var INTERVALO_PARA_PONTOS = 20;
var coleta_pontos;
var mouse = {
	ativo : false,
	ferramenta : 0,
	status : {
		pressionado : false
	},
	posicao : {
		inicial : {
			x : 0,
			y : 0
		},
		x : 0,
		y : 0
	}
}

var preenchimento = {
	r : 0,
	g : 0,
	b : 0
}

var linha = {
	cor : "#F00",
	largura : 1,
	cantos : "rounded",
	estilos_possiveis : ["", "-", ".", "-.", "-..", ". ", "- ", "--", "- .", "--.", "--.."],
	estilo : 0
}

function salvar(){
	alert(canvas.toDataURL("image/png"));
}

function selecionaFerramenta(ferramenta){
//
	mouse.ferramenta = ferramenta;
	switch(ferramenta){
		case 1:
			document.getElementsByTagName('svg')[0].style.cursor = "url('imagens/pencil.png'),auto";
			break;
		case 2:
			document.getElementsByTagName('svg')[0].style.cursor = "url('imagens/Cursor-Fill-96.png'),auto";
			break;
		case 3:
		case 4:
		case 5:
			document.getElementsByTagName('svg')[0].style.cursor = "url('imagens/cursor-cross.png'),auto";
			break;
	}
}

function coletaPontos(){ // conforme o passar do mouse, cria um array de pontos, que serão tratados para criar uma curva suave
	var cursor = {
		x : mouse.posicao.x + 2,
		y : mouse.posicao.y + 16
	}
	var index_ultimo_elemento = lista_pontos.length - 1;
	if (index_ultimo_elemento == -1){ // lista vazia. simplesmente aceita o novo ponto. ponto inicial
		path.push(["M", cursor.x, cursor.y]);
		lista_pontos.push([cursor.x,cursor.y]);
		mao_livre_preview = tela_svg.path(path).attr({stroke: linha.cor, "stroke-width": linha.largura, "stroke-linecap": "rounded", cursor : "url('imagens/pencil.png'),auto" });
	}else{
		var ultimo_elemento = lista_pontos[index_ultimo_elemento];
		if ((ultimo_elemento[0] != cursor.x) || (ultimo_elemento[1] != cursor.y)){ // verifica se é diferente do ponto anterior
			var intermediario = pontoIntermediario(ultimo_elemento, cursor); // adiciona o elemento intermediário, para o cálculo da bezier
			lista_pontos.push([intermediario.x,intermediario.y]);
			lista_pontos.push([cursor.x,cursor.y]);
			if (index_ultimo_elemento == 0){
				path.push(["L", intermediario.x, intermediario.y]);
			}else{
				ponto_velho = path.pop();
				path.push(["C", ponto_velho[1], ponto_velho[2], ponto_velho[1], ponto_velho[2], intermediario.x, intermediario.y]);
			}
			path.push(["L", cursor.x, cursor.y]);
			mao_livre_preview.attr({path: path});
			mao_livre_preview.show();			
		}
	}
}

function addEllipsePath (context, cx, cy, r1, r2, startAngle, endAngle) {
	context.save();
	context.translate(cx, cy);
	context.scale(r1, r2);
	context.arc(0, 0, 1, startAngle, endAngle, 0);
	context.restore();
}

function recSVGparaCanvas(ctx){
	var cursor = {
		x : mouse.posicao.x + 11,
		y : mouse.posicao.y + 11,
		inicial : {
			x : mouse.posicao.inicial.x + 11,
			y : mouse.posicao.inicial.y + 11
		}
	}


	var height = cursor.y - cursor.inicial.y;
	var width = cursor.x - cursor.inicial.x;

	if (height > 0){
		y = cursor.inicial.y;	
	}else{
		y = cursor.inicial.y + height;	
		height = -height;	
	}
	if (width > 0){
		x = cursor.inicial.x;	
	}else{
		x = cursor.inicial.x + width;	
		width = -width;	
	}
	
	ctx.strokeStyle = linha.cor;
	ctx.lineWidth = linha.largura;
	ctx.lineCap = 'rounded';
	ctx.beginPath();
	ctx.rect(x, y, width, height);
	ctx.stroke();
}

function ellipseSVGparaCanvas(ctx){
	var cursor = {
		x : mouse.posicao.x + 11,
		y : mouse.posicao.y + 11,
		inicial : {
			x : mouse.posicao.inicial.x + 11,
			y : mouse.posicao.inicial.y + 11
		}
	}
	var ry = Math.round((cursor.y - cursor.inicial.y)/2);
	var rx = Math.round((cursor.x - cursor.inicial.x)/2);
	elipse = {
		centro : {
			x : (cursor.inicial.x + rx),
			y : (cursor.inicial.y + ry)
		},
		altura : Math.abs(ry),
		largura : Math.abs(rx)
	}
	
	ctx.strokeStyle = linha.cor;
	ctx.lineWidth = linha.largura;
	ctx.lineCap = 'rounded';
	ctx.beginPath();
	addEllipsePath(ctx, elipse.centro.x, elipse.centro.y, elipse.largura, elipse.altura, 0, Math.PI * 2);
	ctx.stroke();
}


function lineSVGparaCanvas(ctx){
	var cursor = {
		x : mouse.posicao.x + 11,
		y : mouse.posicao.y + 11,
		inicial : {
			x : mouse.posicao.inicial.x + 11,
			y : mouse.posicao.inicial.y + 11
		}
	}
	path = [["M",cursor.inicial.x,cursor.inicial.y],["L",cursor.x,cursor.y]];
	pathSVGparaCanvas(ctx);
}

function pathSVGparaCanvas(ctx){
	var tamanho = path.length;

	ctx.strokeStyle = linha.cor;
	ctx.lineWidth = linha.largura;
	ctx.lineCap = 'rounded';
	ctx.beginPath();
	
	for (j=0; j < tamanho; j++){
		if ((path[j][0] == "M") || (path[j][0] == "L")){
			if (path[j][0] == "M"){
				ctx.moveTo(path[j][1],path[j][2]);
			}else{
				ctx.lineTo(path[j][1],path[j][2]);
			}
		}else{
			if (path[j][0] == "C"){
				ctx.bezierCurveTo( path[j][1], path[j][2], path[j][3], path[j][4], path[j][5], path[j][6]);
			}
		}
	}
	ctx.stroke();
}

/*

function formaStringSVG(){
	var tamanho = path.length;
	var stringSVG = "";

	for (j=0; j < tamanho; j++){
		if ((path[j][0] == "M") || (path[j][0] == "L")){
			stringSVG = stringSVG + " " + path[j][0] +" " + path[j][1] + " "+ path[j][2];

		}else{
			if (path[j][0] == "C"){
				stringSVG = stringSVG + " C " + path[j][1] + " "+ path[j][2];
				stringSVG = stringSVG + " " + path[j][3] + " "+ path[j][4];
				stringSVG = stringSVG + " " + path[j][5] + " "+ path[j][6];
			}
		}
	}
	return stringSVG;
}

function renderizaSVGnoCanvas(){
	var c = document.getElementById('tela_canvas');
	var stringPath = '<svg><path d="';
	stringPath = stringPath  + formaStringSVG() + '" stroke="' + linha.cor + '" stroke-width="' + linha.largura+'"';
// Se fosse continuar a implementação para estilos de linhas
//	stringPath = stringPath  + '" stroke-dasharray="''";
	stringPath = stringPath  + ' fill="none" /></svg>';
	
	c.width = '500';
	c.height = '300';
	if (contexto) c.getContext = contexto;
	canvg(c, stringPath ,{ ignoreClear: true});
	mao_livre_preview.remove();
}




*/

function pontoIntermediario(p1, p2){ // calcula o ponto intermediário entre dois pontos
	var p3 = {
		x : Math.round((p1[0] + p2.x)/2),
		y : Math.round((p1[1] + p2.y)/2)
	};
	return p3;
}

function pinta(cor){
	var imageData = contexto.getImageData(0,0,telaWidth,telaHeight);
	var R = cor.r;
	var G = cor.g;
	var B = cor.b;
	var cursor = {
		x : mouse.posicao.x + 2,
		y : mouse.posicao.y + 16
	}
	
	pixelStack = [[cursor.x, cursor.y]];

	pixelPos = (cursor.y*telaWidth + cursor.x) * 4;
	startR = imageData.data[pixelPos];
	startG = imageData.data[pixelPos+1];
	startB = imageData.data[pixelPos+2];
	
	if ((R != startR)||(G != startG)||(B != startB)){
		while(pixelStack.length)
		{
			var newPos, x, y, pixelPos, reachLeft, reachRight;
			newPos = pixelStack.pop();
			x = newPos[0];
			y = newPos[1];

			pixelPos = (y*telaWidth + x) * 4;
			while(y-- >= 1 && matchStartColor(pixelPos)){
				pixelPos -= telaWidth * 4;
			}
			pixelPos += telaWidth * 4;
			++y;
			reachLeft = false;
			reachRight = false;
			while(y++ < telaHeight-1 && matchStartColor(pixelPos)){
				colorPixel(pixelPos);

				if(x > 0){
					if(matchStartColor(pixelPos - 4)){
						if(!reachLeft){
						  pixelStack.push([x - 1, y]);
						  reachLeft = true;
						}
					} else if(reachLeft){
						reachLeft = false;
					}
				}

				if(x < telaWidth-1){
					if(matchStartColor(pixelPos + 4)){
						if(!reachRight){
						  pixelStack.push([x + 1, y]);
						  reachRight = true;
						}
					} else if(reachRight){
						reachRight = false;
					}
				}
		
				pixelPos += telaWidth * 4;
			}
		}
		contexto.putImageData(imageData, 0, 0);
	}
	  
	function matchStartColor(pixelPos){
		var r = imageData.data[pixelPos];	
		var g = imageData.data[pixelPos+1];	
		var b = imageData.data[pixelPos+2];
		var a = imageData.data[pixelPos+3];

		var testeR = ((r > (startR - 50)) && (r < (startR + 50)));
		var testeG = ((g > (startG - 50)) && (g < (startG + 50)));
		var testeB = ((b > (startB - 50)) && (b < (startB + 50)));
		
		return (testeR && testeG && testeB && a == 255);
	}

	function colorPixel(pixelPos){
		imageData.data[pixelPos] = R;
		imageData.data[pixelPos+1] = G;
		imageData.data[pixelPos+2] = B;
		imageData.data[pixelPos+3] = 255;
	}
}

function controlaRetangulo(){
	var cursor = {
		x : mouse.posicao.x + 11,
		y : mouse.posicao.y + 11,
		inicial : {
			x : mouse.posicao.inicial.x + 11,
			y : mouse.posicao.inicial.y + 11
		}
	}
	var height = cursor.y - cursor.inicial.y;
	var width = cursor.x - cursor.inicial.x;

	if (height > 0){
		retangulo_preview.attr({"y" : cursor.inicial.y});	
		retangulo_preview.attr({"height" : height});	
	}else{
		retangulo_preview.attr({"y" : cursor.inicial.y + height});	
		retangulo_preview.attr({"height" : -height});	
	}
	if (width > 0){
		retangulo_preview.attr({"x" : cursor.inicial.x});	
		retangulo_preview.attr({"width" : width});	
	}else{
		retangulo_preview.attr({"x" : cursor.inicial.x + width});	
		retangulo_preview.attr({"width" : -width});	
	}
	retangulo_preview.show();
}

function controlaElipse(){
	var cursor = {
		x : mouse.posicao.x + 11,
		y : mouse.posicao.y + 11,
		inicial : {
			x : mouse.posicao.inicial.x + 11,
			y : mouse.posicao.inicial.y + 11
		}
	}
	var ry = Math.round((cursor.y - cursor.inicial.y)/2);
	var rx = Math.round((cursor.x - cursor.inicial.x)/2);
	
	elipse_preview.attr({"cx" : cursor.inicial.x + rx});	
	elipse_preview.attr({"rx" : Math.abs(rx)});	
	elipse_preview.attr({"cy" : cursor.inicial.y + ry});	
	elipse_preview.attr({"ry" : Math.abs(ry)});	
//	elipse_preview.attr({"cy" : mouse.posicao.inicial.y});	
//	elipse_preview.attr({"ry" : ry});	
	elipse_preview.show();
}

function controlaReta(){
	var cursor = {
		x : mouse.posicao.x + 11,
		y : mouse.posicao.y + 11,
		inicial : {
			x : mouse.posicao.inicial.x + 11,
			y : mouse.posicao.inicial.y + 11
		}
	}
	var reta = [["M",cursor.inicial.x,cursor.inicial.y],["L",cursor.x,cursor.y]];
	reta_preview.attr({path : reta});	
	reta_preview.show();
}

function controleDeFerramentas(){
	if (mouse.ativo){

		switch(mouse.ferramenta){
			case 1:
				coletaPontos();
				break;
			case 3:
				controlaRetangulo();
				break;
			case 4:
				controlaElipse();
				break;
			case 5:
				controlaReta();
				break;
		}
	}
}

jQuery(document).ready(function(){
	tela_svg = Raphael("tela_svg", telaWidth,telaHeight); // prepara o svg para os controles
	canvas = document.getElementById('tela_canvas');
	contexto = canvas.getContext("2d");
	document.getElementById('tela_canvas').width = telaWidth;
	document.getElementById('tela_canvas').height = telaHeight;
	contexto.fillStyle = "rgba(255, 255, 255, 1)";
	contexto.fillRect (0, 0, telaWidth,telaHeight);
	retangulo_preview = tela_svg.rect(0,0,0,0).hide();
	reta_preview = tela_svg.path(["M",0,0,0,0]).hide();
	elipse_preview = tela_svg.ellipse(0,0,0,0).hide();
//	if (typeof(FlashCanvas) != 'undefined') contexto = document.getElementById('tela_canvas').getContext; 
	
	$(document).mouseup(function(e){
		mouse.status.pressionado = false;
		clearInterval(coleta_pontos);
		if (mouse.ativo){
			switch(mouse.ferramenta){
				case 1:
					coletaPontos();
					pathSVGparaCanvas(contexto);
					mao_livre_preview.remove();
					break;
				case 3:
					retangulo_preview.hide();
					recSVGparaCanvas(contexto);
					break;
				case 4:
					elipse_preview.hide();
					ellipseSVGparaCanvas(contexto);
					break;
				case 5:
					reta_preview.hide();
					lineSVGparaCanvas(contexto);
					break;
			}
			mouse.ativo = false;
		}
	});

	$("#tela_svg").mouseout(function(e){
		mouse.status.pressionado = false;
		if (mouse.ativo){
			if( e.toElement ) {				
				current_mouse_target 			 = e.toElement;
			} else if( e.relatedTarget ) {				
				current_mouse_target 			 = e.relatedTarget;
			}	
			if ((current_mouse_target.innerHTML != undefined) && (current_mouse_target.innerHTML) && (current_mouse_target.innerHTML != "")){
				clearInterval(coleta_pontos);
				switch(mouse.ferramenta){
					case 1:
						coletaPontos();
						pathSVGparaCanvas(contexto);
						mao_livre_preview.remove();
						break;
					case 3:
						retangulo_preview.hide();
						recSVGparaCanvas(contexto);
						break;
					case 4:
						elipse_preview.hide();
						ellipseSVGparaCanvas(contexto);
						break;
					case 5:
						reta_preview.hide();
						lineSVGparaCanvas(contexto);
						break;
				}
				mouse.ativo = false;
			}
		}
	});
	
	
	$("#tela_svg").mousedown(function(e){
		mouse.status.pressionado = true;
		mouse.ativo = true;
		if (mouse.ferramenta == 2){
			pinta(preenchimento);
		}else{
			lista_pontos = Array();
			path = Array();
			mouse.posicao.inicial.x = mouse.posicao.x;
			mouse.posicao.inicial.y = mouse.posicao.y;
			retangulo_preview.attr({stroke: linha.cor, "stroke-width": linha.largura, "stroke-linecap": "rounded"});
			elipse_preview.attr({stroke: linha.cor, "stroke-width": linha.largura, "stroke-linecap": "rounded"});
			reta_preview.attr({stroke: linha.cor, "stroke-width": linha.largura, "stroke-linecap": "rounded"});
			coleta_pontos = setInterval("controleDeFerramentas()",INTERVALO_PARA_PONTOS);
		}
	}); 
	
	$(document).mousemove(function(e){
		mouse.posicao.x = e.pageX - document.getElementById("tela_svg").offsetLeft;
		mouse.posicao.y = e.pageY - document.getElementById("tela_svg").offsetTop;
	}); 

	$(".cores").click(function(e){
		linha.cor = this.style.backgroundColor;
		cor = new RGBColor(linha.cor);
		preenchimento.r = cor.r;
		preenchimento.g = cor.g;
		preenchimento.b = cor.b;
	});
})
