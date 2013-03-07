/*---------------------------------------------------
	sistema de inicialização - segunda parte
---------------------------------------------------*/
import flash.geom.ColorTransform;

var objeto:Array;
var objeto_movieclip:String = "";				//Identificação do objeto
var objeto_frame:Number;						//A forma do objeto depende do frame em que o mesmo é carregado
var objeto_terreno_posicao_x:Number;			//Posição X do objeto no palco
var objeto_terreno_posicao_y:Number;			//Posição Y do objeto no palco
var objeto_endereco:String = "";				//Endereço que o objeto direciona


var envia:LoadVars = new LoadVars;
var recebe:LoadVars = new LoadVars;

var porcentagemCarregada:Number = 0;
var informacaoCarregada:Number = 0;
var informacaoTotal:Number = 1000;


recebe.onLoad = recebe_flash_var_principais2;

envia.personagem_id = usuario_status.personagem_id;
envia.action = "4";
envia.sendAndLoad("interface_bd_personagem.php", recebe, "POST");	

switch(_root.animacao){
	case c_barra_progresso.TIPO_FOGUETE:
		attachMovie(c_barra_progresso_foguete.LINK_BIBLIOTECA, "barraProgresso2", getNextHighestDepth(), {_x:0, y:0});
		_root.barraProgresso2.inicializar("Aguardando dados de objetos.");
		break;
	case c_barra_progresso.TIPO_PREDIO:
		attachMovie(c_barra_progresso_predio.LINK_BIBLIOTECA, "barraProgresso2", getNextHighestDepth(), {_x:0, y:0});
		_root.barraProgresso2.inicializar(_root.direcaoAnimacao, "Aguardando dados de objetos.");
		break;
	default:
		attachMovie(c_barra_progresso_foguete.LINK_BIBLIOTECA, "barraProgresso2", getNextHighestDepth(), {_x:0, y:0});
		_root.barraProgresso2.inicializar("Aguardando dados de objetos.");
		break;
}
_root.barraProgresso2.definirPorcentagem(95+FATIA_CARREGAMENTO_INICIALIZACAO_1);

