import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;
import flash.geom.Point;

/*
* Um botão como a maioria dos que ficam no menu.
* Ajusta seu tamanho para ficar de acordo com o texto ou com um tamanho fornecido.
*/
class c_btGrande extends MovieClip {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;
	
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "btGrande";
	
	/*
	* Texto deste botão.
	*/
	private var texto:TextField;
	
	/*
	* Há dois fundos disponíveis, um claro e outro escuro.
	*/
	private var fundoClaro:MovieClip;
	private var fundoEscuro:MovieClip;
	
	/*
	* Cores do texto.
	*/
	private var COR_BRANCA:Number = 0xFFFFFF;
	private var COR_PRETA:Number = 0x000000;
	
//métodos
	/*
	* @param texto_param Texto a ser exibido no botão.
	* @param tamanho_especificado Tamanho que o botão deve ter. Se for undefined, o botão terá o tamanho necessário para conter seu texto.
	* 	Caso seu tamanho não seja suficiente para conter o texto, possuirá uma legenda que o complete.
	*/
	public function inicializar(texto_param:String, comprimento_param:Number, largura_param:Number){
		mx.events.EventDispatcher.initialize(this);
	
		atualizarTexto(texto_param, false);
		escurecer();

		if(comprimento_param == undefined and largura_param == undefined){
			definirTamanho(10 + texto.textWidth + 10, 5 + texto.textHeight + 5);
		} else if(comprimento_param == undefined){
			definirTamanho(10 + texto.textWidth + 10, largura_param);
		} else if(largura_param == undefined){
			definirTamanho(comprimento_param, 5 + texto.textHeight + 5);
		} else {
			definirTamanho(comprimento_param, largura_param);
		}

		/*Listeners*/
		onPress = funcaoOnPress;
		onRollOver = clarear;
		onRollOut = escurecer;
	}

	//---- Mouse
	private function funcaoOnPress(){
		dispatchEvent({target:this, type:"btPressionado", nome: _name});
	}
	private function escurecer(){
		fundoEscuro._visible = true;
		fundoClaro._visible = false;
		var formatoTexto:TextFormat = texto.getTextFormat();
		formatoTexto.bold = true;
		formatoTexto.color = COR_BRANCA;
		texto.setTextFormat(formatoTexto);
	}
	private function clarear(){
		fundoEscuro._visible = false;
		fundoClaro._visible = true;
		var formatoTexto:TextFormat = texto.getTextFormat();
		formatoTexto.bold = true;
		formatoTexto.color = COR_PRETA;
		texto.setTextFormat(formatoTexto);
	}
	/*
	* Define o tamanho total do botão.
	*/
	private function definirTamanho(comprimento_param:Number, largura_param:Number):Void{
		if(comprimento_param != undefined){
			fundoClaro._width = comprimento_param;
			fundoEscuro._width = comprimento_param;
		}
		if(largura_param != undefined){
			fundoClaro._height = largura_param;
			fundoEscuro._height = largura_param;
		}
		centralizarTexto();
	}
	
	/*
	* Centraliza o texto no botão.
	*/
	private function centralizarTexto():Void{
		texto._x = fundoClaro._x + (fundoClaro._width - texto._width)/2; 
		texto._y = fundoClaro._y + (fundoClaro._height - texto._height)/2;
	}
	
	/*
	* Atualiza o texto deste botão. Há opção para não ultrapassar os limites do botão (não modifica a fonte).
	* @param novo_texto_param Texto a ser colocado.
	* @param adaptarTamanho Indica se o texto deve ser adaptado ao tamanho do botão.
	*/
	private function atualizarTexto(novo_texto_param:String, adaptarTamanho:Boolean):Void{
		var tamanhoTexto:Object = texto.getTextFormat().getTextExtent(novo_texto_param);
		texto._width = tamanhoTexto.textFieldWidth + 5;
		texto._height = tamanhoTexto.textFieldHeight + 1;
		texto.text = novo_texto_param;
		if((fundoClaro._width-20) < texto._width and adaptarTamanho){
			texto._width = fundoClaro._width - 20;
		}
		if((fundoClaro._height-10) < texto._height and adaptarTamanho){
			texto._height = fundoClaro._height - 10;
		}
		centralizarTexto();
	}
}
