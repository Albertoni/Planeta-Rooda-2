import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_btOk extends MovieClip {
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
		onRollOver = clarear;
		onRollOut = escurecer;
	}
	
	//---- Teclado
	private function funcaoOnPress(){
		dispatchEvent({target:this, type:"btOk"});
	}
	private function escurecer(){
		gotoAndStop(1);
	}
	private function clarear(){
		gotoAndStop(2);
	}
}
