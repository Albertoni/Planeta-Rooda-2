import flash.geom.Point;
import mx.utils.Delegate;

/*
* Um terreno que pode ter seus objetos editados.
* Para editar um terreno, é necessário explicitar que está sendo editado.
*/
class c_terreno_editavel extends c_terreno {
//dados	
	/*
	* Referências para objetos que podem ser arrastados.
	*/
	private var objetos_arrastaveis:Array = new Array();
	
	/*
	* Necessário para manter a funcionalidade de cancelar após salvar, sem necessitar recarregar a página.
	* Após um salvar, esta pilha conterá todos os elementos que precisam ser deletados e reinseridos no terreno.
	* Isto deve ser feito com uma chamada a reinserirObjetos.
	* ATENÇÃO: É necessário que TODOS objetos já tenham sido salvos ao chamar reinserirObjetos!!!
	*/
	private var pilhaObjetosReinserir:Array;
	
	/*
	* Define se o terreno pode ser editado.
	*/
	private var em_edicao:Boolean = false;
	
	/*
	* Indica se há algum objeto selecionado no momento.
	*/
	private var haObjetoSelecionado:Boolean = false;
	
	/*
	* Quando houver objeto selecionado, é o próprio.
	* Caso contrário, conterá undefined.
	*/
	private var objeto_selecionado:c_objeto_editavel = undefined;
	
	/*
	* Quantidade de objetos salvos, enquanto estiver salvando no banco de dados.
	*/
	private var objetos_salvos:Number;
	
	/*
	* Ao iniciar operação de salvar, o total de objetos a serem salvos.
	*/
	private var totalObjetosParaSalvar:Number;
	
	/*
	* Indica se está realizando operação salvar, quando não pode ser iniciada outra.
	*/
	private var estahSalvando:Boolean;
	
	/*
	* Botão para iniciar edição do mundo.
	*/
	private var btEditarMundo:c_bt_simples;
	private static var POSICAO_BT_EDITAR_MUNDO:Point = new Point(584.65, 0);
	
	/*
	* Botão para cancelar edição do mundo.
	*/
	private var btCancelarEditarMundo:c_bt_simples;
	private static var POSICAO_BT_CANCELAR_EDITAR_MUNDO:Point = new Point(933.65, 0);
	
	/*
	* Botão para salvar edição do mundo.
	*/
	private var btSalvarEdicao:c_bt_simples;
	private static var POSICAO_BT_SALVAR_EDICAO:Point = new Point(1047, 0);
	
	/*
	* Botão de ok para instruções do modo de edição.
	*/
	private var btInstrucoesEdicao:MovieClip;
	
	/*
	* Determina se as instruções devem ser exibidas ao entrar no modo de edição.
	*/
	public var exibirInstrucoesEdicao:Boolean = true;
	
	/*
	* Indica as operações feitas no terreno, para possível ctrl+z.
	*/
	private var historicoEdicoes:Array = new Array();
	private static var INSERCAO:Number = 1;
	private static var MODIFICACAO:Number = 2;
	private static var DELECAO:Number = 3;
	
//métodos
	public function inicializar(dados_personagem_param:c_personagem_bd, imagem_bd_param:c_terreno_bd){
		mx.events.EventDispatcher.initialize(this);
		
		em_edicao = false;
		exibirInstrucoesEdicao = true;
	
		attachMovie("bt_editar_mundo_MC", "btEditarMundo_dummy", getNextHighestDepth(), {_x: POSICAO_BT_EDITAR_MUNDO.x + (_parent._x+_x),
																						 _y: POSICAO_BT_EDITAR_MUNDO.y + (_parent._y+_y)});
		attachMovie("bt_cancelar_edicao_mundo_MC", "btCancelarEditarMundo_dummy", getNextHighestDepth(), {_x: POSICAO_BT_CANCELAR_EDITAR_MUNDO.x - (_parent._x+_x),
																										  _y: POSICAO_BT_CANCELAR_EDITAR_MUNDO.y - (_parent._y+_y)});
		attachMovie("bt_salvar_edicao_MC", "btSalvarEdicao_dummy", getNextHighestDepth(), {_x: POSICAO_BT_SALVAR_EDICAO.x - (_parent._x+_x), 
																						   _y: POSICAO_BT_SALVAR_EDICAO.y - (_parent._y+_y)});
		btEditarMundo = this['btEditarMundo_dummy'];
		btCancelarEditarMundo= this['btCancelarEditarMundo_dummy'];
		btSalvarEdicao = this['btSalvarEdicao_dummy'];
		btInstrucoesEdicao = _root.btInstrucoesEdicao;
		
		super.inicializar(dados_personagem_param, imagem_bd_param);
		estahSalvando = false;
		
		btEditarMundo.inicializar();
		btCancelarEditarMundo.inicializar();
		btSalvarEdicao.inicializar();
		
		btEditarMundo._visible = true;
		btCancelarEditarMundo._visible = false;
		btSalvarEdicao._visible = false;
		
		btEditarMundo.onPress = function(){
			onRollOut();
			
			if(_root.usuario_status.possuiPermissaoDe(c_conta.getNivelProfessor()) || //Professor ou superior
		      (_root.usuario_status.possuiPermissaoDe(c_conta.getNivelAluno()) && this.getImagemBancoDeDados().getPermissaoAlunosEdicao())){ //Aluno ou superior
				_parent.iniciarModoEdicao();
			} else {
				c_aviso_com_ok.mostrar("Desculpe. Você não possui permissão para editar este terreno.");
			}
		};
		
		btInstrucoesEdicao.btAdmOk.onRollOver = function(){
			gotoAndStop(2);	
		};
		btInstrucoesEdicao.btAdmOk.onRollOut = function(){
			gotoAndStop(1);	
		};
		btInstrucoesEdicao.btAdmOk.onPress = function(){
			onRollOut();
			_parent._x = 770;
			_parent._y = -1000;
			if(_parent.checkExibirInstrucoesEdicao.campoCheck.selected){
				_root.planeta.getTerrenoEmQuePersonagemEstah().exibirInstrucoesEdicao = false;
			}
		};
		
		btCancelarEditarMundo.onPress = function(){
			onRollOut();
			_parent.terminarModoEdicao();
		};
		
		btSalvarEdicao.onPress = function(){
			onRollOut();
			_parent.salvar();
		};
		
		onMouseDown = function(){
			var objetoSolto:MovieClip;
			var clicouNoMenu:Boolean = false;
			if(!_root.seleciounouBotaoForaTerreno()){
				clicouNoMenu = (0 <= _root.menuEdicao.sombra._xmouse and _root.menuEdicao.sombra._xmouse <= _root.menuEdicao.sombra._width)
							and (0 <= _root.menuEdicao.sombra._ymouse and _root.menuEdicao.sombra._ymouse <= _root.menuEdicao.sombra._height);
				if(em_edicao and !clicouNoMenu){
					objetoSolto = desfazerSelecaoObjeto();	//Se houver objeto selecionado, terá sua seleção desfeita.
					if(objetoSolto == undefined){
						selecionarObjetoPosicao(_xmouse, _ymouse);   //Procura por um objeto que o usuário tenha selecionado.
					}
				} else {
					mousePress = true;		
					mouseClick = true;
					pontoDestinoClickMp = new Point(_xmouse, _ymouse); 
				}
			}
		}
		
		_root.menuEdicaoMC.addEventListener("btPressionado", Delegate.create(this, criarObjetoDeIcone));
		
		Key.addListener(this);
		onKeyDown = teclaPressionada;
		

		btEditarMundo.swapDepths(getNextHighestDepth());
	}
	
