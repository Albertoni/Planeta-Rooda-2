import mx.utils.Delegate;
import flash.display.BitmapData;
import flash.geom.*;

class c_personagem extends c_objeto_colisao{
//dados
	/*
	* O link do símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "personagem";

	/*
	* Estimativa para o número de quadros por segundo do projeto.
	*/
	private static var QUADROS_POR_SEGUNDO:Number = 25;

	/*
	* Constantes para configuração da aparência do personagem.
	*/
	public static var BAIXO:String = "Baixo";
	public static var CIMA:String = "Cima";
	public static var ESQUERDA:String = "Esq";
	public static var DIREITA:String = "Dir";

	/*
	* Mede a velocidade do movimento.
	*/
	public static var MEDIDA_VELOCIDADE_ANDANDO:Number = 5;
	public static var MEDIDA_VELOCIDADE_CORRENDO:Number = 15;
	public static var MEDIDA_MAIOR_PASSO:Number = MEDIDA_VELOCIDADE_CORRENDO;
	private var velocidade:Number = MEDIDA_VELOCIDADE_ANDANDO;
	
	/*
	* Direções em que pode se movimentar.
	*/
	public static var DIRECAO_INDEFINIDA:String = "0";
	public static var DIRECAO_NORTE:String = "1";
	public static var DIRECAO_SUL:String = "2";
	public static var DIRECAO_LESTE:String = "3";
	public static var DIRECAO_OESTE:String = "4";
	public static var DIRECAO_NORDESTE:String = "5";
	public static var DIRECAO_SUDESTE:String = "6";
	public static var DIRECAO_SUDOESTE:String = "7";
	public static var DIRECAO_NOROESTE:String = "8";
	
	/*
	* Registra o caminho o personagem já percorreu.
	*/
	public var rotaRealizada:Array;
	
	/*
	* Indica se é necessário manter registro da rota do personagem.
	*/
	private var manterRegistroRota:Boolean = false;
	
	/*
	* A direção para qual o personagem está olhando.
	*/
	private var direcaoVisao:String = BAIXO;
	
	/*
	* Constantes com os frames em que o personagem está olhando para a direção dada.
	*/
	private var FRAME_BAIXO:Number = 1;
	private var FRAME_CIMA:Number = 2;
	private var FRAME_ESQUERDA:Number = 3;
	private var FRAME_DIREITA:Number = 4;
	
	/*
	* A rota que é atualizada sempre que o personagem é mandado mover-se.
	* Ele se moverá em sua própria velocidade, seguindo a ordem de movimentos que lhe foi dada.
	* Toda rota é sempre de direções.
	*/
	private var rotaParaRealizar:Array;

	/*
	* Representação deste personagem no banco de dados.
	*/
	private var imagemBancoDeDados:c_personagem_bd = undefined;

	/*
	* Número de quadros que este personagem está parado.
	*/
	private var quadrosParado:Number;

	/*
	* Balão de conversa do personagem.
	*/
	private static var POSICAO_BALAO:Point = new Point(33.15, -60.5);
	private static var NOME_BALAO:String = "balao";
	private var balao:c_balao_conversa;

	/*
	* Conterá a posição salva, caso tenha sido salva.
	*/
	private var posicaoSalva:Point;

//métodos
	/*
	* Ao inicializar este objeto, é necessário definir sua identificação, para que possa ter seus dados 
	* sincronizados com sua imagem no banco de dados.
	*/
	public function inicializar(imagemBancoDeDados_param:c_personagem_bd, manter_registro_param:Boolean){
		/*
		* Atualização da posição só quando inicializa ou sempre que sincronizar?
		*/
		_x = imagemBancoDeDados_param.getPosicaoAtual().x;
		_y = imagemBancoDeDados_param.getPosicaoAtual().y;
		quadrosParado = 0;
		
		manterRegistroRota = manter_registro_param;

		attachMovie(c_balao_conversa.LINK_BIBLIOTECA, NOME_BALAO, getNextHighestDepth());
		balao = this[NOME_BALAO];
		balao.inicializar();
		balao._x = POSICAO_BALAO.x;
		balao._y = POSICAO_BALAO.y;
		
		this['nome'].selectable = false;
		rotaParaRealizar = new Array();//this['debug2'].text = "INICIALIZEI\n";
		rotaRealizada = new Array();
		olharPara(BAIXO);

		sincronizar(imagemBancoDeDados_param);

		onEnterFrame = atualizar;
	}
	
