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
		
		var terreno_recebido:c_terreno_bd = new c_terreno_bd();
		var terrenos_recebidos:Array = new Array();
		//c_aviso_com_ok.mostrar("Teste: Numero de terrenos="+this.numeroTerrenos);
		
		for(var k:Number=0; k<this.numeroTerrenos; k++){
			
			terreno_recebido = new c_terreno_bd();
			
			matriz_parede 			= [];
			matriz_objeto_link		= [];   
			matriz_predios          = [];
			for(var n:Number = 0; n <this["numero_objetos_no_terreno"+k]; n++){	
				objeto = new Array();
				objeto_movieclip = this['objeto_movieclip' + k + ',' + n ];
				objeto_frame = this['objeto_frame' +k + ',' + n];
				objeto_terreno_posicao_x = this['objeto_terreno_posicao_x' + k + ',' + n];
				objeto_terreno_posicao_y = this['objeto_terreno_posicao_y' + k + ',' + n];
				objeto_endereco = this['objeto_link' + k + ',' + n];
				objeto_id = this['objeto_id' + k + ',' + n];
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
			terreno_recebido.setDadosArvores(matriz_parede);
			terreno_recebido.setDadosCasas(matriz_objeto_link);
			terreno_recebido.setDadosPredios(matriz_predios);
			
			terreno_recebido.setIdentificacao(this["terreno_id"+k]);
			terreno_recebido.setNome(this["terreno_nome"+k]);
			terreno_recebido.setIdChat(this["terreno_chat"+k]);
			terreno_recebido.mensagemLocalizacao = this["mensagemLocalizacao"];
			terreno_recebido.setPermissaoParaEditar(this["permissaoEditar"+k]);
			terreno_recebido.setPlaneta(planeta_status);
			
			terrenos_recebidos.push(terreno_recebido);
			if(this.indice_planeta_terreno_personagem == k){
				terreno_principal_status = terreno_recebido;
			}
		}
		
		for(var i:Number=0; i<terrenos_recebidos.length; i++){
			if(0<i){
				terrenos_recebidos[i].setTerrenoOeste(terrenos_recebidos[i-1]);
			} else {
				terrenos_recebidos[i].setTerrenoOeste(terrenos_recebidos[terrenos_recebidos.length-1]);
			}
			if(i<terrenos_recebidos.length-1){
				terrenos_recebidos[i].setTerrenoLeste(terrenos_recebidos[i+1]);
			} else {
				terrenos_recebidos[i].setTerrenoLeste(terrenos_recebidos[0]);
			}
		}
		
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