	/**
	* Ativa/desativa funções deste terreno, o que permite ativação de outros.
	* @param Boolean		estahAtivo_param		Indica se deve ativar.
	*/
	public function estahAtivo(estahAtivo_param:Boolean){
		btEditarMundo._visible = estahAtivo_param;
		btEditarMundo._x = POSICAO_BT_EDITAR_MUNDO.x - _x - _parent._x;
		btEditarMundo._y = POSICAO_BT_EDITAR_MUNDO.y - _y - _parent._y;
		btCancelarEditarMundo._x = POSICAO_BT_CANCELAR_EDITAR_MUNDO.x - _x - _parent._x;
		btCancelarEditarMundo._y = POSICAO_BT_CANCELAR_EDITAR_MUNDO.y - _y - _parent._y;
		btSalvarEdicao._x = POSICAO_BT_SALVAR_EDICAO.x - _x - _parent._x;
		btSalvarEdicao._y = POSICAO_BT_SALVAR_EDICAO.y - _y - _parent._y;
		super.estahAtivo(estahAtivo_param);
	}
	
	/*
	* Cria um objeto neste terreno, com base no ícone clicado.
	* @param eventoIconeEdicaoTerreno Um objeto que possui as propriedades:
	*		- eventoIconeEdicaoTerreno.classe (c_iconeEdicaoTerreno.CLASSE_ARVORES ou c_iconeEdicaoTerreno.CLASSE_CASAS)
	*		- eventoIconeEdicaoTerreno.terreno (segundo c_terreno_bd)
	*		- eventoIconeEdicaoTerreno.tipo (um número de ordenação no subconjunto com a mesma classe e terreno)
	*/
	private function criarObjetoDeIcone(eventoIconeEdicaoTerreno:Object):Void{
		if(eventoIconeEdicaoTerreno.classe == c_iconeEdicaoTerreno.CLASSE_CASAS){
			selecionarObjeto( adicionarCasa(eventoIconeEdicaoTerreno.tipo, _xmouse-150, _ymouse-100, c_banco_de_dados.NAO_SALVO) );
		} else if(eventoIconeEdicaoTerreno.classe == c_iconeEdicaoTerreno.CLASSE_ARVORES){
			selecionarObjeto( adicionarArvore(eventoIconeEdicaoTerreno.tipo, _xmouse-50, _ymouse-200, c_banco_de_dados.NAO_SALVO) );
		} else if(eventoIconeEdicaoTerreno.classe == c_iconeEdicaoTerreno.CLASSE_PREDIOS){
			selecionarObjeto( adicionarPredioQuartos(_root.usuario_status.quarto_id, _xmouse-50, _ymouse-400, c_banco_de_dados.NAO_SALVO) );
		}
	}
	
	/*
	* Desloca o terreno com todos seus objetos.
	* Não move o personagem!
	*/
	public function moverTerreno(deslocamento_x_param:Number, deslocamento_y_param:Number){
		super.moverTerreno(deslocamento_x_param, deslocamento_y_param);
		btEditarMundo._x = POSICAO_BT_EDITAR_MUNDO.x - _x - _parent._x;
		btEditarMundo._y = POSICAO_BT_EDITAR_MUNDO.y - _y - _parent._y;
		btCancelarEditarMundo._x = POSICAO_BT_CANCELAR_EDITAR_MUNDO.x - _x - _parent._x;
		btCancelarEditarMundo._y = POSICAO_BT_CANCELAR_EDITAR_MUNDO.y - _y - _parent._y;
		btSalvarEdicao._x = POSICAO_BT_SALVAR_EDICAO.x - _x - _parent._x;
		btSalvarEdicao._y = POSICAO_BT_SALVAR_EDICAO.y - _y - _parent._y;
	}
	
