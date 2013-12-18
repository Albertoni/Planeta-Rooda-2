//===========================================================
//sistema de inicialização - primeira parte
//===========================================================

//===========================================================
//Classes com as propriedades dos objetos - eD - 12/12/08
//===========================================================

import c_usuario;
import c_terreno;
import c_caixa_texto;

usuario_status = new c_usuario;
terreno_status = new c_terreno;



//===========================================================

var envia:LoadVars = new LoadVars;
var recebe:LoadVars = new LoadVars;

recebe.onLoad = recebe_flash_var_principais;

envia.action = "1";
envia.sendAndLoad("interface_bd_personagem.php", recebe, "POST");	

function recebe_flash_var_principais(success) {
	
	if(success) {
			usuario_status.personagem_id 					= this.personagem_id;
			usuario_status.personagem_nome 					= this.personagem_nome;
			usuario_status.personagem_avatar_1 				= this.personagem_avatar_1
			usuario_status.personagem_cor_texto				= this.personagem_cor_texto;			
			usuario_status.ultima_atualizacao				= this.ultima_atualizacao;
			usuario_status.usuario_nivel					= this.usuario_nivel;
			usuario_status.usuario_grupo_base				= this.usuario_grupo_base;
			usuario_status.personagem_posicao_x 			= this.personagem_posicao_x;  
			usuario_status.personagem_posicao_y 			= this.personagem_posicao_y;
			usuario_status.personagem_posicao_x_auxiliar 	= this.personagem_posicao_x_auxiliar;  
			usuario_status.personagem_posicao_y_auxiliar	= this.personagem_posicao_y_auxiliar;
			usuario_status.personagem_linha_chat        	= this.personagem_linha_chat;
			usuario_status.personagem_fala              	= this.personagem_fala;
			usuario_status.private_chat                 	= this.private_chat;
			usuario_status.lista_contatos               	= this.personagem_contatos;
			usuario_status.lista_grupos                 = this.personagem_grupos;
			
			if (Number(this.personagem_velocidade)==0)
			    usuario_status.velocidade = false;
			else usuario_status.velocidade = true;
			
			terreno_status.terreno_id					= this.terreno_id;
			terreno_status.terreno_pai_id				= this.terreno_pai_id;
			terreno_status.terreno_nome					= this.terreno_nome;
			terreno_status.oeste						= this.oeste;
			terreno_status.nome_oeste					= this.nome_oeste;
			terreno_status.leste						= this.leste;
			terreno_status.nome_leste					= this.nome_leste;
			terreno_status.terreno_solo					= this.terreno_solo;
			terreno_status.terreno_clima				= this.terreno_clima;
			terreno_status.terreno_chat                 = this.terreno_chat;
						
			//Inicialização do texto no objeto chat_box			
		/*	chat = "Sistema ("+ usuario_status.ultima_atualizacao +"): Bem-vindo ao Chat local de " + terreno_status.terreno_nome + " !";			

			//-------------------------------------------------------
			//Debug de texto em tela
			//-------------------------------------------------------
			debug._x = 0;
			debug._y = 0;
			debug.text += "\n usuario_nivel: " + usuario_status.usuario_nivel;
			var textStd:TextFormat = new TextFormat();
			textStd.align = "left";
			debug.setTextFormat(textStd);
		*/
			nextScene();
	}//if(success)
}//function recebe_flash_fala_mp()
