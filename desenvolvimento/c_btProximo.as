import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_btProximo extends MovieClip {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
//métodos
	public function inicializar() {
		mx.events.EventDispatcher.initialize(this);
		
		/*Listeners*/
		onPress = funcaoOnPress;
		onRollOver = escurecer;
		onRollOut = clarear;
	}
	
	//---- Teclado
	private function funcaoOnPress(){
		dispatchEvent({target:this, type:"btProximoPress"});
	}
	public function escurecer(){
		gotoAndStop(2);
	}
	public function clarear(){
		gotoAndStop(1);
	}
	
}