	/*
	* Executada toda vez que uma tecla é pressionada.
	*/
	private function teclaPressionada():Void{
		if(em_edicao){
			switch (Key.getCode()){
				case Key.ESCAPE: terminarModoEdicao();
					break;
				case Key.DELETEKEY: deletarObjetoSelecionado();
					break;
				case Key.UP:
						if(Key.isDown(Key.RIGHT)){
							moverTerreno(-15,15);
						} else if(Key.isDown(Key.LEFT)){
							moverTerreno(15,15);
						} else {
							moverTerreno(0,15);
						}
					break;
				case Key.DOWN: 
						if(Key.isDown(Key.RIGHT)){
							moverTerreno(-15,-15);
						} else if(Key.isDown(Key.LEFT)){
							moverTerreno(15,-15);
						} else {
							moverTerreno(0,-15);
						}
					break;
				case Key.RIGHT: 
						if(haObjetoSelecionado){
							frameSeguinteObjetoSelecionado();
						} else {
							moverTerreno(-15,0);
						}
					break;
				case Key.LEFT: 
						if(haObjetoSelecionado){
							frameAnteriorObjetoSelecionado();
						} else {
							moverTerreno(15,0);
						}
					break;
				default: 
					switch (chr(Key.getAscii())) {
						case 'Z':case 'z': 
							if(Key.isDown(Key.CONTROL)){
								desfazerUltimaEdicao();
							}
							break;
						case 'Y':case 'y': 
							if(Key.isDown(Key.CONTROL)){
								//no futuro...
							}
							break;
					}
			}
		}
	}
	
	/*
	* Desfaz a última edição feita.
	*/
	private function desfazerUltimaEdicao():Void{
		var tamanhoHistorico:Number = historicoEdicoes.length;
		if(0<tamanhoHistorico){
			var nomeObjetoEscolhido:String;
			var objetosCandidatos:Array;
			var tipoUltimaEdicao:Number = historicoEdicoes[historicoEdicoes.length - 1];
			historicoEdicoes.pop();
			switch(tipoUltimaEdicao){
				case INSERCAO: objetosCandidatos = getObjetosNovos();
							   if(0<objetosCandidatos.length){
								   nomeObjetoEscolhido = objetosCandidatos[objetosCandidatos.length-1];
							       desfazerInsercao(nomeObjetoEscolhido);
							   }
					break;
				case MODIFICACAO: objetosCandidatos = getObjetosModificados();
								  if(0<objetosCandidatos.length){
							          nomeObjetoEscolhido = objetosCandidatos[objetosCandidatos.length-1];
							       	  desfazerModificacao(nomeObjetoEscolhido);
							   	  }
					break;
				case DELECAO: objetosCandidatos = getObjetosDeletados();
							  if(0<objetosCandidatos.length){
							      nomeObjetoEscolhido = objetosCandidatos[objetosCandidatos.length-1];
							      desfazerDelecao(nomeObjetoEscolhido);
							  }
					break;
			}
		}
	}
	
	/*
	* Inicia o modo de edição. 
	* Seta variáveis e aparência do personagem. Mostra as instruções. Etc.
	*/
	public function iniciarModoEdicao():Void{
		if(!em_edicao){ 
			definirPermissaoMovimentoMp(false);
			
			btSalvarEdicao._visible 	   = true;
			btCancelarEditarMundo._visible = true;
			btEditarMundo._visible         = false;
		
			editavel(true);
			
			
			_root.menuEdicao._visible = true;
			
			if(exibirInstrucoesEdicao){
				btInstrucoesEdicao.admAviso.autoSize = true;
				btInstrucoesEdicao._x = Stage.width/2;
				btInstrucoesEdicao._y = Stage.height/2 - btInstrucoesEdicao._height/2;
				btInstrucoesEdicao.admAviso.text = "    Instruções do modo de edição.\nESC - Sair\nDEL - Deletar objeto selecionado\nCTRL+Z - Desfazer última ação\nSetas - Modificar objeto selecionado\nSetas (sem objeto) - Mover terreno";
				btInstrucoesEdicao.swapDepths(getNextHighestDepth());
			}
		}
	}
	
	
	/*
	* Termina o modo de edição. 
	* Seta variáveis. Desfaz a seleção do objeto selecionado (se houver).
	*/
	public function terminarModoEdicao():Void{
		if(em_edicao and !estahSalvando){
			centralizarTelaEm(new Point(this['mp']._x, this['mp']._y));
			
			btSalvarEdicao._visible 	   = false;
			btCancelarEditarMundo._visible = false;
			btEditarMundo._visible         = true;
		
			_root.menuEdicao._visible = false;
		
			desfazerSelecaoObjeto();
			desfazerEdicoes();
			editavel(false);
			definirPermissaoMovimentoMp(true);
		} else if(estahSalvando){
			c_aviso_com_ok("Favor esperar o término da gravação.");
		}
	}

	/*
	* @param em_edicao_param Indica se o terreno pode ou não ser editado.
	*/
	public function editavel(em_edicao_param:Boolean):Void{
		em_edicao = em_edicao_param;
	}
	
	/*
	* @return O primeiro objeto encontrado na posição do parâmetro, se houver algum.
	* @param x_param A coordenada x em que o objeto será procurado.
	* @param y_param A coordenada y em que o objeto será procurado.
	* @param objeto_param Um objeto a ser ignorado.
	*/
	private function getObjetoNaPosicao(x_param:Number, y_param:Number, objeto_param:c_objeto_editavel):c_objeto_editavel{
		var encontrouObjeto:Boolean = false;
		var objetoEncontrado:c_objeto_editavel = undefined;
		var nome_objeto:String;
		var indice:Number = 0;
		var tamanhoObjetosArrastaveis:Number = objetos_arrastaveis.length;
		while(indice < tamanhoObjetosArrastaveis and !encontrouObjeto){
			nome_objeto = objetos_arrastaveis[indice];
			if(this[nome_objeto].haColisao(x_param, y_param) and nome_objeto != objeto_param._name
			   and this[nome_objeto]._visible){
				encontrouObjeto = true;
				objetoEncontrado = this[nome_objeto];
			}
			indice++;
		}
		return objetoEncontrado;
	}
	
