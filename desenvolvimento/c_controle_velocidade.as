import flash.geom.Point;

/*
* O controle do layout que permite escolha entre "correndo" ou "andando".
*/
class c_controle_velocidade extends MovieClip{
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "bts_velocidade";
	
	/*
	* Botões para caminhar e correr.
	*/
	public var btCaminhar:MovieClip;
	public var btCorrer:MovieClip;
	
	/*
	* Frames dos botões para correr e caminhar.
	*/
	public static var FRAME_INATIVO:Number = 1;
	public static var FRAME_SELECIONADO:Number = 2;
	public static var FRAME_MIRADO:Number = 3;
	
//métodos
	public function inicializar():Void{
		if(_root.personagem_status.getIndicadorVelocidade() == c_personagem_bd.VELOCIDADE_CORRENDO){
			btCorrer.gotoAndStop(FRAME_SELECIONADO);	
			btCaminhar.gotoAndStop(FRAME_INATIVO);	
		} else if(_root.personagem_status.getIndicadorVelocidade() == c_personagem_bd.VELOCIDADE_ANDANDO){
			btCaminhar.gotoAndStop(FRAME_SELECIONADO);
			btCorrer.gotoAndStop(FRAME_INATIVO);	
		}
		
		btCorrer.onRollOver = function(){ 
			if(_root.personagem_status.getIndicadorVelocidade() == c_personagem_bd.VELOCIDADE_ANDANDO){
				gotoAndStop(c_controle_velocidade.FRAME_MIRADO);
			}}
		btCorrer.onRollOut = function(){ 
			if(_root.personagem_status.getIndicadorVelocidade() == c_personagem_bd.VELOCIDADE_ANDANDO){
				gotoAndStop(c_controle_velocidade.FRAME_INATIVO);
			}}
		
		btCaminhar.onRollOver = function(){ 
			if(_root.personagem_status.getIndicadorVelocidade() == c_personagem_bd.VELOCIDADE_CORRENDO){
				gotoAndStop(c_controle_velocidade.FRAME_MIRADO);
			}}
		btCaminhar.onRollOut = function(){ 
			if(_root.personagem_status.getIndicadorVelocidade() == c_personagem_bd.VELOCIDADE_CORRENDO){
				gotoAndStop(c_controle_velocidade.FRAME_INATIVO);
			}}
		
		btCorrer.onPress = function(){
			_parent.btCaminhar.gotoAndStop(c_controle_velocidade.FRAME_INATIVO);
			gotoAndStop(c_controle_velocidade.FRAME_SELECIONADO);
			_root.planeta.getTerrenoEmQuePersonagemEstah().mp.correr();
			_root.carregar_bd_posicoes();			//Tem que gravar no servidor a velocidade do mp, para que o mesmo possa se locomover corretamente quando visualizado por outro usuário - Guto - 16.01.09
		}
		btCaminhar.onPress = function(){
			_parent.btCorrer.gotoAndStop(c_controle_velocidade.FRAME_INATIVO);
			gotoAndStop(c_controle_velocidade.FRAME_SELECIONADO);
			_root.planeta.getTerrenoEmQuePersonagemEstah().mp.caminhar();		
			_root.carregar_bd_posicoes();			//Tem que gravar no servidor a velocidade do mp, para que o mesmo possa se locomover corretamente quando visualizado por outro usuário - Guto - 16.01.09				
		}
		
		c_localizacao.criarPara(btCorrer, "Correr", new Point(btCorrer._width/2, -3));
		c_localizacao.criarPara(btCaminhar, "Caminhar", new Point(btCaminhar._width/2, -3));
	}

	
}