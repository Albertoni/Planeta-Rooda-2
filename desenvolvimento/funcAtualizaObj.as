/*---------------------------------------------------
*	Inicializa objetos do cenário. Deve ser utilizada na inicialização do terreno - Guto - 23.12.08
---------------------------------------------------*/	
function inicializaObj():Void {
	usuario_status.definirPersonagem(this['planeta'].getTerrenoPrincipal().getPersonagem());
	c_banco_de_dados.salvarImagemAvatar(usuario_status.personagem.capturarImagemCabecaAvatar(), usuario_status.personagem_id);
	
	//inicializando Depht dos elementos de layout. Deve ser feito por último, nessa função, para garantir que estes objetos fique no ponto mais alto.  - eD - 03/11/08
	inicializaDepthElementosLayout();
	
	ponteiro.inicializar();
	ponteiro.swapDepths(getNextHighestDepth());	// faz com que o ponteiro fique acima de todos os outros objetos - Roger - 17/07/2009
	
	velCtrl1.inicializar();
}

/*---------------------------------------------------
*	Funções de carregar e descarregar os objetos da tela, de acordo com o movimento do mp - Guto - 10.07.09
---------------------------------------------------*/
function carregaObj():Void {
	_root.barraProgresso3.atualizarMensagem("Procurando posição para o personagem...");
}

/*---------------------------------------------------
*	Funções de carregar, controlar a movimentação dos ops e enviar a posição do mp
---------------------------------------------------*/
function solicitar_bd_dados_personagens_online():Void {
	var envia:LoadVars = new LoadVars();
	var recebe:LoadVars = new LoadVars();
	
	envia.rota = this['planeta'].getTerrenoEmQuePersonagemEstah().mp.getRota();
	envia.debug = this['planeta'].getTerrenoEmQuePersonagemEstah().mp.rotaRealizada.length;
	this['planeta'].getTerrenoEmQuePersonagemEstah().mp.limparRegistroRotaRealizada();
	envia.posicao_x = this['planeta'].getTerrenoEmQuePersonagemEstah().mp._x;
	envia.posicao_y = this['planeta'].getTerrenoEmQuePersonagemEstah().mp._y;
	envia.terreno_id = this['planeta'].getTerrenoEmQuePersonagemEstah().getImagemBancoDeDados().getIdentificacao();
	envia.personagem_id = personagem_status.getIdentificacaoBancoDeDados();
	envia.personagem_velocidade = personagem_status.getIndicadorVelocidade();
	
	recebe.onLoad = carregar_dados_personagens_online;			//A definição do evento onLoad deve vir antes da chamada sendAndLoad, visto que o evento onLoad deve estar definido quando a instância recebe é atribuida aos dados recebidos do php - Guto - 08.07.10
	envia.sendAndLoad("atualizar_dados_ops.php", recebe, "POST");
}

function apagaAvisoEdicao():Void{
	btAdmAlerta15._y = -705;
	btAdmAlerta15._visible = false;
}
/*---------------------------------------------------
	Carrega e manipula dados enviados do banco (função anterior) para o flash
---------------------------------------------------*/
function carregar_dados_personagens_online(success):Void {
	if(success) {
		var personagemRecebido:c_personagem_bd;
		var personagensRecebidos:Array = new Array();
		
		//Caso o terreno tenha sido editado, mostrará para todos os ops um aviso dizendo "Este terreno foi editado." - Diogo
		//chamaAvisoTerrenoEditado(this.statusTerreno, this.idAutor);
		for (var n:Number = 0; n < this.nLoop - 1; n++) { //verifica a existencia de op no flash
			personagemRecebido = new c_personagem_bd(this['id' + n]);
			personagemRecebido.definirPosicaoAtual(new Point(this['personagem_posicao_x' + n], this['personagem_posicao_y' + n]));
			personagemRecebido.definirNome(this['personagem_nome' + n]);
			personagemRecebido.definirCorNome(this['personagem_cor_texto' + n]);
			personagemRecebido.definirVelocidade(this['personagem_velocidade' + n]);
			personagemRecebido.definirCabelo(this['personagem_cabelos' + n]);
			personagemRecebido.definirOlhos(this['personagem_olhos' + n]);
			personagemRecebido.definirCorPele(this['personagem_cor_pele' + n]);
			personagemRecebido.definirCorLuvasBotas(this['personagem_cor_luvas_botas' + n]);
			personagemRecebido.definirCorCinto(this['personagem_cor_cinto' + n]);
			personagemRecebido.definirRota(this['personagem_rota' + n]);
			
			//_root.outroTerreno.mp.debug.text += this['personagem_rota' + n]+"\n";
			//_root.outroTerreno.mp.debug2.text += personagemRecebido.getRota()+"\n";
			
			for(var falaAtual:Number = 0; falaAtual < this['personagem_total_falas_recentes' + n]; falaAtual++){
				personagemRecebido.adicionarFala( this['personagem_fala_texto' + n + ',' + falaAtual] );
			}
			personagensRecebidos.push(personagemRecebido);
		}
		_root.planeta.getTerrenoEmQuePersonagemEstah().atualizarPersonagens(personagensRecebidos);
	}
}

