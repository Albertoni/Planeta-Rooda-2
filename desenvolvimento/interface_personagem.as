/*---------------------------------------------------
*					Arquivo principal	
*	
*	É constituído somente de inicializações. 
*	Eventos são tratados dentro de seus objetos correspondentes.
*	Cria-se e inicializa-se o comunicador, o menu, o terreno, etc.
---------------------------------------------------*/

/*---------------------------------------------------
*	APIs - Deve ser chamado antes dos arquivos, pois podem vir a usá-las - Guto - 02.06.10
---------------------------------------------------*/
import flash.geom.ColorTransform;
import flash.geom.Matrix;
import flash.display.BitmapData;
import flash.geom.*;
import flash.external.*;
import mx.utils.Delegate;

/*---------------------------------------------------
*	Arquivos com funções
---------------------------------------------------*/
#include "funcAtualizaObj.as"

/*---------------------------------------------------
*	Declaração de variáveis 
* 	Tipos de variáveis separados por linha (Boolean, Number, Array, String, Object)
---------------------------------------------------*/
var bloqMov:Boolean = false;				//Auxiliar para o botão "Start" - eD - 03/11/08
var viuAvisoEdicaoTerreno:Boolean = false;		//Determina se o usuário viu o aviso de edição do terreno. - Diogo - 19.08.11

switch(_root.animacao){
	case c_barra_progresso.TIPO_FOGUETE:
		attachMovie(c_barra_progresso_foguete.LINK_BIBLIOTECA, "barraProgresso3", 5000, {_x:0, y:0});
		_root.barraProgresso3.inicializar("Inicializando o terreno.");
		break;
	case c_barra_progresso.TIPO_PREDIO:
		attachMovie(c_barra_progresso_predio.LINK_BIBLIOTECA, "barraProgresso3", 5000, {_x:0, y:0});
		_root.barraProgresso3.inicializar(_root.direcaoAnimacao, "Inicializando o terreno.");
		break;
	default:
		attachMovie(c_barra_progresso_foguete.LINK_BIBLIOTECA, "barraProgresso3", 5000, {_x:0, y:0});
		_root.barraProgresso3.inicializar("Inicializando o terreno.");
		break;
}
_root.barraProgresso3.definirPorcentagem(95+FATIA_CARREGAMENTO_INICIALIZACAO_1+FATIA_CARREGAMENTO_INICIALIZACAO_2);

function usuarioFalou(evento_mensagem:Object):Void{
	_root.planeta.getTerrenoEmQuePersonagemEstah().mp.falar(evento_mensagem.mensagem);
}
function toggleVisibilidadeBaloesPersonagens():Void{
	var personagensOnline:Array = _root.planeta.getTerrenoEmQuePersonagemEstah().getPersonagensOnline();
	_root.planeta.getTerrenoEmQuePersonagemEstah().mp.toggleVisibilidadeBalao();
	for(var indice:Number=0; indice < personagensOnline.length; indice++){
		personagensOnline[indice].toggleVisibilidadeBalao();
	}
}

var menuEdicao:c_menuEdicaoTerreno;
menuEdicao = _root.menuEdicaoMC;
if(planeta_status.getAparencia() != ''){
	menuEdicao.inicializar(planeta_status.getAparencia());	
} else {
	menuEdicao.inicializar(c_terreno_bd.TIPO_QUARTO);
}
menuEdicao._visible = false;

attachMovie(c_planetaMC.getLinkBiblioteca(planeta_status.getAparencia()), "planeta", 1);
this['planeta'].inicializar(personagem_status, terreno_principal_status, terreno_patio_status);

this['comunicador'].addEventListener("mensagemEnviada", usuarioFalou);
this['comunicador'].addEventListener("toggleVisibilidadeBaloes", toggleVisibilidadeBaloesPersonagens);
this['comunicador'].inicializar(this['planeta'].getTerrenoEmQuePersonagemEstah().getImagemBancoDeDados(),
								this['planeta'].getTerrenoEmQuePersonagemEstah().getImagemBancoDeDados().getIdChat(),
								personagem_status.getChatId());

