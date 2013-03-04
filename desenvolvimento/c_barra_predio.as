import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;
import flash.geom.Point;

class c_barra_predio extends MovieClip {
//dados
	/*
	* Link para este objeto na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "barra_progresso_predio";
	/*
	* Limites de porcentagem.
	*/
	public static var MINIMO_PORCENTAGEM:Number = 0;
	public static var MAXIMO_PORCENTAGEM:Number = 100;
	/*
	* Posições mínima e máxima que o personagem assume durante a animação.
	*/
	public static var MINIMA_POS_Y_PERSONAGEM:Number = 772.85;
	public static var MAXIMA_POS_Y_PERSONAGEM:Number = -142.2;
	/*
	* O último valor passado como porcentagem de carregamento.
	*/
	private var porcentagemCarregada:Number = MINIMO_PORCENTAGEM;
	/*
	* A cor da borda do texto.
	*/
	private static var COR_LARANJA:String = "0xFFB43F";
	/*
	* O sentido do movimento do personagem na animação.
	*/
	private var sentidoMovimentoPersonagem:String=CIMA;
	/*
	* Sentido do movimento do personagem na animação.
	*/
	public static var CIMA:String = "1";
	public static var BAIXO:String = "2";

//métodos		
	public function inicializar(sentido_movimento_param:String, texto_param:String){
		sentidoMovimentoPersonagem = sentido_movimento_param;
		if(sentidoMovimentoPersonagem == CIMA){
			this['personagem']._y = calculaPosicaoYPersonagem(0);
		} else {
			this['personagem']._y = calculaPosicaoYPersonagem(100);
		}
		this['texto'].text = texto_param;
		porcentagemCarregada = MINIMO_PORCENTAGEM;
	}
	
	/*
	* Cria uma animação que ficará acima de tudo no palco, durará o tempo fornecido.
	* @param tempoDuracao_param Tempo em milissegundos de duração da animação.
	* @param sentido_movimento_param O sentido do movimento da animação, se de cima para baixo ou de baixo para cima.
	* @param texto_param Texto a ser exibido durante a animação.
	*/
	public static function criar(tempoDuracao_param:Number, sentido_movimento_param:String, texto_param:String):Void{
		if(_root.animacaoPredio == undefined){
			_root.attachMovie(c_barra_predio.LINK_BIBLIOTECA, 'animacaoPredio', _root.getNextHighestDepth()+1);
		} else {
			_root.animacaoPredio._visible = true;
		}
		_root.animacaoPredio.inicializar(sentido_movimento_param, texto_param);
		
		for(var i:Number=0; i<MAXIMO_PORCENTAGEM; i++){
			_global['setTimeout'](_root.animacaoPredio, 'incrementarPorcentagem', i*tempoDuracao_param/MAXIMO_PORCENTAGEM);
		}
		_global['setTimeout'](_root.animacaoPredio, 'esconder', tempoDuracao_param);
	}
	
	/*
	* @return % carregado até o momento.
	*/
	public function getPorcentagemCarregada():Number{
		return porcentagemCarregada;
	}
	
	/*
	* Esconde esta animação.
	*/
	public function esconder():Void{
		_visible = false;
	}
	
	/*
	* Inicia uma animação com tempo pré-programado.
	* @param fatiaTempo_param A fatia de tempo em milissegundos que 1% leva para passar.
	*/
	private function animarComTempo(fatiaTempo_param:Number){
		//if(MINIMO_PORCENTAGEM <= porcentagemCarregada and porcentagemCarregada <= MAXIMO_PORCENTAGEM){
		if(true){
			//if(250 <= fatiaTempo_param){
			if(false){
				incrementarPorcentagem();
				_global['setTimeout'](this, 'animarComTempo', fatiaTempo_param);
			} else {
				incrementarPorcentagem();
				incrementarPorcentagem();
				incrementarPorcentagem();
				incrementarPorcentagem();
				incrementarPorcentagem();
				incrementarPorcentagem();
				incrementarPorcentagem();
				incrementarPorcentagem();
				
				incrementarPorcentagem();
				//_global['setTimeout'](this, 'animarComTempo', fatiaTempo_param); 
			}
		} else {
			esconder();
		}
	}
	
	/*
	* Atualiza a mensagem que informa ao usuário a operação sendo feita.
	*/
	public function atualizarMensagem(texto_param:String):Void{
		this['texto'].text = texto_param;
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
			
				this['personagem']._y = calculaPosicaoYPersonagem(porcentagem_param);
				this['porcentagem'].text = porcentagem_param+"%";										
			} else {
				definirPorcentagem(100);
			}
		}
	}
	
	/*
	* Dada uma porcentagem, calcula a posição y que deve ter o personagem para ficar de acordo.
	*/
	private function calculaPosicaoYPersonagem(porcentagem_param:Number):Number{
		var variacaoRelativaAoInicio:Number = (porcentagem_param/MAXIMO_PORCENTAGEM)*(MINIMA_POS_Y_PERSONAGEM - MAXIMA_POS_Y_PERSONAGEM);
		var posicaoY:Number;
		if(sentidoMovimentoPersonagem == CIMA){
		 	posicaoY = MINIMA_POS_Y_PERSONAGEM - variacaoRelativaAoInicio;
		} else {
			posicaoY = MAXIMA_POS_Y_PERSONAGEM + variacaoRelativaAoInicio;
		}
		return posicaoY;
	}

	
	
	
}
