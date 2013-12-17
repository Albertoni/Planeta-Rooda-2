import flash.geom.Point;

/*

*/
class c_colisao_terreno extends c_sistema_colisao{
//dados
	/*
	* A matriz de Tiles da cena.
	*/
	private var cenaTiles:Array = new Array();
	
	/*
	* A unidade de getSombra() utilizada, isto é, comprimento (usX) e largura (usY) das Tiles.
	*/
	private var usX:Number = 50;							//Medida da unidade de getSombra() utilizada no projeto - Guto - 16.06.09
	private var usY:Number = 20;
	
	/*
	* Dimensões do terreno representado por este sistema de colisão.
	*/
	private var terrDimX:Number = 1600;						//Dimensões do terreno padronizadas - Guto - 10.07.09
	private var terrDimY:Number = 1200;
	
	/*
	* Relaciona os objetos registrados com suas IDs do sistema de colisão.
	*/
	private var registroObjetos:Array = new Array();

//métodos
	public function c_colisao_terreno(terreno_param:c_terreno){
		terrDimX = terreno_param.COMPRIMENTO_AREA_UTIL;
		terrDimY = terreno_param.LARGURA_AREA_UTIL;
		
		usX = 50;
		usY = 20;
		
		//Cria matriz com as tiles do cenário, com margem de 2 tiles em X e 6 tiles em Y - Guto - 16.06.09
		cenaTiles = criaMtrCena(Math.ceil(terrDimX/usX), Math.ceil(terrDimY/usY));
	}
	
	/*
	* Registra a colisão de um objeto, qualquer que seja.
	* Espera-se que o objeto possua um MovieClip filho de nome "getSombra()", o qual terá a forma de sua colisão.
	* Esta função não fará o registro em tiles que já estejam ocupadas.
	* @param objeto_param O objeto a ter sua colisão registrada.
	*/
	public function registrarColisaoObjeto(objeto_param:c_objeto_colisao){
		var idRegistro:Number = registroObjetos.length+1;
		
		objeto_param.atualizarColisao();
		registroObjetos.push(objeto_param._name);
		
		insereOpTile(objeto_param._x + objeto_param.getSombra()._x,
					 objeto_param._y + objeto_param.getSombra()._y, 
					 usX, usY, objeto_param.getSombra(), cenaTiles, 
					 idRegistro);
	}
	
	/*
	* Registra a colisão de um objeto, qualquer que seja.
	* Espera-se que o objeto possua um MovieClip filho de nome "getSombra()", o qual terá a forma de sua colisão.
	* Esta função forçará o registro, mesmo que as tiles estejam ocupadas.
	* @param objeto_param O objeto a ter sua colisão registrada.
	*/
	public function forcarRegistroColisaoObjeto(objeto_param:c_objeto_colisao){
		var idRegistro:Number = registroObjetos.length;
		
		objeto_param.atualizarColisao();
		registroObjetos.push(objeto_param._name);
		
		insereObjTile(objeto_param._x + objeto_param.getSombra()._x,
					  objeto_param._y + objeto_param.getSombra()._y, 
					  usX, usY, objeto_param.getSombra(), cenaTiles, 
					  idRegistro);
	}
	
	/*
	* Limpa o registro de colisão de um objeto.
	* Espera-se que o objeto possua um MovieClip filho de nome "getSombra()", o qual terá a forma de sua colisão.
	* @param objeto_param O objeto a ter sua colisão limpa.
	*/
	public function limparColisaoObjeto(objeto_param:c_objeto_colisao){
		limpaObjTile( objeto_param._x + objeto_param.getSombra()._x, 
					  objeto_param._y + objeto_param.getSombra()._y, 
					  usX, usY, objeto_param.getSombra(), cenaTiles);
	}
	
	/*
	* Atualiza a colisão de um objeto.
	* @param objeto_param O objeto a ter sua colisão atualizada.
	*/
	public function atualizarColisaoObjeto(objeto_param:c_objeto_colisao){
		limparColisaoObjeto(objeto_param);
		forcarRegistroColisaoObjeto(objeto_param);
	}
	
	/*
	* Indica se houve colisão entre os dois objeto passados como argumento.
	* Utiliza um modificador da posição do personagem, para poder testar em posições em que ele ainda não está.
	* @param primeiro_objeto_param, segundo_objeto_param Objetos que serão testados.
	* @param deslocamento_x, deslocamento_y Deslocamento somado ao primeiro objeto antes do teste.
	*/
	public function houveColisao(primeiro_objeto_param:c_objeto_colisao, segundo_objeto_param:c_objeto_colisao, deslocamento_x:Number, deslocamento_y:Number):Boolean{
		if(objHitTest(primeiro_objeto_param._x + primeiro_objeto_param.getSombra()._x + deslocamento_x 
					  		- (segundo_objeto_param._x + segundo_objeto_param.getSombra()._x),  
					  primeiro_objeto_param._y + primeiro_objeto_param.getSombra()._y + deslocamento_y 
					  		- (segundo_objeto_param._y + segundo_objeto_param.getSombra()._y), 
					  primeiro_objeto_param.getReferenciasColisao(), segundo_objeto_param.getNucleoColisao())
		   != 0){
			return true;
		} else {
			return false;
		}
	}

