/*
* Algoritmo para mudar a cor da borda do texto.
* 0. Fazer o MovieClip que contém o TextField ficar invisível.
* 1. De 0 até 100, faça:
* 	1.1. Escrever texto no TextField, que está dentro de um MovieClip.
* 	1.2. Converter MovieClip do TextField para BitmapData usando o construtor de BitmapData.
* 	1.3. Tornar o BitmapData visível e colocálo na posição certa.
* 	1.4. Usar applyFilter de BitmapData para aplicar um filtro passa-altas. Usar um ConvolutionFilter.
* 	1.5. Pintar de laranja os pixels que foram modificados pelo filtro.
*/

class c_barra_progresso_foguete extends MovieClip {
//dados
	/*
	* Link para este objeto na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "barra_progresso_foguete";
	/*
	* Limites de porcentagem.
	*/
	public var MINIMO_PORCENTAGEM:Number = 0;
	public var MAXIMO_PORCENTAGEM:Number = 100;
	/*
	* Posições mínima e máxima que o foguete assume durante a animação.
	*/
	public var MINIMA_POS_X_FOGUETE:Number = 181.9;
	public var MINIMA_POS_Y_FOGUETE:Number = 0;
	public var MAXIMA_POS_X_FOGUETE:Number = 838.9;
	public var MAXIMA_POS_Y_FOGUETE:Number = 0;
	/*
	* Comprimentos mínimo e máximo que a fumaça do foguete assume durante a animação.
	*/
	public var MINIMO_COMPRIMENTO_FUMACA:Number = 1;
	public var MAXIMO_COMPRIMENTO_FUMACA:Number = 682.85;
	/*
	* O último valor passado como porcentagem de carregamento.
	*/
	private var porcentagemCarregada:Number = MINIMO_PORCENTAGEM;
	/*
	* A cor da borda do texto.
	*/
	private var COR_LARANJA:String = "0xFFB43F";

//métodos		
	public function inicializar(texto_param:String){
		this['texto'].text = texto_param;
	}
	
	/*
	* Atualiza a mensagem que informa ao usuário a operação sendo feita.
	*/
	public function atualizarMensagem(texto_param:String):Void{
		this['texto'].text = texto_param;
	}
	
	public static function teste(){
		c_aviso_com_ok.mostrar("teste ok");
	}
	
	/*
	* Incrementa a porcentagem carregada até o momento em 1 (ou 1%).
	*/
	public function incrementarPorcentagem():Void{
		definirPorcentagem(porcentagemCarregada+1);
	}
	/*
	* Confere se a porcentagem é válida. Só iniciará suas tarefas se o for.
	* Atualiza o atributo porcentagemCarregada com a porcentagem de parâmetro.
	* Move a animação para ficar de acordo com a porcentagem do parâmetro.
	*/
	public function definirPorcentagem(porcentagem_param:Number):Void{
		if(MINIMO_PORCENTAGEM <= porcentagem_param){
			if(porcentagem_param <= MAXIMO_PORCENTAGEM){
				porcentagemCarregada = porcentagem_param;
			
				this['fumaca']._width = calculaComprimentoFumaca(porcentagem_param);
				this['foguete']._x = calculaPosicaoXFoguete(porcentagem_param);
				this['porcentagem'].text = porcentagem_param+"%";										
			}
			else{
				definirPorcentagem(100);
			}
		}
	}
	
	/*
	* Dada uma porcentagem, calcula o comprimento que deve ter a fumaça do foguete para ficar de acordo.
	*/
	private function calculaComprimentoFumaca(porcentagem_param:Number):Number{
		var comprimentoFumaca:Number = (porcentagem_param/MAXIMO_PORCENTAGEM)*MAXIMO_COMPRIMENTO_FUMACA;
		 
		if(MINIMO_COMPRIMENTO_FUMACA < comprimentoFumaca and comprimentoFumaca < MAXIMO_COMPRIMENTO_FUMACA){
			return comprimentoFumaca;
		} else if(comprimentoFumaca <= MINIMO_COMPRIMENTO_FUMACA){
			comprimentoFumaca = MINIMO_COMPRIMENTO_FUMACA;
		} else if(MAXIMO_COMPRIMENTO_FUMACA <= comprimentoFumaca){
			comprimentoFumaca = MAXIMO_COMPRIMENTO_FUMACA;
		} else {
			comprimentoFumaca = MINIMO_COMPRIMENTO_FUMACA;
		}
				
		return comprimentoFumaca;
	}
	/*
	* Dada uma porcentagem, calcula a posição x que deve ter o foguete para ficar de acordo.
	*/
	private function calculaPosicaoXFoguete(porcentagem_param:Number):Number{
		var posicaoX:Number = (porcentagem_param/MAXIMO_PORCENTAGEM)*MAXIMA_POS_X_FOGUETE;

		if(MINIMA_POS_X_FOGUETE < posicaoX and posicaoX < MAXIMA_POS_X_FOGUETE){
			return posicaoX;
		} else if(posicaoX <= MINIMA_POS_X_FOGUETE){
			posicaoX = MINIMA_POS_X_FOGUETE;
		} else if(MAXIMA_POS_X_FOGUETE <= posicaoX){
			posicaoX = MAXIMA_POS_X_FOGUETE;
		} else {
			posicaoX = MINIMA_POS_X_FOGUETE;
		}
		
		return posicaoX;
	}

	
	
	
}