	/*
	* Para otimização, esta função utiliza vetores de "passo" <10, 10>, descartando grande parte das possbilidades, mas garantindo precisão.
	* @return O primeiro objeto encontrado que ocupa parcialmente a posição do objeto de parâmetro.
	* @param objeto_param Objeto em cujas coordenadas serão procurados outros objetos.
	*/
	private function getObjetoNaPosicaoObjeto(objeto_param:c_objeto_editavel):c_objeto_editavel{
		var encontrouObjeto:Boolean = false;
		var objetoEncontrado:c_objeto_editavel = undefined;
		var posicaoProcura:Point = new Point(objeto_param._x + objeto_param.getSombra()._x, 
											 objeto_param._y + objeto_param.getSombra()._y);
		while(posicaoProcura.x < objeto_param._x + objeto_param.getSombra()._x + objeto_param.getSombra()._width and !encontrouObjeto){
			while(posicaoProcura.y < objeto_param._y + objeto_param.getSombra()._y + objeto_param.getSombra()._height and !encontrouObjeto){
				objetoEncontrado = getObjetoNaPosicao(posicaoProcura.x, posicaoProcura.y, objeto_param);
				encontrouObjeto = (objetoEncontrado != undefined and objeto_param.haColisao(posicaoProcura.x, posicaoProcura.y));
				posicaoProcura.y += 10;
			}
			posicaoProcura.x += 10;
			posicaoProcura.y = objeto_param._y + objeto_param.getSombra()._y;
		}
		return objetoEncontrado;
	}
	
	/*
	* Procura por objetos nas redondezas de um outro, incluindo a região que ocupa.
	* @param objeto_param Objeto em cujas redondezas será feita a procura.
	* @param comprimento_param Comprimento da caixa de limite.
	* @param largura_param Largura da caixa de limite.
	* @return O primeiro objeto encontrado nas redondezas de objeto_param.
	*/
	private function getObjetoRedondezasObjeto(objeto_param:c_objeto_editavel, comprimento_param:Number, largura_param:Number):c_objeto_editavel{
		var encontrouObjeto:Boolean = false;
		var objetoEncontrado:c_objeto_editavel = undefined;
		var posicaoProcura:Point = new Point(objeto_param._x + objeto_param.getSombra()._x - comprimento_param, 
											 objeto_param._y + objeto_param.getSombra()._y - largura_param);
		
		while(posicaoProcura.x < objeto_param._x + objeto_param.getSombra()._x + objeto_param.getSombra()._width + comprimento_param and !encontrouObjeto){
			while(posicaoProcura.y < objeto_param._y + objeto_param.getSombra()._y + objeto_param.getSombra()._height + largura_param and !encontrouObjeto){
				objetoEncontrado = getObjetoNaPosicao(posicaoProcura.x, posicaoProcura.y, objeto_param);
				encontrouObjeto = (objetoEncontrado != undefined);
				posicaoProcura.y += 10;
			}
			posicaoProcura.x += 10;
			posicaoProcura.y = objeto_param._y + objeto_param.getSombra()._y - largura_param;
		}
		return objetoEncontrado;
	}
	
	/*
	* Sobrecarregar funções de c_terreno para definir quais objetos são arrastáveis.
	*/
	public function adicionarPredioQuartos(id_quarto_param:String, x_param:Number, y_param:Number, identificacao_param:String):c_objeto_editavel{
		var nome_adicionado:String = super.adicionarPredioQuartos(id_quarto_param, x_param, y_param, identificacao_param);
		objetos_arrastaveis.push(nome_adicionado);
		this[nome_adicionado].definirDadosIniciais(new Point(this[nome_adicionado]._x, this[nome_adicionado]._y), this[nome_adicionado]._currentframe);
		if(identificacao_param == c_banco_de_dados.NAO_SALVO){
			this[nome_adicionado].definirEstado(c_objeto_editavel.ESTADO_RECEM_INSERIDO);
		}
		btEditarMundo.swapDepths(getNextHighestDepth());
		btCancelarEditarMundo.swapDepths(getNextHighestDepth());
		btInstrucoesEdicao.swapDepths(getNextHighestDepth());
		btSalvarEdicao.swapDepths(getNextHighestDepth());
		return this[nome_adicionado];
	}
	public function adicionarCasa(tipo_aparencia_param:Number, x_param:Number, y_param:Number, identificacao_param:String):c_objeto_editavel{
		var nome_adicionado:String = super.adicionarCasa(tipo_aparencia_param, x_param, y_param, identificacao_param);
		objetos_arrastaveis.push(nome_adicionado);
		this[nome_adicionado].definirDadosIniciais(new Point(this[nome_adicionado]._x, this[nome_adicionado]._y), this[nome_adicionado]._currentframe);
		if(identificacao_param == c_banco_de_dados.NAO_SALVO){
			this[nome_adicionado].definirEstado(c_objeto_editavel.ESTADO_RECEM_INSERIDO);
		}
		btEditarMundo.swapDepths(getNextHighestDepth());
		btCancelarEditarMundo.swapDepths(getNextHighestDepth());
		btInstrucoesEdicao.swapDepths(getNextHighestDepth());
		btSalvarEdicao.swapDepths(getNextHighestDepth());
		return this[nome_adicionado];
	}
	public function adicionarArvore(tipo_aparencia_param:Number, x_param:Number, y_param:Number, identificacao_param:String):c_objeto_editavel{
		var nome_adicionado:String = super.adicionarArvore(tipo_aparencia_param, x_param, y_param, identificacao_param);
		objetos_arrastaveis.push(nome_adicionado);
		this[nome_adicionado].definirDadosIniciais(new Point(this[nome_adicionado]._x, this[nome_adicionado]._y), this[nome_adicionado]._currentframe);
		if(identificacao_param == c_banco_de_dados.NAO_SALVO){
			this[nome_adicionado].definirEstado(c_objeto_editavel.ESTADO_RECEM_INSERIDO);
		}
		btEditarMundo.swapDepths(getNextHighestDepth());
		btCancelarEditarMundo.swapDepths(getNextHighestDepth());
		btInstrucoesEdicao.swapDepths(getNextHighestDepth());
		btSalvarEdicao.swapDepths(getNextHighestDepth());
		return this[nome_adicionado];
	}
	
