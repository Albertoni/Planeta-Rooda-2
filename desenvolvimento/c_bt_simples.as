import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

/*
* Todo botão precisa estar envolvido em um MovieClip.
*
*/
class c_bt_simples extends MovieClip {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;
		
//métodos
	public function c_bt_simples(){
		inicializar();
	}
	public function inicializar(){
		mx.events.EventDispatcher.initialize(this);

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
		gotoAndStop(1);
	}
	private function clarear(){
		gotoAndStop(2);
	}
}