	/*
	* Salva a posição em que o personagem está, para poder ser retomada depois.
	*/
	public function salvarPosicao():Void{
		posicaoSalva = new Point(_x, _y);
	}
	
	/*
	* Muda a posição para posicaoSalva.
	*/
	public function retomarPosicaoSalva():Void{
		_x = posicaoSalva.x;
		_y = posicaoSalva.y;
	}

	/*
	* Aumenta este personagem no fator dado, junto com seus dados de colisão.
	* Não aumenta dados como nome, etc.
	*/
	public function escalar(fator_escala_param:Number):Void{
		this['personagem']._x *= fator_escala_param;
		this['personagem']._y *= fator_escala_param;
		this['personagem']._width *= fator_escala_param;
		this['personagem']._height *= fator_escala_param;
		this['sombra']._x *= fator_escala_param;
		this['sombra']._y *= fator_escala_param;
		this['sombra']._width *= fator_escala_param;
		this['sombra']._height *= fator_escala_param;
		this['nome']._y *= fator_escala_param;
	}

	/*
	* Sincroniza o objeto com a imagem de parâmetro.
	* @param imagemBancoDeDados_param Imagem com a qual este personagem deverá sincronizar-se.
	*/
	public function sincronizar(imagemBancoDeDados_param:c_personagem_bd):Void{
		imagemBancoDeDados = imagemBancoDeDados_param;
		this['nome'].text = imagemBancoDeDados_param.getNome();
		this['nome'].textColor = imagemBancoDeDados_param.getCorNome();
		balao.chamar(imagemBancoDeDados_param.getFalasRecentes());
		definirVelocidade(imagemBancoDeDados_param.getIndicadorVelocidade());
		//this['debug'].text+="\n";
		//c_aviso_com_ok.mostrar("atual="+imagemBancoDeDados_param.getPosicaoAtual());
		moverDestino(imagemBancoDeDados_param.getPosicaoAtual(), false);
		/*var novaRota:Array = imagemBancoDeDados_param.getRota();
		var direcao:String;
		testeRecebidas+=novaRota.length;
		if(1 < novaRota.length){
			for(var indice:Number = 0; indice < novaRota.length; indice++){
				direcao = novaRota[indice];
				rotaParaRealizar.push(direcao);
			}
			this['debug2'].text += "recebido("+(novaRota.length)+")\n";
		}*/
		
		olharPara(direcaoQueOlha()); //Atualiza a aparência, sincronizando com a nova imagem.
	}

	/*
	* Define se o balão pode ser visto.
	*/
	public function toggleVisibilidadeBalao(){
		balao.toggleVisibilidade();
	}

	/*
	* Fala a mensagem, mostrando o balão. Não acontecerá se o balão estiver escondido.
	*/
	public function falar(mensagem_param:String){
		var filaMensagens:Array = new Array();
		filaMensagens.push(mensagem_param);
		balao.chamar(filaMensagens);
	}

	/*
	* Indica se o personagem está parado há mais de/há exatamente tempo_param milisegundos.
	*/
	public function estaParadoHa(tempo_param:Number):Boolean{
		var tempoParadoMilisegundos:Number = (quadrosParado/QUADROS_POR_SEGUNDO)*1000;
		if(tempo_param <= tempoParadoMilisegundos){
			return true;
		} else {
			return false;
		}
	}

	/*
	* @return Sua imagem no banco de dados.
	*/
	public function getImagemBancoDeDados():c_personagem_bd{
		return imagemBancoDeDados;
	}