if(!turma_status.permissao_batePapo){
	this['comunicador']._visible = false;
} else {
	if(planeta_status.tipo == c_planeta.TURMA){
		if(usuario_status.getPermissao() == c_conta.getNivelAluno()){
			this['comunicador'].setPermissaoChatTerreno(turma_status.habilitado_chatTerrenoParaAlunos);
			this['comunicador'].setPermissaoChatTurma(turma_status.habilitado_chatTurmaParaAlunos);
			this['comunicador'].setPermissaoChatAmigo(turma_status.habilitado_chatAmigoParaAlunos);
			this['comunicador'].setPermissaoChatPrivado(turma_status.habilitado_chatPrivadoParaAlunos);
		} else if(usuario_status.getPermissao() == c_conta.getNivelMonitor()){
			this['comunicador'].setPermissaoChatTerreno(turma_status.habilitado_chatTerrenoParaMonitores);
			this['comunicador'].setPermissaoChatTurma(turma_status.habilitado_chatTurmaParaMonitores);
			this['comunicador'].setPermissaoChatAmigo(turma_status.habilitado_chatAmigoParaMonitores);
			this['comunicador'].setPermissaoChatPrivado(turma_status.habilitado_chatPrivadoParaMonitores);
		}
	} else {
		this['comunicador'].desabilitarChatTurmaDoPlaneta();
	}
}



if(usuario_status.nome_escola != undefined){
	nomeEscola.nome.text = usuario_status.nome_escola;
} else {
	nomeEscola._visible = false;
}

var menu:c_menu;
menu = _root.menuMC;
menu.inicializar();

switch(usuario_status.getPermissao()){
	case c_conta.getNivelVisitante(): menu.configurarFuncionalidades( menu.EDITAR_USUARIO | menu.TROCAR_DE_PLANETA ); 
	break;
	
	case c_conta.getNivelAluno(): menu.configurarFuncionalidades( menu.EDITAR_USUARIO | menu.TROCAR_DE_PLANETA );
	break;
	
	case c_conta.getNivelMonitor(): menu.configurarFuncionalidades( menu.EDITAR_USUARIO | menu.TROCAR_DE_PLANETA );
	break;
	
	case c_conta.getNivelProfessor(): menu.configurarFuncionalidades( menu.EDITAR_TURMA | menu.EDITAR_CONTA | menu.EDITAR_PLANETA 
							                                     | menu.EDITAR_USUARIO | menu.TROCAR_DE_PLANETA );
	break;	

	case c_conta.getNivelCoordenador(): menu.configurarFuncionalidades( menu.EDITAR_TURMA | menu.EDITAR_CONTA | menu.EDITAR_PLANETA 
							                                       | menu.EDITAR_USUARIO | menu.CRIAR_TURMA | menu.TROCAR_DE_PLANETA
																   | menu.CRIAR_ESCOLA );
	break;
	
	case c_conta.getNivelAdministrador(): menu.configurarFuncionalidades( menu.TODAS );
	break;
	
	default: menu.configurarFuncionalidades( menu.NENHUMA );
	break;
}
menu._visible = true;

/*---------------------------------------------------
*	Chamadas de funções iniciais 
---------------------------------------------------*/
setInterval(_root, "solicitar_bd_dados_personagens_online", 1500);

inicializaObj();										//Inicializa objetos no terreno - Guto - 23.12.8
carregaObj();											//Posiciona objetos no terreno - Guto - 10.07.09

_root.barraProgresso3.removeMovieClip();

/*
* Sai do planeta.
*/
function sair(){
	getURL("../index.php?action=log0001","_self");
}

