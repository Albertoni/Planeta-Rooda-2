/*
* A barra de texto do comunicador.
* Campo de texto em que o usuário digita o que quer falar.
* As falas podem ser enviadas tanto via [Enter] como por um botão.
*/

class c_fala_comunicador extends MovieClip{
//dados	
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "fala_comunicador";

	/*
	* Atalhos para chats.
	* Ao digita um destes, seguido de enter, o usuário passa a falar com o chat ao qual corresponde o atalho.
	*/
	private static var INICIO_ATALHO:String = "-";
	public static var ATALHO_CHAT_TERRENO:String = INICIO_ATALHO.concat("t");
	public static var ATALHO_CHAT_TURMA:String = INICIO_ATALHO.concat("p");
	public static var ATALHO_CHAT_PRIVADO:String = INICIO_ATALHO.concat("a"); //Possui uma primeira opção, no estilo "-a 'nome de pessoa'"
	public static var ATALHO_CHAT_AMIGO:String = INICIO_ATALHO.concat("g"); //Possui uma primeira opção, no estilo "-g 'nome de chat'"
	public static var ATALHO_CRIAR_CHAT_AMIGO:String = INICIO_ATALHO.concat("cg"); //Possui uma primeira opção, no estilo "-cg 'nome de chat'"
	public static var ATALHO_SAIR_CHAT_AMIGO:String = INICIO_ATALHO.concat("sg");
	public static var ATALHO_CHAT_AMIGO_CONVIDAR:String = INICIO_ATALHO.concat("c"); //Possui uma primeira opção, no estilo "-gc 'nome de pessoa'"
	public static var ATALHO_VER_TODOS_CHATS:String = INICIO_ATALHO.concat("v");
	public static var ATALHO_VER_CHAT_TERRENO:String = INICIO_ATALHO.concat("vt");
	public static var ATALHO_VER_CHAT_TURMA:String = INICIO_ATALHO.concat("vp");
	public static var ATALHO_VER_CHAT_AMIGO:String = INICIO_ATALHO.concat("vg");
	public static var ATALHO_AJUDA:String = INICIO_ATALHO.concat("ajuda");
	public static var SEM_ATALHOS:String = "";
	
	/*
	* A caixa de texto.
	*/
	private var fala:TextField;
	
	/*
	* Função a ser executada toda vez que alguma fala for digitada aqui.
	*/
	private var falaDigitada:Function;
	
	/*
	* Onde falaDigitada deve ser executada.
	*/
	private var escopoFalaDigitada:Object;
	
//métodos
	/*
	* @param falaDigitada_param Função a ser executada toda vez que alguma fala for digitada aqui.
	*/
	public function inicializar(falaDigitada_param:Function, escopoFalaDigitada_param:Object){
		falaDigitada = falaDigitada_param;
		escopoFalaDigitada = escopoFalaDigitada_param;
		
		fala.text = "";
		fala.border = false;
		
		Key.addListener(fala);                 //inicializacao do listener		
		fala.onKeyDown = function() {
			if ((Key.getCode() == Key.ENTER) and (Selection.getFocus() == targetPath(this))) {
				_parent.filtrarTextoSemPerdas();
				
				if((this.text) != ""){
					_parent.falaDigitada.call(_parent.escopoFalaDigitada);
				}
			}
		}
		
	}
	
	/*
	* @return O texto digitado.
	*/
	public function getTexto():String{
		return fala.text;
	}
	
	/*
	* Apaga o texto.
	*/
	public function limparTexto():Void{
		fala.text = "";
	}
	
	/*
	* Filtra a caixa de texto para que não deixe passar mensagens indesejadas.
	* Exemplos são: mensagens vazias, só com espaços, tabulações e enters.
	* Também filtra o caso do primeiro caractere ser um enter.
	* Importante: Notas que só são filtrados caracteres absolutamente indesejáveis, qualquer que seja o uso do que estiver escrito.
	*/
	private function filtrarTextoSemPerdas():Void{
		if((fala.text).charCodeAt(0) == Key.ENTER){
			fala.text = (fala.text).substr(1,(fala.text).length);
			while((fala.text != "") and ((fala.text).charCodeAt(0) == Key.ENTER)){
				fala.text = (fala.text).substr(1,(fala.text).length);
			}
		}
	}
	
	/*
	* Filtra o texto preservando-o e retornando o texto filtrado.
	* São deixados apenas caracteres visíveis.
	*/
	private function getTextoFiltrado():String{
		var fala_filtrada:String = (fala.text).split("\r").join("").split("\n").join("").split("\t").join("").split(" ").join("");
		return fala_filtrada;
	}
	
	/*
	* Filtra o texto preservando-o e retornando o texto filtrado.
	* São deixados apenas caracteres que possam pertencer a um atalho, sem suas opções.
	*/
	private function getTextoAtalho():String{
		var fala_filtrada:String = new String();
		var indice:Number=0;
		while((fala.text).charAt(indice) != " " and indice < (fala.text).length){
			fala_filtrada += (fala.text).charAt(indice);
			indice++;
		}
		return fala_filtrada;
	}
	
	/*
	* Considerando que foi digitado um atalho que possua opções, retorna-as.
	*/
	public function getPrimeiraOpcaoAtalho():String{
		var texto_fala:String = new String();
		var primeira_opcao:String = new String();
		var indice:Number=0;
		
		indice = 0;
		while((fala.text).charAt(indice) != " " and indice < (fala.text).length){
			indice++;
		}
		primeira_opcao = (fala.text).substr(indice+1, (fala.text).length-1 -(indice));
		
		switch(detectarAtalhos()){
			case ATALHO_CHAT_PRIVADO: 
			case ATALHO_CHAT_AMIGO: 
			case ATALHO_CHAT_AMIGO_CONVIDAR:
			case ATALHO_CRIAR_CHAT_AMIGO: return primeira_opcao.substr(1, primeira_opcao.length-2);
				break;
			default: return new String();
		}
	}
	
	/*
	* Considerando que o usuário tenha digitado algo na caixa de texto, confere se algum atalho foi dado.
	* Atalhos só são considerados como digitados quando os únicos caracteres são eles mesmos.
	* Caso algum atalho tenha sido acionado, retorna-o. Caso contrário, retorna SEM_ATALHOS.
	*/
	public function detectarAtalhos():String{
		if((fala.text).charAt(0) == INICIO_ATALHO){
			var fala_filtrada:String = getTextoAtalho();
			
			//_root.outroTerreno.mp.debug2.text += "fala("+fala.text+")\n";
			//_root.outroTerreno.mp.debug2.text += "fala_filtrada("+fala_filtrada+")\n";
			
			switch(fala_filtrada){
				case ATALHO_CRIAR_CHAT_AMIGO:
				case ATALHO_CHAT_AMIGO:
				case ATALHO_CHAT_PRIVADO:
				case ATALHO_CHAT_TERRENO:
				case ATALHO_CHAT_AMIGO_CONVIDAR:
				case ATALHO_VER_CHAT_TERRENO:
				case ATALHO_VER_CHAT_TURMA:
				case ATALHO_VER_CHAT_AMIGO:
				case ATALHO_VER_TODOS_CHATS:
				case ATALHO_AJUDA:
				case ATALHO_SAIR_CHAT_AMIGO:
				case ATALHO_CHAT_TURMA: return fala_filtrada;
					break;
				default: return SEM_ATALHOS;
			}
		} else {
			return SEM_ATALHOS;
		}
	}
	
	
	
	
	
	
}