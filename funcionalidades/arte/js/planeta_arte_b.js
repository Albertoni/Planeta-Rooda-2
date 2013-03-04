

/*
*	os valores de telaWidth e telaHeight devem ser os mesmos das dimens�es colocadas para a classe tela no css
*
*	no javascript:
*	var telaWidth = 550;
*	var telaHeight = 270;
*	
*	no CSS (que se encontra em arte_desenho.css):
*	.tela{
*		width:550px;
*		height:270px;
*	}
*/
// Propor��es da tela do desenho
var telaWidth = 550;	
var telaHeight = 270;

// Canvas e Raphael
var canvas;
var contexto;
var tela_svg;

// elementos do Raphael (SVG) que aparecem enquanto o o usu�rio n�o solta o bot�o do mouse
var guia;						// ret�ngulo pontilhado que serve de guia para o carimbo
var carimbo_preview;				// os previews s�o as vers�es SVG do objeto que ser� por fim desenhado no Canvas
var reta_preview;
var elipse_preview;
var retangulo_preview;
var mao_livre_preview = Array();
var path = Array();				// path � um preview (SVG) da m�o livre/ pincel
var lista_pontos = Array();		// lista de pontos utilizado para desenhar a m�o livre no canvas

var areaTransferencia;			// c�pia da imagem, utilizada para o Copiar/Colar (n�o implentado completamente)



var INTERVALO_PARA_PONTOS = 20;	// intervalo de tempo em que a posi��o do mouse ser� guardada (para fazer o risco)
var coleta_pontos;				// vari�vel que guarda o handler do setInterval()

var carimbo;					// carimbo (objeto image) que ser� utilizado

// configura��es do texto
var texto = {
	tamanho : 12,
	cor : '#000',
	fonte : 'Arial',
	sublinhado : false,
	negrito : false,
	italico : false
}

// dados da ferramenta que est� sendo utilizada
var mouse = {
	ativo : false,				// false se for o cursor comum (nenhuma ferramenta selecionada)
	ferramenta : 0,				// tipo de ferramenta
	status : {
		pressionado : false		// status do bot�o esquerdo do mouse
	},
	posicao : {
		inicial : {			// posi��o em que o mouse estava quando o bot�o esquerdo foi pressionado (sem ser solto)
			x : 0,
			y : 0
		},
		x : 0,				// posi��o atual do mouse
		y : 0
	},
	area : {					// dados do ret�ngulo formado pelas coordenadas iniciais e finais do mouse, com o boto�o esquerdo pressionado
		x : 0,		// sim... � redundante com mouse.posicao.inicial.x
		y : 0,		// idem
		width : 0,
		height : 0
	},
	carimbo : "",				// carimbo selecionado (redundante com a vari�vel global carimbo)
	preenchido : false			// se ret�ngulo ou elipse ser�o preenchidas
}

var preenchimento = {			// cor de preenchimento
	r : 0,
	g : 0,
	b : 0
}

var linha = {					// dados da linha
	cor : "#000",
	cor2 : "#FFF",
	largura : 1,
	cantos : "rounded",
	estilo : 0
}
var borracha = {				// dados da borracha
	cor : "#FFF",
	largura : 10
}

//	configura o http_salvar conforme o navegador
if(navigator.appName == "Microsoft Internet Explorer") {
	http_salvar = new ActiveXObject("Microsoft.XMLHTTP");
} else {
	http_salvar = new XMLHttpRequest();
}

/*
*	Salva a imagem via ajax
*	O arquivo salva_imagem.php recebe os seguintes par�metros
*	-	id :		id do desenho
*	-	turma :		id da turma
*	-	titulo :	titulo do desenho
*	-	imagem :	imagem em Base64
*	-	existente :	se o desenho � novo ent�o existente = false
*/
function salvar(){

	if (document.getElementById('titulo').value == ''){ // o desenho precisa ter um t�tulo
		alert('O desenho precisa ter um t�tulo.');
		return 0;
	}
	titulo = encodeURIComponent(document.getElementById('titulo').value);
	
	var imagem = canvas.toDataURL("image/png");		// pega os dados da imagem, para envi�-la ao servidor em base64
	imagem = encodeURIComponent(imagem);
	
	var url = "salva_imagem.php";
	var params = "imagem="+imagem+"&turma="+turma+"&titulo="+titulo+"&existente="+existente+"&id="+id_do_desenho;
	http_salvar.open("POST", url, true);

	http_salvar.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_salvar.setRequestHeader("Content-length", params.length);
	http_salvar.setRequestHeader("Connection", "close");

	http_salvar.onreadystatechange = function() {
		if(http_salvar.readyState == 4 && http_salvar.status == 200) {
			alert("Desenho salvo.");
			existente = 1;
			id_do_desenho = http_salvar.responseText;
		}
	}
	http_salvar.send(params);
	
}