	/*
	* Procura um objeto nas coordenadas passadas e o seleciona, se houver algum.
	* @param x_param A coordenada x em que o objeto será procurado.
	* @param y_param A coordenada y em que o objeto será procurado.
	*/
	public function selecionarObjetoPosicao(x_param:Number, y_param:Number):Void{
		var objeto_posicao:c_objeto_editavel;
		if(em_edicao){
			objeto_posicao = getObjetoNaPosicao(x_param, y_param);
			selecionarObjeto(objeto_posicao);
		}
	}
	
	/*
	* Seleciona o objeto passado como parâmetro.
	* @param objeto_param O objeto a ser selecionado.
	*/
	public function selecionarObjeto(objeto_param:c_objeto_editavel):Void{
		if(em_edicao){
			if(haObjetoSelecionado){
				if(this[objeto_selecionado._name].getIdenficacaoBancoDeDados() == c_banco_de_dados.NAO_SALVO){
					removerObjeto(objeto_selecionado._name);
				} else {
					desfazerSelecaoObjeto();
				}
			}
			
			if(objeto_param != undefined){
				haObjetoSelecionado = true;
				objeto_param.selecionar();
				objeto_selecionado = objeto_param;
			}
		}
	}
	
	/*
	* Remove o objeto.
	* @param id_param A id do objeto a ser removido.
	* @override c_terreno.removerObjeto(id_param:String).
	*/
	public function removerObjeto(id_param:String){
		super.removerObjeto(id_param);
		var indice:Number=0;
		var objetoEncontrado:Boolean = false;
		var tamanhoObjetosArrastaveis:Number = objetos_arrastaveis.length;
		while(indice< tamanhoObjetosArrastaveis and !objetoEncontrado){
			if(objetos_arrastaveis[indice] == id_param){
				objetoEncontrado = true;
				objetos_arrastaveis.splice(indice,1);
			}
			indice++;
		}
	}
	
	/*
	* Caso haja objeto selecionado, desfaz sua seleção, verificando e tratando erros.
	* @return O objeto cuja seleção foi desfeita.
	*/
	public function desfazerSelecaoObjeto():c_objeto_editavel{
		var ultimoObjetoSelecionado:c_objeto_editavel = undefined;
		if(em_edicao and haObjetoSelecionado){	//Se há um objeto selecionado, desfazer a seleção.
			if(!estaNaAreaUtilObjeto(objeto_selecionado)){
				c_aviso_com_ok.mostrar("Desculpe. Posição inválida.");
				if(objeto_selecionado.getEstado() != undefined
				   and objeto_selecionado.getEstado() == c_objeto_editavel.ESTADO_RECEM_INSERIDO
				   and !objeto_selecionado.editado()){
					deletarObjetoSelecionado();
				} else {
					objeto_selecionado._x = objeto_selecionado.getPosicaoAnteriorSelecao().x;
					objeto_selecionado._y = objeto_selecionado.getPosicaoAnteriorSelecao().y;
					ultimoObjetoSelecionado = obrigarDesfazerSelecaoObjeto();
				}
			} else if(getObjetoNaPosicaoObjeto(objeto_selecionado) != undefined){
				c_aviso_com_ok.mostrar("Desculpe. Já há um objeto nesta posição.");
				if(objeto_selecionado.getEstado() != undefined
				   and objeto_selecionado.getEstado() == c_objeto_editavel.ESTADO_RECEM_INSERIDO
				   and !objeto_selecionado.editado()){
					deletarObjetoSelecionado();
				} else {
					objeto_selecionado._x = objeto_selecionado.getPosicaoAnteriorSelecao().x;
					objeto_selecionado._y = objeto_selecionado.getPosicaoAnteriorSelecao().y;
					ultimoObjetoSelecionado = obrigarDesfazerSelecaoObjeto();
				}
			} else if(getObjetoRedondezasObjeto(objeto_selecionado, this['mp'].getSombra()._width + 20, this['mp'].getSombra()._height + 20) != undefined){
				c_aviso_com_ok.mostrar("Desculpe. Não é possível inserir um objeto nas redondezas de outro.");
				if(objeto_selecionado.getEstado() != undefined
				   and objeto_selecionado.getEstado() == c_objeto_editavel.ESTADO_RECEM_INSERIDO
				   and !objeto_selecionado.editado()){
					deletarObjetoSelecionado();
				} else {
					objeto_selecionado._x = objeto_selecionado.getPosicaoAnteriorSelecao().x;
					objeto_selecionado._y = objeto_selecionado.getPosicaoAnteriorSelecao().y;
					ultimoObjetoSelecionado = obrigarDesfazerSelecaoObjeto();
				}
			} else if(this['teleporte'].colideCom(objeto_selecionado)){
				c_aviso_com_ok.mostrar("Desculpe. Não é possível inserir objetos no teleporte.");
				if(objeto_selecionado.getEstado() != undefined
				   and objeto_selecionado.getEstado() == c_objeto_editavel.ESTADO_RECEM_INSERIDO
				   and !objeto_selecionado.editado()){
					deletarObjetoSelecionado();
				} else {
					objeto_selecionado._x = objeto_selecionado.getPosicaoAnteriorSelecao().x;
					objeto_selecionado._y = objeto_selecionado.getPosicaoAnteriorSelecao().y;
					ultimoObjetoSelecionado = obrigarDesfazerSelecaoObjeto();
				}
			} else {
				ultimoObjetoSelecionado = obrigarDesfazerSelecaoObjeto();
			}
		}
		return ultimoObjetoSelecionado;
	}
	
