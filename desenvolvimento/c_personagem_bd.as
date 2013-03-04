import flash.geom.Point;
/*
* Representação de um personagem no banco de dados.
*
*
*
*/
class c_personagem_bd{
//dados

	/*
	* O sexo do personagem.
	*/
	private var sexo:String = new String();

	/*
	* A identificação deste objeto no banco de dados.
	* Esta informação é necessária para poder atualizar seus dados, conforme forem mudados.
	*/
	private var identificacao:String = c_banco_de_dados.NAO_SALVO;

	/*
	* Indica, e não mede, a velocidade com que se movimenta.
	*/    
	public static var VELOCIDADE_CORRENDO:Number = 1;
	public static var VELOCIDADE_ANDANDO:Number = 0;
	private var indicadorVelocidade:Number = VELOCIDADE_ANDANDO;

	/*
	* Frames da aparência do personagem.
	*/
	private static var APARENCIA_INDEFINIDA:Number = 0;
	private var cabelo:Number = APARENCIA_INDEFINIDA;
	private var olhos:Number = APARENCIA_INDEFINIDA;
	private var corPele:Number = APARENCIA_INDEFINIDA;
	private var corLuvasBotas:Number = APARENCIA_INDEFINIDA;
	private var corCinto:Number = APARENCIA_INDEFINIDA;

	/*
	* Constantes de aparências padrões.
	*/
	private static var CABELO_MASCULINO_PADRAO:Number = 2;
	private static var CABELO_FEMININO_PADRAO:Number = 1;
	private static var OLHOS_PADRAO:Number = 2;
	private static var PELE_PADRAO:Number = 1;
	private static var LUVAS_MASCULINO_PADRAO:Number = 29; //azul
	private static var LUVAS_FEMININO_PADRAO:Number = 1; //rosa fraco
	private static var CINTO_MASCULINO_PADRAO:Number = 29; //azul
	private static var CINTO_FEMININO_PADRAO:Number = 1; //rosa fraco

	/*
	* Constantes para validação de dados.
	*/
	private static var QUANTIDADE_CABELOS:Number;
	private static var QUANTIDADE_OLHOS:Number = 8;
	private static var QUANTIDADE_CORES_PELE:Number;
	private static var QUANTIDADE_CORES_LUVAS_BOTAS:Number;
	private static var QUANTIDADE_CORES_CINTO:Number;

	/*
	* O nome do personagem e sua cor de texto.
	*/
	private var nome:String;
	private var corNome:String;

	/*
	* Rota de direções para as quais o personagem precisa andar.
	*/
	private var rota:String;

	/*
	* Posição em que o personagem está.
	*/
	private var posicaoAtual:Point;

	/*
	* Falas do personagem.
	*/
	private var falas:Array = new Array();
	
	/*
	* Identificação no banco de dados do chat que recebe mensagens enviadas a este personagem.
	*/
	private var chat_id:String;

//métodos
	public function c_personagem_bd(id_banco_de_dados_param:String){
		identificacao = id_banco_de_dados_param;
		falas = new Array();
		rota = new String();
		posicaoAtual = new Point(0,0);
		cabelo = APARENCIA_INDEFINIDA;
		olhos = APARENCIA_INDEFINIDA;
		corPele = APARENCIA_INDEFINIDA;
		corLuvasBotas = APARENCIA_INDEFINIDA;
		corCinto = APARENCIA_INDEFINIDA;
	}

	/*
	* @param chat_id_param Identificação no banco de dados do chat que recebe mensagens enviadas a este personagem.
	*/
	public function definirChatId(chat_id_param:String):Void{
		chat_id = chat_id_param;
	}
	/*
	* @return Identificação no banco de dados do chat que recebe mensagens enviadas a este personagem.
	*/
	public function getChatId():String{
		return chat_id;
	}

	/*
	* @param posicaoAtual_param A posição em que o personagem está.
	*/
	public function definirPosicaoAtual(posicaoAtual_param:Point){ posicaoAtual = new Point(posicaoAtual_param.x, posicaoAtual_param.y); }
	/*
	* @return A posição em que o personagem está, segundo a última vez em que foi atualizado.
	*/
	public function getPosicaoAtual():Point{ return new Point(posicaoAtual.x, posicaoAtual.y); }

	/*
	* @param fala_param A fala do personagem.
	*/
	public function adicionarFala(fala_param:String):Void{ 
		falas.push(fala_param);
	}
	/*
	* @param id_base_fala_param O id a partir do qual devem ser retornadas falas.
	* @return Falas do personagem com id maior que o passo de parâmetro. 
	*/
	public function getFalasRecentes():Array{ 
		return falas;
	}
	
