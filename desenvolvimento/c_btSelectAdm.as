import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;
import flash.geom.Point;

/*
* Botão que compõe menus select e dropdown.
*
*/
class c_btSelectAdm extends MovieClip{
//dados
	/*
	* Link deste objeto na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "btSelectAdm";
	
	/*
	* Eventos.
	*/
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	/*
	* Indica se este botão está selecionado.
	*/
	private var selecionado:Boolean = false;
	
	/*
	* Guarda o texto original, caso precise ser truncado.
	*/
	private var textoOriginal:String;
	
	/*
	* Indica se foi truncado.
	*/
	private var foiTruncado:Boolean;
	
	/*
	* Medidas caso tenha sido redimensionado.
	*/
	private var foiRedimensionado:Boolean = false;
	private var comprimentoRedimensionado:Number = undefined;
	private var larguraRedimensionado:Number = undefined;

//métodos
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		this['selecao']._visible = false;
		foiRedimensionado = false;
		foiTruncado = false;
		textoOriginal = new String();
		onPress = funcaoOnPress;
	}
	
	/*
	* Dispara um evento avisando que este botão foi pressionado.
	*/
	private function funcaoOnPress():Void{
		dispatchEvent({target:this, type:"btPressionado", nome: _name});
	}
	
	/*
	* Faz com que apenas o texto e a seleção apareçam.
	* @param invisivel_param Define se está invisível (com apenas o texto e a seleção aparecendo).
	*/
	public function setInvisivel(invisivel_param:Boolean):Void{
		if(invisivel_param){
			this['conteudo']._x = 0;
			this['selecao']._x = 0;
		}
		this['sistemaAbre']._visible = !invisivel_param;
		this['sistemaFecha']._visible = !invisivel_param;
		this['fundoSelect']._visible = !invisivel_param;
	}
	
	/*
	* Redimensiona este botão para caber em determinado tamanho.
	*/
	public function redimensionar(comprimento_param:Number, largura_param:Number):Void{
		foiRedimensionado = true;
		comprimentoRedimensionado = comprimento_param;
		larguraRedimensionado = largura_param;
		this['selecao']._width = comprimento_param;
		this['selecao']._height = largura_param;
	}
	
	/*
	* Muda a mensagem deste botão.
	* @param mensagem_param Mensagem a ser exibida no botão.
	*/
	public function atualizar_mensagem(mensagem_param:String):Void{
		this['conteudo'].text = mensagem_param;
		if(foiRedimensionado and comprimentoRedimensionado < this['conteudo'].textWidth){
			truncarConteudo(comprimentoRedimensionado);
		} else if(_width < this['conteudo'].textWidth){
			truncarConteudo(_width);
		} else {
			restaurarTruncagem();
		}
	}
	
	/*
	* Trunca o texto para que tenha o comprimento do parâmetro.
	*/
	private function truncarConteudo(comprimento_param:Number):Void{
		var posicaoLocalizacao:Point = new Point(this['conteudo']._x + this['selecao']._width/2, 0);
		foiTruncado = true;
		textoOriginal = this['conteudo'].text;
		if(c_localizacao.getObjetoLocalizacao(this) == undefined){
			c_localizacao.criarPara(this, textoOriginal, posicaoLocalizacao);
		} else {
			c_localizacao.getObjetoLocalizacao(this).atualizarMensagem(textoOriginal);
		}
		while(comprimento_param < this['conteudo'].textWidth){
			this['conteudo'].text = this['conteudo'].text.substr(0, this['conteudo'].text.length-2);
		}
	}
	
	/*
	* Restaura o conteúdo após truncagem.
	* É importante observar que duas chamadas sucessivas de "truncarConteudo" inviabilizam o retorno ao estado inicial.
	*/
	private function restaurarTruncagem():Void{
		if(foiTruncado){
			foiTruncado = false;
			this['conteudo'].text = textoOriginal;
			c_localizacao.destruirDe(this);
		}
	}
	
	/*
	* Modifica a aparência do botão baseado em sua aparência atual.
	* Se estiver selecionado, perde a seleção.
	* Se não tiver seleção, torna-se selecionado.
	*/
	public function toggle_selecao():Void{
		if(selecionado){
			this['selecao']._visible = false;
			selecionado = false;
		} else {
			this['selecao']._visible = true;
			selecionado = true;
		}
	}
	
	
}