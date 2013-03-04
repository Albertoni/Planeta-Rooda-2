import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_interface_edicao_terreno extends ac_interface_menu {
//dados		
	//---- Label
	public var LABEL_CRIAR_TERRENO:String = "CRIAR TERRENO";
	public var LABEL_EDITAR_TERRENO:String = "EDITAR TERRENO";

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Botões
	private var btConfirmar:c_btConfirmar;
	private var POSX_BT_CONFIRMAR:Number = 0;
	private var POSY_BT_CONFIRMAR:Number = 260;
	
	private var btCancelar:c_btCancelar;
	private var POSX_BT_CANCELAR:Number = 180;
	private var POSY_BT_CANCELAR:Number = 260;
	
	//---- Terreno Editado
	private var terreno_editado:c_terreno_bd;
	
//métodos
	public function inicializar() {
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();
		
		attachMovie("btConfirmar", "btConfirmar", getNextHighestDepth());
		btConfirmar.inicializar();
		btConfirmar._x = POSX_BT_CONFIRMAR;
		btConfirmar._y = POSY_BT_CONFIRMAR;
		btConfirmar.addEventListener("btConfirmarPress", Delegate.create(this, confirmar));	
		btConfirmar._visible = true;
		
		attachMovie("btCancelar", "btCancelar", getNextHighestDepth());
		btCancelar.inicializar();
		btCancelar._x = POSX_BT_CANCELAR;
		btCancelar._y = POSY_BT_CANCELAR;
		btCancelar.addEventListener("btCancelarPress", Delegate.create(this, cancelar));	
		btCancelar._visible = true;
	}
	
	//---- Interface
	private function confirmar():Void{
		dispatchEvent({target:this, type:"confirmar"});
	}
	private function cancelar():Void{
		dispatchEvent({target:this, type:"cancelar"});
	}
	
	//---- Getters
	public function getTerrenoSendoEditado():c_terreno_bd{
		return terreno_editado;
	}
	public function getNome():String{
		return this['nome'].text;
	}
	
	//---- Setters
	public function setTerreno(terreno_editado_param:c_terreno_bd):Void{
		terreno_editado = terreno_editado_param;
	}
	public function setLabel(label_param:String):Void{
		var formatoAtualComBold:TextFormat = new TextFormat();
		formatoAtualComBold = this['label'].getTextFormat();
		formatoAtualComBold.bold = true;
		this['label'].setNewTextFormat(formatoAtualComBold);
		this['label'].replaceText(0, this['label'].length, label_param);
	}
	public function setNome(nome_param:String):Void{
		this['nome'].text = nome_param;
	}
	
}
