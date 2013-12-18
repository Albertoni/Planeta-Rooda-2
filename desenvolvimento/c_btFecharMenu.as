import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

//o botão precisa estar envolvido em um movieclip!
class c_btFecharMenu extends MovieClip {
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
		onRollOver = marcarComoPressionado;
		onRollOut = desmarcar;
	}

	//---- Mouse
	private function funcaoOnPress(){
		dispatchEvent({target:this, type:"btFecharMenuPress"});
	}
	private function marcarComoPressionado(){
		gotoAndStop(2);
	}
	private function desmarcar(){
		gotoAndStop(1);
	}
}
