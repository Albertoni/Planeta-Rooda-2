class c_sistema_colisao{
//dados
	
//métodos

/*---------------------------------------------------
*	Guto - 04.02.09
*
*	O objetivo deste arquivo é armazenar todas as funções necessárias para executar o hitTest 
* com matrizes, desenvolvido pelo NUTED e baseado em idéias sobre tiles (google it!) para engines 
* de jogos antigos. Usando este sistema é possivel verificar as colisões de um objeto com o cenário 
* e todos os seus objetos com um único teste. O cenário e as posições de todos os objetos que o constituem
* são gravados em uma matriz. Quando um objeto deve se mover pelo cenário, é realizada uma comparação da
* matriz deste objeto com a sua posição na matriz do cenário. Todos os objetos que se moverem devem ter suas 
* novas posições gravadas na matriz.
*	A explicação é muito sucinta, por isso deve ser acompanhada da interpretação do código e das descrições
* das funções.
*
*	Guto - 16.06.09
*	
*	Acrescentadas funções para nova engine de hitTest. Agora o sistema se aproxima mais das clássicas Tiles.
* Teremos, ao invés de uma matriz do tamanho do cenário (1600x1200), teremos uma matriz das tiles que compôem 
* a cena (32x60). O tamanho destas tiles é baseado na menor dimensão que um objeto pode ter, o que chamamos de 
* unidade de sombra (50x20 pixels). Essa redução da matriz referente ao cenário deve resolver  o problema de
* tempo excessivo de carregamento do software.
*	Primeiramente é testado se o objeto que se locomove irá ocupar alguma tile já ocupada no cenário por outro 
* objeto. Se isso acontecer é realizado um  hit teste entre os dois objetos unicamente.
*	As funções do sistema antigo serão mantidas neste arquivo, além das funções reaproveitadas.
*		
---------------------------------------------------*/

/*---------------------------------------------------
*	Função que recebe um objeto e grava uma matriz com seu conteúdo
---------------------------------------------------*/
public static function objNucleo(posX:Number, posY:Number, obj:Object):Array {
	var nucleo:Array = new Array();
	
	posX = Math.round(posX);
	posY = Math.round(posY);
		
	for (var i = 0; i < obj._height; i++) {
		nucleo[i] = new Array();
		for (var j = 0; j < obj._width; j++) {
			if(obj.hitTest((j + posX), (i + posY), true)) {
				nucleo[i][j] = 1;
			} else {
				nucleo[i][j] = "";
			}
		}
	}		
	
	return nucleo;	
}

/*---------------------------------------------------
*	Função que recebe uma matriz com o núcleo do objeto 
* e grava outra matriz com as referências da recebida
---------------------------------------------------*/
public static function refNucleo(mtrNucleo:Array):Array {
	var refMtrNucleo:Array = new Array();
	
	//Inicilaiza matriz de referências
	refMtrNucleo = [];
	
	for (var i = 0; i < mtrNucleo.length; i++) {
		for (var j = 0; j < mtrNucleo[0].length; j++) {
			if(mtrNucleo[i][j] == 1) {
				refMtrNucleo.push([i,j]);
			}
		}
	}		
	return refMtrNucleo;	
}


/*---------------------------------------------------
*	Função que recebe uma matriz com o núcleo do objeto, traça o seu contorno, 
* grava outra matriz com as referências do seu contorno e a retorna 
---------------------------------------------------*/
public static function refBorda(nucleo:Array):Array {
	var nuclBordaRef:Array = new Array();
	
	//Inicializa matriz de referências e do objeto
	nuclBordaRef = [];
	//Preenche a matriz com as referências de posições dos pixels do contorno do objeto
	for (var i = 0; i < nucleo.length; i++) {
		for (var j = 0; j < nucleo[0].length; j++) {			
			if (nucleo[i][j] == 1) {				
				if ((i == 0) or ((i + 1) > nucleo.length)){
					nuclBordaRef.push([i,j]);
				} else {
					if ((j == 0) or ((j + 1) > nucleo[i].length)){
						nuclBordaRef.push([i,j]);
					} else {
						if((nucleo[i - 1][j] != 1) or  (nucleo[i][j - 1] != 1) or  (nucleo[i + 1][j] != 1) or  (nucleo[i][j + 1] != 1)) {
							nuclBordaRef.push([i,j]);
						}
					}
				}
			}
		}
	}		
	return nuclBordaRef;	
}

/*--------------------------------------------------
*	Cria matriz da cena incializada com zeros
---------------------------------------------------*/
public static function criaMtrCena(larg:Number, alt:Number):Array {
	var mtr:Array = new Array();
	
	for (var i = 0; i < alt; i++) {
		mtr[i] = new Array();
		for (var j = 0; j < larg; j++) {
			mtr[i][j] = 0;
		}
	}		
	return mtr;
}

/*---------------------------------------------------
*	Adiciona matriz de referências do objeto na matriz da cena
---------------------------------------------------*/
public static function juntaMtr(posX:Number, posY:Number, mtrObj:Array, mtrCena:Array, tipo:Number):Void {	
	
	posX = Math.round(posX);
	posY = Math.round(posY);
		
	for (var i = 0; i < mtrObj.length; i++) {
		//Verifica se varredura não escede os limites da matriz da cena
		if(((mtrObj[i][0] + posY) >= 0) and ((mtrObj[i][0] + posY) < mtrCena.length) and
		   ((mtrObj[i][1] + posX) >= 0) and ((mtrObj[i][1] + posX) < mtrCena[0].length)) {	
			mtrCena[mtrObj[i][0] + posY][mtrObj[i][1] + posX] = tipo;			
		}
	}
	
}

/*---------------------------------------------------
*	Retira matriz de referências do objeto na matriz da cena
---------------------------------------------------*/
public static function apagaMtr(posX:Number, posY:Number, mtrObj:Array, mtrCena:Array):Void {
	
	posX = Math.round(posX);
	posY = Math.round(posY);
		
	for (var i = 0; i < mtrObj.length; i++) {
		//Verifica se varredura não escede os limites da matriz da cena
		if(((mtrObj[i][0] + posY) >= 0) and ((mtrObj[i][0] + posY) < mtrCena.length) and
		   ((mtrObj[i][1] + posX) >= 0) and ((mtrObj[i][1] + posX) < mtrCena[0].length)) {	
			mtrCena[mtrObj[i][0] + posY][mtrObj[i][1] + posX] = 0;
		}
	}
}

/*---------------------------------------------------
*	Função que executo o hitTest de um objeto em todo o cenário através 
* da comparacção da matriz de referência deste objeto com a matriz de objetos do cenário
*
* OBS: Se houver algum elemento no cenário muito menor que o objeto testado, e for testado apenas
* o contorno do objeto, quando este elemento estiver "dentro" do objeto, o hit teste não acusará 
* colisão. Isso não é um problema grave, pois o teste pelo contorno é muito mais rápido e no momento
* que o contorno tocar o elemento, haverá colisão  
---------------------------------------------------*/
public static function mtrHitTest(posX:Number, posY:Number, mtrObj:Array, mtrCena:Array):Number {
	
	posX = Math.round(posX);
	posY = Math.round(posY);	
	
	for (var i = 0; i < mtrObj.length; i++) {	
		//Verifica se varredura não escede os limites da matriz da cena
		if(((mtrObj[i][0] + posY) >= 0) and ((mtrObj[i][0] + posY) < mtrCena.length) and
		   ((mtrObj[i][1] + posX) >= 0) and ((mtrObj[i][1] + posX) < mtrCena[mtrObj[i][0]].length)) {
			if(mtrCena[mtrObj[i][0] + posY][mtrObj[i][1] + posX] != 0){		
				return mtrCena[mtrObj[i][0] + posY][mtrObj[i][1] + posX];
			}
		}
	}
	return 0;	
}

/*---------------------------------------------------
*	Debug para testar a matriz da cena nas posições de algum objeto espefífico
* OBS: Utilizar apenas com objetos pequenos!!!!!
---------------------------------------------------*/
public static function debugObjCena(posX:Number, posY:Number, obj:Object, mtrCena:Array):Void {	
	var textStd:TextFormat = new TextFormat();	
	
	posX = Math.round(posX);
	posY = Math.round(posY);
	
	//Posiciona e inicializa caixa de texto
	/*debug._x = 0;
	debug._y = 0;
	debug.text = "";	*/
	
	//Imprime matriz de referências
	for (var i = 0; i < obj._height; i++) {
		for (var j = 0; j < obj._width; j++) {
			//debug.text += mtrCena[i + posY][j + posX];								
		}
		//debug.text += "\n ";
	}
	textStd.align = "left";      
	textStd.bold = true;         
	//debug.setTextFormat(textStd);
}

/*---------------------------------------------------
*	Debug para testar a matriz de referências
* OBS: Utilizar apenas com objetos pequenos!!!!!
---------------------------------------------------*/
public static function debugRef(obj:Object, objBorda:Array):Void {	
	var textStd:TextFormat = new TextFormat();
	var ind:Number = 0;	
	
	//Posiciona e inicializa caixa de texto
	/*debug._x = 0;
	debug._y = 0;
	debug.text = "";	*/
	
	//Imprime matriz de referências
	for (var i = 0; i < obj._height; i++) {
		for (var j = 0; j < obj._width; j++) {
			if ((i == objBorda[ind][0]) and (j == objBorda[ind][1])){
				//debug.text += 1 + " ";
				ind++;
			} else {
				//debug.text += "   ";
			}
		}
		//debug.text += "\n ";
	}
	textStd.align = "left";      
	textStd.bold = true;         
	//debug.setTextFormat(textStd);
}

/*---------------------------------------------------
*	Preenchimento de array com as tiles que o objeto ocupa no cenário
---------------------------------------------------*/
public static function verifTilesObj(posX:Number, posY:Number, usX:Number, usY:Number, obj:Object):Array {	
	var qtdTilesX:Number = 0;
	var qtdTilesY:Number = 0;
	var posTilesX:Number = 0;
	var posTilesY:Number = 0;
	var arrayTiles:Array = new Array();
	
	qtdTilesX = Math.ceil((obj._width + posX)/usX) - Math.floor(posX/usX);			//Diferença entre a tile final e a tile incial, em X e Y. Ceil pega o valor inteiro superior referente a divisão e Round pega o valor inteiro inferior
	qtdTilesY = Math.ceil((obj._height + posY)/usY) - Math.floor(posY/usY);
	
	posTilesX = Math.floor(posX/usX);		//Tile inicial que o objeto ocupa para referência
	posTilesY = Math.floor(posY/usY);
		
	for (var i = 0; i < qtdTilesY; i++) {
		for (var j = 0; j < qtdTilesX; j++) {
			arrayTiles.push([i + posTilesY, j + posTilesX]);	//Armazena as tiles ocupadas do cenário no array
		}
	}
	return arrayTiles;
}

/*---------------------------------------------------
*	Compara mudanças nos arrays
*	
*	Obs: Como as posições das tiles no array nunca mudam, pois são registrdas sempre na mesma ordem,
*	um laço for é o suficiente.
---------------------------------------------------*/
public static function verArrayTile(arrayTile:Array, arrayTileRef:Array):Number {
	for (var i = 0; i < arrayTile.length; i++) {
		if((arrayTile[i][0] != arrayTileRef[i][0]) or (arrayTile[i][1] != arrayTileRef[i][1])){
			return 1;
		}
	}
	return 0;
}

/*---------------------------------------------------
*	Adiciona um objeto na matriz de tiles da cena
---------------------------------------------------*/
public static function insereObjTile(posX:Number, posY:Number, usX:Number, usY:Number, obj:Object, mtrTile:Array, tipo:Number):Void {	
	var qtdTilesX:Number = 0;
	var qtdTilesY:Number = 0;
	var posTilesX:Number = 0;
	var posTilesY:Number = 0;
	
	qtdTilesX = Math.ceil((Math.floor(obj._width) + posX)/usX) - Math.floor(posX/usX);		//Diferença entre a tile final e a tile incial, em X e Y. Ceil pega o valor inteiro superior referente a divisão e Round pega o valor inteiro inferior
	qtdTilesY = Math.ceil((Math.floor(obj._height) + posY)/usY) - Math.floor(posY/usY);
	
	posTilesX = Math.floor(posX/usX);		//Tile inicial que o objeto ocupa para referência
	posTilesY = Math.floor(posY/usY);
	
	for (var i = 0; i < qtdTilesY; i++) {
		for (var j = 0; j < qtdTilesX; j++) {
			//Verifica se varredura não excede os limites da matriz da cena
			if(((i + posTilesY) < mtrTile.length) and
			  ((j + posTilesX) < mtrTile[0].length)) {	
				mtrTile[i + posTilesY][j + posTilesX] = tipo;
			}
		}
	}
}

/*---------------------------------------------------
*	Retira um objeto da matriz de tiles da cena
---------------------------------------------------*/
public static function limpaObjTile(posX:Number, posY:Number, usX:Number, usY:Number, obj:Object, mtrTile:Array):Void {	
	var qtdTilesX:Number = 0;
	var qtdTilesY:Number = 0;
	var posTilesX:Number = 0;
	var posTilesY:Number = 0;
	
	qtdTilesX = Math.ceil((obj._width + posX)/usX) - Math.floor(posX/usX);		//Diferença entre a tile final e a tile incial, em X e Y. Ceil pega o valor inteiro superior referente a divisão e Round pega o valor inteiro inferior
	qtdTilesY = Math.ceil((obj._height + posY)/usY) - Math.floor(posY/usY);
	
	posTilesX = Math.floor(posX/usX);		//Tile inicial que o objeto ocupa para referência
	posTilesY = Math.floor(posY/usY);
	
	
	for (var i = 0; i < qtdTilesY; i++) {
		for (var j = 0; j < qtdTilesX; j++) {
			//Verifica se varredura não excede os limites da matriz da cena
			if(((i + posTilesY) < mtrTile.length) and
			  ((j + posTilesX) < mtrTile[0].length)) {	
				mtrTile[i + posTilesY][j + posTilesX] = 0;
			}
		}
	}
}

/*---------------------------------------------------
*	Adiciona um op na matriz de tiles da cena - Guto - 21.07.09
---------------------------------------------------*/
public static function insereOpTile(posX:Number, posY:Number, usX:Number, usY:Number, obj:Object, mtrTile:Array, tipo:Number):Void {	
	var qtdTilesX:Number = 0;
	var qtdTilesY:Number = 0;
	var posTilesX:Number = 0;
	var posTilesY:Number = 0;
	
	qtdTilesX = Math.ceil((Math.floor(obj._width) + posX)/usX) - Math.floor(posX/usX);		//Diferença entre a tile final e a tile incial, em X e Y. Ceil pega o valor inteiro superior referente a divisão e Round pega o valor inteiro inferior
	qtdTilesY = Math.ceil((Math.floor(obj._height) + posY)/usY) - Math.floor(posY/usY);
	
	posTilesX = Math.floor(posX/usX);		//Tile inicial que o objeto ocupa para referência
	posTilesY = Math.floor(posY/usY);
	
	
	for (var i = 0; i < qtdTilesY; i++) {
		for (var j = 0; j < qtdTilesX; j++) {			
			if(((i + posTilesY) < mtrTile.length) and				//Verifica se varredura não excede os limites da matriz da cena
			  ((j + posTilesX) < mtrTile[0].length)) {
				if(mtrTile[i + posTilesY][j + posTilesX] == 0){  	//Verifica se a tile a ser escrita já não está sendo ocupada por outro objeto - Guto - 21.07.09
					mtrTile[i + posTilesY][j + posTilesX] = tipo;
				}
			}
		}
	}
}

/*---------------------------------------------------
	Retira um op da matriz de tiles da cena - Guto - 21.07.09
---------------------------------------------------*/
public static function limpaOpTile(posX:Number, posY:Number, usX:Number, usY:Number, obj:Object, mtrTile:Array, tipo:Number):Void {	
	var qtdTilesX:Number = 0;
	var qtdTilesY:Number = 0;
	var posTilesX:Number = 0;
	var posTilesY:Number = 0;
	
	qtdTilesX = Math.ceil((obj._width + posX)/usX) - Math.floor(posX/usX);		//Diferença entre a tile final e a tile incial, em X e Y. Ceil pega o valor inteiro superior referente a divisão e Round pega o valor inteiro inferior
	qtdTilesY = Math.ceil((obj._height + posY)/usY) - Math.floor(posY/usY);
	
	posTilesX = Math.floor(posX/usX);		//Tile inicial que o objeto ocupa para referência
	posTilesY = Math.floor(posY/usY);
	
	
	for (var i = 0; i < qtdTilesY; i++) {
		for (var j = 0; j < qtdTilesX; j++) {			
			if(((i + posTilesY) < mtrTile.length) and					//Verifica se varredura não excede os limites da matriz da cena
			  ((j + posTilesX) < mtrTile[0].length)) {
				if(mtrTile[i + posTilesY][j + posTilesX] == tipo){	  	//Verifica se a tile a ser apagada não está sendo ocupada por outro objeto - Guto - 21.07.09
					mtrTile[i + posTilesY][j + posTilesX] = 0;
				}
				
			}
		}
	}
}

/*---------------------------------------------------
* 	Função que testa se as tiles onde o objeto se encontra estão ocupadas por algum outro objeto 
* na matriz de tiles do cenário.
---------------------------------------------------*/
public static function tileHitTest(objTile:Array, mtrTile:Array):Array {
	var tilesOcup:Array = new Array();
	var pertence:Boolean = false;
	
	for (var i = 0; i < objTile.length; i++) {	
		//Verifica se varredura não excede os limites da matriz da cena
		if(((objTile[i][0]) >= 0) and ((objTile[i][0]) < mtrTile.length) and
		   ((objTile[i][1]) >= 0) and ((objTile[i][1]) < mtrTile[0].length)) {			
			if (tilesOcup.length > 0) {
				for (var j = 0; j < tilesOcup.length; j++) {
					if(tilesOcup[j] == mtrTile[objTile[i][0]][objTile[i][1]]){
						pertence = true;
					}
				}
				if(pertence == false){
					tilesOcup.push(mtrTile[objTile[i][0]][objTile[i][1]]);
				}
				pertence = false;
			} else {						
				tilesOcup.push(mtrTile[objTile[i][0]][objTile[i][1]]);
			}			
		}
	}
	return tilesOcup;	
}

/*---------------------------------------------------
*	Função utilizada quando verifica-se que as tiles onde o objeto se encontra estão ocupadas
* por outros objetos. Com ela é feito um hitTest da matriz com as referências do objeto e a matriz do núcleo
* do objeto que ocupa a tile do cenário em questão.
*
*	OBS: Se houver algum elemento no cenário muito menor que o objeto testado, e for testado apenas
* o contorno do objeto, quando este elemento estiver "dentro" do objeto, o hit teste não acusará 
* colisão. Isso não é um problema grave, pois o teste pelo contorno é muito mais rápido e no momento
* que o contorno tocar o elemento, haverá colisão  
---------------------------------------------------*/
public static function objHitTest(difX:Number, difY:Number, mtrObjMov:Array, mtrObjEsta:Array):Number {
	
	difX = Math.round(difX);	//Tranforma os valores em inteiros para podermos varrer as matrizes com essas referências
	difY = Math.round(difY);
	
	for (var i = 0; i < mtrObjMov.length; i++) {	
		//Verifica se varredura não excede os limites da matriz da cena
		if(((mtrObjMov[i][0] + difY) >= 0) and ((mtrObjMov[i][0] + difY) < mtrObjEsta.length) and
		   ((mtrObjMov[i][1] + difX) >= 0) and ((mtrObjMov[i][1] + difX) < mtrObjEsta[0].length)) {
			if(mtrObjEsta[mtrObjMov[i][0] + difY][mtrObjMov[i][1] + difX] == 1){		//Se o elemento da matriz for igual a 1 significa que o objeto ocupa essa posição na caixa que delimita seu tamanho
				return 1;
			}
		}
	}
	return 0;	
}




}