function recebe_flash_var_principais2(success) {      //existe uma funçao de exato mesmo nome em interface_personagem_inicializacao.as. Mudar no futuro - Roger - 03.08.09
	if(success) {
		var tem_permissaoFuncionalidade:Boolean = false;
		
		_root.barraProgresso2.atualizarMensagem("Carregando dados dos objetos recebidos.");
		
		terreno_principal_status = new c_terreno_bd();
		terreno_patio_status = new c_terreno_bd();
		
		/*
					PRINCIPAL
		*/
		matriz_parede 			= [];
		matriz_objeto_link		= [];   
		matriz_predios          = [];
		for(var n:Number = 0; n <this["principal_numero_objetos_no_terreno"]; n++){	
			objeto = new Array();
			objeto_movieclip = this['principal_objeto_movieclip' + n ];
			objeto_frame = this['principal_objeto_frame' + n];
			objeto_terreno_posicao_x = this['principal_objeto_terreno_posicao_x' + n];
			objeto_terreno_posicao_y = this['principal_objeto_terreno_posicao_y' + n];
			objeto_endereco = this['principal_objeto_link' + n];
			objeto_id = this['principal_objeto_id' + n];
			objeto.push(objeto_frame);
			objeto.push(objeto_terreno_posicao_x);
			objeto.push(objeto_terreno_posicao_y);
			objeto.push(objeto_endereco);
			objeto.push(objeto_id);
			
			switch(objeto_movieclip){
				case "parede": matriz_parede.push(objeto);  
					break;
				case "objeto_link": 
						tem_permissaoFuncionalidade = true;
						switch(c_casa.frameParaTipo(objeto_frame)){
							case c_casa.TIPO_BIBLIOTECA: tem_permissaoFuncionalidade = turma_status.permissao_biblioteca;
								break;
							case c_casa.TIPO_BLOG: tem_permissaoFuncionalidade = turma_status.permissao_blog;
								break;
							case c_casa.TIPO_FORUM: tem_permissaoFuncionalidade = turma_status.permissao_forum;
								break;
							case c_casa.TIPO_PORTFOLIO: tem_permissaoFuncionalidade = turma_status.permissao_portfolio;
								break;
							case c_casa.TIPO_APARENCIA: tem_permissaoFuncionalidade = true;
								break;
							case c_casa.TIPO_ARTE: tem_permissaoFuncionalidade = turma_status.permissao_planetaArte;
								break;
							case c_casa.TIPO_PERGUNTA: tem_permissaoFuncionalidade = turma_status.permissao_planetaPergunta;
								break;
							case c_casa.TIPO_AULA: tem_permissaoFuncionalidade = turma_status.permissao_aulas;
								break;
						}
						if(tem_permissaoFuncionalidade){
							matriz_objeto_link.push(objeto); 
						}
					break;
				case "predio": matriz_predios.push(objeto);
					break;
			}
		} 
		terreno_principal_status.setDadosArvores(matriz_parede);
		terreno_principal_status.setDadosCasas(matriz_objeto_link);
		terreno_principal_status.setDadosPredios(matriz_predios);
		terreno_principal_status.setIdentificacao(this["principal_terreno_id"]);
		terreno_principal_status.setNome(this["principal_terreno_nome"]);
		terreno_principal_status.setIdChat(this["principal_terreno_chat"]);
		terreno_principal_status.mensagemLocalizacao = this["principal_mensagemLocalizacao"];
		terreno_principal_status.setPermissaoParaEditar(this["principal_permissaoEditar"]);
		terreno_principal_status.setPlaneta(planeta_status);
		
		/*
					PÁTIO
		*/
		matriz_parede 			= [];
		matriz_objeto_link		= [];   
		matriz_predios          = [];
		for(var n:Number = 0; n <this["patio_numero_objetos_no_terreno"]; n++){	
			objeto = new Array();
			objeto_movieclip = this['patio_objeto_movieclip' + n ];
			objeto_frame = this['patio_objeto_frame' + n];
			objeto_terreno_posicao_x = this['patio_objeto_terreno_posicao_x' + n];
			objeto_terreno_posicao_y = this['patio_objeto_terreno_posicao_y' + n];
			objeto_endereco = this['patio_objeto_link' + n];
			objeto_id = this['patio_objeto_id' + n];
			objeto.push(objeto_frame);
			objeto.push(objeto_terreno_posicao_x);
			objeto.push(objeto_terreno_posicao_y);
			objeto.push(objeto_endereco);
			objeto.push(objeto_id);
			
			switch(objeto_movieclip){
				case "parede": matriz_parede.push(objeto);  
					break;
				case "objeto_link": 
						tem_permissaoFuncionalidade = true;
						switch(c_casa.frameParaTipo(objeto_frame)){
							case c_casa.TIPO_BIBLIOTECA: tem_permissaoFuncionalidade = turma_status.permissao_biblioteca;
								break;
							case c_casa.TIPO_BLOG: tem_permissaoFuncionalidade = turma_status.permissao_blog;
								break;
							case c_casa.TIPO_FORUM: tem_permissaoFuncionalidade = turma_status.permissao_forum;
								break;
							case c_casa.TIPO_PORTFOLIO: tem_permissaoFuncionalidade = turma_status.permissao_portfolio;
								break;
							case c_casa.TIPO_APARENCIA: tem_permissaoFuncionalidade = true;
								break;
							case c_casa.TIPO_ARTE: tem_permissaoFuncionalidade = turma_status.permissao_planetaArte;
								break;
							case c_casa.TIPO_PERGUNTA: tem_permissaoFuncionalidade = turma_status.permissao_planetaPergunta;
								break;
							case c_casa.TIPO_AULA: tem_permissaoFuncionalidade = turma_status.permissao_aulas;
								break;
						}
						if(tem_permissaoFuncionalidade){
							matriz_objeto_link.push(objeto); 
						}
					break;
				case "predio": matriz_predios.push(objeto);
					break;
			}
		} 
		terreno_patio_status.setDadosArvores(matriz_parede);
		terreno_patio_status.setDadosCasas(matriz_objeto_link);
		terreno_patio_status.setDadosPredios(matriz_predios);
		terreno_patio_status.setIdentificacao(this["patio_terreno_id"]);
		terreno_patio_status.setNome(this["patio_terreno_nome"]);
		terreno_patio_status.setIdChat(this["patio_terreno_chat"]);
		terreno_patio_status.mensagemLocalizacao = this["patio_mensagemLocalizacao"];
		terreno_patio_status.setPermissaoParaEditar(this["patio_permissaoEditar"]);
		terreno_patio_status.setPlaneta(planeta_status);
		
		_root.barraProgresso2.definirPorcentagem(95+FATIA_CARREGAMENTO_INICIALIZACAO_1+FATIA_CARREGAMENTO_INICIALIZACAO_2);
		_root.barraProgresso2.atualizarMensagem("Aguardando sincronização...");
		
		setTimeout(proximaCena, 2000);
	}
}

function proximaCena():Void{
	_root.barraProgresso2.removeMovieClip();
	nextScene();
}



/* //Barra que muda de cor...
function definirPorcentagem(porcentagem_param):Void{
	var transformaCor:ColorTransform = new ColorTransform();
	transformaCor.rgb = 0x000000;
	
	if(porcentagem_param >= 0){
		if(porcentagem_param <= 100){
			porcentagemCarregada = porcentagem_param;
			barraCarregamento._xscale = porcentagem_param;
			
			if(porcentagem_param <= 50){ //Transição de 0xFF0000 (vermelho puro) para 0xFFFF00 (amarelo)
				transformaCor.rgb = 0xFF0000 + Math.round((porcentagem_param/50)*255)*0x000100;	  
			}
			else{						//Transição de 0xFFFF00 (amarelo) para 0x00FF00 (verde puro)
				transformaCor.rgb = Math.round((1 - (porcentagem_param-50)/50)*255)*0x010000 + 0x00FF00;                                                
			}            
			barraCarregamento.transform.colorTransform = transformaCor;
			textoPorcentagem.text = porcentagem_param+"%";										
		}
		else{
			definirPorcentagem(100);
		}
	}
}*/



