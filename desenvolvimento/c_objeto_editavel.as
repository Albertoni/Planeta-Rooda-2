import flash.geom.Point;
class c_objeto_editavel extends c_objeto_colisao{
//dados
	/*
	* Indica se foi editada no modo de edição.
	*/
	private var statusEdicao:Boolean = false;
	
	/*
	* Indica se está selecionada no modo de edição.
	*/
	private var selecionada:Boolean = false;
	
	/*
	* Indica se esta casa já existe no banco de dados ou se é uma casa nova.
	*/
	private var existe:Boolean = true;
	
	/*
	* Determina se este objeto teve sua deleção simulada, mas ainda não realizada.
	*/
	private var delecaoSimulada:Boolean = false;

	/*
	* Indica se o objeto foi editado recentemente.
	*/
	private var editadoRecentemente:Boolean = false;
	
	/*
	* Posição que possuía antes do início da edição.
	*/
	private var posicaoAnterior:Point;
	
	/*
	* Dados iniciais, para poder reverter, caso necessário.
	*/
	private var posicaoInicial:Point;
	private var aparenciaInicial:Number;
	private var profundidadeInicial:Number;
	
	/*
	* A identificação deste objeto no banco de dados.
	* Esta informação é necessária para poder atualizar seus dados, conforme forem mudados.
	*/
	private var identificacao:String = c_banco_de_dados.NAO_SALVO;
	
	/*
	* Estados possíveis.
	*/
	private var estado:Number = ESTADO_SALVO;
	public static var ESTADO_RECEM_INSERIDO:Number = 1;
	public static var ESTADO_RECEM_ATUALIZADO:Number = 2;
	public static var ESTADO_RECEM_DELETADO:Number = 3;
	public static var ESTADO_SALVO:Number = 4;
	
//métodos
	/*
	* Ao inicializar este objeto, é necessário definir sua identificação, para que possa ter seus dados 
	* sincronizados com sua imagem no banco de dados.
	*/
	public function inicializar(identificacao_param:String){
		identificacao = identificacao_param;
	}

	/*
	* Define dados iniciais, para poder reverter, caso necessário.
	*/
	public function definirDadosIniciais(posicaoInicial_param:Point, aparenciaInicial_param:Number):Void{
		posicaoInicial = posicaoInicial_param;
		aparenciaInicial = aparenciaInicial_param;
		profundidadeInicial = getDepth();
		//hitArea = getSombra();
	}

	/*
	* Devolve a identificação deste objeto no banco de dados, para encontrar sua imagem lá.
	*/
	public function getIdentificacaoBancoDeDados():String{
		return identificacao;
	}

	/*
	* Define o estado deste objeto.
	* @param estado_param Um dos estados definidos nesta classe.
	*/
	public function definirEstado(estado_param:Number):Void{
		if(estado_param == ESTADO_RECEM_INSERIDO
		   or estado_param == ESTADO_RECEM_ATUALIZADO
		   or estado_param == ESTADO_RECEM_DELETADO
		   or estado_param == ESTADO_SALVO){
			estado = estado_param;
		}
	}

	/*
	* @return String, conforme definido nesta classe, indicando se foi recém inserido, deletado, atualizado ou nada.
	*/
	public function getEstado():Number{
		return estado;
	}

	/*
	* Reverte dados editados para seu estado inicial.
	*/
	public function desfazerEdicao(){
		_x = posicaoInicial.x;
		_y = posicaoInicial.y;
		definirTipoAparencia(aparenciaInicial);
		setStatusEdicao(false);
		swapDepths(profundidadeInicial);
	}
		
	/*
	* Simula a deleção deste objeto.
	*/
	public function simularDelecao():Void{
		if(!delecaoSimulada){
			desfazerSelecao();
			delecaoSimulada = true;
			_visible = false;
		}
	}
	/*
	* Restaura o objeto, caso tenha tido sua deleção simulada.
	*/
	public function restaurar():Void{
		if(delecaoSimulada){
			delecaoSimulada = false;
			_visible = true;
		}
	}

	/*
	* Define como editada ou não.
	*/
	public function setStatusEdicao(status_param:Boolean):Void{
		statusEdicao = status_param;
	}

	/*
	* Retorna seu status de edição.
	*/
	public function editado():Boolean{
		return statusEdicao;
	}
	
	/*
	* Retorna boolean indicando se este objeto teve sua deleção simulada, mas ainda não realizada.
	*/
	public function teveDelecaoSimulada():Boolean{
		return delecaoSimulada;
	}
	
	/*
	* @return A posição que possuía antes da última vez que foi selecionado.
	*/
	public function getPosicaoAnteriorSelecao():Point{
		return posicaoAnterior;
	}

	/*
	* Seleciona esta casa (da forma como é no modo de edição).
	* A seleção não deve ser refeita caso já esteja selecionada.
	*/
	public function selecionar(){
		if(!selecionada){
			selecionada = true;
			_alpha = _alpha/2;
			posicaoAnterior = new Point(_x, _y);
			startDrag(this, false);
		}
	}
	
	/*
	* Desfaz a seleção feita (do modo de edição).
	* A seleção só é desfeita se estiver selecionada.
	*/
	public function desfazerSelecao(){
		if(selecionada){
			selecionada = false;
			_alpha = _alpha * 2;
			stopDrag();
		}
	}
	
	/*
	* Deve ser definida em casa classe filha.
	*/
	public function definirTipoAparencia(tipo_aparencia_param:Number):Void{
		
	}
	
	/*
	* Altera a aparência para a adjacente à direita do mesmo tipo de terreno.
	* No limite, volta para o início (primeira da esquerda para a direita).
	*/
	public function aparencia_seguinte():Void{
		
	}

	/*
	* Altera a aparência para a adjacente à esquerda do mesmo tipo de terreno.
	* No limite, vai para o fim (última da esquerda para a direita).
	*/
	public function aparencia_anterior():Void{

	}
	
	
}