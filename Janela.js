/**
*	Este arquivo destina-se à implementação da classe Janela.
*
*	@param String _texto		O texto que será exibido por esta janela.
*/
function Janela(/*String*/ _texto){
	//Texto exibido por esta janela.
	/*String*/ this.texto = _texto;
	//Define se a janela está visível ou não.
	/*Booleano*/ this.visivel = true;
	//-------------------------------------------------------------------------------------------------------------------------
	//											CONSTANTES ESTÁTICAS
	//-------------------------------------------------------------------------------------------------------------------------
	/*float*/ this.COMPRIMENTO = 348;
	/*float*/ this.ALTURA = 164;
}

//-------------------------------------------------------------------------------------------------------------------------
//											MÉTODOS PÚBLICOS
//-------------------------------------------------------------------------------------------------------------------------

/**
* @param 	int 	_x		Posição x desta janela em relação a seu 'container'.
* @param 	int 	_y		Posição y desta janela em relação a seu 'container'.
* @return 	String			Esta janela convertida para HTML.
*/
Janela.prototype.converterParaHtml = /*String*/ function(/*int*/ _x, /*int*/ _y){
	return 	"<div class=\"janela\" style=\"display:"+(this.visivel? "block" : "none")+"\">"
			+	"<p class=\"texto_janela\">"+this.texto+"</p>"
			+"</div>";
}

/**
*	Muda a visibilidade da janela, tornando-a visível ou escondendo-a.
*
*	@param Booleano _visibilidade	True para mostrar e false para esconder.
*/
Janela.prototype.mudarVisibilidade = /*void*/ function(/*Booleano*/ _visibilidade){
	this.visivel = _visibilidade;
}
