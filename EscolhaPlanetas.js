/**
*	Este arquivo destina-se à implementação da classe EscolhaPlanetas.
*/
function EscolhaPlanetas(/*void*/){
	//Todos os links que estão neste menu.
	/*Array<LinkPlaneta>*/	this.linksPlanetas = new Array();
	//O primeiro link que é exibido. A posição deste no array.
	/*int*/ this.indiceArrayPrimeiroLinkExibido = 0;
	
	
}

/**
* Calcula a largura da tela para os planetas ocuparem todo o espaço possivel
*/
/*int*/ function calculaEspacoHorizontalDisponivelPlanetas(/*void*/){
	// Sem this na frente das variáveis, pois são pertinentes ao método, não à classe
	espacoJanela = $(window).width();
	espacoOcupadoMural = 554+8; // EDITAR O CSS DO MURAL CASO FOR MUDAR ISSO
	
	return espacoJanela - espacoOcupadoMural;
}

/**
* Calcula a altura da tela para os planetas ocuparem todo o espaço possivel
*/
/*int*/ function calculaEspacoVerticalDisponivelPlanetas(/*void*/){
	var alturaJanela = $(window).height();
	var alturaOcupada = $("body").height() + 16; // 16 é porque a página tem 8 de margem em cima e embaixo
	
	return alturaJanela - alturaOcupada;
}

/*int*/ function calculaNumColunas(/*int*/EspacoDisponivel){
	/*console.log("unflor " + (EspacoDisponivel / (LinkPlaneta.COMPRIMENTO + 50)));
	console.log("numcol "+Math.floor(EspacoDisponivel / (LinkPlaneta.COMPRIMENTO + 50)));*/
	return Math.floor(EspacoDisponivel / (LinkPlaneta.COMPRIMENTO + 50));
}

//-------------------------------------------------------------------------------------------------------------------------
//											CONSTANTES ESTÁTICAS
//-------------------------------------------------------------------------------------------------------------------------

/*int*/ EscolhaPlanetas.ESPACO_DISPONIVEL_PARA_PLANETAS = calculaEspacoHorizontalDisponivelPlanetas();
/*int*/ EscolhaPlanetas.QUANTIDADE_COLUNAS_EXIBIDAS = calculaNumColunas(EscolhaPlanetas.ESPACO_DISPONIVEL_PARA_PLANETAS);


//-------------------------------------------------------------------------------------------------------------------------
//											MÉTODOS PÚBLICOS
//-------------------------------------------------------------------------------------------------------------------------

/**
*	Adiciona um planeta.
*	@param LinkPlaneta 	_linkPlaneta	O link que será adicionado a este menu.
*/
EscolhaPlanetas.prototype.adicionarLinkPlaneta = /*void*/ function(/*LinkPlaneta*/ _linkPlaneta){
	this.linksPlanetas.push(_linkPlaneta);
}

/**
* @param 	int 	_x		Posição x desta janela em relação a seu 'container'.
* @param 	int 	_y		Posição y desta janela em relação a seu 'container'.
* @return 	String			Esta janela convertida para HTML.
*/
EscolhaPlanetas.prototype.converterParaHtml = /*String*/ function(/*int*/ _x, /*int*/ _y){
	var htmlMenu = "";
	htmlMenu	+=	"<div style=\"overflow-y:auto; overflow-x:hidden;";
	htmlMenu	+=		"width:"+(EscolhaPlanetas.ESPACO_DISPONIVEL_PARA_PLANETAS)+"px;";
	htmlMenu	+=		"height:"+calculaEspacoVerticalDisponivelPlanetas()+"px;\">";
	htmlMenu	+=	"<table>";
	
	var x = 0;
	var y = 0;
	var i = 0;
	var linha = 0;
	var coluna = 0;
	var linkExiste = false;
	var linhasTotais = Math.ceil(this.linksPlanetas.length/EscolhaPlanetas.QUANTIDADE_COLUNAS_EXIBIDAS);
	
	for(linha=0; linha<linhasTotais; linha++){
		htmlMenu	+=	"<tr>";
		for(coluna=0; coluna<EscolhaPlanetas.QUANTIDADE_COLUNAS_EXIBIDAS; coluna++){
			i = linha*EscolhaPlanetas.QUANTIDADE_COLUNAS_EXIBIDAS + coluna;
			linkExiste = i<this.linksPlanetas.length;
			
			if(linkExiste){
				htmlMenu	+=	"<td>";
				x = 50*coluna;
				x += (linha%2==1	? 50 : 0 );
				y = -50*linha;
				y += (coluna%2==0	? 0 : 30 );
				y -= 10;
				
				htmlMenu 	+= this.linksPlanetas[i].converterParaHtml(x,y);
				htmlMenu	+=	"<td>";
			}
		}
		htmlMenu	+=	"</tr>";
	}
	
	htmlMenu	+=	"</table>";
	htmlMenu	+=	"</div>";
	return htmlMenu;
}





