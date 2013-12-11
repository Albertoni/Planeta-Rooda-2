/**
*	Este arquivo destina-se à implementação da classe linkPlaneta.
*
*	@param String _idTerreno						Id do terreno que será acessado quando este link for clicado.
*	@param linkPlaneta.TiposPlanetas _tipoPlaneta 	O tipo do planeta, que define a aparência deste link.
*	@param String _texto							Texto exibido abaixo da imagem do link. O nome do planeta/turma.
*/
function LinkPlaneta(/*String*/ _idTerreno, /*linkPlaneta.TiposPlanetas*/ _tipoPlaneta, /*String*/ _texto){
	//Id do terreno que será acessado quando este link for clicado.
	/*String*/ 						this.idTerreno = _idTerreno;
	//Tipo deste planeta, que define a aparência do link.
	/*linkPlaneta.TiposPlanetas*/ 	this.tipo = _tipoPlaneta;
	//Define se a janela está visível ou não.
	/*Booleano*/ 					this.visivel = false;
	//Texto exibido por este link.
	/*String*/ 						this.texto = _texto;
	
	
}

//-------------------------------------------------------------------------------------------------------------------------
//											CONSTANTES ESTÁTICAS
//-------------------------------------------------------------------------------------------------------------------------

/*float*/ LinkPlaneta.COMPRIMENTO = 192;
/*float*/ LinkPlaneta.ALTURA = 184;

//Os tipos de planetas definem somente a aparência do link.
LinkPlaneta.TiposPlanetas = {
	GRAMA 		: 0,
	NEVE 		: 1,
	URBANO 		: 2,
	LAVA 		: 3
};

//-------------------------------------------------------------------------------------------------------------------------
//											MÉTODOS PÚBLICOS
//-------------------------------------------------------------------------------------------------------------------------

/**
* @param 	int 	_x		Posição x desta janela em relação a seu 'container'.
* @param 	int 	_y		Posição y desta janela em relação a seu 'container'.
* @return 	String			Esta janela convertida para HTML.
*/
LinkPlaneta.prototype.converterParaHtml = /*String*/ function(/*int*/ _x, /*int*/ _y){
	return 	"<div style=\"position:relative; left:"+_x+"px; bottom:"+_y+"px; width:"+LinkPlaneta.COMPRIMENTO+"px; height:"+LinkPlaneta.ALTURA+"px; "
			+			" background-image:url('"+this.buscaLinkImagem()+"'); cursor:pointer;'\""
			+			" onclick='redirecionarParaDesenvolvimento("+this.idTerreno+")'>"
			+	"<p style=\"color:#ffffff; position:relative; top:"+LinkPlaneta.ALTURA+"px; font-weight:bold; text-align:center; \">"+this.texto+"</p>"
			+"</div>";
}

/**
* @return Link da imagem deste planeta.
*/
LinkPlaneta.prototype.buscaLinkImagem = /*void*/function(/*void*/){
	diretorioImagens = "images/tela_inicial/";
	
	switch(this.tipo){
		case LinkPlaneta.TiposPlanetas.GRAMA:	return diretorioImagens + "planetagrama.png";
			break;
		case LinkPlaneta.TiposPlanetas.NEVE:	return diretorioImagens + "planetagelo.png";
			break;
		case LinkPlaneta.TiposPlanetas.URBANO:	return diretorioImagens + "planetaurbano.png";
			break;
		case LinkPlaneta.TiposPlanetas.LAVA:	return diretorioImagens + "planetalava.png";
			break;
		default:								return diretorioImagens + "planetagrama.png";
	}
}