	/*
	* @param indicador_velocidade_param Um indicador da velocidade do personagem, conforme definidos nesta classe.
	*/
	public function definirVelocidade(indicador_velocidade_param:Number){
		if(indicador_velocidade_param == VELOCIDADE_CORRENDO
		   or indicador_velocidade_param == VELOCIDADE_ANDANDO){
			indicadorVelocidade = indicador_velocidade_param;
		}
	}
	/*
	* @return Indicador da velocidade do personagem.
	*/
	public function getIndicadorVelocidade():Number{
		return indicadorVelocidade;
	}

	/*
	* Devolve a identificação deste objeto no banco de dados, para encontrar sua imagem lá.
	*/
	public function getIdentificacaoBancoDeDados():String{ return identificacao; }

	/*
	* @param nome_param O nome do personagem.
	*/
	public function definirNome(nome_param:String):Void{ nome = nome_param; }
	/*
	* @return O nome do personagem.
	*/
	public function getNome():String{ return nome; }

	/*
	* @param corNome_param A cor do texto do nome do personagem.
	*/
	public function definirCorNome(corNome_param:Number):Void{ corNome = '0x' + corNome_param; }
	/*
	* @return A cor do nome do personagem.
	*/
	public function getCorNome():String{ return corNome; }

	/*
	* @param Lista de direções em que o personagem se moveu.
	*/
	public function definirRota(rota_param:String){ rota = rota_param; }
	/*
	* @return A rota do personagem.
	*/
	public function getRota():Array{ return rota.split(","); }

	/*
	*
	*
	*/
	public function definirCabelo(cabelo_param:String):Void{
		if(cabelo_param == APARENCIA_INDEFINIDA
		   or Number(cabelo_param) < 1 or QUANTIDADE_CABELOS < Number(cabelo_param)
		   or isNaN(Number(cabelo_param))){
			cabelo = CABELO_MASCULINO_PADRAO;
		} else {
			cabelo = Number(cabelo_param);
		} 
	}

	public function definirOlhos(olhos_param:String):Void{
		if(olhos_param == APARENCIA_INDEFINIDA
		   or Number(olhos_param) < 1 or QUANTIDADE_OLHOS < Number(olhos_param)
		   or isNaN(Number(olhos_param))){
			olhos = OLHOS_PADRAO;
		} else {
			olhos = Number(olhos_param);
		}
	}

	public function definirCorPele(corPele_param:String):Void{
		if(corPele_param == APARENCIA_INDEFINIDA
		   or Number(corPele_param) < 1 or QUANTIDADE_CORES_PELE < Number(corPele_param)
		   or isNaN(Number(corPele_param))){
			corPele = PELE_PADRAO;
		} else {
			corPele = Number(corPele_param);
		}
	}

	public function definirCorLuvasBotas(luvasBotas_param:String):Void{
		if(luvasBotas_param == APARENCIA_INDEFINIDA
		   or Number(luvasBotas_param) < 1 or QUANTIDADE_CORES_LUVAS_BOTAS < Number(luvasBotas_param)
		   or isNaN(Number(luvasBotas_param))){
			corLuvasBotas = CABELO_MASCULINO_PADRAO;
		} else {
			corLuvasBotas = Number(luvasBotas_param);
		}
	}

	public function definirCorCinto(cinto_param:String):Void{
		if(cinto_param == APARENCIA_INDEFINIDA
		   or Number(cinto_param) < 1 or QUANTIDADE_CORES_CINTO < Number(cinto_param)
		   or isNaN(Number(cinto_param))){
			corCinto = CINTO_MASCULINO_PADRAO;
		} else {
			corCinto = Number(cinto_param);
		}
	}

	/*
	* @return O tipo do cabelo.
	*/
	public function getCabelo():Number{ return cabelo; }

	/*
	* @return O tipo de olhos.
	*/
	public function getOlhos():Number{ return olhos; }

	/*
	* @return O tipo de cor da pele.
	*/
	public function getCorPele():Number{ return corPele; }

	/*
	* @return O tipo de cor das luvas e botas.
	*/
	public function getCorLuvasBotas():Number{ return corLuvasBotas; }

	/*
	* @return O tipo de cor do cinto.
	*/
	public function getCorCinto():Number{ return corCinto; }


}