ChamaPopupSair.onRollOver = function(){
	_root.ChamaPopupSair.gotoAndStop(2);
}
ChamaPopupSair.onRollOut = function(){
	_root.ChamaPopupSair.gotoAndStop(1);
}
ChamaPopupSair.onPress = function(){
	var objetoPai:MovieClip = _root;
	var mensagem:String = "Deseja Sair?";
	var posicao:Point = undefined;
	var opcaoEsquerda:String = "Sair";
	var opcaoDireita:String = "Voltar";
	var funcaoEsquerda:Function = _root.sair;
	var funcaoDireita:Function = function(){ c_aviso_dicotomico.destruirDe(_root); };
	var escopoFuncoes:Object = _root;
		
	c_aviso_dicotomico.criarPara(objetoPai, mensagem, posicao,
								 opcaoEsquerda, opcaoDireita, 
								 funcaoEsquerda, funcaoDireita, escopoFuncoes);
}

function voltarMenuInicial(){
	getURL("../tela_inicial_geral.php","_self");
}

ChamaPopupVoltarMenuInicial.onRollOver = function(){
	_root.ChamaPopupVoltarMenuInicial.gotoAndStop(2);
}
ChamaPopupVoltarMenuInicial.onRollOut = function(){
	_root.ChamaPopupVoltarMenuInicial.gotoAndStop(1);
}
ChamaPopupVoltarMenuInicial.onPress = function(){
	var objetoPai:MovieClip = _root;
	var mensagem:String = "Deseja Voltar ao Menu Inicial?  ";
	var posicao:Point = undefined;
	var opcaoEsquerda:String = "Voltar";
	var opcaoDireita:String = "Cancelar";
	var funcaoEsquerda:Function = _root.voltarMenuInicial;
	var funcaoDireita:Function = function(){ c_aviso_dicotomico.destruirDe(_root); };
	var escopoFuncoes:Object = _root;
		
	c_aviso_dicotomico.criarPara(objetoPai, mensagem, posicao,
								 opcaoEsquerda, opcaoDireita, 
								 funcaoEsquerda, funcaoDireita, escopoFuncoes);
}

/*---------------------------------------------------
*	Ações relacionadas ao evento de troca de frame. Similar a função main() do projeto - Guto - 30.04.10
---------------------------------------------------*/
this.onEnterFrame = function() {
	menu.atualizacoesEnterFrame();
}

/*---------------------------------------------------
*	Deve-se verificar aqui TODOS OS BOTÕES, pois o avatar só pode se movimentar quando o clique do mouse não for realizado
*	em cima de um botão ou objeto selecionável. - Guto - 21.01.10
*	Qndo se clica no terreno o  botaoMousePress vira true. Enquanto ela for true o mp segue o mouse. Só se torna false qndo o botao do mouse é solto - Roger - 21.08.09										
---------------------------------------------------*/
function seleciounouBotaoForaTerreno():Boolean{
	if(!(velCtrl1.hitTest(_xmouse, _ymouse)		
		or ChamaPopupSair.hitTest(_xmouse, _ymouse)
		or TelaSaida.hitTest(_xmouse, _ymouse)
		or velCtrl1.move.hitTest(_xmouse, _ymouse)
		or imprime.hitTest(_xmouse, _ymouse)
		or (!menuMC.btMenu.hitTest and menuMC._currentframe == 1)
		or (menuMc.hitTest(_xmouse, _ymouse) and 1 < menuMC._currentframe)
		or mapa.hitTest(_xmouse, _ymouse)
		or btAdmAlerta.hitTest(_xmouse, _ymouse)	
		or (comunicador.hitTest(_xmouse, _ymouse) and comunicador.header_._currentframe != 1)
		or comunicador.fala.hitTest(_xmouse, _ymouse)
		or comunicador.barra_rolagem.hitTest(_xmouse, _ymouse)
		or comunicador.redimensionador.hitTest(_xmouse, _ymouse)
		or btSalvarEdicao.hitTest(_xmouse, _ymouse)
		or btCancelarEditarMundo.hitTest(_xmouse, _ymouse)
		or btEditarMundo.hitTest(_xmouse, _ymouse)
		or (btInstrucoesEdicao.hitTest(_xmouse, _ymouse) and btInstrucoesEdicao._visible)
		or telaAguardarBD.hitTest(_xmouse, _ymouse)
	) and !comunicador.header_.botaoPrincipal.hitTest(_xmouse, _ymouse) 
	) {
		return false;
	} else {
		return true;
	}
}

