import flash.geom.Point;

/*
* Um balão de conversa, como o que possuem personagens online.
* Capaz de criar uma fila de falas, todas com um tempo fixo.
*
*/
class c_balao_conversa extends MovieClip{
//dados
	/*
	* Link deste símbolo na bibliteca.
	*/
	public static var LINK_BIBLIOTECA:String = "balao";

	/*
	* O tempo total que um texto pode ficar neste balão.
	*/
	private static var TEMPO_EXIBICAO_FALA_MILISEGUNDOS:Number = 4000;

	/*
	* O textfield deste balão.
	*/
	private var fala:TextField = undefined;
	private static var NOME_FALA:String = "fala";
	private static var POSICAO_FALA:Point = new Point(19.2, 5.55);

	/*
	* Fila de textos para serem exibidos.
	*/
	private var textosNaoExibidos:Array = new Array();

	/*
	* Indica se este balão pode ser visto pelo usuário que está online.
	*/
	private var escondido:Boolean = false;
	
	/*
	* Desenho do balão.
	*/
	private var balao:MovieClip;

	/*
	* Dados necessários para redimensionamento.
	*/
	private static var POSICAO_INICIAL_BALAO:Point = new Point(0,0);
	private static var POSICAO_INICIAL_FALA:Point = new Point(19.2, 5.55);
	private var COMPRIMENTO_INICIAL:Number = undefined;
	private var LARGURA_INICIAL:Number = undefined;
	private static var COMPRIMENTO_MINIMO:Number = 15;
	private static var LARGURA_MINIMA:Number = 20;

//método
	public function inicializar():Void{
		if(COMPRIMENTO_INICIAL == undefined or LARGURA_INICIAL == undefined){
			COMPRIMENTO_INICIAL = balao._width;
			LARGURA_INICIAL = balao._height;
		}
		
		textosNaoExibidos = new Array();
		escondido = false;
		_visible = false;
		balao._width = COMPRIMENTO_INICIAL;
		balao._height = LARGURA_INICIAL;
		
		if(fala == undefined){
			fala = this[NOME_FALA];
		}
		fala.text = new String();
		fala._x = POSICAO_FALA.x;
		fala._y = POSICAO_FALA.y;
		fala.selectable = false;
		fala.autoSize = "left";
		//fala.html = true;
		
		balao = this['balao'];
	}

	/*
	* Adiciona textos à fila e inicia a exibição, caso não tenha iniciado ainda.
	*/
	public function chamar(textos_param:Array):Void{
		var tamanhoTextosParametro:Number = textos_param.length;
		if(!escondido and textos_param != undefined and 0 < textos_param.length){
			for(var indice:Number = 0; indice < tamanhoTextosParametro; indice++){
				textosNaoExibidos.push(textos_param[indice]);
			}
			
			if(!_visible){ //Se estiver visível, está mostrando uma mensagem que veio antes de textos_param.
				exibirProximoTexto();
			}
		}
	}

	/*
	* Exibe o próximo texto na fila.
	*/
	private function exibirProximoTexto(){
		//_parent.debug.text += "exibirProximoTexto\n";
		if(!escondido){
			if(0 < textosNaoExibidos.length){
				fala.text = textosNaoExibidos[0];
				redimensionarSegundoFala();
				if(2 <= textosNaoExibidos.length){
					textosNaoExibidos = textosNaoExibidos.slice(1, textosNaoExibidos.length - 1);
				} else {
					textosNaoExibidos = new Array();
				}
				_visible = true;
				if(1 < textosNaoExibidos.length){
					_global['setTimeout'](this, 'exibirProximoTexto', TEMPO_EXIBICAO_FALA_MILISEGUNDOS/textosNaoExibidos.length); 
				} else {
					_global['setTimeout'](this, 'exibirProximoTexto', TEMPO_EXIBICAO_FALA_MILISEGUNDOS); 
				}
			} else {
				inicializar();
			}
		} else {
			_visible = false;
		}
	}

