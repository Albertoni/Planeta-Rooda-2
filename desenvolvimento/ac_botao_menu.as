/*
		Classe abstrata.
		
		Não deve ser instanciada.
		Sua construção deveria duplicar o movieclip botão_defaultmc, mas foi impossível determinar o depth do movieclip duplicado
		 para que ficasse acima do fundo branco do menu.
		A solução foi criar classes-filhas.
*/
class ac_botao_menu extends ac_objeto_do_menu{
//dados
	//---- Funcionalidade
	public var funcionalidade:ac_interface_menu;
	
	
	
//métodos
	public function ac_botao_menu(){
		
	}
	
	//---- MovieClip
	public function setPosicao(posx_menu_param:Number, posy_menu_param:Number):Void{
		this.posx_menu = posx_menu_param;
		this.posy_menu = posy_menu_param;
	}
	public function pressionado():Boolean{
		if(this.mc.hitTest(_xmouse, _ymouse)){
			return true;
		}
		else{
			return false;
		}
	}
	public function alturaMC():Number{
		return this.mc._height;
	}
	
	//---- Funcionalidade
	public function adicionarFuncionalidade(funcionalidade_param:ac_interface_menu){
		this.funcionalidade = funcionalidade_param;
	}
	public function ativarFuncionalidade(){
		this.funcionalidade.mostrar();
	}
	public function desativarFuncionalidade(){
		this.funcionalidade.esconder();
	}	
	
	
}