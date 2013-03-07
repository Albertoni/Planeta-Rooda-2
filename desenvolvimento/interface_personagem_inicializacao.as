//===========================================================
//sistema de inicialização - primeira parte
//===========================================================
import flash.geom.Point;

//===========================================================
//Classes com as propriedades dos objetos - eD - 12/12/08
//===========================================================
import c_turma;
import c_conta;
import c_usuario;
import c_personagem_bd;
import c_terreno_bd;
import c_caixa_texto;
import c_obj_selecionado;
import c_casa;
import c_arvore;
import c_menu;
import c_planeta;

var FATIA_CARREGAMENTO_INICIALIZACAO_1:Number = 2;
var FATIA_CARREGAMENTO_INICIALIZACAO_2:Number = 2;
var FATIA_CARREGAMENTO_INICIALIZACAO_3:Number = 1;

var personagem_status:c_personagem_bd;
var conta_status:c_conta = new c_conta();
var turma_status:c_turma = new c_turma();
var usuario_status:c_usuario = new c_usuario();
var terreno_principal_status:c_terreno_bd = new c_terreno_bd();
var terreno_patio_status:c_terreno_bd = new c_terreno_bd();
var planeta_status:c_planeta = new c_planeta();

btStart._visible = false;
animacao_usuario._visible = false;

//===========================================================
//					FUNÇÕES AUXILIARES
//===========================================================
function global_trim(base_param:String):String{
	var MININO_CODIGO_ASCII_VALIDO:Number=33;
	for(var i = 0; base_param.charCodeAt(i) < MININO_CODIGO_ASCII_VALIDO; i++);
    for(var j = base_param.length-1; base_param.charCodeAt(j) < MININO_CODIGO_ASCII_VALIDO; j--);
    return base_param.substring(i, j+1);
}

//===========================================================

var envia:LoadVars = new LoadVars();
var recebe:LoadVars = new LoadVars();

recebe.onLoad = recebe_flash_var_principais;

envia.action = "1";
envia.sendAndLoad("interface_bd_personagem.php", recebe, "POST");	

switch(_root.animacao){
	case c_barra_progresso.TIPO_FOGUETE:
		attachMovie(c_barra_progresso_foguete.LINK_BIBLIOTECA, "barraProgresso1", getNextHighestDepth(), {_x:0, y:0});
		_root.barraProgresso1.inicializar("Aguardando dados do usuário.");
		break;
	case c_barra_progresso.TIPO_PREDIO:
		attachMovie(c_barra_progresso_predio.LINK_BIBLIOTECA, "barraProgresso1", getNextHighestDepth(), {_x:0, y:0});
		_root.barraProgresso1.inicializar(_root.direcaoAnimacao, "Aguardando dados do usuário.");
		break;
	default:
		attachMovie(c_barra_progresso_foguete.LINK_BIBLIOTECA, "barraProgresso1", getNextHighestDepth(), {_x:0, y:0});
		_root.barraProgresso1.inicializar("Aguardando dados do usuário.");
		break;
}
_root.barraProgresso1.definirPorcentagem(95);

