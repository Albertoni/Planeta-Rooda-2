/*
* Interface do menu que permite acesso às interfaces de edição de planetas, turmas e contas.
*/

import mx.utils.Delegate;

class c_interface_edicoes extends ac_interface_menu{
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "interfaceMenuEdicoes";
	
	/*
	* Esta interface possui links para outras, que podem ser acionados quando esta interface estiver aberta.
	* As outras ficam armazenadas no seguinte array:
	*/
	private var interfaces:Array;
	
	/*
	* Botões usados para abrir as interfaces.
	*/
	private var botoesInterfaces:Array;
	private static var ESPACAMENTO_BOTOES:Number = 40;
	
	/*
	* Interface que está sendo mostrada aqui, uma das cadastradas em no array interfaces.
	*/
	private var interfaceAtiva:ac_interface_menu;
	
//métodos
	public function inicializar(){
		mx.events.EventDispatcher.initialize(this);
		
		super.inicializacoes();
		
		interfaces = new Array();
		botoesInterfaces = new Array();
		
		interfaceAtiva = undefined;
	}
	
	public function mostrar():Void{
		super.mostrar();
		for(var botao:Number=0; botao<botoesInterfaces.length; botao++){
			botoesInterfaces[botao]._visible = true;
		}
		interfaceAtiva.mostrar();
	}
	public function esconder():Void{
		for(var botao:Number=0; botao<botoesInterfaces.length; botao++){
			botoesInterfaces[botao]._visible = false;
		}
		interfaceAtiva.esconder();
		super.esconder();
	}
	
	/*
	* Adiciona uma interface e um botão, cuja funcionalidade será abrir a interface adicionada.
	* @param interface_param Interface que será adicionada e poderá ser acessada.
	* @param nome_param Um nome para a função, que será exibido em seu botão correspondente.
	*/
	public function adicionarInterface(interface_param:ac_interface_menu, nome_param:String):Void{
		var botaoAbrirInterface:c_btAbre;
		attachMovie(c_btAbre.LINK_BIBLIOTECA, nome_param, getNextHighestDepth());
		botaoAbrirInterface = this[nome_param];
		botaoAbrirInterface.inicializar(nome_param);
		botaoAbrirInterface.addEventListener("btPressionado", Delegate.create(this, abrirInterface));
		
		interface_param._x += 30;
		
		botoesInterfaces.push(botaoAbrirInterface);
		interfaces.push(interface_param);
		
		restaurarPosicoesBotoes();
	}
	
	/*
	* Abre uma interface abaixo do botão que a abre.
	* @param evento_param Evento do botão que foi pressionado.
	*/
	private function abrirInterface(evento_param:Object):Void{
		var indiceBotao:Number=0;
		var indiceInterface:Number=0;
		var botao_foiEncontrado:Boolean = false;
		
		while(!botao_foiEncontrado and indiceBotao < botoesInterfaces.length){
			if(botoesInterfaces[indiceBotao]._name == evento_param.nome){
				botao_foiEncontrado = true;
			} else {
				indiceBotao++;
			}
		}
		
		if(botao_foiEncontrado){
			if(interfaceAtiva != undefined){
				restaurarPosicoesBotoes();
				interfaceAtiva.esconder();
			}
			indiceInterface = indiceBotao;
			interfaceAtiva = interfaces[indiceInterface];
			interfaceAtiva._y = botoesInterfaces[indiceBotao]._y - 170;
			jogarBotoesParaBaixo(indiceBotao+1);
			interfaceAtiva.mostrar();
		}
	}
	
	/*
	* Dado um índice de botão, joga todos os outros para baixo, de forma a acomodar uma interface.
	* @param indice_botao_param Indice do botão à partir do qual todos serão jogados para baixo.
	*/
	private function jogarBotoesParaBaixo(indice_botao_param:Number):Void{
		var indiceBotao;
		var espacoExtra = (indice_botao_param==1? 30 : 0);
		for(indiceBotao = indice_botao_param; indiceBotao < botoesInterfaces.length; indiceBotao++){
			botoesInterfaces[indiceBotao]._y += interfaceAtiva._height - espacoExtra;
		}
	}
	
	/*
	* Joga os botões para suas posições normais, em que ficam quando não há interface aberta.
	*/
	private function restaurarPosicoesBotoes():Void{
		var indiceBotao;
		for(indiceBotao = 0; indiceBotao < botoesInterfaces.length; indiceBotao++){
			botoesInterfaces[indiceBotao]._x = 0;
			botoesInterfaces[indiceBotao]._y = indiceBotao* ESPACAMENTO_BOTOES;
		}
	}
	
	
}