	function atualizar(){
		this['personagem'].mpCima.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
		this['personagem'].mpBaixo.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
		this['personagem'].mpEsq.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
		this['personagem'].mpDir.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
		
		this['personagem'].mpCima.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
		this['personagem'].mpBaixo.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
		this['personagem'].mpEsq.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
		this['personagem'].mpDir.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
		
		this['personagem'].mpCima.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
		this['personagem'].mpBaixo.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
		this['personagem'].mpEsq.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
		this['personagem'].mpDir.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
		
		if(0 < rotaParaRealizar.length){
			//this['debug2'].text = "rota["+rotaParaRealizar[0]+"]";
			quadrosParado = 0;
			mover();
		} /*else if(imagemBancoDeDados != undefined and !manterRegistroRota){
			_x = imagemBancoDeDados.getPosicaoAtual().x;
			_y = imagemBancoDeDados.getPosicaoAtual().y;
		} */else {
			quadrosParado++;
			parar();
		}
	}

	/*
	* Retorna o destino atual deste personagem.
	*/
	public function getRota():String{
		if(0 < rotaRealizada.length){
			return rotaRealizada.toString();
		} else {
			return new String();
		}
	}

	/*
	* Limpa o registro de rota realizada.
	*/
	public function limparRegistroRotaRealizada():Void{
		rotaRealizada = new Array();
	}
	
	/*
	* Retorna a direção para a qual o personagem está olhando.
	* Podem ser: BAIXO, CIMA, ESQUERDA ou DIREITA.
	*/
	public function direcaoQueOlha():String{
		return direcaoVisao;
	}

	/*
	* Retorna a posição para a qual o personagem iria, baseado em uma posição considerada a atual e uma direção.
	*/
	public function getPosicaoDestino(direcao_param:String, posicao_atual_param:Point):Point{
		var posicaoDestino:Point = new Point();
		var deslocamento_x:Number = 0;
		var deslocamento_y:Number = 0;
		var medida_velocidade:Number;
		
		if(getImagemBancoDeDados().getIndicadorVelocidade() == c_personagem_bd.VELOCIDADE_CORRENDO){
			medida_velocidade = MEDIDA_VELOCIDADE_CORRENDO;
		} else if(getImagemBancoDeDados().getIndicadorVelocidade() == c_personagem_bd.VELOCIDADE_ANDANDO){
			medida_velocidade = MEDIDA_VELOCIDADE_ANDANDO;
		} else {
			medida_velocidade = MEDIDA_VELOCIDADE_ANDANDO;
		}
		switch(direcao_param){
			case DIRECAO_NORTE:
					deslocamento_x = 0;
					deslocamento_y = -medida_velocidade;
				break;
			case DIRECAO_SUL:
					deslocamento_x = 0;
					deslocamento_y = medida_velocidade;
				break;
			case DIRECAO_LESTE:
					deslocamento_x = medida_velocidade;
					deslocamento_y = 0;
				break;
			case DIRECAO_OESTE:
					deslocamento_x = -medida_velocidade;
					deslocamento_y = 0;
				break;
			case DIRECAO_NORDESTE:
					deslocamento_x = Math.round(Math.pow((medida_velocidade*medida_velocidade)/2,(1/2)));
					deslocamento_y = -Math.round(Math.pow((medida_velocidade*medida_velocidade)/2,(1/2)));
				break;
			case DIRECAO_SUDESTE:
					deslocamento_x = Math.round(Math.pow((medida_velocidade*medida_velocidade)/2,(1/2)));
					deslocamento_y = Math.round(Math.pow((medida_velocidade*medida_velocidade)/2,(1/2)));
				break;
			case DIRECAO_SUDOESTE:
					deslocamento_x = -Math.round(Math.pow((medida_velocidade*medida_velocidade)/2,(1/2)));
					deslocamento_y = Math.round(Math.pow((medida_velocidade*medida_velocidade)/2,(1/2)));
				break;
			case DIRECAO_NOROESTE:
					deslocamento_x = -Math.round(Math.pow((medida_velocidade*medida_velocidade)/2,(1/2)));
					deslocamento_y = -Math.round(Math.pow((medida_velocidade*medida_velocidade)/2,(1/2)));
				break;
		}
		//this['debug'].text += "medida:"+medida_velocidade+", dx:"+deslocamento_x+", dy:"+deslocamento_y;
		posicaoDestino = new Point(posicao_atual_param.x + deslocamento_x, posicao_atual_param.y + deslocamento_y);
		return posicaoDestino;
	}
	