	/*
	* Retorna um array de objetos na mesma tile do objeto testado.
	* Possibilita especificar deslocamentos para o personagem de modo a testa-lo em posição que não está ainda.
	* @param objeto_param O objeto a ser testado.
	* @param deslocamento_x_param, deslocamento_y_param Deslocamentos para o objeto.
	* @return Um array com os objetos que estiverem na mesma tile.
	*/
	public function getObjetosMesmaTile(objeto_param:c_objeto_colisao, deslocamento_x_param:Number, deslocamento_y_param:Number):Array{
		var nomeObjetoMesmaTile:String;
		var indiceObjetoMesmaTile:Number;
		var idObjetoMesmaTile:Number;
		var idsObjetosMesmaTile:Array = new Array();
		var tilesObjetoTestado:Array; 
		var idsObjetosMesmaTile:Array;
		var objetosMesmaTile:Array = new Array();
		
		tilesObjetoTestado = verifTilesObj(objeto_param._x + objeto_param.getSombra()._x + deslocamento_x_param, 
								  		   objeto_param._y + objeto_param.getSombra()._y + deslocamento_y_param, 
								  		   usX, usY, objeto_param.getSombra());
		idsObjetosMesmaTile = tileHitTest(tilesObjetoTestado, cenaTiles); //Estas ids ainda são ids de colisão.
		
		for(var indice:Number = 0; indice < idsObjetosMesmaTile.length; indice++){
			idObjetoMesmaTile = idsObjetosMesmaTile[indice];
			if(idObjetoMesmaTile != 0){ // ID e índice não utilizáveis
				indiceObjetoMesmaTile = idObjetoMesmaTile;
				nomeObjetoMesmaTile = registroObjetos[idObjetoMesmaTile];
				objetosMesmaTile.push(nomeObjetoMesmaTile);
			}
		}
		
		return objetosMesmaTile;
	}

	/*
	* Dado um objeto suspeito de estar dentro de outro, esta função determina se há objeto que o contenha.
	* A procura é limitada a certo raio em torno do objeto suspeito. O raio é dado pelo tamanho de um objeto molde fornecido.
	* @param terreno_param Terreno no qual objetos que possam colidir serão procurados.
	* @param objeto_suspeito_param O objeto suspeito de estar dentro de outro.
	* @param objeto_molde_param Molde para determinação do raio de procura de objetos que podem conter o objeto suspeito.
	* @return Booleano indicando se objeto_suspeito_param está dentro de outro objeto.
	*/
	public function objetoEstahDentroDeOutro(terreno_param:c_terreno, objeto_suspeito_param:c_objeto_colisao, objeto_molde_param:c_objeto_colisao):Boolean{
		var estah:Boolean = false;
		var comprimentoSombraMolde:Number = objeto_molde_param.getSombra()._width;
		var larguraSombraMolde:Number = objeto_molde_param.getSombra()._height;
		var raioEmTiles:Number = (larguraSombraMolde/(2*usY) < comprimentoSombraMolde/(2*usX)? comprimentoSombraMolde/(2*usX) : larguraSombraMolde/(2*usY));
		var usUsada:Number = (larguraSombraMolde/(2*usY) < comprimentoSombraMolde/(2*usX)? usX : usY);
		var linhaTileProcurada:Number=-raioEmTiles;
		var colunaTileProcurada:Number=-raioEmTiles;
		var boundingBoxSuspeito:c_bounding_box = new c_bounding_box(objeto_suspeito_param);
		var boundingBoxMolde:c_bounding_box = new c_bounding_box(objeto_suspeito_param);
		var objetosMesmaTile:Array;
		var objetoMesmaTile:c_objeto_colisao;
		var indiceObjetoTestado:Number;
		var TILES_BORDA:Number = 0; //Tiles da borda são desconsideradas. Esta é uma medida que melhora a precisão do algoritmo, que ainda não está pronto.
		
		linhaTileProcurada+=TILES_BORDA;
		colunaTileProcurada+=TILES_BORDA;
		
		while(!estah and linhaTileProcurada<raioEmTiles-TILES_BORDA){
			colunaTileProcurada = -raioEmTiles;
			while(!estah and colunaTileProcurada<raioEmTiles-TILES_BORDA){
				objetosMesmaTile = getObjetosMesmaTile(objeto_suspeito_param, linhaTileProcurada*usUsada, colunaTileProcurada*usUsada);
				
				indiceObjetoTestado = 0;
				while(!estah and indiceObjetoTestado<objetosMesmaTile.length){
					objetoMesmaTile = terreno_param[objetosMesmaTile[indiceObjetoTestado]];
					boundingBoxMolde.inicializar(objetoMesmaTile);

					estah = boundingBoxMolde.colideCom(boundingBoxSuspeito);
					
					indiceObjetoTestado++;
				}
				colunaTileProcurada++;
			}
			linhaTileProcurada++;
		}
		
		return estah;
	}
}