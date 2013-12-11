import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

//o botão precisa estar envolvido em um movieclip!
class c_btAbreTrocaEmail extends MovieClip {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
		
//métodos
	public function inicializar(){
		mx.events.EventDispatcher.initialize(this);

		/*Listeners*/
		onPress = funcaoOnPress;
		onRollOver = clarear;
		onRollOut = escurecer;
	}

	//---- Mouse
	private function funcaoOnPress(){
		dispatchEvent({target:this, type:"btAbreTrocaEmailPress"});
	}
	private function escurecer(){
		gotoAndStop(1);
	}
	private function clarear(){
		gotoAndStop(2);
	}
}