	/*
	* @param direcao_param Uma direção segundo definida no início.
	* @return booleano indicando se a direção é válida.
	*/
	public static function direcaoValida(direcao_param:String):Boolean{
		if(direcao_param == c_personagem.DIRECAO_NORTE or
		   direcao_param == c_personagem.DIRECAO_SUL or
		   direcao_param == c_personagem.DIRECAO_LESTE or
		   direcao_param == c_personagem.DIRECAO_OESTE or
		   direcao_param == c_personagem.DIRECAO_NORDESTE or
		   direcao_param == c_personagem.DIRECAO_SUDESTE or
		   direcao_param == c_personagem.DIRECAO_SUDOESTE or
		   direcao_param == c_personagem.DIRECAO_NOROESTE){
			return true;
		} else {
			return false;
		}
	}
	
	/*
	* Muda a posição do personagem segundo os deslocamentos dados.
	* Modifica sua aparência de acordo com o movimento.
	*/
	private function mover(){
		var direcao = rotaParaRealizar[0];
		var posicaoDestino:Point;
		if(direcaoValida(direcao)){
			posicaoDestino = getPosicaoDestino(direcao, new Point(_x, _y));
			velocidade = Point.distance(posicaoDestino, new Point(_x, _y));
			
			_x = posicaoDestino.x;
			_y = posicaoDestino.y; 
			olharPara(direcao);
			iniciarAnimacaoMovimento();
			if(1 < rotaParaRealizar.length){
				rotaParaRealizar = rotaParaRealizar.slice(1, rotaParaRealizar.length - 1);
			} else {
				rotaParaRealizar = new Array();
			}
			
			_root.planeta.getTerrenoEmQuePersonagemEstah().mapa.moverIndicador(_x, _y, _name);
			if(manterRegistroRota){
				rotaRealizada.push(direcao);
			}
		} else {
			if(1 < rotaParaRealizar.length){
				rotaParaRealizar = rotaParaRealizar.slice(1, rotaParaRealizar.length - 1);
			} else {
				rotaParaRealizar = new Array();
			}
		}
	}
	
	/*
	* Organiza chamadas a mover(direcao_param:String) para que o personagem siga uma ordem de passos, sem pular nenhum.
	* O actio
	* @param destino_eh_absoluto_param Indica se deve ou não apagar a rota atual e começar uma nova com o ponto fornecido.
	*/
	public function moverDestino(ponto_destino_param:Point, destino_eh_absoluto_param:Boolean){
		var pontoIntermediario:Point = new Point(_x, _y);
		var direcaoIntermediaria:String;
		if(destino_eh_absoluto_param){
			//this['debug2'].text = "NOVO EM MOVER DESTINO\n";
			rotaParaRealizar = new Array();
		} 
		//c_aviso_com_ok.mostrar("distancia "+Point.distance(ponto_destino_param, pontoIntermediario)
									//   +", posicao "+pontoIntermediario+", objetivo "+ponto_destino_param);
		while(getMedidaVelocidade() <= Point.distance(ponto_destino_param, pontoIntermediario)){
				//c_aviso_com_ok.mostrar("distancia "+Point.distance(ponto_destino_param, pontoIntermediario)
									 //  +", posicao "+pontoIntermediario+", objetivo "+ponto_destino_param);
			//this['debug'].text += "("+Point.distance(ponto_destino_param, pontoIntermediario)+")";
			//this['debug'].text += ponto_destino_param+" distancia:"+Point.distance(ponto_destino_param, pontoIntermediario)+", vel:"+velocidade;
			direcaoIntermediaria = getDirecao(ponto_destino_param, pontoIntermediario, getMedidaVelocidade());
			pontoIntermediario = getPosicaoDestino(direcaoIntermediaria, pontoIntermediario);
			//this['debug'].text += "dir:"+direcaoIntermediaria+"\n";
			if(!_root.planeta.getTerrenoEmQuePersonagemEstah().estaNaAreaUtil(pontoIntermediario.x, pontoIntermediario.y) and _name != "mp"){
				//c_aviso_com_ok.mostrar("Gerado ponto "+pontoIntermediario.toString()+" fora do terreno\n");
			}
			rotaParaRealizar.push(direcaoIntermediaria);
		}
	}
	
