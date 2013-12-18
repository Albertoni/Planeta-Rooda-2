import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_select extends MovieClip{
//dados
	/*
	* Link do símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "select_mc";

	//---- Constantes
	private var ALTURA_INICIAL_BARRA_ROLAGEM:Number = 99;//do .fla
	private var ALTURA_BOTAO_SELECT:Number = 19.8;//do .fla
	
	private var POSX_NOVO_BOTAO:Number = 15.75;//do .fla
	private var POSY_PRIMEIRO_BOTAO:Number = 3.8;//do .fla

	private var POSX_BARRA_ROLAGEM:Number = 392.65;
	private var POSY_BARRA_ROLAGEM:Number = 1.1;

	private var NUMERO_INICIAL_DE_BOTOES:Number = 0;//do .fla
	private var NUMERO_INICIAL_DE_BOTOES_VISIVEIS:Number = 0;//do .fla
	
	//---- Botões
	private var textosDeBotoes:Array = new Array();
	private var nomesDeBotoes:Array = new Array();
	private var numeroDeBotoes:Number = NUMERO_INICIAL_DE_BOTOES;
	private var numeroDeBotoesVisiveis:Number = NUMERO_INICIAL_DE_BOTOES_VISIVEIS;
	private var numeroMaximoDeBotoesVisiveis:Number = NUMERO_INICIAL_DE_BOTOES_VISIVEIS;
	
	private var posicaoTextoBotaoPressionado:Number = null;
	private var posicaoBotaoPressionado:Number = null;
	private var botaoPressionado:c_btSelectAdm = null;
	
	/*
	* Faz com que apenas o texto, seleção, label e barra de rolagem apareçam.
	*/
	private var invisivel:Boolean = false;
	
	//---- Rolagem
	private var barra_rolagem:c_barra_rolagem;
	private var inicioListaVisivel:Number = null;
	private var fimListaVisivel:Number = null;
	public var fundo:MovieClip;

	//---- Evento
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	

//métodos
	public function c_select(){
		mx.events.EventDispatcher.initialize(this);
		
		
	}
	public function inicializar(_numeroDeBotoesVisiveis:Number, _textosDeBotoes:Array, _label:String):Void{
		/*Label*/
		var formatoLabel:TextFormat;
		formatoLabel = this['label'].getNewTextFormat();
		formatoLabel.bold = true;
		this['label'].setNewTextFormat(formatoLabel);
		this['label'].replaceText(0, _label.length-1, _label);
		
		/*Armazenar dados*/
		textosDeBotoes = _textosDeBotoes;
		nomesDeBotoes = new Array();
		numeroDeBotoes = _textosDeBotoes.length;
		numeroDeBotoesVisiveis = _numeroDeBotoesVisiveis;
		numeroMaximoDeBotoesVisiveis = _numeroDeBotoesVisiveis;
		
		posicaoBotaoPressionado = null;
		botaoPressionado = null;

		/*Inicializações*/
		inicioListaVisivel = 0;
		if(numeroDeBotoesVisiveis <= numeroDeBotoes){
			fimListaVisivel = numeroDeBotoesVisiveis;
		}else{
			fimListaVisivel = numeroDeBotoes;
		}
		//fimListaVisivel = NUMERO_INICIAL_DE_BOTOES_VISIVEIS;
		//onMouseDown = funcaoMouseDown;
		inicializarBarraRolagem();
		
		/*Configurações*/
		for(var quantidadeDeBotoesInseridos:Number = 0; quantidadeDeBotoesInseridos<numeroDeBotoesVisiveis; quantidadeDeBotoesInseridos++){
			adicionarBotao(quantidadeDeBotoesInseridos);
			atualizarTextoBotao(quantidadeDeBotoesInseridos, quantidadeDeBotoesInseridos);
		}
		atualizarTamanhoFundo();
		atualizarTamanhoBarraRolagem();
		if(_textosDeBotoes.length < _numeroDeBotoesVisiveis){
			numeroDeBotoesVisiveis = _textosDeBotoes.length;
		}
		atualizarListaSelect();

		_visible = false;
	}
	
	/*
	* Remove todas as opções que possui.
	*/
	public function limparOpcoes():Void{
		var opcoes:Array = getListaOpcoes();
		var tamanhoOpcoes:Number = opcoes.length;
		for(var indice:Number=0; indice < tamanhoOpcoes; indice++){
			retirarOpcao(opcoes[indice]);
		}
	}
	
	/*
	* Faz com que apenas o texto, seleção, label e barra de rolagem apareçam.
	* @param invisivel_param Define se está invisível (com apenas o texto, seleção, label e barra de rolagem aparecendo).
	*/
	public function setTipoInvisivel(invisivel_param:Boolean):Void{
		//invisivel = invisivel_param;
		var tamanhoNomesDeBotoes:Number = nomesDeBotoes.length;
		POSX_NOVO_BOTAO = 0;
		for(var indice:Number=0; indice < tamanhoNomesDeBotoes; indice++){
			this[nomesDeBotoes[indice]].setInvisivel(invisivel_param);
			this[nomesDeBotoes[indice]]._x = POSX_NOVO_BOTAO;
		}
		this['fundo']._visible = !invisivel_param;
	}
	
	/*
	* Redimensiona este select para caber em determinado tamanho.
	*/
	public function redimensionar(comprimento_param:Number, largura_param:Number):Void{
		var tamanhoNomesDeBotoes:Number = nomesDeBotoes.length;
		for(var indice:Number=0; indice < tamanhoNomesDeBotoes; indice++){
			this[nomesDeBotoes[indice]].redimensionar(comprimento_param - this.barra_rolagem._width, this[nomesDeBotoes[indice]]._height);
		}
		this['fundo']._width = comprimento_param;
		this['fundo']._height = largura_param;
		this['label']._width = comprimento_param;
		POSX_BARRA_ROLAGEM = comprimento_param - barra_rolagem._width;
		barra_rolagem._x = POSX_BARRA_ROLAGEM; 
		barra_rolagem.set_tamanho_barra_rolagem(largura_param);
	}
	
	//---- Select
	private function atualizarListaSelect(_event:Object){
		if(_event.inicioSubLista != undefined) inicioListaVisivel = _event.inicioSubLista;
		//else                                   inicioListaVisivel = 0;
		if(_event.fimSubLista != undefined)    fimListaVisivel = _event.fimSubLista;
		//else                                   fimListaVisivel = 0;

		atualizarDadosListaSelect();
	}
	private function atualizarDadosListaSelect(){
		moverTextoDosBotoes(inicioListaVisivel, fimListaVisivel);
		moverSelecaoBotao(inicioListaVisivel, fimListaVisivel);
	}
	
	/*
	* Indica se há algum botão no estado pressionado, mas não necessariamente sob o mouse.
	* @return Booleano indicando se há botão que foi pressionado.
	*/
	public function haBotaoPressionado():Boolean{
		if(botaoPressionado != null){
			return true;
		}else{
			return false;
		}
	}
	
	/*
	* Retorna todas as opções contidas neste menu.
	* @return Array de strings em cada elemento corresponde a uma opção.
	*/
	public function getListaOpcoes():Array{
		//Para não retornar ponteiro!
		var listaOpcoes:Array = new Array();
		var index:Number = 0;
		var tamanhoTextosDeBotoes:Number = textosDeBotoes.length;
		while(index<tamanhoTextosDeBotoes){
			listaOpcoes.push(textosDeBotoes[index]);
			index++;
		}
		return listaOpcoes;
	}
	
	/*
	* @return A opção que está selecionada. Se não houver, undefined.
	*/
	public function getOpcaoSelecionada():String{
		if(haBotaoPressionado()){
			return textosDeBotoes[posicaoTextoBotaoPressionado];
		}else{
			return undefined;
		}
	}
	
	/*
	* Sendo a lista de opções ordenada, retorna o índice nesta lista da opção que estiver selecionada.
	* Se não houver, retorna undefined.
	* @return O índice da opção selecionada ou undefined.
	*/
	public function getIndiceOpcaoSelecionada():Number{
		if(haBotaoPressionado()){
			return posicaoTextoBotaoPressionado;
		}else{
			return undefined;
		}
	}
	
	/*
	* @param opcao_param Opção procurada na lista.
	* @return Booleano indicando que se a opção procurada está na lista.
	*/
	public function existeOpcao(opcao_param:String):Boolean{
		var existe:Boolean = false;
		var tamanhoTextosDeBotoes:Number = textosDeBotoes.length;
		for(var numTexto:Number=0; numTexto<tamanhoTextosDeBotoes; numTexto++){
			if(opcao_param == textosDeBotoes[numTexto]){
				existe = true;
			} 
		}
		return existe;
	}
	
	private function limparTextosDeBarrasOpcoes(){
		var tamanhoNomesDeBotoes:Number = nomesDeBotoes.length;
		for(var numBotao:Number=0; numBotao<tamanhoNomesDeBotoes; numBotao++){
			this[nomesDeBotoes[numBotao]].atualizar_mensagem(" ");
		}
	}
	
	/*
	* Insere uma opção no final do menu.
	* @param opcao_param A opção que deseja-se inserir na lista.
	*/
	public function inserirOpcao(opcao_param:String):Void{
		limparTextosDeBarrasOpcoes();
		
		textosDeBotoes.push(opcao_param);
		numeroDeBotoes++;
		if(numeroDeBotoesVisiveis < numeroMaximoDeBotoesVisiveis){
			numeroDeBotoesVisiveis++;
		}
		barra_rolagem.removeMovieClip();	
		inicializarBarraRolagem();		
		atualizarTamanhoBarraRolagem(); 	
		fimListaVisivel -= inicioListaVisivel;
		fimListaVisivel++;
		inicioListaVisivel = 0;
		atualizarDadosListaSelect();
	}
	
	/*
	* Substitui a primeira ocorrência de uma opção por outra.
	* @param opcaoAtual_param Opção que será removida.
	* @param novaOpcao_param Opção que substituirá a outra.
	*/
	public function substituirOpcao(opcaoAtual_param:String, novaOpcao_param:String):Void{
		var encontrou:Boolean = false;
		var numTexto:Number = 0;
		var tamanhoTextosDeBotoes:Number = textosDeBotoes.length;
		while(numTexto<tamanhoTextosDeBotoes and !encontrou){
			if(opcaoAtual_param == textosDeBotoes[numTexto]){
				encontrou = true;
				textosDeBotoes[numTexto] = novaOpcao_param;
				if(posicaoTextoBotaoPressionado == numTexto){
					atualizarTextoBotao(posicaoBotaoPressionado, posicaoTextoBotaoPressionado);
				}
			} 
			numTexto++;
		}
	}
	
	/*
	* Remove a primeira ocorrência da opção da lista de opções.
	* @param opcao_param Opção a ser removida.
	*/
	public function retirarOpcao(opcao_param:String):Void{
		limparTextosDeBarrasOpcoes();
		
		var encontrou:Boolean = false;
		var numTexto:Number = 0;
		var tamanhoTextosDeBotoes:Number = textosDeBotoes.length;
		
		while(numTexto<tamanhoTextosDeBotoes and !encontrou){
			if(opcao_param == textosDeBotoes[numTexto]){
				encontrou = true;
				textosDeBotoes.splice(numTexto,1);
			} 
			numTexto++;
		}

		numeroDeBotoes--;
		if(numeroDeBotoes < numeroMaximoDeBotoesVisiveis){
			numeroDeBotoesVisiveis--;
		}
		barra_rolagem.removeMovieClip();	
		inicializarBarraRolagem();		
		atualizarTamanhoBarraRolagem(); 	

		fimListaVisivel -= inicioListaVisivel;
		if(fimListaVisivel != 0){
			fimListaVisivel--;
		}
		inicioListaVisivel = 0;
		atualizarDadosListaSelect();
	}
	
	//---- Listeners
	/*
	* Executada sempre que um botão é pressionado.
	* @param evento_botao_param Possui a id do botão.
	*/
	private function botaoFoiPressionado(evento_botao_param:Object){
		var nomeBotaoPressionado:String = evento_botao_param.nome;
		var posicaoBotao:Number=0;
		var encontrouBotao:Boolean;
		var tamanhoNomesDeBotoes:Number = nomesDeBotoes.length;
		while(posicaoBotao < tamanhoNomesDeBotoes
			  and nomesDeBotoes[posicaoBotao] != nomeBotaoPressionado){
			posicaoBotao++;
		}
		encontrouBotao = (posicaoBotao < nomesDeBotoes.length);
		if(encontrouBotao and (posicaoBotao+1) <= numeroDeBotoes){
			salvarPosicaoTextoBotaoPressionado(posicaoBotao);
			salvarPosicaoBotaoPressionado(posicaoBotao);
			pressionarBotao(this[nomeBotaoPressionado]);
			enviarSinalBotaoPressionado();
		}
	}
	
	/*
	* Envia um evento avisando que algum botão deste select foi pressionado.
	* O evento possui dois atributos:
	*	- posicaoTexto A posição na lista ordenada de opções da opção selecionada.
	*	- nomeSelect O nome deste menu.
	*/
	private function enviarSinalBotaoPressionado(){
		dispatchEvent({target:this, type:"botaoPressionado", posicaoTexto: posicaoTextoBotaoPressionado, nomeSelect: _name});
	}

	//---- Botões
	private function adicionarBotao(_posicao:Number):Void{
		var nomeDaInstancia:String = "btSelectAdm"+(_posicao.toString());
		nomesDeBotoes.push(nomeDaInstancia);
		
		attachMovie("btSelectAdm", nomeDaInstancia, this.getNextHighestDepth());
		this[nomeDaInstancia].inicializar();
		this[nomeDaInstancia].addEventListener("btPressionado", Delegate.create(this, botaoFoiPressionado));
		this[nomeDaInstancia]._x = POSX_NOVO_BOTAO;
		this[nomeDaInstancia]._y = POSY_PRIMEIRO_BOTAO + _posicao * ALTURA_BOTAO_SELECT;
		this[nomeDaInstancia].setInvisivel(invisivel);
	}
	private function atualizarTextoBotao(_posicaoBotao:Number, _posicaoTexto:Number){
		if(_posicaoTexto < textosDeBotoes.length){
			this[nomesDeBotoes[_posicaoBotao]].atualizar_mensagem(textosDeBotoes[_posicaoTexto]);
		}else{
			this[nomesDeBotoes[_posicaoBotao]].atualizar_mensagem(" ");
		}
	}
	
	private function salvarPosicaoTextoBotaoPressionado(_posicaoBotao:Number){
		posicaoTextoBotaoPressionado = inicioListaVisivel+_posicaoBotao;
	}
	private function salvarPosicaoBotaoPressionado(_posicaoBotao:Number){
		posicaoBotaoPressionado = _posicaoBotao;
	}
	private function pressionarBotao(_botao:c_btSelectAdm){
		if(botaoPressionado != null){
			botaoPressionado.toggle_selecao();
		}
		botaoPressionado = _botao;
		botaoPressionado.toggle_selecao();
		
	}
	private function esquecerPosicaoTextoBotaoPressionado(_posicaoBotao:Number){
		posicaoTextoBotaoPressionado = null;
	}
	private function esquecerPosicaoBotaoPressionado(){
		posicaoBotaoPressionado = null;
	}
	private function soltarBotaoPressionado(){
		if(botaoPressionado != null){
			botaoPressionado.toggle_selecao();
			botaoPressionado = null;
		}
	}
	
	private function moverTextoDosBotoes(_inicioListaVisivel:Number, _fimListaVisivel:Number):Void{
		var posicaoBotao:Number = 0;
		for(var posicaoTexto:Number = _inicioListaVisivel; posicaoTexto<_fimListaVisivel; posicaoTexto++){
			posicaoBotao = posicaoTexto - _inicioListaVisivel;
			atualizarTextoBotao(posicaoBotao, posicaoTexto);
		}
	}
	private function moverSelecaoBotao(_inicioListaVisivel:Number, _fimListaVisivel:Number){
		var posicaoNovoBotaoPressionado:Number;
		var novoBotaoPressionado:c_btSelectAdm;

		if(_inicioListaVisivel <= posicaoTextoBotaoPressionado and posicaoTextoBotaoPressionado < _fimListaVisivel){
			posicaoNovoBotaoPressionado = posicaoTextoBotaoPressionado - _inicioListaVisivel;
			if(0 <= posicaoNovoBotaoPressionado and posicaoNovoBotaoPressionado < numeroDeBotoesVisiveis){
				novoBotaoPressionado = this[nomesDeBotoes[posicaoNovoBotaoPressionado]];
				
				salvarPosicaoBotaoPressionado(posicaoNovoBotaoPressionado);
				pressionarBotao(novoBotaoPressionado);
			}
		} else {
			esquecerPosicaoBotaoPressionado();
			soltarBotaoPressionado();
		}
	}
	
	private function houvePressionamentoBotao(_botao:c_btSelectAdm):Boolean{
		if(_botao.hitTest(_root._xmouse, _root._ymouse)){
			return true;
		} else {
			return false;
		}
	}

	
	//---- Barra de Rolagem
	private function inicializarBarraRolagem(){
		var barraJahCriada = (barra_rolagem != undefined);
		var tamanhoBarra:Number;
		if(barraJahCriada){
			tamanhoBarra = barra_rolagem._height;
		}
		this.attachMovie("barraRolagem", "barra_rolagem", this.getNextHighestDepth());		
		this.barra_rolagem._x = POSX_BARRA_ROLAGEM;
		this.barra_rolagem._y = POSY_BARRA_ROLAGEM;
		this.barra_rolagem.addEventListener("scrollMoveu", Delegate.create(this, atualizarListaSelect));	
		this.barra_rolagem.init_barra_rolagem(numeroDeBotoes, numeroDeBotoesVisiveis);
		if(barraJahCriada){
			this.barra_rolagem.set_tamanho_barra_rolagem(tamanhoBarra);
		}
		
		if(this.textosDeBotoes.length <= this.numeroDeBotoesVisiveis){
			this.barra_rolagem._visible = false;
		} else {
			this.barra_rolagem._visible = true;
		}
	}
	private function atualizarTamanhoBarraRolagem(){
		if(!invisivel){
			this.barra_rolagem.set_tamanho_barra_rolagem(this['fundo']._height);
		}
	}

	//---- Fundo
	private function atualizarTamanhoFundo(){
		this['fundo']._height = numeroDeBotoesVisiveis * ALTURA_BOTAO_SELECT + 2*POSY_PRIMEIRO_BOTAO;
	}











}