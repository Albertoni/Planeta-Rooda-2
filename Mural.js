/**
*	Este arquivo destina-se � implementa��o da classe Mural.
*/
function Mural(/*void*/){
	/**
	* Calcula a altura da tela para o mural ocupar todo o espa�o possivel
	*/
	/*int*/ this.detectaAlturaDisponivelParaMural = function(/*void*/){
		var alturaJanela = $(window).height();
		var alturaOcupada = 162 /*vem do #mural_topo, mudar o CSS tamb�m caso necess�rio*/ + $("body").height() + 16; // 16 � porque a p�gina tem 8 de margem em cima e embaixo
		
		return alturaJanela - alturaOcupada;
	}
	
	// Com base na altura do monitor, calcula quantas janelas o mural pode mostrar.
	/*int*/this.calcularJanelasExibidas = function(/*void*/){
		espacoOcupadoPorUmaJanela = 164/*altura da janela*/+32;/*altura do espa�amento entre elas*/
		espacoLivre = this.detectaAlturaDisponivelParaMural();
		
		return Math.floor(espacoLivre/espacoOcupadoPorUmaJanela);
	}
	
//dados
	//Id da div que cont�m o mural e s� ele.
	/*String*/ this.paiId = 'caixaMural';
	//Id deste mural.
	/*String*/ this.id = 'Mural';
	//Id do bot�o UP (ver janelas acima).
	/*String*/ this.idBotaoUp = 'up'+this.id;
	//Id do bot�o DOWN (ver janelas abaixo).
	/*String*/ this.idBotaoDown = 'down'+this.id;
	//Array de janelas deste mural. As janelas s�o ordenadas (visivelmente) segundo suas posi��es no array.
	 /*array*/ this.janelas = new Array();
	//A quantidade de janelas que s�o exibidas a qualquer momento.
	/*int*/ this.quantidadeJanelasExibidas = this.calcularJanelasExibidas();
	//A primeira janela que � exibida. A posi��o desta no array.
	/*int*/ this.indiceArrayPrimeiraJanelaExibida = 0;
	
//m�todos
	/**
	* @return Booleano	Indica se h� janela escondida imediatamente abaixo das janelas expostas.
	*/
	/*Booleano*/ this.haJanelaAbaixo = function(/*void*/){ return (this.indiceArrayPrimeiraJanelaExibida+this.quantidadeJanelasExibidas < this.janelas.length); }
	
	/**
	* @return Booleano	Indica se h� janela escondida imediatamente acima das janelas expostas.
	*/
	/*Booleano*/ this.haJanelaAcima = function(/*void*/){ return (0 < this.indiceArrayPrimeiraJanelaExibida); }
	
	/**
	* Esconde a janela de cima e mostra a de baixo.
	* Caso n�o haja janela abaixo, n�o far� nada.
	*/
	/*void*/ this.percorrerUmaParaBaixo = function(/*void*/){
		if(this.haJanelaAbaixo()){
			this.indiceArrayPrimeiraJanelaExibida++;
			this.redesenhar();
		}
	}
	
	/**
	* Esconde a janela de baixo e mostra a de cima.
	* Caso n�o haja janela acima, n�o far� nada.
	*/
	/*void*/ this.percorrerUmaParaCima = function(/*void*/){
		if(this.haJanelaAcima()){
			this.indiceArrayPrimeiraJanelaExibida--;
			this.redesenhar();
		}
	}
	
	/**
	* Atualiza o mural com as modifica��es feitas.
	*/
	/*void*/ this.redesenhar = function(/*void*/){
		var containerMural = document.getElementById(this.paiId);
		containerMural.innerHTML = this.converterParaHtml();
	}
	
	//-------------------------------------------------------------------------------------------------------------------------
	//											CONSTANTES EST�TICAS
	//-------------------------------------------------------------------------------------------------------------------------
	/*float*/ this.COMPRIMENTO = 511;
	/*float*/ this.ALTURA = 554;
}



//-------------------------------------------------------------------------------------------------------------------------
//											M�TODOS P�BLICOS
//-------------------------------------------------------------------------------------------------------------------------

/**
* @return Este mural convertido para HTML.
*/
Mural.prototype.converterParaHtml = /*String*/ function(){
	var htmlMural = "";
	htmlMural += "<div id="+this.paiId+"/>";
	htmlMural += "<div id=\"mural_topo\"></div>";
	htmlMural += "<div id="+this.id+" class=\"mural\" style=\"height:"+this.detectaAlturaDisponivelParaMural()+"px\">";
	htmlMural += "<div id=\"contemJanelas\">";
		var i=0;
		var janelaExiste;
		for(i=this.indiceArrayPrimeiraJanelaExibida; i<this.indiceArrayPrimeiraJanelaExibida+this.quantidadeJanelasExibidas; i++){
			janelaExiste = i<this.janelas.length;
			if(janelaExiste){
				htmlMural += this.janelas[i].converterParaHtml(20, -180);
			}
		}
	htmlMural += "</div>";
	htmlMural += "<div id=\"contemBotoes\">";
		htmlMural += "<div id="+this.idBotaoUp+" class=\"botao_cima\" style=\"visibility:"+(this.haJanelaAcima() ? 'visible' : 'hidden')+";\" onclick='Mural.percorrerJanelas(-1)'></div>";
		htmlMural += "<div id=\"espacador_botoes\" style=\"height:325px\"><!-- Diz a lenda que IE odeia div sem nada dentro, e que um coment�rio conserta --></div>"
		htmlMural += "<div id="+this.idBotaoDown+" class=\"botao_baixo\" style=\"visibility:"+(this.haJanelaAbaixo()? 'visible' : 'hidden')+";\" onclick='Mural.percorrerJanelas(1)'></div>"
	htmlMural += "</div>";
	htmlMural += "</div>";
	return htmlMural;
}


/**
*	Adiciona uma nova janela ao mural.
*
*	@param String _textoJanela 		O texto que a janela deve ter.
*/
Mural.prototype.adicionarJanela = /*void*/ function(/*String*/ _textoJanela){
	this.janelas.push(new Janela(_textoJanela));
}

/**
*	Percorre as janelas do mural, escondendo algumas e mostrando outras.
*
*	@param int _deslocamento 	A quantidade de janelas que devem ser 'puladas'. 
*								quantidadeJanelasNoMural < |_deslocamento| 	=> Deslocar at� a �ltima.
*								quantidadeJanelasNoMural < 0 				=> Esconder as de cima, mostrar as de baixo.
*								0 < quantidadeJanelasNoMural 				=> Esconder as de baixo, mostrar as de cima.
*								_deslocamento = 0							=> Fazer nada.
*/
Mural.prototype.percorrerJanelas = /*void*/ function(/*int*/ _deslocamento){
	var quantidadeJanelasDisponiveis = _deslocamento < 0? 	(this.indiceArrayPrimeiraJanelaExibida) :
															(this.janelas.length - (this.indiceArrayPrimeiraJanelaExibida+this.quantidadeJanelasExibidas));
															
	var haJanelasSuficientes = (0 <= (quantidadeJanelasDisponiveis - Math.abs(_deslocamento)));
	if(haJanelasSuficientes){
		var i=0;
		if(_deslocamento < 0){
			for(i=0; i<Math.abs(_deslocamento); i++){
				this.percorrerUmaParaCima();
			}
		} else {
			for(i=0; i<_deslocamento; i++){
				this.percorrerUmaParaBaixo();
			}
		}
	} else {
		this.percorrerJanelas(quantidadeJanelasDisponiveis*(_deslocamento/Math.abs(_deslocamento)));
	}
}












