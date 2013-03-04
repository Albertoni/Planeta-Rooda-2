class c_barra_selecao extends ac_objeto_do_menu {
	private var mc:MovieClip;
	
	public function c_barra_selecao(){
		this.mc = _root.barraSelecao;
	}
	
	/*
	private function selecionado(botao:Object):Void{ 
		_root.barraSelecao._x = this.menuMC._x + botao._x + botao._width/2;
		_root.barraSelecao._y = this.menuMC._y + botao._y;
	}*/	//Faz uma barra de selecao aparecer embaixo do link mirado. - Giovani - 22.04.10
	
	public function selecionarBotão(botão_param:Object):Void{
		this.mc._x = botão_param._x;
		this.mc._y = botão_param._y;
	}
}