	/*
	* Desfaz a seleção de um objeto, sem tratamento de erros.
	*/
	private function obrigarDesfazerSelecaoObjeto():c_objeto_editavel{
		var ultimoObjetoSelecionado:c_objeto_editavel = undefined;
		if(em_edicao and haObjetoSelecionado){	//Se há um objeto selecionado, desfazer a seleção.
			atualizarObjetoSelecionado();
			haObjetoSelecionado = false;
			objeto_selecionado.desfazerSelecao();
			ultimoObjetoSelecionado = objeto_selecionado;
			objeto_selecionado = undefined;
		}
		return ultimoObjetoSelecionado;
	}
	
	/*
	* Atualiza os dados do objeto que está selecionado e mantém sua seleção.
	*/
	public function atualizarObjetoSelecionado(){
		if(em_edicao and haObjetoSelecionado){
			objeto_selecionado.setStatusEdicao(true);
			this['mapa'].moverIndicador(objeto_selecionado._x + objeto_selecionado.getSombra()._x, 
										objeto_selecionado._y + objeto_selecionado.getSombra()._y, 
										objeto_selecionado._name);
										
			if(objeto_selecionado.getIdentificacaoBancoDeDados() == c_banco_de_dados.NAO_SALVO){
				historicoEdicoes.push(INSERCAO);
			} else {
				historicoEdicoes.push(MODIFICACAO);
			}
		}
	}
	
	/*
	* Deleta o objeto que está selecionado, desfazendo sua seleção.
	*/
	public function deletarObjetoSelecionado(){
		if(em_edicao){
			historicoEdicoes.push(DELECAO);
			obrigarDesfazerSelecaoObjeto().simularDelecao();
		}
	}

	
	
	/*-----------------------------------------------
	*	Se estiver no modo de edição e houver objeto selecionado,
	*   coloca este em seu próximo frame. Isto muda sua aparência. - Diogo - 19.08.11
	------------------------------------------------*/
	public function frameSeguinteObjetoSelecionado():Void{
		if(em_edicao and haObjetoSelecionado){
			objeto_selecionado.aparencia_seguinte();
		}
	}

	/*-----------------------------------------------
	*	Se estiver no modo de edição e houver objeto selecionado,
	*   coloca este em seu frame anterior. Isto muda sua aparência. - Diogo - 19.08.11
	------------------------------------------------*/
	public function frameAnteriorObjetoSelecionado():Void{
		if(em_edicao and haObjetoSelecionado){
			objeto_selecionado.aparencia_anterior();
		}
	}
	
	/*
	* Desfaz edições feitas.
	*/
	public function desfazerEdicoes(){
		var nome_objeto:String = new String();
		var pilhaObjetosNovos:Array = getObjetosNovos();
		var pilhaObjetosModificados:Array = getObjetosModificados();
		var pilhaObjetosDeletados:Array = getObjetosDeletados();
		
		/*var stringAviso:String = new String();
		stringAviso += "Novos = "+pilhaObjetosNovos.length+"\n";
		stringAviso += "Modificados = "+pilhaObjetosModificados.length+"\n";
		stringAviso += "Deletados = "+pilhaObjetosDeletados.length+"\n";
		c_aviso_com_ok.mostrar(stringAviso);*/
		while(0 < pilhaObjetosNovos.length){
			nome_objeto = pilhaObjetosNovos[pilhaObjetosNovos.length - 1];
			desfazerInsercao(nome_objeto);
			pilhaObjetosNovos.pop();
		}

		while(0 < pilhaObjetosModificados.length){
			nome_objeto = pilhaObjetosModificados[pilhaObjetosModificados.length - 1];
			desfazerModificacao(nome_objeto);
			pilhaObjetosModificados.pop();
		}
		
		while(0 < pilhaObjetosDeletados.length){
			nome_objeto = pilhaObjetosDeletados[pilhaObjetosDeletados.length - 1];
			if(this[nome_objeto].getIdentificacaoBancoDeDados() != c_banco_de_dados.NAO_SALVO){
				desfazerDelecao(nome_objeto);
			}
			pilhaObjetosDeletados.pop();
		}
	}
	
	/*
	* Desfaz a inserção de um objeto inserido pelo sistema de edição.
	*/
	private function desfazerInsercao(nome_objeto_param:String):Void{
		this['mapa'].deletarIndicador(nome_objeto_param);
		this[nome_objeto_param].removeMovieClip();
	}
	/*
	* Desfaz modificação de um objeto modificado pelo sistema de edição.
	*/
	private function desfazerModificacao(nome_objeto_param:String):Void{
		this[nome_objeto_param].desfazerEdicao();
		this['mapa'].moverIndicador(this[nome_objeto_param]._x + this[nome_objeto_param].getSombra()._x, 
									this[nome_objeto_param]._y + this[nome_objeto_param].getSombra()._y, 
									nome_objeto_param);
			
	}
	/*
	* Desfaz a deleção de um objeto deletado pelo sistema de edição.
	*/
	private function desfazerDelecao(nome_objeto_param:String):Void{
		this[nome_objeto_param].restaurar();
	}
	