/*
atualiza algumas informa��es da vari�vel global mouse, conforme a ferramenta escolhida
esta fun��o � chamada pelo html, no onclick do �cone da ferramenta
*/
function selecionaFerramenta(ferramenta){
	some_editor();				// faz o editor de texto desaparecer (editor usado para escrever no canvas)
	mouse.ferramenta = ferramenta;	// guarda a ferramenta utilizada na vari�vel global mouse (que guarda os dados do mouse e da ferramenta)
	switch(ferramenta){
/*
		case 1:
		case 8:
			break;
*/			
		case 3:	// retangulo vazio
		case 4:	// elipse vazia
		case 5:	// linha
		case 6:	// carimbo
		case 7:	//
			mouse.preenchido = false;
			break;
		case 10:	// retangulo cheio
		case 11:	// elipse cheia
			mouse.preenchido = true;
			break;
	}
}

/*
*	Abre a janela de escolha dos carimbos
*	"div_escolhe_carimbos"
*/
function selecionaCarimbo(){
	$("#fundo_escolhe_carimbos").css("display","block");
	document.getElementById("div_escolhe_carimbos").style.display = "block";
	mouse.carimbo = carimbo;
}

/*
*	Limpa todos 
*
*/
function limpa_mao_livre(){
	console.log("limpa_mao_livre:");
	console.log("\ttamanho do mao_livre_preview:"+mao_livre_preview.length);
	while (mao_livre_preview.length > 0){
		mlp = mao_livre_preview.pop();
		mlp.hide();
		mlp.remove();
	}
}

/*
*
*	A fun��o coletaPontos() � chamada pelo handler coleta_pontos com um intervalo INTERVALO_PARA_PONTOS (vari�veis globais)
*
*	Sempre que � chamada a fun��o pega a posi��o atual do ponteiro do mouse
*	Depois ela adiciona o ponto a um array, que ser� utilizado para a constru��o de uma linha com curvas suaves
*	
*	Obs.: Esta fun��o � utilizada pelo l�pis (m�o_livre) e a borracha (pois ambos funcionam da mesma forma)
*
*
*
*	O c�lculo para a b�zier funciona da seguinte forma:
*	a partir de 2 pontos A e B � gerado um ponto intermedi�rio C a partir da fun��o pontoIntermediario(A,B)
*		C.x := Math.round((Ax + Bx)/2)
*		C.y := Math.round((Ay + By)/2)

*	Como o �ltimo elemento do array path sempre � um ["L",x,y]
*	este elemento � retirado para ser colocado uma Bezier ( identificada por "C" )
*	Que inicia no final da curva anterior, tenta atingir o ponto x,y antigo e termina no novo ponto intermedi�rio
*	no final, � colocada uma reta entre o ponto intermedi�rio e o ponto x,y atual (esta reta � tempor�ria, a n�o ser que seja a �ltima)
*/
function coletaPontos(){
	var cursor = {				//pega a posi��o do cursor, que � guardado na vari�vel global mouse
		x : mouse.posicao.x,
		y : mouse.posicao.y
	}

	//trabalha sobre o array lista_pontos
	var index_ultimo_elemento = lista_pontos.length - 1;
	if (index_ultimo_elemento == -1){ // array vazio. simplesmente aceita o novo ponto. (ponto inicial do path)
		path.push(["M", cursor.x, cursor.y]);	// para entender o que ocorre aqui vale dar uma procurada no google sobre a documenta��o do SVG
		lista_pontos.push([cursor.x,cursor.y]);	// guarda ponto
		
		if (mouse.borracha){		// verifica o tipo de ferramenta, pois cada uma tem um preview diferente
			mao_livre_preview.push(tela_svg.path(path).attr({stroke: borracha.cor, "stroke-width": borracha.largura, "stroke-linecap": "rounded"})); // borracha
		}else{
			mao_livre_preview.push(tela_svg.path(path).attr({stroke: linha.cor, "stroke-width": linha.largura, "stroke-linecap": "rounded"})); // l�pis (m�o livre)
		}
	}else{
		var ultimo_elemento = lista_pontos[index_ultimo_elemento]; 				// pega o ponto anterior
		if ((ultimo_elemento[0] != cursor.x) || (ultimo_elemento[1] != cursor.y)){	// verifica se � diferente do ponto anterior
			var intermediario = pontoIntermediario(ultimo_elemento, cursor); 			// adiciona o elemento intermedi�rio, para o c�lculo da bezier
			lista_pontos.push([intermediario.x,intermediario.y]);
			lista_pontos.push([cursor.x,cursor.y]);
			
// se o ponto anterior � o primeiro, ent�o n�o � poss�vel fazer uma bezier, ent�o � feita uma linha reta
			if (index_ultimo_elemento == 0){
				path.push(["L", intermediario.x, intermediario.y]);
			}else{
// sen�o, a bezier � constru�da
				ponto_velho = path.pop();
				path.push(["C", ponto_velho[1], ponto_velho[2], ponto_velho[1], ponto_velho[2], intermediario.x, intermediario.y]);
			}
			path.push(["L", cursor.x, cursor.y]); // faz linha reta para o �ltimo ponto
			
			mao_livre_preview[mao_livre_preview.length - 1].attr({path: path});
			mao_livre_preview[mao_livre_preview.length - 1].show();
		}
	}
}

