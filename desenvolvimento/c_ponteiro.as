class c_ponteiro extends MovieClip {
//dados
	/**
	 * O ponteiro do mouse, único que existe.
	 */
	 public static var PONTEIRO:c_ponteiro;
		
//métodos
	public static function inicializar() {
		PONTEIRO = _root.ponteiro;
		PONTEIRO.onMouseMove = PONTEIRO.atualizar;
		PONTEIRO.gotoAndStop(1);
	}
	
	//---- Atualização
	private function atualizar(){
		var frame:Number;
		
		PONTEIRO._x = PONTEIRO._parent._xmouse;
		PONTEIRO._y = PONTEIRO._parent._ymouse;
		Mouse.hide();                           //some com o ponteiro do mouse default do windows - Roger - 17/07/2009
		//updateAfterEvent();
		
		//var frame:Number = _root.mp._currentframe;
		//frame = _root.mp._currentframe;_root.mp.fala.text+=frame+",";
		//_root.usuario_status.personagem.congelar_aparencia_frame(_root.usuario_status.personagem.direcaoQueOlha(), frame);
		//_root.mp.gotoAndStop(frame);
	}
	
}