	/*
	* Faz com que o personagem mova-se em uma direção.
	* @param destino_eh_absoluto_param Indica se deve ou não apagar a rota atual e começar uma nova com o ponto fornecido.
	*/
	public function moverDirecao(direcao_param:String, destino_eh_absoluto_param:Boolean){
		if(destino_eh_absoluto_param){
			//this['debug2'].text = "NOVO EM MOVER DIRECAO\n";
			rotaParaRealizar = new Array();
		}
		rotaParaRealizar.push(direcao_param);
	}
	
	/*
	* Dah a medida da velocidade do personagem.
	*/
	public function getMedidaVelocidade():Number{
		return velocidade;
	}
	
	/*
	* Retorna a direção para a qual deve se mover, baseado em um ponto de destino, uma posição no momento do movimento e a velocidade.
	*/
	public function getDirecao(ponto_destino_param:Point, posicao_atual_param:Point, velocidade_param:Number):String{
		var vetorDirecao:Point = new Point(ponto_destino_param.x - posicao_atual_param.x, ponto_destino_param.y - posicao_atual_param.y);
		//this['debug'].text += ", x:"+vetorDirecao.x+", y:"+vetorDirecao.y+", vel:"+velocidade_param+"\n";
		var TOLERANCIA:Number=10;
		/*if(posicao_atual_param.x == _x and posicao_atual_param.y == _y and vetorDirecao.length < velocidade_param)
			return c_personagem.DIRECAO_INDEFINIDA;
		else if ((vetorDirecao.x > velocidade_param+MODIFICADOR) and (vetorDirecao.y > velocidade_param+MODIFICADOR))
			return c_personagem.DIRECAO_SUDESTE;
		else if ((vetorDirecao.x > velocidade_param+MODIFICADOR) and (vetorDirecao.y < velocidade_param-MODIFICADOR) )
			return c_personagem.DIRECAO_NORDESTE;
		else if ((vetorDirecao.x < velocidade_param-MODIFICADOR) and (vetorDirecao.y > velocidade_param+MODIFICADOR) )
			return c_personagem.DIRECAO_SUDOESTE;
		else if ((vetorDirecao.x < velocidade_param-MODIFICADOR) and (vetorDirecao.y < velocidade_param-MODIFICADOR) )
			return c_personagem.DIRECAO_NOROESTE;
		else if (vetorDirecao.x > velocidade_param)
			return c_personagem.DIRECAO_LESTE;
		else if (vetorDirecao.x < velocidade_param)
			return c_personagem.DIRECAO_OESTE;
		else if (vetorDirecao.y > velocidade_param)
			return c_personagem.DIRECAO_SUL;
		else if (vetorDirecao.y < velocidade_param) 
			return c_personagem.DIRECAO_NORTE;
		else return c_personagem.DIRECAO_INDEFINIDA;*/
		
		if(velocidade_param <= vetorDirecao.length){
			if(velocidade_param < Math.abs(vetorDirecao.x) and Math.abs(vetorDirecao.y) < velocidade_param){
				if (0 < vetorDirecao.x)
					return c_personagem.DIRECAO_LESTE;
				else return c_personagem.DIRECAO_OESTE;
			} else if(velocidade_param < Math.abs(vetorDirecao.y) and Math.abs(vetorDirecao.x) < velocidade_param){
				if (0 < vetorDirecao.y)
					return c_personagem.DIRECAO_SUL;
				else return c_personagem.DIRECAO_NORTE;
			} else {
				if ((0 < vetorDirecao.x) and (0 < vetorDirecao.y))
					return c_personagem.DIRECAO_SUDESTE;
				else if ((0 < vetorDirecao.x) and (vetorDirecao.y < 0))
					return c_personagem.DIRECAO_NORDESTE;
				else if ((vetorDirecao.x < 0) and (0 < vetorDirecao.y) )
					return c_personagem.DIRECAO_SUDOESTE;
				else return c_personagem.DIRECAO_NOROESTE;
			}
		} else {
			return c_personagem.DIRECAO_INDEFINIDA;
		}
	}
	
