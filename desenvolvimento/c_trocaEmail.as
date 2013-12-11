import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_trocaEmail extends MovieClip {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Botões
	public var abreTrocaEmail:c_btAbreTrocaEmail;
	private var POSX_BT_ABRE_TROCA_EMAIL:Number = 0;
	private var POSY_BT_ABRE_TROCA_EMAIL:Number = 0;
	
//métodos
	public function inicializar() {
		mx.events.EventDispatcher.initialize(this);
		
		this['campos'].novoEmail.multiline = false;
		this['campos']._visible = false;
		
		/*Listeners*/
		attachMovie("abre_troca_email", "abreTrocaEmail", getNextHighestDepth());
		abreTrocaEmail.inicializar();
		abreTrocaEmail._x = POSX_BT_ABRE_TROCA_EMAIL;
		abreTrocaEmail._y = POSY_BT_ABRE_TROCA_EMAIL;
		abreTrocaEmail.addEventListener("btAbreTrocaEmailPress", Delegate.create(this, abrir));	
		
		this['campos']._visible = false;
	}
	
	//---- Inteface
	public function abrirInterface(){
		this['campos']._visible = true;
	}
	public function fecharInterface(){
		this['campos']._visible = false;
	}
	
	//---- Teclado
	private function abrir(){
		dispatchEvent({target:this, type:"abrirTrocaEmail"});
	}
	
	//---- Getters
	public function getNovoEmail():String{
		return this['campos'].novoEmail.text;
	}


}