	/*
	* Redimensiona o balão para que o texto em seu TextField caiba.
	*/
	private function redimensionarSegundoFala():Void{
		//Modela o balão para que contenha o texto e não seja muito maior do que este. - Diogo - 12.08.11
		//Problema 1: ao redimensionar o balão inteiro, modifica-se o tamanho do espaço destinado ao texto.
		//	Este espaço deve ter exatamente o tamanho do texto (mp.fala.textWidth/textHeight).
		//			(100%/pctEspacoTextoOcupa)*tamanhoTexto
		//       <=>(156.35/135.7)*mp.fala.textWidth   - em width
		//       <=>(49.8/42.8)*mp.fala.textHeight     - em height
		//mp.balao._width  = (156.35/130.7)*mp.fala.textWidth;
		//mp.balao._height = (49.8/41.8)*mp.fala.textHeight;
		//Problema 2: o balão sairá do lugar. - Diogo - 12.08.11
		//Solução 2.1: colocar o texto no lugar em que deve aparecer dentro do balão. 
		//         como?
		//difPos = ;
		//mp.fala._x = ;
		//mp.fala._y = ;
		//Solução 2.2: modificar a posição do balão de modo que o texto fique dentro dele.
		//difPos = ;
		//mp.balao._x = ;
		//mp.balao._y = ;
		//Problema 3: como o redimensionamento modifica a posição do balão, deve-se colocá-lo de volta em sua posição de origem.
		//mp.fala._x = mp.fala._x + (mp.balao._x-22.85);//mp.fala._x - (mp.balao._x-22.85)
		//mp.fala._y = mp.fala._y + (mp.balao._y+67.95);//mp.fala._y - (mp.balao._y+67.95)
		//mp.balao._x = 22.85;
		//mp.balao._y = -67.95;
		
		//Solução provisória: o balão será grande o suficiente para conter o texto. A posição do texto estará imperfeita. O mesmo vale
		//					  para a posiçao do balão.
		balao._x = POSICAO_INICIAL_BALAO.x;
		balao._y = POSICAO_INICIAL_BALAO.y;
		fala._x = POSICAO_INICIAL_FALA.x;
		fala._y = POSICAO_INICIAL_FALA.y;
		
		balao._width = COMPRIMENTO_INICIAL;
		balao._height = LARGURA_INICIAL;

		balao._width  = COMPRIMENTO_INICIAL*(fala.textWidth/129.8);
		balao._height = LARGURA_INICIAL*(fala.textHeight/37.45);
		if(balao._width<COMPRIMENTO_MINIMO){
			balao._width = COMPRIMENTO_MINIMO;
		}
		if(balao._height<LARGURA_MINIMA){
			balao._height = LARGURA_MINIMA;
		}
		fala._x = balao._x + (balao._width/COMPRIMENTO_INICIAL)*17.15;
		fala._y = balao._y + (balao._height/LARGURA_INICIAL)*3.5;
		
		fala._y = POSICAO_INICIAL_FALA.y - (balao._height/LARGURA_INICIAL)*25;
		balao._y = POSICAO_INICIAL_BALAO.y - (balao._height/LARGURA_INICIAL)*25;;
	}

	/*
	* Esconde este balão, reinicializando seus dados.
	* Enquanto estiver escondido, um balão nunca será exibido até que haja uma chamada a "mostrar".
	*/
	public function esconder(){
		escondido = true;
		_visible = false;
	}

	/*
	* Mostra o balão.
	* O único efeito desta função é reverter o estado em que o balão fica quando "esconder" é chamada.
	*/
	public function mostrar(){
		escondido = false;
	}


	/*
	* Se estiver visível, esconde.
	* Se estiver escondido, mostra.
	*/
	public function toggleVisibilidade():Void{
		if(escondido){
			mostrar();
		} else {
			esconder();
		}
	}





}
