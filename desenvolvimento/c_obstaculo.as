/*
* Classe com o objetivo de criar um hitTest simples e funcional.
* Recebe em seu construtor um MovieClip, do qual cria um BitmapData.
* O BitmapData é usado para avaliar hitTest da seguinte forma:
*	- Pontos verde fracos são considerados ocupados.
*	- Pontos de qualquer outra cor são considerados não ocupados.
* Esta classe implementa um método de hitTest próprio, haColisao(x, y);
*/
import flash.display.BitmapData;
class c_obstaculo{
//dados	
	/*
	* Constantes de cores.
	*/
	public static var COR_LARANJA:Number = 0xFFAC00;
	public static var COR_VERDE_FRACO:Number = 0x9FCD53;
	public static var COR_AZUL:Number = 0x0000CC;
	public static var COR_PRETA:Number = 0;
	
	/*
	* A imagem criada pelo construtor do movieclip passado.
	*/
	private var imagemObstaculo:BitmapData;
	
	/*
	* Servem para habilitar o uso de coordenadas do movieclip na detecção de colisão.
	*/
	private var offset_x:Number;
	private var offset_y:Number;
	
	/*
	* Constantes do limite de representação de BitmapData.
	*/
	public static var MAXIMO_COMPRIMENTO_IMAGEM:Number = 2500;
	public static var MAXIMA_LARGURA_IMAGEM:Number = 2500;
	
//métodos
	/*
	* À partir de um movieclip, cria a imagem do obstáculo e a deixa pronta para ser usada em hitTest.
	* obstaculo_mc é o MovieClip cujas cores definirão a área de hitTest.
	* offset_x é o deslocamento do movieclip em relação à sua posição x=0. 
	* offset_y é o deslocamento em relação à y=0.
	* Assim, se o desenho dentro de um movieclip começar na posição x=-100, y=300, os parâmetros deverão ser 
	* offset_x=-100, offset_y=300.
	*/
	public function c_obstaculo(obstaculo_mc_param:MovieClip, offset_x_param:Number, offset_y_param:Number){
		offset_x = offset_x_param;
		offset_y = offset_y_param;
		
		if(MAXIMO_COMPRIMENTO_IMAGEM < obstaculo_mc_param._width){
			if(MAXIMA_LARGURA_IMAGEM < obstaculo_mc_param._height){
				imagemObstaculo = new BitmapData(MAXIMO_COMPRIMENTO_IMAGEM, MAXIMA_LARGURA_IMAGEM, false, COR_LARANJA);
			} else {
				imagemObstaculo = new BitmapData(MAXIMO_COMPRIMENTO_IMAGEM, obstaculo_mc_param._height, false, COR_LARANJA);
			}
		} else {
			if(MAXIMA_LARGURA_IMAGEM < obstaculo_mc_param._height){
				imagemObstaculo = new BitmapData(obstaculo_mc_param._width, MAXIMA_LARGURA_IMAGEM, false, COR_LARANJA);
			} else {
				imagemObstaculo = new BitmapData(obstaculo_mc_param._width, obstaculo_mc_param._height, false, COR_LARANJA);
			}
		}
		imagemObstaculo.draw(obstaculo_mc_param);
	}

	/*
	* Método para conferir colisão.
	* Utiliza o sistema de coordenadas do MovieClip que foi passado ao construtor.
	*/
	public function haColisao(x_param:Number, y_param:Number):Boolean{
		if(imagemObstaculo.getPixel(x_param - offset_x, y_param - offset_y) == COR_VERDE_FRACO
		   or imagemObstaculo.getPixel(x_param - offset_x, y_param - offset_y) == COR_AZUL){
			return true;
		} else {
			return false;
		}
	}
	
	
}