//Desenha a Elipse no canvas
function addEllipsePath (context, cx, cy, r1, r2, startAngle, endAngle) {
	context.save();
	context.translate(cx, cy);
	context.scale(r1, r2);
	context.arc(0, 0, 1, startAngle, endAngle, 0);
	context.restore();
}

//A partir de um elemento SVG de um ret�ngulo, desenha um similar no canvas
function recSVGparaCanvas(ctx){
	var cursor = {
		x : mouse.posicao.x,
		y : mouse.posicao.y,
		inicial : {
			x : mouse.posicao.inicial.x,
			y : mouse.posicao.inicial.y
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
	
	ctx.fillStyle = linha.cor;
	ctx.strokeStyle = linha.cor;
	ctx.lineWidth = linha.largura;
	ctx.lineCap = 'rounded';
	ctx.beginPath();
	ctx.rect(x, y, width, height);
	ctx.stroke();
	if (mouse.preenchido){
		ctx.fill();
	}
}

//A partir de um elemento SVG de uma Elipse, desenha um similar no canvas
function ellipseSVGparaCanvas(ctx){
	var cursor = {
		x : mouse.posicao.x,
		y : mouse.posicao.y,
		inicial : {
			x : mouse.posicao.inicial.x,
			y : mouse.posicao.inicial.y
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
	
	ctx.fillStyle = linha.cor;
	ctx.strokeStyle = linha.cor;
	ctx.lineWidth = linha.largura;
	ctx.lineCap = 'rounded';
	ctx.beginPath();
	addEllipsePath(ctx, elipse.centro.x, elipse.centro.y, elipse.largura, elipse.altura, 0, Math.PI * 2);
	ctx.stroke();
	if (mouse.preenchido){
		ctx.fill();
	}
}

//A partir de um elemento SVG de uma Linha, desenha um similar no canvas
function lineSVGparaCanvas(ctx){
	var cursor = {
		x : mouse.posicao.x,
		y : mouse.posicao.y,
		inicial : {
			x : mouse.posicao.inicial.x,
			y : mouse.posicao.inicial.y
		}
	}
	path = [["M",cursor.inicial.x,cursor.inicial.y],["L",cursor.x,cursor.y]];
	pathSVGparaCanvas(ctx);
}

//Desenha um path (SVG) qualquer no canvas
function pathSVGparaCanvas(ctx){
	console.log("pathSVGparaCanvas:");
	console.log("\ttamanho do path:"+path.length);
	var tamanho = path.length;


	if (mouse.borracha){
		ctx.strokeStyle = borracha.cor;
		ctx.lineWidth = borracha.largura;
	}else{
		ctx.strokeStyle = linha.cor;
		ctx.lineWidth = linha.largura;
	}
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

//Desenha uma imagem (SVG) no canvas
function imageSVGparaCanvas(ctx){
	var image = mouse.carimbo;
	var cursor = {
		x : mouse.posicao.x,
		y : mouse.posicao.y,
		inicial : {
			x : mouse.posicao.inicial.x,
			y : mouse.posicao.inicial.y
		}
	}


	var height = cursor.y - cursor.inicial.y;
	var width = cursor.x - cursor.inicial.x;

	//Ajustes de tamanho
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

	//Fun��o que ser� chamada assim que a imagem for carregada
	var colaImagem = function() {
		ctx.drawImage(image,x,y,width,height); //desenha a imagem no canvas
	}

	if (image.complete) { 	// se a imagem j� estiver carregada, ent�o chama a fun��o colaImagem()
		colaImagem();
	}else {				// sen�o liga o evento onLoad � fun��o colaImagem()
		image.onload = colaImagem;
	}
}

/*
*	Desenha um texto no canvas
*	O estilo do texto est� guardado na vari�vel global texto
*	O texto � pego a partir do array linhas (que � um split com o caractere \n do value do textarea)
*/
function textoCanvas(ctx){
	var x = document.getElementById('float').offsetLeft + 1;
	var y = document.getElementById('float').offsetTop + 18;
	x = x - (document.getElementById("conteudo").offsetLeft + document.getElementById("tela_div").offsetLeft + document.getElementById("geral").offsetLeft);
	y = y - (document.getElementById("tela_div").offsetTop + document.getElementById("geral").offsetTop + document.getElementById("conteudo_meio").offsetTop + document.getElementById("conteudo").offsetTop);

	desloc = texto.tamanho - 12;
	y = y + desloc;

	var fonte = texto.fonte;
	fonte = texto.tamanho+"pt " + fonte;
	if (texto.negrito)
		fonte = "bold " + fonte;
	if (texto.sublinhado)
		fonte = "underline " + fonte;
	if (texto.italico)
		fonte = "italic " + fonte;

	ctx.font = fonte;
	ctx.lineWidth = 0;
	ctx.fillStyle = texto.cor;

	altura_linha = 15;
	linhas = document.getElementById('texto_b').value.split("\n");
	for (var i = 0; i<linhas.length; i++)
		ctx.fillText(linhas[i], x, y + (i*altura_linha) );

}

/* 
Implementa��o incompleta do Copiar/Colar

function areaSelecionada(ctx){
	var cursor = {
		x : mouse.posicao.x,
		y : mouse.posicao.y,
		inicial : {
			x : mouse.posicao.inicial.x,
			y : mouse.posicao.inicial.y
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
	
	x = (x < 0)? 0: x;
	y = (y < 0)? 0: y;

	width = ((width+x) > telaWidth)? (telaWidth - x) : width;
	height = ((height+y) > telaHeight)? (telaHeight - y) : height;
	
	mouse.area.x = x;
	mouse.area.y = y;
	mouse.area.width = width;
	mouse.area.height = height;
//	var Pixels = ctx.getImageData(0,0,500,500); 

}

function copiaImagem(){
	areaTransferencia = contexto.getImageData(mouse.x,mouse.y,mouse.width,mouse.height); 
}




function colaImagemNoCanvas(){
	var x = image_preview.attr({attrName: "x"});
	var y = image_preview.attr({attrName: "y"});
	contexto.putImageData(areaTransferencia, x , y);
}


// Copia a imagem que est� na vari�vel areaTransferencia para um canvas auxiliar, para ent�o gerar um src que possa ser utilizado pelo raphael
// Isso tudo s� para fazer o preview de onde a imagem ser� colada.

function preview_colaImagem(){
	var canvas = document.getElementById('canvas_auxiliar'); // verifica se o canvas auxiliar j� existe
	
	canvas.width = mouse.area.width;
	canvas.height = mouse.area.height;
		
	var contextoAT = canvas.getContext("2d");  // prepara o canvas para receber os dados da sele��o feita anteriormente
	contextoAT.putImageData(areaTransferencia, 0, 0);
	var src = canvas.toDataURL("image/png");	// cria uma refer�ncia a esta imagem, para que possa ser utilizada com o Raphael

	carimbo_preview.attr({"width" : -width});

	imagem_preview.attr({src: src, x: 0, y:0});
	imagem_preview.show();
	imagem_preview.drag(cola_inicio,cola_move,cola_fim);
}
*/

function downloadImagem(){
	document.location.href = canvas.toDataURL("image/png");
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
// Se fosse continuar a implementa��o para estilos de linhas
//	stringPath = stringPath  + '" stroke-dasharray="''";
	stringPath = stringPath  + ' fill="none" /></svg>';
	
	c.width = '500';
	c.height = '300';
	if (contexto) c.getContext = contexto;
	canvg(c, stringPath ,{ ignoreClear: true});
	mao_livre_preview.remove();
}




*/
/*
*	Calcula o ponto m�dio entre dois pontos
*/
function pontoIntermediario(p1, p2){ // calcula o ponto intermedi�rio entre dois pontos
	var p3 = {
		x : Math.round((p1[0] + p2.x)/2),
		y : Math.round((p1[1] + p2.y)/2)
	};
	return p3;
}

/*
*	Algoritmo de preenchimento
*	Infelizmente n�o achei um nativo do Canvas
*/
function pinta(cor){
	var imageData = contexto.getImageData(0,0,telaWidth,telaHeight);
	var R = cor.r;
	var G = cor.g;
	var B = cor.b;
	var cursor = {
		x : mouse.posicao.x,
		y : mouse.posicao.y
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

/*
*	Controla o tamanho do ret�ngulo que ser� desenhado
*	For�a o ret�ngulo a n�o passar dos limites da tela de desenho
*/

function controlaRetangulo(){
	var cursor = {
		x : mouse.posicao.x,
		y : mouse.posicao.y,
		inicial : {
			x : mouse.posicao.inicial.x,
			y : mouse.posicao.inicial.y
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


/*
*	Controla o tamanho do carimbo que ser� desenhado
*	For�a o carimbo a n�o passar dos limites da tela de desenho
*/
function controlaCarimbo(){
	var cursor = {
		x : mouse.posicao.x,
		y : mouse.posicao.y,
		inicial : {
			x : mouse.posicao.inicial.x,
			y : mouse.posicao.inicial.y
		}
	}
	var height = cursor.y - cursor.inicial.y;
	var width = cursor.x - cursor.inicial.x;
	
	if (height > 0){
		carimbo_preview.attr({"y" : cursor.inicial.y});	
		carimbo_preview.attr({"height" : height});	
		guia.attr({"y" : cursor.inicial.y, "height" : height});
	}else{
		carimbo_preview.attr({"y" : cursor.inicial.y + height});	
		carimbo_preview.attr({"height" : -height});	
		guia.attr({"y" : cursor.inicial.y + height, "height" : -height});
	}
	if (width > 0){
		carimbo_preview.attr({"x" : cursor.inicial.x});	
		carimbo_preview.attr({"width" : width});	
		guia.attr({"x" : cursor.inicial.x, "width" : width});
	}else{
		carimbo_preview.attr({"x" : cursor.inicial.x + width});	
		carimbo_preview.attr({"width" : -width});
		guia.attr({"x" : cursor.inicial.x + width, "width" : -width});
	}


	carimbo_preview.show();
	guia.show();
}

/*
function controlaSelecao(){
	var cursor = {
		x : mouse.posicao.x,
		y : mouse.posicao.y,
		inicial : {
			x : mouse.posicao.inicial.x,
			y : mouse.posicao.inicial.y
		}
	}
	var height = cursor.y - cursor.inicial.y;
	var width = cursor.x - cursor.inicial.x;
	
	if (height > 0){
		guia.attr({"y" : cursor.inicial.y, "height" : height});
	}else{
		guia.attr({"y" : cursor.inicial.y + height, "height" : -height});
	}
	if (width > 0){
		guia.attr({"x" : cursor.inicial.x, "width" : width});
	}else{
		guia.attr({"x" : cursor.inicial.x + width, "width" : -width});
	}

	guia.show();
}
*/

/*
*	Controla o tamanho da elipse que ser� desenhada
*	For�a a elipse a n�o passar dos limites da tela de desenho
*/
function controlaElipse(){
	var cursor = {
		x : mouse.posicao.x,
		y : mouse.posicao.y,
		inicial : {
			x : mouse.posicao.inicial.x,
			y : mouse.posicao.inicial.y
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


/*
*	Controla o tamanho da reta que ser� desenhada
*	For�a a reta a n�o passar dos limites da tela de desenho
*/
function controlaReta(){
	var cursor = {
		x : mouse.posicao.x,
		y : mouse.posicao.y,
		inicial : {
			x : mouse.posicao.inicial.x,
			y : mouse.posicao.inicial.y
		}
	}
	var reta = [["M",cursor.inicial.x,cursor.inicial.y],["L",cursor.x,cursor.y]];
	reta_preview.attr({path : reta});	
	reta_preview.show();
}

/*
*	Chamada pelas imagens da classe traco
*	Modifica a largura da linha da ferramenta utilizada (m�o livre, borracha, reta, elipse, ret�ngulo)
*/
function selecionaLargura(largura){
  linha.largura = largura;
  borracha.largura = largura + 2;
}


/*
*	Chama as fun��es correspondentes a cada tipo de ferramenta
*/
function controleDeFerramentas(){
	if (mouse.ativo){
		guia.hide();

		switch(mouse.ferramenta){
			case 1:				// l�pis
				coletaPontos();
				break;
			case 3:				// ret�ngulo vazio
			case 10:				// ret�ngulo cheio
				controlaRetangulo();
				break;
			case 4:				// elipse vazia
			case 11:				// elipse cheia
				controlaElipse();
				break;
			case 5:				// reta
				controlaReta();
				break;
			case 6:				// carimbo
				controlaCarimbo();
				break;
			case 8:				// borracha
				coletaPontos();
//				copiaImagem();
				break;
			case 9:
//				preview_colaImagem();
				
				break;
		}
	}
}

/*
*	Carrega a imagem no canvas, caso ela j� exista
*
*/
function carregaImagemInicial(){
	contexto.drawImage(imagem_inicial, 0, 0, telaWidth, telaHeight);
}

/*
*	Fecha a janela de escolha de carimbos "div_escolhe_carimbos"
*
*/
function fechaEscolhaDeCarimbos(){
	$("#fundo_escolhe_carimbos").css("display","none");
	document.getElementById('div_escolhe_carimbos').style.display = 'none';
}

/*
*	C�digo executado assim que a p�gina for carregada
*
*/
jQuery(document).ready(function(){
	if (document.getElementById("tela_svg")){	// verifica se o svg existe
		tela_svg = Raphael("tela_svg", telaWidth,telaHeight);		// prepara o svg para os controles
		img = document.getElementById("carimbo1");
		canvas = document.getElementById('tela_canvas');
		contexto = canvas.getContext("2d");

		document.getElementById('tela_canvas').width = telaWidth;	// ajusta o tamanho do canvas
		document.getElementById('tela_canvas').height = telaHeight;	//

		contexto.fillStyle = "rgba(255, 255, 255, 1)";			// pinta o canvas de branco
		contexto.fillRect (0, 0, telaWidth,telaHeight);			//

		retangulo_preview = tela_svg.rect(0,0,0,0).hide();			// cria os elementos SVG que servir�o de preview
		reta_preview = tela_svg.path(["M",0,0,0,0]).hide();			//
		elipse_preview = tela_svg.ellipse(0,0,0,0).hide();			//
		carimbo_preview = tela_svg.image(img.src, 0,0,0,0).hide();	//
		imagem_preview = tela_svg.image(img.src, 0,0,0,0).hide();	//
		guia = tela_svg.rect(0,0,0,0).attr({stroke: "#CCC", "stroke-width": 2, "stroke-dasharray": "."}).hide(); // cria o elemento SVG que servir� de guia do carimbo

		imagem_inicial = document.getElementById('imagem_fonte');	// se o desenho j� existir, ele � carregado de imagem_fonte (um elemento img do html)
		imagem_inicial.onload = carregaImagemInicial;				//
	}

	atualiza('ajusta()');	// gambiarra do planeta, feita h� muito tempo pelo Giovani
						// faz alguns ajustes nas posi��es de divs e etc. EU ACHO :D
	
	var cola_inicio,cola_move = function () {
		// nada ainda
	}
	var cola_fim = function () {
		colaImagemNoCanvas();
		imagem_preview.hide();
	}

// Se algum carimbo for clicado
	$(".classe_carimbo").click(function(){
		fechaEscolhaDeCarimbos();		// fecha a janela de carimbos
		carimbo = this;				// seleciona o carimbo
		mouse.carimbo = this;		// seleciona o carimbo
	});

// Destaca um carimbo, caso o mouse seja passado por cima dele
	$(".classe_carimbo").hover(
		function(){
			$(this).css("border","2px solid silver");
	},  function(){
			$(this).css("border","2px solid white");
	});

// Guarda na vari�vel global mouse a informa��o de que bot�o esquerdo do mouse est� pressionado
	$(document).mousedown(function(e){
		mouse.status.pressionado = true;
	});

// Bot�o esquerdo do mouse foi solto, ent�o algumas a��es podem ocorrer
	$(document).mouseup(function(e){
		mouse.status.pressionado = false;			// atualiza status do bot�o esquerdo do mouse
		clearInterval(coleta_pontos);				// p�ra a coleta de pontos
		if (mouse.ativo){
			switch(mouse.ferramenta){
				case 8:						// BORRACHA
				case 1:						// L�PIS
	console.log("*MOUSEUP*");
					coletaPontos();				// coleta o �ltimo ponto (ponto onde o bot�o do mouse foi solto)
					pathSVGparaCanvas(contexto);	// transforma o path (SVG) criado em um desenho no canvas
					mouse.borracha = false;		// deseleciona a borracha
					limpa_mao_livre();
	console.log("*FIM DO MOUSEUP*");
//					mao_livre_preview.hide();		// esconde o path que foi utilizado no preview
//					mao_livre_preview.remove();	//
					break;
				case 3:						// RET�NGULO VAZIO
				case 10:						// RET�NGULO CHEIO
					retangulo_preview.hide();		// esconde o preview do ret�ngulo
					recSVGparaCanvas(contexto);	// desenha o SVG no canvas
					retangulo_preview.remove();	//
					break;
				case 4:						// ELIPSE VAZIA
				case 11:						// ELIPSE CHEIA
					elipse_preview.hide();		// esconde o preview da elipse
					ellipseSVGparaCanvas(contexto);// desenha o SVG no canvas
					elipse_preview.hide();
					break;
				case 5:						// RETA
					reta_preview.hide();			// esconde o preview da reta
					lineSVGparaCanvas(contexto);	// desenha o SVG no canvas
					reta_preview.remove();
					break;
				case 6:						// CARIMBO
					carimbo_preview.hide();		// esconde o preview do carimbo
					guia.hide();				// esconde o guia do carimbo
					imageSVGparaCanvas(contexto);	// desenha o carimbo no canvas
					carimbo_preview.remove();
					break;
			}
			mouse.ativo = false;
		}
	});

// Eventos de click que s� podem ocorrer dentro da tela de desenho
	$("#tela_svg").click(function(e){
		switch(mouse.ferramenta){
			case 9:			// TEXTO
				coletaPontos();	// coleta a posi��o atual do mouse
				posTexto();	// posiciona a janela para a edi��o do texto
			break;
		}
	});

/*
* Necess�rio para capturar o ponto onde o mouse entra na tela de desenho,
* se a pessoa estiver no meio de um desenho com o l�pis, ou borracha
*/
	$("#tela_svg").mouseenter(function(e){
		if (mouse.status.pressionado){
			if ((mouse.ferramenta == 1) || (mouse.ferramenta == 8)){
				lista_pontos = Array();
				path = Array();
				mouse.posicao.inicial.x = mouse.posicao.x;
				mouse.posicao.inicial.y = mouse.posicao.y;
				coleta_pontos = setInterval("controleDeFerramentas()",INTERVALO_PARA_PONTOS);
				mouse.ativo = true;
			}
		}
	});

/*
* Necess�rio para capturar o ponto onde o mouse sai na tela de desenho,
* se a pessoa estiver no meio de um desenho com o l�pis, ou borracha
*/
	$("#tela_svg").mouseout(function(e){
		if (mouse.ativo){
			if( e.toElement ) {				
				current_mouse_target 			 = e.toElement;
			} else if( e.relatedTarget ) {				
				current_mouse_target 			 = e.relatedTarget;
			}	
			if ((current_mouse_target.innerHTML != undefined) && (current_mouse_target.innerHTML) && (current_mouse_target.innerHTML != "")){
				switch(mouse.ferramenta){
					case 8:
					case 1:
	console.log("*MOUSEOUT*");
						clearInterval(coleta_pontos);
						coletaPontos();
						pathSVGparaCanvas(contexto);
//						mouse.borracha = false;		// deseleciona a borracha
//						mao_livre_preview.hide();		// esconde o path que foi utilizado no preview
//						mao_livre_preview.remove();
						limpa_mao_livre();
						mouse.ativo = false;
	console.log("*FIM MOUSEOUT*");
						break;
				}
			}
		}
	});

/*
*	Evento de mousedown na tela de desenho
*	Todas as ferramentas utilizam este evento
*/
	$("#tela_svg").mousedown(function(e){
		mouse.status.pressionado = true;
		mouse.ativo = true;
		if (mouse.ferramenta == 2){					// PREENCHIMENTO
			pinta(preenchimento);					// chama o algoritmo de preenchimento
		}else{
			lista_pontos = Array();					// novos arrays para serem trabalhados pelo l�pis/borracha
			path = Array();							//
			mouse.borracha = (mouse.ferramenta == 8);	// atualiza informa��es da ferramenta 
			mouse.posicao.inicial.x = mouse.posicao.x;	//
			mouse.posicao.inicial.y = mouse.posicao.y;	//
			
			retangulo_preview.attr({stroke: linha.cor, "stroke-width": linha.largura, "stroke-linecap": "rounded"});	// ajusta os previews
			elipse_preview.attr({stroke: linha.cor, "stroke-width": linha.largura, "stroke-linecap": "rounded"});		//
			reta_preview.attr({stroke: linha.cor, "stroke-width": linha.largura, "stroke-linecap": "rounded"});		//
			carimbo_preview.attr({src: mouse.carimbo.src});												//

			coleta_pontos = setInterval("controleDeFerramentas()",INTERVALO_PARA_PONTOS);	// liga a coleta de pontos
		}
	}); 

// Atualiza a posi��o guardada do mouse
	$(document).mousemove(function(e){
		posicaoXdaTela = (document.getElementById("conteudo").offsetLeft + document.getElementById("tela_div").offsetLeft + document.getElementById("geral").offsetLeft);
		posicaoYdaTela = (document.getElementById("tela_div").offsetTop + document.getElementById("geral").offsetTop + document.getElementById("conteudo_meio").offsetTop + document.getElementById("conteudo").offsetTop);
		mouse.posicao.x = e.pageX - posicaoXdaTela;
		mouse.posicao.y = e.pageY - posicaoYdaTela;
	}); 

// Evento da paleta de cores
	$(".amostra_cor2").click(function(e){
		linha.cor = this.style.backgroundColor;
		cor = new RGBColor(linha.cor);
		preenchimento.r = cor.r;
		preenchimento.g = cor.g;
		preenchimento.b = cor.b;
	});

/*
*=====================================================================================
*	Eventos da edi��o de texto
*
*/
	$(".editor_mbtns").click(function(){
		if (this.id == "editor_maior") texto.tamanho++;
		if (this.id == "editor_menor") texto.tamanho--;
		if (texto.tamanho > 20) texto.tamanho = 20;
		if (texto.tamanho < 6) texto.tamanho = 6;
		document.getElementById('texto_b').style.fontSize= texto.tamanho+"pt";
		document.getElementById('texto_b').focus();
	});

	$(".sel_fonte").click(function(){
		if (this.id == "fnt_arial") texto.fonte = "Arial";
		if (this.id == "fnt_sans") texto.fonte = "Sans-Serif";
		if (this.id == "fnt_tahoma") texto.fonte = "Tahoma";
		if (this.id == "fnt_times") texto.fonte = "Times New Roman";
		if (this.id == "fnt_verdana") texto.fonte = "Verdana";
		document.getElementById('texto_b').style.fontFamily = texto.fonte;
		$("#selFontes").fadeOut(300);
		document.getElementById('texto_b').focus();
	});

	$(".sel_fonte").mouseover(function(){
		this.style.backgroundColor="#111180";
	});
	
	$(".sel_fonte").mouseout(function(){
		this.style.backgroundColor="transparent";
	});
	$("#edcolor").click(function(){
		recolhe();
		if (document.getElementById("tabela_cores").style.display=="none")
		{
			this.src="imagens/"+this.id+"_press.png";
			$("#tabela_cores").fadeIn(300);
		}else{
			this.src="imagens/"+this.id+".png";
			$("#tabela_cores").fadeOut(300);
		}
	});

	$("#edfonte").click(function(){
		recolhe();
		if (document.getElementById("selFontes").style.display=="none")
		{
			this.src="imagens/"+this.id+"_press.png";
			$("#selFontes").fadeIn(300);
		}else{
			this.src="imagens/"+this.id+".png";
			$("#selFontes").fadeOut(300);
		}
	});	

	$("#edbold").click(function(){
		recolhe();
		if (!texto.negrito){
			this.src="imagens/"+this.id+"_press.png";
			texto.negrito = true;
			document.getElementById('texto_b').style.fontWeight = "bold";
		}else{
			this.src="imagens/"+this.id+".png";
			texto.negrito = false;
			document.getElementById('texto_b').style.fontWeight = "normal";
		}
		document.getElementById('texto_b').focus();
	});
	
	$("#editalic").click(function(){
		recolhe();
		if (!texto.italico){
			this.src="imagens/"+this.id+"_press.png";
			texto.italico = true;
			document.getElementById('texto_b').style.fontStyle = "italic";
		}else{
			this.src="imagens/"+this.id+".png";
			texto.italico = false;
			document.getElementById('texto_b').style.fontStyle = "normal";
		}
		document.getElementById('texto_b').focus();
	});	
	
	$("#edconfirm").click(function(){
		textoCanvas(contexto);
		some_editor();	
	});	
	
	$("#edcancel").click(function(){
		document.getElementById('texto_b').value = "";
		some_editor();	
	});	

	$(".amostra_cor").click(function(){
		texto.cor = this.style.backgroundColor;
		document.getElementById('texto_b').style.color = texto.cor;
		$("#tabela_cores").fadeOut(300);
		document.getElementById('texto_b').focus();
	});	

/*
*
*	Fim dos eventos da edi��o de texto
*=====================================================================================
*/

	selecionaFerramenta(1);	// inicia com o l�pis selecionado
})

/*
*	Ajusta a janela de edi��o de texto onde o mouse est� posicionado
*/
function posTexto(){

	// c�lculo da posi��o inicial da tela de desenho
	posicaoXdaTela = (document.getElementById("conteudo").offsetLeft + document.getElementById("tela_div").offsetLeft + document.getElementById("geral").offsetLeft);
	posicaoYdaTela = (document.getElementById("tela_div").offsetTop + document.getElementById("geral").offsetTop + document.getElementById("conteudo_meio").offsetTop + document.getElementById("conteudo").offsetTop);

	// posi��o da tela de desenho + o deslocamento do mouse
	x1 = posicaoXdaTela + mouse.posicao.x;
	y1 = posicaoYdaTela + mouse.posicao.y;

	//ajusta os elementos nas devidas posi��es
	document.getElementById('float').style.left = x1 + "px";
	document.getElementById('float').style.top = y1 + "px";
	document.getElementById('editor_tam').style.left = (x1-14) + "px";
	document.getElementById('editor_tam').style.top = y1 + "px";
	
	document.getElementById('editor_barra').style.left = x1 + "px";
	document.getElementById('editor_barra').style.top = (y1-20) + "px";
	document.getElementById('tabela_cores').style.top = (y1-120)+"px";
	document.getElementById('tabela_cores').style.left = (x1+74)+"px";
	document.getElementById('selFontes').style.top = (y1-105)+"px";
	document.getElementById('selFontes').style.left = x1+"px";

	//faz os elementos aparecerem
	$("#editor_barra").fadeIn(200);
	$("#editor_tam").fadeIn(200);

	//ajusta o estilo do textarea, conforme o que est� guardado na vari�vel global texto
	document.getElementById('texto_b').style.fontSize = texto.tamanho+"pt";
	document.getElementById('texto_b').style.fontFamily = texto.fonte;
	document.getElementById('texto_b').style.textDecoration = (texto.sublinhado)?"underline":"none";
	document.getElementById('texto_b').style.fontWeight = (texto.negrito)?"bold":"normal";
	document.getElementById('texto_b').style.fontStyle = (texto.italico)?"italic":"none";
	document.getElementById('texto_b').style.display = 'block';
	document.getElementById('texto_b').style.backgroundColor = 'transparent';
	document.getElementById('texto_b').style.background = 'none';
	document.getElementById('texto_b').focus();
}

// faz o editor de texto desaparecer
function some_editor(){
	caixa=0;
	document.getElementById('texto_b').value = '';
	document.getElementById('texto_b').blur();
	document.getElementById('texto_b').style.display = 'none';
	$("#tabela_cores").fadeOut(200);
	$("#editor_barra").fadeOut(200);
	$("#editor_tam").fadeOut(200);
	$("#selFontes").fadeOut(200);
}

// faz os menus (fonte, tamanho, cores) do editor serem recolhidos (desaparecerem)
function recolhe(){
    document.getElementById("edfonte").src="imagens/edfonte.png";		// ajusta os bot�es para o formato onde eles n�o est�o selecionados
    document.getElementById("edcolor").src="imagens/edcolor.png";

	$("#selFontes").fadeOut(300);
	$("#tabela_cores").fadeOut(300);
}

// Captura o ESC, e ajusta o tamanho do textarea para aumentar de acordo com o texto digitado
function captureKeys (evt) {
	var tamanho;
	var ctexto = document.getElementById('texto_b');
	var keyCode = evt.keyCode ? evt.keyCode :
	evt.charCode ? evt.charCode : evt.which;

	if (keyCode == 27){						// ESC: fecha o editor
			some_editor();
			return false;
	}else{								// Outra tecla: o tamanho do textarea � recalculado
		splt = ctexto.value.split("\n");
		tamanho = ctexto.value.length + 1;

		td_l = (document.getElementById("conteudo").offsetLeft + document.getElementById("tela_div").offsetLeft + document.getElementById("geral").offsetLeft);
		td_w = document.getElementById('tela_div').offsetWidth;
		td_t = (document.getElementById("tela_div").offsetTop + document.getElementById("geral").offsetTop + document.getElementById("conteudo_meio").offsetTop + document.getElementById("conteudo").offsetTop);
		td_h = document.getElementById('tela_div').offsetHeight;

		txt_l = document.getElementById('float').offsetLeft + 1;
		txt_w = (tamanho * 30 + 30);
		txt_t = document.getElementById('float').offsetTop;
		txt_h = (splt.length * 20 + 30);

		if ((txt_l + txt_w) > (td_l + td_w))
			txt_w = (td_l + td_w) - txt_l;

		if ((txt_t + txt_h) > (td_t + td_h))
			txt_h = (td_t + td_h) - txt_t;

		ctexto.style.height = txt_h + "px";
		ctexto.style.width = txt_w + "px";
	}
	return true;
}
