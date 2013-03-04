import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

//o botão precisa estar envolvido em um movieclip!
class c_btVoltarCampos extends MovieClip {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
		
//métodos
	public function c_btVoltarCampos(){
		inicializar();
	}
	public function inicializar(){
		mx.events.EventDispatcher.initialize(this);
		
		/*Listeners*/
		onPress = funcaoOnPress;
		onRollOver = clarear;
		onRollOut = escurecer;
	}

	//---- Teclado
	private function funcaoOnPress(){
		dispatchEvent({target:this, type:"btVoltarCamposPress"});
	}
	public function escurecer(){
		gotoAndStop(1);
	}
	public function clarear(){
		gotoAndStop(2);
	}
	
}