	/*
	* Faz o personagem olhar para a direção dada.
	*/
	public function olharPara(direcao_param:String):Void{
		switch(direcao_param){
			case DIRECAO_NORTE: direcaoVisao = c_personagem.CIMA;
				break;
			case DIRECAO_SUL: direcaoVisao = c_personagem.BAIXO;
				break;
			case DIRECAO_LESTE: direcaoVisao = c_personagem.DIREITA;
				break;
			case DIRECAO_OESTE: direcaoVisao = c_personagem.ESQUERDA;
				break;
			case DIRECAO_NORDESTE: direcaoVisao = c_personagem.CIMA;
				break;
			case DIRECAO_SUDESTE: direcaoVisao = c_personagem.BAIXO;
				break;
			case DIRECAO_SUDOESTE: direcaoVisao = c_personagem.BAIXO;
				break;
			case DIRECAO_NOROESTE: direcaoVisao = c_personagem.CIMA;
				break;
			case DIRECAO_INDEFINIDA: //direcaoVisao = direcaoVisao;
				break;
			default: direcaoVisao = direcao_param;
				break;
		}
		
		switch(direcaoVisao){
			case BAIXO:
				this['personagem'].gotoAndStop(FRAME_BAIXO);
				this['personagem'].mpBaixo.cabeloFrente.gotoAndStop(getImagemBancoDeDados().getCabelo());
				this['personagem'].mpBaixo.olhosFrente.gotoAndStop(getImagemBancoDeDados().getOlhos());
				this['personagem'].mpBaixo.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
				this['personagem'].mpBaixo.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
				this['personagem'].mpBaixo.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
			break;
			case CIMA:
				this['personagem'].gotoAndStop(FRAME_CIMA);
				this['personagem'].mpCima.cabeloCostas.gotoAndStop(getImagemBancoDeDados().getCabelo());
				//Olhos não aparecem quando está de costas.
				this['personagem'].mpCima.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
				this['personagem'].mpCima.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
				this['personagem'].mpCima.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
			break;
			case ESQUERDA:
				this['personagem'].gotoAndStop(FRAME_ESQUERDA);
				this['personagem'].mpEsq.cabeloEsq.gotoAndStop(getImagemBancoDeDados().getCabelo());
				this['personagem'].mpEsq.olhosEsq.gotoAndStop(getImagemBancoDeDados().getOlhos());
				this['personagem'].mpEsq.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
				this['personagem'].mpEsq.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
				this['personagem'].mpEsq.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
			break;
			case DIREITA:
				this['personagem'].gotoAndStop(FRAME_DIREITA);
				this['personagem'].mpDir.cabeloDir.gotoAndStop(getImagemBancoDeDados().getCabelo());
				this['personagem'].mpDir.olhosDir.gotoAndStop(getImagemBancoDeDados().getOlhos());
				this['personagem'].mpDir.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
				this['personagem'].mpDir.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
				this['personagem'].mpDir.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
			break;
		}
	}
	
	/*
	* Inicia o movimento do personagem na direção que está olhando.
	*/
	public function iniciarAnimacaoMovimento(){
		switch(direcaoVisao){
			case BAIXO: 
					this['personagem'].mpBaixo.play();
					this['personagem'].mpCima.stop();
					this['personagem'].mpEsq.stop();
					this['personagem'].mpDir.stop();
				break;
			case CIMA: 
					this['personagem'].mpBaixo.stop();
					this['personagem'].mpCima.play();
					this['personagem'].mpEsq.stop();
					this['personagem'].mpDir.stop();
				break;
			case ESQUERDA: 
					this['personagem'].mpBaixo.stop();
					this['personagem'].mpCima.stop();
					this['personagem'].mpEsq.play();
					this['personagem'].mpDir.stop();
				break;
			case DIREITA: 
					this['personagem'].mpBaixo.stop();
					this['personagem'].mpCima.stop();
					this['personagem'].mpEsq.stop();
					this['personagem'].mpDir.play();
				break;
		}
	}
    