	/*
	* @return Array com objeto que já foram editados, após inseridos, neste terreno.
	*/
	public function getObjetosModificados():Array{
		var objetosModificados:Array = new Array();
		var nome_objeto:String = new String();
		var tamanhoObjetosArrastaveis:Number = objetos_arrastaveis.length;
		for(var indice:Number = 0; indice < tamanhoObjetosArrastaveis; indice++){
			nome_objeto = objetos_arrastaveis[indice];
			if(this[nome_objeto].getIdentificacaoBancoDeDados() != c_banco_de_dados.NAO_SALVO 
			   and this[nome_objeto].editado()
			   and !this[nome_objeto].teveDelecaoSimulada()){
				objetosModificados.push(nome_objeto);
			}
		}
		return objetosModificados;
	}

	/*
	* @return Array com objetos deste terreno que ainda não foram salvos no BD, isto é, não possuem imagem lá.
	*/
	public function getObjetosNovos():Array{
		var objetosNovos:Array = new Array();
		var nome_objeto:String = new String();
		var tamanhoObjetosArrastaveis:Number = objetos_arrastaveis.length;
		for(var indice:Number = 0; indice < tamanhoObjetosArrastaveis; indice++){
			nome_objeto = objetos_arrastaveis[indice];
			if(this[nome_objeto].getIdentificacaoBancoDeDados() == c_banco_de_dados.NAO_SALVO
			   and !this[nome_objeto].teveDelecaoSimulada()){
				objetosNovos.push(nome_objeto);
			}
		}
		return objetosNovos;
	}

	/*
	* @return Array com objetos deste terreno que foram deletados.
	*/
	public function getObjetosDeletados():Array{
		var objetosDeletados:Array = new Array();
		var nome_objeto:String = new String();
		var tamanhoObjetosArrastaveis:Number = objetos_arrastaveis.length;
		for(var indice:Number = 0; indice < tamanhoObjetosArrastaveis; indice++){
			nome_objeto = objetos_arrastaveis[indice];
			if(this[nome_objeto].teveDelecaoSimulada()){
				objetosDeletados.push(nome_objeto);
			}
		}
		return objetosDeletados;
	}

	/*
	* Salva modificações feitas em objetos deste terreno.
	*/
	public function salvar():Void{
		if(!estahSalvando){
			estahSalvando = true;
			c_aviso_espera.criarPara(this, "Favor aguardar enquanto os dados são gravados...");
			objetos_salvos = 0;
		
			var nome_objeto:String = new String();
			var pilhaObjetosNovos:Array = getObjetosNovos();
			var pilhaObjetosModificados:Array = getObjetosModificados();
			var pilhaTodosObjetosDeletados:Array = getObjetosDeletados();
			var pilhaObjetosDeletadosNaoNovos:Array = new Array();
			pilhaObjetosReinserir = new Array();
			
			while(0 < pilhaTodosObjetosDeletados.length){
				nome_objeto = pilhaTodosObjetosDeletados[pilhaTodosObjetosDeletados.length - 1];
				if(this[nome_objeto].getIdentificacaoBancoDeDados() != c_banco_de_dados.NAO_SALVO){
					pilhaObjetosDeletadosNaoNovos.push(nome_objeto);
				}
				pilhaTodosObjetosDeletados.pop();
			}
			
			totalObjetosParaSalvar = pilhaObjetosNovos.length+pilhaObjetosModificados.length+pilhaObjetosDeletadosNaoNovos.length;
			
			/*var stringAviso:String = new String();
			stringAviso += "Vou salvar "+totalObjetosParaSalvar+" objetos\n";
			stringAviso+= pilhaObjetosNovos.length+" novos\n";
			stringAviso+= pilhaObjetosModificados.length+" modificados\n";
			stringAviso+= pilhaObjetosDeletadosNaoNovos.length+" deletados\n";
			c_aviso_com_ok.mostrar(stringAviso);*/

			if(pilhaObjetosNovos.length == 0
			   and pilhaObjetosModificados.length == 0
			   and pilhaObjetosDeletadosNaoNovos.length == 0){
				c_aviso_com_ok.mostrar("Desculpe. Não há dados a serem salvos.");
				c_aviso_espera.destruirDe(this);
				estahSalvando = false;
			}

			while(0 < pilhaObjetosNovos.length){
				nome_objeto = pilhaObjetosNovos[pilhaObjetosNovos.length - 1];
				pilhaObjetosReinserir.push(nome_objeto);
				c_banco_de_dados.inserirObjeto(this[nome_objeto], getImagemBancoDeDados().getIdentificacao(),
											   function(dados_param:LoadVars){
												   this[dados_param.nome_objeto].inicializar(dados_param.novo_id);
												   							  objetoSalvo(dados_param);}, this);
				this[nome_objeto].setStatusEdicao(false);
				this[nome_objeto].definirEstado(c_objeto_editavel.ESTADO_SALVO);
				
				if(ehPredio(nome_objeto)){
					imagemBancoDeDados.inserirPredio(this[nome_objeto].getIdentificacaoBancoDeDados(),
												   this[nome_objeto]._currentframe,
												   new Point(this[nome_objeto]._x, this[nome_objeto]._y));
				} else if(ehCasa(nome_objeto)){
					imagemBancoDeDados.inserirCasa(this[nome_objeto].getIdentificacaoBancoDeDados(),
												   this[nome_objeto]._currentframe,
												   new Point(this[nome_objeto]._x, this[nome_objeto]._y));
				} else if(ehArvore(nome_objeto)){
					imagemBancoDeDados.inserirArvore(this[nome_objeto].getIdentificacaoBancoDeDados(),
												   this[nome_objeto]._currentframe,
												   new Point(this[nome_objeto]._x, this[nome_objeto]._y));
				}
				
				pilhaObjetosNovos.pop();
			}
		
			while(0 < pilhaObjetosModificados.length){
				nome_objeto = pilhaObjetosModificados[pilhaObjetosModificados.length - 1];
				pilhaObjetosReinserir.push(nome_objeto);
				c_banco_de_dados.atualizarObjeto(this[nome_objeto], objetoSalvo, this);
				this[nome_objeto].setStatusEdicao(false);
				this[nome_objeto].definirEstado(c_objeto_editavel.ESTADO_SALVO);
				imagemBancoDeDados.modificarObjeto(this[nome_objeto].getIdentificacaoBancoDeDados(),
												   this[nome_objeto]._currentframe,
												   new Point(this[nome_objeto]._x, this[nome_objeto]._y));
				pilhaObjetosModificados.pop();
			}

			while(0 < pilhaObjetosDeletadosNaoNovos.length){
				nome_objeto = pilhaObjetosDeletadosNaoNovos[pilhaObjetosDeletadosNaoNovos.length - 1];
				if(this[nome_objeto].getIdentificacaoBancoDeDados() != c_banco_de_dados.NAO_SALVO){
					c_banco_de_dados.apagarObjeto(this[nome_objeto], objetoSalvo, this);
					imagemBancoDeDados.deletarObjeto(this[nome_objeto].getIdentificacaoBancoDeDados());
				}
				removerObjeto(nome_objeto);
				this[nome_objeto].definirEstado(c_objeto_editavel.ESTADO_SALVO);
				pilhaObjetosDeletadosNaoNovos.pop();
			}
		} else {
			c_aviso_com_ok.mostrar("Por favor, aguarde o término da operação.");
		}
	}

