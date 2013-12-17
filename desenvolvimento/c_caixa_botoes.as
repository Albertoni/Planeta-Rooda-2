/*
		E uma regiao retangular contendo um numero variado de botoes,
	com funcoes variadas atribuídas.
	
		Uma caixa pode ter um unico botao pressionado/com sua funcionalidade ativa.
*/
class c_caixa_botoes extends MovieClip{
//dados:
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "caixa_botoes";

	//caixa
	private var lista_dos_botoes:Array = new Array();
	private var numero_botoes:Number = 0;
	private var espaco_entre_botoes:Number = 22.5;

	//botoes
	private var altura_cada_botao:Number;//Assume-se que todos os botoes têm a mesma altura.

//metodos:
	public function inicializar(){
	}

	//funcoes da caixa
	public function adicionar_botao(botao_param:MovieClip):Void{
		altura_cada_botao = botao_param._height;
		numero_botoes += 1;
		
		lista_dos_botoes.push( botao_param );
	}

	//funcoes dos botoes
	public function haBotoes():Boolean{
		if(1 <= numero_botoes){
			return true;
		} else {
			return false;
		}
	}
	private function haEspacoParaTodosBotoes():Boolean{
		//if(this.numero_botoes*(this.altura_cada_botao+this.espaco_entre_botoes) <= this.largura){
			return true;
		//}
		//else{
			//return false;
		//}
	}
	public function reposicionarBotoes():Void{
		var posy_botao_mais_acima:Number;
		
		if(6 < numero_botoes){
			_y += 40;
		}
		if(haBotoes() and haEspacoParaTodosBotoes()){
			if(numero_par(numero_botoes)){
				posy_botao_mais_acima = _y + _width/2 + (espaco_entre_botoes+altura_cada_botao);
				posy_botao_mais_acima -= (numero_botoes/2)*(espaco_entre_botoes+altura_cada_botao);
			} else {
				posy_botao_mais_acima = _y + _width/2 + (espaco_entre_botoes+altura_cada_botao/2);
				posy_botao_mais_acima -= ((numero_botoes - 1)/2)*(espaco_entre_botoes+altura_cada_botao);
			}
			
			for(var i:Number=0; i<numero_botoes; i++){
				lista_dos_botoes[i]._x = _x;
				lista_dos_botoes[i]._y = posy_botao_mais_acima + i*espaco_entre_botoes;
			}
		}
	} //sistema de posicionamento dos botoes capaz de criar menus para qualquer configuracao de funcionalidades disponíveis.
	public function mostrarTodosBotoes():Void{
		for(var i:Number=0; i<numero_botoes; i++){
			lista_dos_botoes[i]._visible = true;
		}
	}
	public function esconderTodosBotoes():Void{
		for(var i:Number=0; i<numero_botoes; i++){
			lista_dos_botoes[i]._visible = false;
		}
	}
	
	//auxiliares
	private function numero_par(numero_param:Number):Boolean{
		if(numero_param%2 == 0){
			return true;
		} else {
			return false;
		}
	}
	
	
}