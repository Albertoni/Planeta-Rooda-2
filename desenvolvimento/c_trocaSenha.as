import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_trocaSenha extends MovieClip {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Botões
	public var abreTrocaSenha:c_btAbreTrocaSenha;
	private var POSX_BT_ABRE_TROCA_SENHA:Number = 0;
	private var POSY_BT_ABRE_TROCA_SENHA:Number = 0;
	
//métodos
	public function inicializar() {
		mx.events.EventDispatcher.initialize(this);
		
		this['campos'].novaSenha.multiline = false;
		this['campos'].novaSenha.password = true;
		this['campos']._visible = false;
		
		/*Listeners*/
		attachMovie("abre_troca_senha", "abreTrocaSenha", getNextHighestDepth());
		abreTrocaSenha.inicializar();
		abreTrocaSenha._x = POSX_BT_ABRE_TROCA_SENHA;
		abreTrocaSenha._y = POSY_BT_ABRE_TROCA_SENHA;
		abreTrocaSenha.addEventListener("btAbreTrocaSenhaPress", Delegate.create(this, abrir));	
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
		dispatchEvent({target:this, type:"abrirTrocaSenha"});
	}
	
	//---- Getters
	public function getNovaSenha():String{
		return this['campos'].novaSenha.text;
	}
	
	

	
	
}
