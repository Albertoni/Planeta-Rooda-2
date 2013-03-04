/*
* Classe para um simples botão.
*/

import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

/*
* Todo botão precisa estar envolvido em um MovieClip.
*
*/
class c_btAbre extends MovieClip {
//dados		
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "btAbre";
	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;
		
//métodos
	/*
	* Inicializar com o nome passado como parâmetro.
	* @param nome_param Nome que aparecerá no botão.
	*/
	public function inicializar(nome_param:String){
		mx.events.EventDispatcher.initialize(this);

		/*Listeners*/
		onPress = funcaoOnPress;
		onRollOver = clarear;
		onRollOut = escurecer;
		
		this['nome'].text = nome_param;
		
		escurecer();
	}

	//---- Mouse
	private function funcaoOnPress(){
		dispatchEvent({target:this, type:"btPressionado", nome: _name});
	}
	private function escurecer(){
		var formatoTexto:TextFormat = this['nome'].getTextFormat();
		formatoTexto.bold = true;
		formatoTexto.color = 0x333333;
		this['nome'].setTextFormat(formatoTexto);
	}
	private function clarear(){
		var formatoTexto:TextFormat = this['nome'].getTextFormat();
		formatoTexto.bold = true;
		formatoTexto.color = 0xCC6600;
		this['nome'].setTextFormat(formatoTexto);
	}
}