	/*
	* Conta o objeto, incrementando o total de objetos salvos.
	* Lida com possíveis erros ocorridos.
	* Executar sempre que um objeto for salvo no banco de dados.
	* @param dados_param LoadVars com os dados recebidos do servidor.
	*/
	private function objetoSalvo(dados_param:LoadVars):Void{
		if(dados_param.erro == c_banco_de_dados.SEM_ERRO){
			this.objetos_salvos = this.objetos_salvos + 1;
			if(totalObjetosParaSalvar == objetos_salvos){
				c_aviso_espera.destruirDe(this);
				reinserirObjetos(pilhaObjetosReinserir);
				c_aviso_com_ok.mostrar("Dados salvos com sucesso!");
				estahSalvando = false;
			} else if(false){
				c_aviso_com_ok.mostrar((totalObjetosParaSalvar-objetos_salvos)+" de "+totalObjetosParaSalvar+" dados não foram salvos...");
			}
		} else {
			c_aviso_com_ok.mostrar(c_banco_de_dados.getMensagemErro(dados_param.erro),
								   new Point(Stage.width/2 - 300, Stage.height/2));
		}
	}
	
	/*
	* Dada uma pilha de objetos, deleta-os e os reinsere para garantir que estão de acordo com todas restrições.
	* @param pilha_objetos_param Um array com os objetos a serem reinseridos.
	*/
	private function reinserirObjetos(pilha_objetos_param:Array){
		var nome_objeto:String;
		var posicao:Point;
		var frame:Number;
		var identificacao:String;
		var pilhaObjetosParaReinserir:Array = pilha_objetos_param;
		
		while(0 < pilhaObjetosParaReinserir.length){
			nome_objeto = pilhaObjetosParaReinserir[pilhaObjetosParaReinserir.length - 1];
			frame = this[nome_objeto]._currentframe;
			posicao = new Point(this[nome_objeto]._x, this[nome_objeto]._y);
			identificacao = this[nome_objeto].getIdentificacaoBancoDeDados();
			if(ehPredio(nome_objeto)){
				removerObjeto(nome_objeto);
				adicionarPredioQuartos(_root.usuario_status.quarto_id, 
									   posicao.x, posicao.y, identificacao);
			} else if(ehCasa(nome_objeto)){
				removerObjeto(nome_objeto);
				adicionarCasa(frame, posicao.x, posicao.y, identificacao);
			} else if(ehArvore(nome_objeto)){
				removerObjeto(nome_objeto);
				frame = this[nome_objeto].getFrameTipoAparencia();
				adicionarArvore(frame, posicao.x, posicao.y, identificacao);
			} else {
				c_aviso_com_ok.mostrar("Favor atualizar a página, houve um erro ao salvar os objetos.");
			}
			pilhaObjetosParaReinserir.pop();
		}
	}
			
	/*
	* Infelizmente, AS2 não permite descobrir dinamicamente as classes de objetos.
	* Portanto, as três funções à seguir são provisórias, até que a mudança para AS3 seja feita (se for feita).
	* OBS1: Elas só funcionam para nomes de objetos que sejam ou prédios ou casas ou árvores. Também, estes objetos precisam ser filhos deste terreno.
	* OBS2: Pequenas mudanças nas classes podem destruir o funcionamento destas funções!
	*/
	private function ehPredio(nome_objeto_param:String):Boolean{
		if(!ehCasa(nome_objeto_param) 
		   and !ehArvore(nome_objeto_param)){
			return true;
		} else {
			return false;
		}
	}
	private function ehCasa(nome_objeto_param:String):Boolean{
		if(this[nome_objeto_param].getLink() != undefined){
			return true;
		} else {
			return false;
		}
	}
	private function ehArvore(nome_objeto_param:String):Boolean{
		if(this[nome_objeto_param].getFrameTipoAparencia() != undefined){
			return true;
		} else {
			return false;
		}
	}
	
	/*
	* @param permissao_param Determina se o personagem do usuário logado pode (true) ou não (false) se movimentar.
	*/
	public function definirPermissaoMovimentoMp(permissao_param:Boolean):Void{
		if(permissao_param){
			if(!em_edicao){
				permissaoMovimento = permissao_param;
			}
		} else {
			permissaoMovimento = permissao_param;
		}
	}
}