function recebe_flash_var_principais(success) {
	_root.barraProgresso1.atualizarMensagem("Carregando dados do usuário recebidos.");
	if(success) {
			turma_status.identificacao							= recebe.turma_id;
			turma_status.permissao_batePapo 					= (recebe.permissao_batePapo == 'true'? true : false);
			turma_status.permissao_biblioteca 					= (recebe.permissao_biblioteca == 'true'? true : false);
			turma_status.permissao_blog 						= (recebe.permissao_blog == 'true'? true : false);
			turma_status.permissao_portfolio 					= (recebe.permissao_portfolio == 'true'? true : false);
			turma_status.permissao_forum 						= (recebe.permissao_forum == 'true'? true : false);
			turma_status.permissao_planetaArte 					= (recebe.permissao_planetaArte == 'true'? true : false);
			turma_status.permissao_planetaPergunta 				= (recebe.permissao_planetaPergunta == 'true'? true : false);
			turma_status.permissao_aulas 						= (recebe.permissao_aulas == 'true'? true : false);
			turma_status.habilitado_chatTerrenoParaAlunos		= (recebe.habilitado_chatTerrenoParaAlunos == 'true'? true : false);
			turma_status.habilitado_chatTerrenoParaMonitores	= (recebe.habilitado_chatTerrenoParaMonitores == 'true'? true : false);
			turma_status.habilitado_chatTurmaParaAlunos			= (recebe.habilitado_chatTurmaParaAlunos == 'true'? true : false);
			turma_status.habilitado_chatTurmaParaMonitores		= (recebe.habilitado_chatTurmaParaMonitores == 'true'? true : false);
			turma_status.habilitado_chatAmigoParaAlunos			= (recebe.habilitado_chatAmigoParaAlunos == 'true'? true : false);
			turma_status.habilitado_chatAmigoParaMonitores		= (recebe.habilitado_chatAmigoParaMonitores == 'true'? true : false);
			turma_status.habilitado_chatPrivadoParaAlunos		= (recebe.habilitado_chatPrivadoParaAlunos == 'true'? true : false);
			turma_status.habilitado_chatPrivadoParaMonitores	= (recebe.habilitado_chatPrivadoParaMonitores == 'true'? true : false);
			
			planeta_status.tipo = this.planeta_tipo;
			
			if(this.ehQuarto != 'true' and this.ehQuarto != '1'){
				planeta_status.setAparencia(this.planeta_aparencia);
			} else {
				planeta_status.setAparencia(c_terreno_bd.TIPO_QUARTO);
			}
			
			personagem_status = new c_personagem_bd(this.personagem_id);
			personagem_status.definirNome(this.personagem_nome);
			personagem_status.definirCorNome(this.personagem_cor_texto);
	
			personagem_status.definirPosicaoAtual(new Point(Number(this.personagem_posicao_x), 
														  	Number(this.personagem_posicao_y)));
															
			personagem_status.definirCabelo(this.personagem_cabelos);
			personagem_status.definirOlhos(this.personagem_olhos);
			personagem_status.definirCorPele(this.personagem_cor_pele);
			personagem_status.definirCorLuvasBotas(this.personagem_cor_luvas_botas);
			personagem_status.definirCorCinto(this.personagem_cor_cinto);
			personagem_status.definirVelocidade(this.personagem_velocidade);
			personagem_status.definirChatId(this.personagem_chat_id);
		
			usuario_status.nome_escola = this.nomeEscola;
			usuario_status.ultimo_terreno_id				= this.ultimo_terreno_id;
			usuario_status.quarto_id						= this.quarto_id;
			usuario_status.identificacao					= this.usuario_id;
			usuario_status.ultima_atualizacao				= this.ultima_atualizacao;
			usuario_status.usuario_nivel					= this.usuario_nivel;
			usuario_status.usuario_grupo_base				= this.usuario_grupo_base;
			usuario_status.personagem_linha_chat        	= this.personagem_linha_chat;
			usuario_status.personagem_fala              	= this.personagem_fala;
			usuario_status.private_chat                 	= this.private_chat;
			usuario_status.lista_contatos               	= this.personagem_contatos;
			usuario_status.personagem_animacao              = (this.personagem_animacao).substr(1);//Retira as aspas duplas do início da palavra.
			usuario_status.personagem_animacao			    = usuario_status.personagem_animacao.substr(0,usuario_status.personagem_animacao.indexOf("\""));//Retira as aspas duplas do fim da palavra.
			
			var imagemPlanetaQuarto = new c_planeta();
			var imagemTerrenoQuarto = new c_terreno_bd();
			imagemPlanetaQuarto.setAparencia(c_terreno_bd.TIPO_QUARTO);
			imagemTerrenoQuarto.setIdentificacao(usuario_status.quarto_id);
			imagemTerrenoQuarto.setPlaneta(imagemPlanetaQuarto);
			imagemTerrenoQuarto.mensagemLocalizacao = 'Quarto';
			imagemTerrenoQuarto.setNome(personagem_status.getNome());
			usuario_status.setImagemTerrenoQuarto(imagemTerrenoQuarto);
			
			_root.barraProgresso1.definirPorcentagem(FATIA_CARREGAMENTO_INICIALIZACAO_1);
			_root.barraProgresso1.removeMovieClip();
			
			c_objeto_acesso.inicializar();
			nextScene();
	} else {
		
		_root.barraProgresso1.atualizarMensagem("Houve um erro ao tentar carregar os dados de usuário. Erro:"+this.toString());
	}//if(success)
}//function recebe_flash_fala_mp()

