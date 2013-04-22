/*---------------------------------------------------
	sistema de inicialização - segunda parte
---------------------------------------------------*/
var objeto_permissao_ver:Number;				//Nivel para que o personagem possa enchergá-lo
var objeto_frame:Number;						//A forma do objeto depende do frame em que o mesmo é carregado
var objeto_terreno_posicao_x:Number;			//Posição X do objeto no palco
var objeto_terreno_posicao_y:Number;			//Posição Y do objeto no palco

var objeto_movieclip:String = "";				//Identificação do objeto
var objeto_endereco:String = "";				//Endereço que o objeto direciona

var matriz_parede:Array = new Array();			//Informações sobre os objetos para serem acessadas futuramente
var	matriz_np_a:Array = new Array();
var	matriz_objeto_link:Array = new Array();

var envia:LoadVars = new LoadVars;
var recebe:LoadVars = new LoadVars;

btStart._visible = false;

recebe.onLoad = recebe_flash_var_principais2;

envia.personagem_id = usuario_status.personagem_id;
envia.action = "4";
envia.sendAndLoad("interface_bd_personagem.php", recebe, "POST");	

function recebe_flash_var_principais2(success) {      //existe uma funçao de exato mesmo nome em interface_personagem_inicializacao.as. Mudar no futuro - Roger - 03.08.09
	if(success) {
		matriz_parede 			= [];
		matriz_np_a   			= [];
		matriz_objeto_link		= [];        
		
		for(var n:Number = 0; n <this.nLoop; n++){		
			objeto_movieclip = this['objeto_movieclip' + n];
			
			if(objeto_movieclip == "np_a") {
				objeto_permissao_ver = 100; //this['objeto_permissao_ver' + n];			//No futuro pode haver um controle sobre a permissão de visualização dos objetos - 18.05.10 - Guto
				if(Number(usuario_status.usuario_nivel) <= Number(objeto_permissao_ver)) {
					objeto_frame = this['objeto_frame' + n];
					objeto_terreno_posicao_x = this['objeto_terreno_posicao_x' + n];
					objeto_terreno_posicao_y = this['objeto_terreno_posicao_y' + n];
					matriz_np_a.push( [ objeto_frame , objeto_terreno_posicao_x , objeto_terreno_posicao_y] );  
				}
			}
			
			if(objeto_movieclip == "parede") {
				objeto_permissao_ver = 100; //this['objeto_permissao_ver' + n];			//No futuro pode haver um controle sobre a permissão de visualização dos objetos - 18.05.10 - Guto
				if(Number(usuario_status.usuario_nivel) <= Number(objeto_permissao_ver)) {
					objeto_frame = this['objeto_frame' + n];
					objeto_terreno_posicao_x = this['objeto_terreno_posicao_x' + n];
					objeto_terreno_posicao_y = this['objeto_terreno_posicao_y' + n];
					matriz_parede.push( [ objeto_frame , objeto_terreno_posicao_x , objeto_terreno_posicao_y] );  
				}
			}
			
			if(objeto_movieclip == "objeto_link") {
				objeto_permissao_ver = 100; //this['objeto_permissao_ver' + n];			//No futuro pode haver um controle sobre a permissão de visualização dos objetos - 18.05.10 - Guto
				if(usuario_status.usuario_nivel <= objeto_permissao_ver) {
					objeto_permissao_acessar = 100; //this['objeto_permissao_acessar' + n];			//No futuro pode haver um controle sobre a permissão de acesso dos objetos de link - 18.05.10 - Guto
					if(usuario_status.usuario_nivel <= objeto_permissao_acessar) {
							objeto_frame = this['objeto_frame' + n];
							objeto_terreno_posicao_x = this['objeto_terreno_posicao_x' + n];
							objeto_terreno_posicao_y = this['objeto_terreno_posicao_y' + n];
							objeto_endereco = this['objeto_link' + n];
							matriz_objeto_link.push( [ objeto_frame , objeto_terreno_posicao_x , objeto_terreno_posicao_y, objeto_endereco] );  
					} else {
							objeto_frame = this['objeto_frame' + n];
							objeto_terreno_posicao_x = this['objeto_terreno_posicao_x' + n];
							objeto_terreno_posicao_y = this['objeto_terreno_posicao_y' + n];
							objeto_endereco = "null";
							matriz_objeto_link.push( [ objeto_frame , objeto_terreno_posicao_x , objeto_terreno_posicao_y, objeto_endereco] );  
					}
				}
			}
		}
		btStart._visible = true;
		//nextScene();
	}
}

this.onMouseUp = function() {
	if(btStart.hitTest(_xmouse, _ymouse)) {
		nextScene();		
	}
}