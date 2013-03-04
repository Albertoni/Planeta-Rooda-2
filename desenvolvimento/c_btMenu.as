import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;

class c_btMenu extends MovieClip {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
		
//métodos
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);

		/*Listeners*/
		onPress = funcaoOnPress;
		onRollOver = funcaoRollOver;
		onRollOut = funcaoRollOut;
	}
	
	//---- Mouse
	private function funcaoOnPress(){
		dispatchEvent({target:this, type:"btMenuPress"});
	}
	private function funcaoRollOver(){
		dispatchEvent({target:this, type:"btMenuOver"});
	}
	private function funcaoRollOut(){
		dispatchEvent({target:this, type:"btMenuOut"});
	}
	public function clarear(){
		gotoAndStop(1);
	}
	public function escurecer(){
		gotoAndStop(2);
	}
	
}