	/*
	* Congela a aparência do personagem no primeiro frame, quando está parado de pé.
	*/
	private function congelar_aparencia(direcao:String):Void{
		direcaoVisao = direcao;

		this['personagem'].mpBaixo.gotoAndStop(1);
		this['personagem'].mpBaixo.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
		this['personagem'].mpBaixo.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
		this['personagem'].mpBaixo.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
		
		this['personagem'].mpCima.gotoAndStop(1);
		this['personagem'].mpCima.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
		this['personagem'].mpCima.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
		this['personagem'].mpCima.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
		
		this['personagem'].mpEsq.gotoAndStop(1);
		this['personagem'].mpEsq.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
		this['personagem'].mpEsq.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
		this['personagem'].mpEsq.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
				
		this['personagem'].mpDir.gotoAndStop(1);
		this['personagem'].mpDir.cor_pele.gotoAndStop(getImagemBancoDeDados().getCorPele());
		this['personagem'].mpDir.cor_cinto.gotoAndStop(getImagemBancoDeDados().getCorCinto());
		this['personagem'].mpDir.cor_luvas_botas.gotoAndStop(getImagemBancoDeDados().getCorLuvasBotas());
	}

	/*
	* Alteram a velocidade do personagem.
	*/
	public function definirVelocidade(velocidade_param:Number){
		if(velocidade_param == c_personagem_bd.VELOCIDADE_CORRENDO){
			getImagemBancoDeDados().definirVelocidade(c_personagem_bd.VELOCIDADE_CORRENDO);
			velocidade = MEDIDA_VELOCIDADE_CORRENDO;
		} else if(velocidade_param == c_personagem_bd.VELOCIDADE_ANDANDO){
			getImagemBancoDeDados().definirVelocidade(c_personagem_bd.VELOCIDADE_ANDANDO);
			velocidade = MEDIDA_VELOCIDADE_ANDANDO;
		} else {
			getImagemBancoDeDados().definirVelocidade(c_personagem_bd.VELOCIDADE_ANDANDO);
			velocidade = MEDIDA_VELOCIDADE_ANDANDO;
		}
	}
	public function correr(){
		getImagemBancoDeDados().definirVelocidade(c_personagem_bd.VELOCIDADE_CORRENDO);
		velocidade = MEDIDA_VELOCIDADE_CORRENDO;
	}
	public function caminhar(){
		getImagemBancoDeDados().definirVelocidade(c_personagem_bd.VELOCIDADE_ANDANDO);
		velocidade = MEDIDA_VELOCIDADE_ANDANDO;
	}
	
	/*
	* Pára o personagem.
	*/
	public function parar(){
		//this['debug2'].text = "PAREI, meu nome é:"+this['nome'].text+"\n";
		rotaParaRealizar = new Array();
		congelar_aparencia(direcaoQueOlha());
	}
	
	/*
	* Retorna uma imagem BitmapData com a imagem da cabeça do avatar.
	*/
	public function capturarImagemCabecaAvatar():BitmapData{
		//imagem precisa ser de 138x138 até 192x192.
		var bmpSrc:BitmapData = new BitmapData(this['personagem'].mpBaixo._width+400, this['personagem'].mpBaixo._height-60, false, 0x9FCD53);
		var matrizParaVerSS:Matrix = new Matrix();
		var matrizTranslacao:Matrix = new Matrix();
		var matrizEscala:Matrix = new Matrix();
	
		//O retângulo é 530x550 (e não 192x192), pois a imagem do mpBaixo contém uma parte em branco antes do personagem.
		var areaCabecaPersonagem:Rectangle = new Rectangle(408, 100, 530, 550);
		var pontoAcimaCabecaPersonagem:Point = new Point(0,0);
		var bmpDes:BitmapData = new BitmapData(192, 192, false, 0xFFAC00);//192x192, cor de fundo = verde do terreno.
	
		matrizEscala.scale(0.8,0.8);
		matrizParaVerSS.concat(matrizEscala);
		matrizTranslacao.translate(500, 100);
		matrizParaVerSS.concat(matrizTranslacao);
	
		bmpSrc.draw(this['personagem'].mpBaixo, matrizParaVerSS);
		bmpDes.copyPixels(bmpSrc, areaCabecaPersonagem, pontoAcimaCabecaPersonagem);

		return bmpDes;
		//Instrução de debug. Mostra a imagem gerada (bmpDes) no canto superior esquerdo da tela.
		//_root.attachBitmap(bmpDes, 1);
	}
	
}
