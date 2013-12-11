/**
*	Este arquivo destina-se � implementa��o da classe Janela.
*
*	@param String _texto		O texto que ser� exibido por esta janela.
*/
function Janela(/*String*/ _texto){
	//Texto exibido por esta janela.
	/*String*/ this.texto = _texto;
	//Define se a janela est� vis�vel ou n�o.
	/*Booleano*/ this.visivel = true;
	//-------------------------------------------------------------------------------------------------------------------------
	//											CONSTANTES EST�TICAS
	//-------------------------------------------------------------------------------------------------------------------------
	/*float*/ this.COMPRIMENTO = 348;
	/*float*/ this.ALTURA = 164;
}

//-------------------------------------------------------------------------------------------------------------------------
//											M�TODOS P�BLICOS
//-------------------------------------------------------------------------------------------------------------------------

/**
* @param 	int 	_x		Posi��o x desta janela em rela��o a seu 'container'.
* @param 	int 	_y		Posi��o y desta janela em rela��o a seu 'container'.
* @return 	String			Esta janela convertida para HTML.
*/
Janela.prototype.converterParaHtml = /*String*/ function(/*int*/ _x, /*int*/ _y){
	return 	"<div class=\"janela\" style=\"display:"+(this.visivel? "block" : "none")+"\">"
			+	"<p class=\"texto_janela\">"+this.texto+"</p>"
			+"</div>";
}

/**
*	Muda a visibilidade da janela, tornando-a vis�vel ou escondendo-a.
*
*	@param Booleano _visibilidade	True para mostrar e false para esconder.
*/
Janela.prototype.mudarVisibilidade = /*void*/ function(/*Booleano*/ _visibilidade){
	this.visivel = _visibilidade;
}