function inicializaDepthElementosLayout():Void{
	clima.swapDepths(getNextHighestDepth());
	bloqlayTerra.swapDepths(getNextHighestDepth());
	chat_box_main.swapDepths(getNextHighestDepth());
	velCtrl1.swapDepths(getNextHighestDepth());
	ChamaPopupSair.swapDepths(getNextHighestDepth());
	ChamaPopupVoltarMenuInicial.swapDepths(getNextHighestDepth());
	btSalvarEdicao.swapDepths(getNextHighestDepth());
	btCancelarEditarMundo.swapDepths(getNextHighestDepth());
	btEditarMundo.swapDepths(getNextHighestDepth());
	btInstrucoesEdicao.swapDepths(getNextHighestDepth());
	telaAguardarBD.swapDepths(getNextHighestDepth());
	btAdmAlerta15.swapDepths(getNextHighestDepth());
	btStart.swapDepths(getNextHighestDepth());
	imprime.swapDepths(getNextHighestDepth());
	btAba1.swapDepths(getNextHighestDepth());
	portalCentro.swapDepths(getNextHighestDepth());
	mapaInfo.swapDepths(getNextHighestDepth());
	contatos.swapDepths(getNextHighestDepth());
	turmas.swapDepths(getNextHighestDepth());
	barraSelecao.swapDepths(getNextHighestDepth());
	TelaSaida.swapDepths(getNextHighestDepth());
	comunicador.swapDepths(getNextHighestDepth());
	menuMC.swapDepths(getNextHighestDepth());
	menuEdicaoMC.swapDepths(getNextHighestDepth());
	gMask.swapDepths(getNextHighestDepth());			//A máscara e o botão de início precisam estar na frente de tudo, exceto opções que a chamam - Guto - 03/02/10	
	barraSelecao.swapDepths(getNextHighestDepth());
	alerta.swapDepths(getNextHighestDepth());
	TelaSaida.swapDepths(getNextHighestDepth());
	btStart.swapDepths(getNextHighestDepth());
	camposCriarUsuario.swapDepths(getNextHighestDepth());
	camposCriarTurma.swapDepths(getNextHighestDepth());
	camposTrocaSist.swapDepths(getNextHighestDepth());
	camposEditarUsuario.swapDepths(getNextHighestDepth());
	camposEditarTurmas.swapDepths(getNextHighestDepth());
	camposEditarPlanetas.swapDepths(getNextHighestDepth());
	camposEditarContas.swapDepths(getNextHighestDepth());
	btEditarContas.swapDepths(getNextHighestDepth());
	btEditarPlanetas.swapDepths(getNextHighestDepth());
	btEditarTurmas.swapDepths(getNextHighestDepth());
	btCriarTurma.swapDepths(getNextHighestDepth());
	btTrocaSist.swapDepths(getNextHighestDepth());
	btEditarUsuario.swapDepths(getNextHighestDepth());
	botão_defaultmc.swapDepths(getNextHighestDepth());
	debugMenu.swapDepths(getNextHighestDepth());
	dropDown.swapDepths(getNextHighestDepth());
	btAdmAlerta.swapDepths(getNextHighestDepth());
	listaUsuarios.swapDepths(getNextHighestDepth());
	mascara.swapDepths(getNextHighestDepth());
	localizacao.swapDepths(getNextHighestDepth());
	barraProgresso3.swapDepths(getNextHighestDepth());
	nomeEscola.swapDepths(getNextHighestDepth());
}