import flash.geom.Point;

/*
* Aviso sem botões que informa que um o usuário não foi encontrado.
* A mensagem não é editável, mas possui um tempo máximo durante o qual aparece.
*/
class c_aviso_usuario_nao_encontrado extends c_aviso_espera {
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "usu_N_encontrado";

	/*
	* Contém todos avisos ativos, usado para deletá-los.
	*/
	private static var avisos_ativos:Array;

//métodos
	/*
	* Cria um aviso com a mensagem passada de parâmetro.
	* @param objeto_param Objeto que conterá este movieclip.
	* @param posioca_param Posição deste movieclip no sistema de coordenadas de objeto_param.
	* @param tempo_para_destruicao_param O tempo, em milissegundos, durante o qual este movieclip ficará presente no palco.
	*/
	public static function criarPara(objeto_param:MovieClip, posicao_param:Point, tempo_para_destruicao_param:Number):Void{
		if(avisos_ativos == undefined){
			avisos_ativos = new Array();
		}
		
		var nomeAviso:String = getNomeAviso(objeto_param);
		objeto_param.attachMovie(c_aviso_usuario_nao_encontrado.LINK_BIBLIOTECA, nomeAviso, objeto_param.getNextHighestDepth(), {_x:Stage.width/2 - objeto_param._x, 
								 																				 _y:Stage.height/2 - objeto_param._y});
		objeto_param[nomeAviso].inicializar();
		objeto_param[nomeAviso]._x -= objeto_param[nomeAviso]._width/2;
		objeto_param[nomeAviso]._y -= objeto_param[nomeAviso]._height/2;
		objeto_param[nomeAviso].chamar(new String());
		avisos_ativos.push(objeto_param[nomeAviso]);
		
		if(posicao_param!=undefined){
			objeto_param[nomeAviso]._x = posicao_param.x;
			objeto_param[nomeAviso]._y = posicao_param.y;
		}
		
		setTimeout(esconder, tempo_para_destruicao_param);
	}

	/*
	* Atualiza a mensagem mostrada pelo aviso ao usuário.
	*/
	private function atualizarMensagem(mensagem_param:String):Void{
		fazNada();
	}
	
	/*
	* Destrói este aviso.
	* Notar a mudança de escopo. Na classe superior, este método é público.
	*/
	private static function esconder():Void{
		var aviso_destruido:c_aviso_usuario_nao_encontrado = avisos_ativos[0];
		aviso_destruido._visible = false;
		removeMovieClip(aviso_destruido);
	}
	
	private function fazNada(){};

}
