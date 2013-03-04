/*
* Colisão para procura de lugar livre.
*/

import flash.geom.Point;

class c_colisao_rapida{
//dados
	/*
	* Array de arrays.
	* Cada posição corresponde a um determinado X e contém um array.
	* Cada um destes arrays aninhados possui como elementos os 'Y' de cada tile ocupada.
	* Desta forma, se a tile (3,5) está desocupada, não haverá tilesOcupadas[3][k] = 5, onde k é uma posição qualquer.
	* Por outro lado, se tilesOcupadas[6][g] = 7, onde g é uma posição qualquer, a tile (6,7) está ocupada.
	*/
	private var tilesOcupadas:Array;

	/*
	* Terreno representado pela colisão.
	*/
	private var terreno_representado:c_terreno;
	
	/*
	* Bounding box que dita o tamanho de tile.
	*/
	private var moldeTile:c_bounding_box;
	
	/*
	* Maior largura de bounding box encontrada nesta colisão.
	*/
	private var maiorLarguraBoundingBoxEmTiles:Number;
	
	/*
	* Dimensões do terreno em tiles do tamanho de moldeTile.
	*/
	private var comprimentoTerrenoEmTiles:Number;
	private var larguraTerrenoEmTiles:Number;

//métodos
	public function c_colisao_rapida(terrenoRepresentado_param:c_terreno, moldeTile_param:c_bounding_box){
		var indice_x:Number;
		var indice_y:Number;
		var MARGEM_TILES:Number = 1;
		var comprimentoTiles:Number = terrenoRepresentado_param.COMPRIMENTO_AREA_UTIL/moldeTile_param.getComprimento();
		var larguraTiles:Number = terrenoRepresentado_param.LARGURA_AREA_UTIL/moldeTile_param.getLargura();
		comprimentoTerrenoEmTiles = comprimentoTiles - MARGEM_TILES;
		larguraTerrenoEmTiles = larguraTiles - MARGEM_TILES;
		
		moldeTile = moldeTile_param;
		terreno_representado = terrenoRepresentado_param;
		maiorLarguraBoundingBoxEmTiles = 0;
		
		tilesOcupadas = new Array();
		
		for(indice_x=0; indice_x<comprimentoTiles; indice_x++){
			tilesOcupadas[indice_x] = new Array();
		}
	}
	
	/*
	* Adiciona uma bounding box, marcando sua posição como ocupada.
	* Considera-se que:
	*	- a posição da boundingbox esteja em coordenadas do terreno_representado.
	*	- a bounding box pertence ao terreno, i.e. não está fora de seus limites.
	*/
	public function adicionarForma(boundingbox_param:c_bounding_box):Void{
		var pontoInicioBoundingBoxTiles:Point = terrenoParaTile(new Point(boundingbox_param.getX(), boundingbox_param.getY()));
		var comprimentoBoundingBoxTiles:Number = boundingbox_param.getComprimento()/moldeTile.getComprimento();
		var larguraBoundingBoxTiles:Number = boundingbox_param.getLargura()/moldeTile.getLargura();
		var indice_x:Number;
		var indice_y:Number;
		
		if(maiorLarguraBoundingBoxEmTiles < larguraBoundingBoxTiles){
			maiorLarguraBoundingBoxEmTiles = larguraBoundingBoxTiles;
		}
		
		for(indice_x=pontoInicioBoundingBoxTiles.x; indice_x<pontoInicioBoundingBoxTiles.x+comprimentoBoundingBoxTiles; indice_x++){
			for(indice_y=pontoInicioBoundingBoxTiles.y; indice_y<pontoInicioBoundingBoxTiles.y+larguraBoundingBoxTiles; indice_y++){
				tilesOcupadas[indice_x].push(indice_y);
			}
		}
	}
	
	/*
	* Procura um lugar em que a moldeTile caiba, sem que fique dentro de outra.
	* Leva em consideração os limites do terreno representado.
	* @return undefined caso não exista lugar livre. O ponto do lugar nas coordenadas do terreno_representado, caso exista.
	*/
	public function procurarLugarLivre():Point{
		var k:Number;
		var g:Number;
		var pontoLivre:Point = undefined;
		var terminouBusa:Boolean = false;
		var existeLugarLivre:Boolean = true;
		
		tilesOcupadas.sortOn("length", Array.NUMERIC);
		
		k=0;
		while(k<tilesOcupadas.length and existeLugarLivre and !terminouBusa){
			if(larguraTerrenoEmTiles-maiorLarguraBoundingBoxEmTiles < tilesOcupadas[k].length){
				pontoLivre = undefined;
				existeLugarLivre = false;
			} else {
				g=0;
				while(g<tilesOcupadas[k].length-1 and !terminouBusa){
					if(maiorLarguraBoundingBoxEmTiles < tilesOcupadas[k][g+1] - tilesOcupadas[k][g]){
						terminouBusa = true;
						pontoLivre = tileParaTerreno(new Point(k, tilesOcupadas[k][g]+1));
						if(!terreno_representado.estaNaAreaUtil(pontoLivre.x, pontoLivre.y)){
							terminouBusa = false;
							pontoLivre = undefined;
						}
					}
					g++;
				}
				k++;
			}
		}
		
		return pontoLivre;
	}
	
	/*
	* @param pontoTerreno_param Ponto em coordenadas do terreno_represntado.
	* @return Tile no terreno que contém o ponto dado.
	*/
	private function terrenoParaTile(pontoTerreno_param:Point):Point{
		var pontoTile:Point = new Point(0,0);
		pontoTile.x = Math.floor(pontoTerreno_param.x/comprimentoTerrenoEmTiles);
		pontoTile.y = Math.floor(pontoTerreno_param.y/larguraTerrenoEmTiles);
		return pontoTile;
	}
	private function tileParaTerreno(pontoTile_param:Point):Point{
		var pontoTerreno:Point = new Point(0,0);
		pontoTerreno.x = pontoTile_param.x*moldeTile.getComprimento();
		pontoTerreno.y = pontoTile_param.y*moldeTile.getLargura();
		return pontoTerreno;
	}
	
}









