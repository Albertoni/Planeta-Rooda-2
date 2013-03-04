import mx.utils.Delegate;


class ac_interface_menu extends MovieClip{	
//Dados
	//---- Servidor
	private var envia:LoadVars;
	private var recebe:LoadVars;
	
//Métodos
	private function inicializacoes(){
	}
	
	//---- Interface
	public function mostrar():Void{
		_visible = true;
	}
	public function esconder():Void{
		_visible = false;
	}
	public function abrirInterface():Void{
		mostrar();
	}
	public function fecharInterface():Void{
		esconder();
	}

	//---- Dados
	//Determina se o dado passado: (está vazio, não foi setado)=false, (tem conteúdo)=true.
	private function informado(dado_param:String):Boolean{
		var stringVazia:String = new String();
		if(dado_param != undefined and dado_param != stringVazia){
			return true;
		}
		else{
			return false;
		}
	}
	private function dadosRecebidos():Boolean{ return false; } //Deve ser implementada em cada classe que use este template.
}
















