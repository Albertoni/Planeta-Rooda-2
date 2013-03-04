import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_camposEditarDadosPlaneta extends MovieClip {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	

	
	
//métodos
	public function inicializar() {
		mx.events.EventDispatcher.initialize(this);

		
	}
	
	//---- Inteface
	public function abrirInterface(){
		_visible = true;
	}
	public function fecharInterface(){
		_visible = false;
	}


	//---- Getters
	public function getNome():String{
		return this['nome'].text;
	}
	public function getTipo():String{
		var planeta:c_planeta = new c_planeta();
		switch(true){
			case this['escola'].selected: return c_planeta.ESCOLA;
			break;
			case this['ano'].selected: return c_planeta.ANO; 
			break;
			case this['turma'].selected: return c_planeta.TURMA;
			break;
		}
	}
	
	public function getAparencia():String{
		switch(true){
			case this['verde'].selected: return c_terreno_bd.TIPO_VERDE;
				break;
			case this['grama'].selected: return c_terreno_bd.TIPO_GRAMA;
				break;
			case this['lava'].selected: return c_terreno_bd.TIPO_LAVA;
				break;
			case this['neve'].selected: return c_terreno_bd.TIPO_GELO;
				break;
			case this['urbano'].selected: return c_terreno_bd.TIPO_URBANO;
				break;
		}
	}
	
	//---- Setters
	public function setNome(nome_param:String):Void{
		this['nome'].text = nome_param;
	}
	public function setTipo(tipo_param:String):Void{
		var planeta:c_planeta = new c_planeta();
		switch(tipo_param){
			case c_planeta.ESCOLA: this['escola'].selected = true;
			break;
			case c_planeta.ANO: this['ano'].selected = true;
			break;
			case c_planeta.TURMA: this['turma'].selected = true;
			break;
		}
	}
	public function setAparencia(tipo_param:String):Void{
		switch(tipo_param){
			case c_terreno_bd.TIPO_VERDE: this['verde'].selected = true;
				break;
			case c_terreno_bd.TIPO_GRAMA: this['grama'].selected = true;
				break;
			case c_terreno_bd.TIPO_LAVA: this['lava'].selected = true;
				break;
			case c_terreno_bd.TIPO_GELO: this['neve'].selected = true;
				break;
			case c_terreno_bd.TIPO_URBANO: this['urbano'].selected = true;
				break;
		}
	}





}
