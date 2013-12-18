import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

/*
* Todo botão precisa estar envolvido em um MovieClip.
*
*/
class c_bt_toggle extends MovieClip{
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;
		
//métodos
	public function inicializar(){
		mx.events.EventDispatcher.initialize(this);
		_parent.debug.text += "ini("+this._name+")\n";
		/*Listeners*/
		onPress = this.funcaoOnPress;
	}

	//---- Mouse
	private function funcaoOnPress(){
		_parent.debug.text += "funcaoOnPress("+this._name+")\n";
		if(_currentframe == 1){
			gotoAndStop(2);
		} else {
			gotoAndStop(1);
		}
		dispatchEvent({target:this, type:"btPressionado", nome: _name});
	}
}
