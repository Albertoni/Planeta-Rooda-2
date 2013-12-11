/**
 * ...
 * @author ...
 */

import mx.utils.Delegate;
 
dynamic class c_comunicador_menu extends MovieClip{
	private var abaTerreno:MovieClip;
	private var abaContato:MovieClip;
	private var abaSistema:MovieClip;
	private var espacoDireita:MovieClip;
	private var espacoEsquerda:MovieClip;
	private var addContato:TextField;
	private var botaoAddContato:MovieClip;
	private var novaMsg:Boolean;
	private var desligarBaloes:MovieClip;
	private var initObject:Object;
	private var colunaDireitaTerreno:c_botoes_comunicador;
	private var colunaDireitaSistemas:c_botoes_comunicador;
	private var colunaEsquerdaSistemas:c_botoes_comunicador;
	private var colunaDireitaContatos:c_botoes_comunicador;
	
	private var listaContatos:Array;      //array de arrays de duas posicoes, onde a posicao 0 eh o nome e a posicao 1 eh o id
	private var listaGrupos:Array;
	
	//private var botoesContatos:c_botoes_comunicador;
	//private var botoesSistemas:c_botoes_comunicador;
	
	private var abaAtiva:Number = 1;     //o numero da aba(frame) que estah ativa no momento
	
	
	function c_comunicador_menu() {
		//this.colunaDireita._visible = false;
		//this.colunaEsquerda._visible = false;
		//this.opcoesInicializacao();
		this.novaMsg = false;
		this.guardarContatos();
		this.guardarGrupos();		
		
		this.attachMovie("botoesComunicador", "colunaDireitaTerreno", this.getNextHighestDepth())		
		this.attachMovie("botoesComunicador", "colunaDireitaSistemas", this.getNextHighestDepth())
		this.attachMovie("botoesComunicador", "colunaEsquerdaSistemas", this.getNextHighestDepth())
		this.attachMovie("botoesComunicador", "colunaDireitaContatos", this.getNextHighestDepth())
		
		this.colunaDireitaTerreno._x      = this.espacoDireita._x;
		this.colunaDireitaTerreno._y      = this.espacoDireita._y;
		this.colunaDireitaTerreno._height = this.espacoDireita._height;
		this.colunaDireitaTerreno._width  = this.espacoDireita._width;	
		
		this.colunaDireitaSistemas._x      = this.espacoDireita._x;
		this.colunaDireitaSistemas._y      = this.espacoDireita._y;
		this.colunaDireitaSistemas._height = this.espacoDireita._height;
		this.colunaDireitaSistemas._width  = this.espacoDireita._width;		
		
		this.colunaEsquerdaSistemas._x      = this.espacoEsquerda._x;
		this.colunaEsquerdaSistemas._y      = this.espacoEsquerda._y;
		this.colunaEsquerdaSistemas._height = this.espacoEsquerda._height;
		this.colunaEsquerdaSistemas._width  = this.espacoEsquerda._width;
		
		this.colunaDireitaContatos._x      = this.espacoDireita._x;
		this.colunaDireitaContatos._y      = this.espacoDireita._y;
		this.colunaDireitaContatos._height = this.espacoDireita._height;
		this.colunaDireitaContatos._width  = this.espacoDireita._width;
		
		//this.colunaDireitaTerreno.setListaContatos(lista);
		//this.colunaDireitaTerreno.addEventListener("botaoClicado", Delegate.create(this, this.clickBotao));	
		this._parent._parent.organizador_caixas_texto.addEventListener("recebidoChatconversa", Delegate.create(this, this.recebidoChat));
		this._parent._parent.organizador_caixas_texto.addEventListener("recebidoChatcontato", Delegate.create(this, this.recebidoChat));
		this._parent._parent.organizador_caixas_texto.addEventListener("recebidoChatgrupo", Delegate.create(this, this.recebidoChat));		
		
		this.colunaEsquerdaSistemas.tipo = 3;
		this.colunaEsquerdaSistemas.setListaContatos(this.listaGrupos,"grupo");
		this.colunaEsquerdaSistemas.addEventListener("botaoClicado", Delegate.create(this, this.clickBotao));		
		
		this.colunaDireitaContatos.tipo = 2;
		this.colunaDireitaContatos.setListaContatos(this.listaContatos, "contato");
        this.colunaDireitaContatos.addEventListener("botaoClicado", Delegate.create(this, this.clickBotao));		
		
		
		this._parent.interval = -1;
		
		this.abaTerreno.nBotoesPiscando = 0;
		this.abaTerreno.interval = -1;
		this.abaSistema.nBotoesPiscando = 0;
		
		this.abaSistema.interval = -1;
		this.abaContato.nBotoesPiscando = 0;
		this.abaContato.interval = -1;
		
		this.setAbaAtiva(1);				
		
		this.abaTerreno["comunicador_menu"] = this;
		this.abaTerreno.onRelease = function() {
			this.comunicador_menu.setAbaAtiva(1);	
			comunicador.organizador_caixas_texto.set_caixa_texto_ativa("conversa","conversa");
		}	
		
		this.abaSistema["comunicador_menu"] = this;
		this.abaSistema.onRelease = function() {		
			this.comunicador_menu.setAbaAtiva(2);			
		}	
		
		this.abaContato["comunicador_menu"] = this;
		this.abaContato.onRelease = function() {
			this.comunicador_menu.setAbaAtiva(3);
			this.comunicador_menu.addContato["comunicador_menu"] = this.comunicador_menu;
			Key.addListener(this.comunicador_menu.addContato);
			this.comunicador_menu.addContato.onKeyDown = function(){
				if ((Key.getCode() == Key.ENTER) and (Selection.getFocus() == targetPath(this))) {
					this.comunicador_menu.adicionarContato(this.text, this.comunicador_menu.listaContatos, this.comunicador_menu.colunaDireitaContatos);													
					this.text = "";
				}
			}
			this.comunicador_menu.botaoAddContato["comunicador_menu"] = this.comunicador_menu;
			this.comunicador_menu.botaoAddContato.onRelease = function() {
				this.comunicador_menu.adicionarContato(this.text, this.comunicador_menu.listaContatos, this.comunicador_menu.colunaDireitaContatos);													
				this.text = "";
			}
		}		
		
		this.desligarBaloes.gotoAndStop(1);
		this.desligarBaloes.hide = false;		
		this.desligarBaloes.onPress = function() {
			if (this.hide == false) {
				this.hide = true;
				this.gotoAndStop(2);
				mp.fala._visible = false;
				//chamar aki funcao que deixa invisivel baloes de todos ops				
			}
			else {
				this.hide = false;
				this.gotoAndStop(1);
				mp.fala._visible = true;
				//chamar aki funcao deixa visivel os baloes de todos ops
			}
		}		
	}
	
	
	private function guardarGrupos(Void):Void {
		var temp:Array = new Array();
		temp = usuario_status.lista_grupos.split(",");								
		this.listaGrupos = new Array();
		
		var I:Number;
	    for (I = 0 ; I < temp.length ; I++) {
			if (temp[I] != ""){
			    this.listaGrupos.push(new Array(temp[I].substring(0, temp[I].indexOf("#")),
			                                    temp[I].substring(temp[I].indexOf("#") + 1)
					    						 )
						    		   );
			}			
		}			
	}
	
	private function guardarContatos(Void):Void {
		var temp:Array = new Array();
		//exemplo de usuario_status.lista_contatos:String
		//           roger2#72,guto#15,gt#50,gabriel#73,Giovani#74,
		
		temp = usuario_status.lista_contatos.split(",");		
		this.listaContatos = new Array();
		
		var I:Number;
	    for (I = 0 ; I < temp.length ; I++) {
			if (temp[I] != ""){
			    this.listaContatos.push(new Array(temp[I].substring(0, temp[I].indexOf("#")),
			                                      temp[I].substring(temp[I].indexOf("#") + 1)
					    						 )
						    		   );
			}
		}		
	}	
	
	//alterna entre o frame 1 e o 2 de um Movieclip
	//utilizado dar o efeito de piscada
	private function alternar_frame(aba:MovieClip) {		
		if (aba.interval != -1) {
			if (aba._currentframe == 1) {
				aba.gotoAndStop(2);
			}
			else {
				aba.gotoAndStop(1);
			}
		}
	}
	
	private function piscar_stop(aba:MovieClip) {	
		if (aba.interval != -1){
		    clearInterval(aba.interval);
		    aba.interval = -1;
		    aba.gotoAndStop(2); //pq assume-se que a aba soh parou de piscar pq foi clicada
		}
	}
	
	private function recebidoChat(evento:Object) {
		var tempoIntervalo:Number = 1000;		
		switch(evento.tipo) {
			case "conversa":
			    if (( this.abaAtiva != 1 ) and (this.abaTerreno.interval == -1)) {
			        this.abaTerreno.interval = setInterval(this, "alternar_frame", tempoIntervalo, this.abaTerreno);
				}		        
			break;
			case "contato":
			    if ((this.abaAtiva != 3) and (this.abaContato.interval == -1)){					
				    this.abaContato.interval = setInterval(this, "alternar_frame", tempoIntervalo, this.abaContato);
				}			    
			break;
			case "grupo":
			    if ((this.abaAtiva != 2) and (this.abaSistema.interval == -1)){
					this.abaSistema.interval = setInterval(this, "alternar_frame", tempoIntervalo, this.abaSistema);			    
				}
			break;
		}
		if ((this._parent._currentframe == 1) and (this._parent.botaoPrincipal.interval == -1)) {   // this._parent eh o header
			this._parent.botaoPrincipal.interval = setInterval(this, "alternar_frame", tempoIntervalo, this._parent.botaoPrincipal);
		}
	}
	
	private function clickBotao(evento:Object) {		 
		if (comunicador.organizador_caixas_texto.existe_caixa_texto(evento.nomeBotao,evento.tipo) == false) {			
	        comunicador.organizador_caixas_texto.nova_caixa_texto(evento.nomeBotao, evento.id, evento.tipo);
			comunicador.organizador_caixas_texto.set_caixa_texto_ativa(evento.nomeBotao, evento.tipo);
	    }
	    else {
		    comunicador.organizador_caixas_texto.set_caixa_texto_ativa(evento.nomeBotao, evento.tipo);
	    }		
	}	
	private function setAbaAtiva(aba:Number):Void {		
		var tempoIntervalo:Number = 1000;
		this.abaAtiva = aba;		
		this.abaTerreno.gotoAndStop(1);		
		this.abaContato.gotoAndStop(1);
		this.abaSistema.gotoAndStop(1);
		this.gotoAndStop(aba);
		
		switch(aba) {
			case 1:
			
				this.piscar_stop(this.abaTerreno);
				
				if ((this.abaSistema.interval == -1) and (this.abaSistema.nBotoesPiscando > 0)){					
				    this.abaSistema.interval = setInterval(this, "alternar_frame", tempoIntervalo, this.abaSistema);
				}
				if ((this.abaContato.interval == -1) and (this.abaContato.nBotoesPiscando > 0)){					
				    this.abaContato.interval = setInterval(this, "alternar_frame", tempoIntervalo, this.abaContato);					
				}
				this.abaTerreno.gotoAndStop(2);				
			    this.colunaDireitaTerreno._visible   = true;			
			    this.colunaDireitaContatos._visible = false;
		        this.colunaDireitaSistemas._visible = false;
		        this.colunaEsquerdaSistemas._visible = false;
				this.espacoDireita._visible = true;
	            this.espacoEsquerda._visible = false;
			break;
			case 2:			    
				this.piscar_stop(this.abaSistema);
				if ((this.abaContato.interval == -1) and (this.abaContato.nBotoesPiscando > 0)){					
				    this.abaContato.interval = setInterval(this, "alternar_frame", tempoIntervalo, this.abaContato);
				}
				if ((this.abaTerreno.interval == -1) and (this.abaTerreno.nBotoesPiscando > 0)){					
				    this.abaTerreno.interval = setInterval(this, "alternar_frame", tempoIntervalo, this.abaTerreno);
				}
			    this.abaSistema.gotoAndStop(2);
			    this.colunaDireitaSistemas._visible  = true;
		        this.colunaEsquerdaSistemas._visible = true;			
			    this.colunaDireitaTerreno._visible   = false;
				this.colunaDireitaContatos._visible  = false;		        
				this.espacoDireita._visible          = false;
	            this.espacoEsquerda._visible         = false;
			break;
			case 3:
			    this.piscar_stop(this.abaContato);
				if ((this.abaTerreno.interval == -1) and (this.abaTerreno.nBotoesPiscando > 0)){					
				    this.abaTerreno.interval = setInterval(this, "alternar_frame", tempoIntervalo, this.abaTerreno);
				}
				if ((this.abaSistema.interval == -1) and (this.abaSistema.nBotoesPiscando > 0)){					
				    this.abaSistema.interval = setInterval(this, "alternar_frame", tempoIntervalo, this.abaSistema);
				}
			    this.abaContato.gotoAndStop(2);
			    this.colunaDireitaContatos._visible  = true;			
			    
			    this.colunaDireitaTerreno._visible   = false;				
		        this.colunaDireitaSistemas._visible  = false;
		        this.colunaEsquerdaSistemas._visible = false;
				this.espacoDireita._visible          = false;
	            this.espacoEsquerda._visible         = false;
			break;
		}
		
	}	
	
	private function initColuna(lado:String):Object {
		var initObject:Object;
		if (lado == "esquerda"){
		    initObject = new Object ( { _x: this.espacoEsquerda._x,
			                            _y: this.espacoEsquerda._y,
										_height: this.espacoEsquerda._height,
										_width: this.espacoEsquerda._width} );
		}
		else if (lado == "direita") {
			initObject = new Object ( { _x: this.espacoDireita._x,
			                            _y: this.espacoDireita._y,
										_height: this.espacoDireita._height,
										_width: this.espacoDireita._width } );
			
		}
		
		return initObject;
	}
	
	//-----------------------------------------------------------------------------------------------------------
	//  funcao que adiciona um contato na lista de contatos do comunicador
	//-----------------------------------------------------------------------------------------------------------
	private function adicionarContato(nomeContato:String, lista:Array, listaBotoes:c_botoes_comunicador):Void {
		//	verifica se o contato jah nao esta presente na lista de contatos (lista)
		//  se estiver devolve uma msg e sai da funcao
		//  se nao estiver na lista procura no banco de dados o contato
		//  se encontrar retorna o id do personagem e atualiza o bd constando o personagem como contato do usuario
		//  guarda as informações na lista de contatos (organizar alfabeticamente no futuro)
		//  chama o metodo da listaBotoes passada como parametro para reorganiza-la com a nova lista
		
		var I:Number;
		for (I = 0 ; I < lista.length ; I++) {
			if (lista[I][0] == nomeContato) {
				mp.fala.text = "já possuo esse contato";	
				return;
			}
		}	
		
		var envia:LoadVars = new LoadVars;
		var recebe:LoadVars = new LoadVars;	
		envia.action = "11";
		envia.personagem_id = usuario_status.personagem_id;
		envia.nomeContato = nomeContato;
		
		
		//recebe.lengthAnterior = lista.length;
		
		recebe.lista = lista;
		recebe.listaBotoes = listaBotoes;
		recebe.onLoad = function(sucess) {
			if (sucess) {				    			
				if (this.usuario_personagem_id == "ERRO") { //nao encontrou usuario no banco de dados
					
				    var tempX: Number = usuNencontrado._x;
	                var tempY: Number = usuNencontrado._y;
					
					function mover_movieClip(MC:MovieClip , x:Number , y:Number):Void {
	                    MC._x = x;
						MC._y = y;	
						bloqMov = false;
					}
					
					usuNencontrado._x = (Stage.width/2) - (usuNencontrado._width / 2);
					usuNencontrado._y = (Stage.height / 2) - (usuNencontrado._height / 2);		
					usuNencontrado.swapDepths(_root.getNextHighestDepth());
					bloqMov = true;
					setTimeout(mover_movieClip, 2000, usuNencontrado, tempX, tempY);//move mensagem "nao encontrei essa pessoa" para o centro da tela e tranca os comandos por 2 seg					
 				    return;
			    }						
		        this.lista.push(new Array(this.usuario_personagem_nome, this.usuario_personagem_id ));		    			
				this.listaBotoes.setListaContatos(this.lista,this.listaBotoes.getTipoContatos());				
		    }	
	    }
		envia.sendAndLoad("interface_bd_personagem.php", recebe, "POST");	
    }

	
}