/*---------------------------------------------------
*	Inicializa objetos do cenário. Deve ser utilizada na inicialização do terreno - Guto - 23.12.08
---------------------------------------------------*/	
function inicializaObj():Void {
	/*---------------------------------------------------
	*	Inicialização do Stage - Guto - 25.05.10
	---------------------------------------------------*/
	//Stage.scaleMode = "showAll";	
	
	/*---------------------------------------------------
	*	Inicializacao dos textfields necessários
	---------------------------------------------------*/
	abaAtiva = "chat_box_main.chat_geral";
	chat_box_main.aba_todos._visible = false;
	chat_box_main.chat_geral._visible = false;
	chat_box_main.chat_geral.html = true;
	chat_box_main.chat_geral.htmlText = "";
	chat_box_main.fala.selectable=true;
	chat_box_main.chat_geral.selectable=true;	
	chat_box_main.chat_geral.htmlText = "chat_geral";
	chat_box_main.aba_todos.htmlText = "aba_todos";
	camposCriarUsuario.btSelNivel.conteudo.selectable = false;
	camposCriarUsuario.btSelSistema.conteudo.selectable = false;
	camposCriarUsuario.btSelNivel.conteudo.text = "";
	camposCriarUsuario.btSelSistema.conteudo.text = "";
	camposCriarUsuario.btSelNivel.sistemaAbre._visible = false;
	camposCriarUsuario.btSelNivel.sistemaFecha._visible = false;
	camposCriarUsuario.btSelSistema.sistemaAbre._visible = false;
	camposCriarUsuario.btSelSistema.sistemaFecha._visible = false;
	camposEditarUsuario.procuraUsuario.conteudo.text = "Digite o apelido";
	camposEditarUsuario.procuraUsuario.sistemaAbre._visible = false;
	camposEditarUsuario.procuraUsuario.sistemaFecha._visible = false;
	for (var i = 0; i < 7; i++){												//Inicializa botões de escolha de niveis e sistemas na inserção de usuários. - Guto - 20.05.10
		eval("dropDown.btSelAdm" + i).conteudo.selectable = false;		
		eval("dropDown.btSelAdm" + i).conteudo.text = "";
		eval("dropDown.btSelAdm" + i).sistemaAbre._visible = false;
		eval("dropDown.btSelAdm" + i).sistemaFecha._visible = false;
	}	
	for (var i = 0; i < 17; i++){												//Inicializa botões de escolha de sistemas da troca de sistema. - Guto - 20.05.10
		eval("camposTrocaSist.btSelSist" + i).conteudo.selectable = false;
		eval("camposTrocaSist.btSelSist" + i).conteudo.text = "";
		eval("camposTrocaSist.btSelSist" + i).sistemaAbre._visible = false;
		eval("camposTrocaSist.btSelSist" + i).sistemaFecha._visible = false;
	}
	for (var i = 0; i < 17; i++){												//Inicializa botões de escolha do usuário na edição de usuário. - Guto - 05.08.10
		eval("listaUsuarios.btSelUsu" + i).conteudo.selectable = false;
		eval("listaUsuarios.btSelUsu" + i).conteudo.text = "";
		eval("listaUsuarios.btSelUsu" + i).sistemaAbre._visible = false;
		eval("listaUsuarios.btSelUsu" + i).sistemaFecha._visible = false;
	}
	mp.nome.selectable = false;    									//impede o texto do nome de ser selecionável - Roger - 09.04.10 
	mp.nome.text = usuario_status.personagem_nome; 					//atribue o nome do personagem
	mp.nome.textColor = '0x' + usuario_status.personagem_cor_texto; //atribui a cor do nome do personagem
	
	usuario_status.rotaMp = ""; //[];							//Inicializa vetor de rota
	
	

	/*---------------------------------------------------
	*	Atribui características dos objetos
	---------------------------------------------------*/
	mp.personagem.mpBaixo.cabeloOlhoFrente.gotoAndStop(usuario_status.personagem_avatar_1);		//atribui o modelo do personagem
	terreno.gotoAndStop(terreno_status.terreno_solo);				//atribui o modelo do terreno
	clima.gotoAndStop(terreno_status.terreno_clima);				//atribui o modelo do clima
	mp.fala.selectable = false;   									//impede o texto da fala na cabeça do mp de ser selecionável
	mp.personagem.mpBaixo.espada._alpha = 0;
	mp.personagem.mpCima.espada._alpha = 0;
	mp.personagem.mpEsq.espada._alpha = 0;
	mp.personagem.mpDir.espada._alpha = 0;
	posCamposOrgY = camposCriarUsuario._y;							//Variáveis para o menu criadas pelo giovani - Giovani - xx.xx.xx
	inilocalY = localizacao._y;
	barraSelecaoY = barraSelecao._y;
	dropDownY = dropDown._y;
	mapa.mapaTxt.selectable = false;								//Mapa do terreno - Giovani - 05.05.10
	dropDown.barraRolagem._visible = false;
	limiteSuperiorScrollDropdown = dropDown.barraRolagem.barra_scroll._y;
	limiteInferiorScrollDropdown = dropDown.barraRolagem._height - dropDown.barraRolagem.barra_scroll._height -  dropDown.barraRolagem.barra_scroll._y;
	limiteSuperiorScrollTrocaSistemas = camposTrocaSist.rolagem_sist.barra_scroll._y;
	limiteInferiorScrollTrocaSistemas = camposTrocaSist.rolagem_sist._height - camposTrocaSist.rolagem_sist.barra_scroll._height -  camposTrocaSist.rolagem_sist.barra_scroll._y;
	
	camposCriarUsuario.masc.gotoAndStop(2);							//Objetos para a seleção do sexo na inserção de usuários - Guto - 20.05.10
	camposCriarUsuario.fem.gotoAndStop(1);
	
	camposTrocaSist.rolagem_sist._visible = false;					//Tirando a visibilidade das barras de rolagem das caixas de seleção - Guto - 05.08.10
	listaUsuarios.rolagemUsuarios._visible = false;
	
	depthBaseObj = 10000;
	ajustX = mp._x + mp.sombra._x - usuario_status.personagem_posicao_x;
	ajustY = mp._y + mp.sombra._y - usuario_status.personagem_posicao_y;

	posIniLimX = mp._x + mp.sombra._x;
	posIniLimY = mp._y + mp.sombra._y;
	
	//Cria as matrizes com as referências do objeto da sombra do mp e do op - Guto - 13.02.09
	nuclLimMp = refBorda(objNucleo(mp._x + mp.sombra._x, mp._y + mp.sombra._y, mp.sombra));
	nuclLimOp = refBorda(objNucleo(op._x + op.sombra._x, op._y + op.sombra._y, op.sombra));
	
	//Cria matriz com o nucleo do objeto da sombra do op - Guto - 21.07.09
	nuclOp = objNucleo(op._x + op.sombra._x, op._y + op.sombra._y, op.sombra);
	
	//Cria matrizes que registram as tiles que a sombra do mp está ocupando no momento e que ocupava no momento anterior - Guto - 16.06.09
	limMpTiles = verifTilesObj(usuario_status.personagem_posicao_x + margemTilesX*usX, usuario_status.personagem_posicao_y + margemTilesY*usY, usX, usY, mp.sombra);
	limMpTilesBuff = limMpTiles;
	
	//Cria matriz com as tiles do cenário, com margem de 2 tiles em X e 6 tiles em Y - Guto - 16.06.09
	cenaTiles = criaMtrCena((terrDimX/usX) + 2*margemTilesX, (terrDimY/usY) + 2*margemTilesY);

	//movendo terreno e limite_terreno
	terreno._x				+= ajustX;
	terreno._y				+= ajustY;
		
	//paredes
	n_matriz_parede = matriz_parede.length;
	nuclParede = objNucleo(parede._x + parede.sombra._x, parede._y + parede.sombra._y,  parede.sombra);		//Essa matriz será utilizada para hitTest dentro das tiles - Guto - 16.06.09
	for(var f:Number = 0; f <n_matriz_parede; f++){
		objReg.push([Number(matriz_parede[f][0]), Number(matriz_parede[f][1]), Number(matriz_parede[f][2]), 0x500 + f]);
		insereObjTile(Number(matriz_parede[f][1]) + margemTilesX*usX, Number(matriz_parede[f][2]) + margemTilesY*usY, usX, usY, parede.sombra, cenaTiles, 0x500 + f);	
	}
	
	//np_a
	n_matriz_np_a = matriz_np_a.length;
	nuclNpA = objNucleo(np_a._x, np_a._y,  np_a);		//Essa matriz será utilizada para hitTest dentro das tiles - Guto - 16.06.09	
	for(var f:Number = 0; f <n_matriz_np_a; f++){
		objReg.push([Number(matriz_np_a[f][0]), Number(matriz_np_a[f][1]), Number(matriz_np_a[f][2]), 0x600 + f]);
		insereObjTile(Number(matriz_np_a[f][1]) + margemTilesX*usX, Number(matriz_np_a[f][2]) + margemTilesY*usY, usX, usY, np_a, cenaTiles, 0x600 + f);
	}
	
	//objeto_link
	n_matriz_objeto_link = matriz_objeto_link.length;
	nuclObjetoLink = objNucleo(objeto_link._x + objeto_link.sombra._x, objeto_link._y + objeto_link.sombra._y, objeto_link.sombra);		//Essae matrizes serão utilizadas para hitTest dentro das tiles - Guto - 16.06.09		
	nuclObjLinkAcesso = objNucleo(objeto_link._x + objeto_link.acesso._x, objeto_link._y + objeto_link.acesso._y, objeto_link.acesso);
	for(var f:Number = 0; f <n_matriz_objeto_link; f++){		
		objReg.push([Number(matriz_objeto_link[f][0]), Number(matriz_objeto_link[f][1]), Number(matriz_objeto_link[f][2]), 0x700 + f]);
		insereObjTile(Number(matriz_objeto_link[f][1]) + margemTilesX*usX, Number(matriz_objeto_link[f][2]) + margemTilesY*usY, usX, usY, objeto_link.sombra, cenaTiles, 0x700 + f);		
		insereObjTile(Number(matriz_objeto_link[f][1]) + objeto_link.acesso._x + margemTilesX*usX, Number(matriz_objeto_link[f][2]) + objeto_link.acesso._y + margemTilesY*usY, usX, usY, objeto_link.acesso, cenaTiles, 0x800 + f);	//Obs: Sempre deixar a inclusão do objeto de acesso por último, para sobrescrever as tiles - Guto - 16.06.09		
	}

	//O depth deve ser ajustado no início também, para evitar que o mp inicie "em baixo" dos outros objetos - Guto - 13.02.09
	depth = depthBaseObj + mp.sombra._y + Number(mp.sombra._height)/2 + Number(usuario_status.personagem_posicao_y);			//A altura z é definida pela da posição em Y da sombra no mapa através da variável auxiliar - Guto - 30.12.08
	if((depth < 10000) and (depth > 15000)){									//Verifica se depth é um valor válido - Guto - 19.01.09
		while(getInstanceAtDepth(depth) != undefined) {							//A posição do eixo z do mp deve variar com sua posição no cenário e não deve invadir uma posição já ocupada - Guto - 23.12.08
			depth = Number(depth) + 1;			//Esses cast são muito comuns no código devido a fraca tipagem do ActionScript 2.0. Espero que "quando" utilizarmos o as3 isso mude... - Guto - 16.06.09
		}
	}
	mp.swapDepths(depth);	
	   		
	//inicializando Depht dos elementos de layout. Deve ser feito por último, nessa função, para garantir que estes objetos fique no ponto mais alto.  - eD - 03/11/08
	
	clima.swapDepths(getNextHighestDepth());
	bloqlayTerra.swapDepths(getNextHighestDepth());
	chat_box_main.swapDepths(getNextHighestDepth());
	velCtrl1.swapDepths(getNextHighestDepth());
	ChamaPopupSair.swapDepths(getNextHighestDepth());	
	btStart.swapDepths(getNextHighestDepth());	
	imprime.swapDepths(getNextHighestDepth());
	btAba1.swapDepths(getNextHighestDepth());
	portalCentro.swapDepths(getNextHighestDepth());
	mapa.swapDepths(getNextHighestDepth());
	mapaInfo.swapDepths(getNextHighestDepth());
	contatos.swapDepths(getNextHighestDepth());
	turmas.swapDepths(getNextHighestDepth());
	barraSelecao.swapDepths(getNextHighestDepth());
	TelaSaida.swapDepths(getNextHighestDepth());
	comunicador.swapDepths(getNextHighestDepth());
	menu.swapDepths(getNextHighestDepth());
	gMask.swapDepths(getNextHighestDepth());			//A máscara e o botão de início precisam estar na frente de tudo, exceto opções que a chamam - Guto - 03/02/10	
	barraSelecao.swapDepths(getNextHighestDepth());
	alerta.swapDepths(getNextHighestDepth());
	TelaSaida.swapDepths(getNextHighestDepth());
	btStart.swapDepths(getNextHighestDepth());	
	camposCriarUsuario.swapDepths(getNextHighestDepth());
	camposCriarTurma.swapDepths(getNextHighestDepth());
	camposTrocaSist.swapDepths(getNextHighestDepth());
	camposEditarUsuario.swapDepths(getNextHighestDepth());
	dropDown.swapDepths(getNextHighestDepth());
	btAdmAlerta.swapDepths(getNextHighestDepth());
	listaUsuarios.swapDepths(getNextHighestDepth());
	mascara.swapDepths(getNextHighestDepth());
	localizacao.swapDepths(getNextHighestDepth());
	
	Mouse.hide();                           //some com o ponteiro do mouse default do windows - Roger - 17/07/2009
	terreno.useHandCursor=false;
	ponteiro.gotoAndStop(1);
	ponteiro.swapDepths(getNextHighestDepth());	// faz com que o ponteiro fique acima de todos os outros objetos - Roger - 17/07/2009
	
	/*---------------------------------------------------
	*	Inicialização dos botões de velocidade - Giovani - 12.04.10
	---------------------------------------------------*/	
	if(usuario_status.velocidade == 1){
		velCtrl1.speed1.gotoAndStop(2);	
		velCtrl1.speed2.gotoAndStop(1);	
	}
	if(usuario_status.velocidade == 0){
		velCtrl1.speed2.gotoAndStop(2);
		velCtrl1.speed1.gotoAndStop(1);	
	}
	
} //inicializaObj()

/*---------------------------------------------------
*	Funções de carregar e descarregar os objetos da tela, de acordo com o movimento do mp - Guto - 10.07.09
---------------------------------------------------*/
function carregaObj():Void {
	for (var i = 0;i < objReg.length; i++){
		//Pega o id do objeto lembrando que objReg[i][3] = 0xY00, onde Y identifica o tipo de objeto e os dois últimos zeros indicam o id
		id = objReg[i][3] - (Math.floor(objReg[i][3]/0x100)*0x100);
		switch(Math.floor(objReg[i][3]/0x100)){
		case 0x5:
			//parede
			depth = depthBaseObj + Number(objReg[i][2]) + Number(parede.sombra._height)/2; 		//Faço com que a referência esteja no meio da sobra. Isso evita problemas com objetos muito grandes e de base elipsoidal - Guto - 04.05.09 
			while(getInstanceAtDepth(depth) != undefined) {			//Evita que já exista algum objeto neste nível - Guto - 19.12.08 
				depth++;
			}
			duplicateMovieClip("parede","parede"+id,depth);
			this["parede"+id].gotoAndStop(objReg[i][0]);
			this["parede"+id]._x = Number(objReg[i][1]) - parede.sombra._x + ajustX;
			this["parede"+id]._y = Number(objReg[i][2]) - parede.sombra._y + ajustY;
		break;
		case 0x6:	
			//np_a
			depth = depthBaseObj + Number(objReg[i][2]);
			while(getInstanceAtDepth(depth) != undefined) {			
				depth++;
			}
			duplicateMovieClip("np_a","np_a"+id,depth);
			this["np_a"+id].gotoAndStop(objReg[i][0]);
			this["np_a"+id]._x = Number(objReg[i][1]) + ajustX;
			this["np_a"+id]._y = Number(objReg[i][2]) + ajustY;
		break;
		case 0x7:	
			//objeto_link
			depth = depthBaseObj + Number(objReg[i][2]) + Number(objeto_link.sombra._height)/2; 		//Faço com que a referência esteja no meio da sobra. Isso evita problemas com objetos muito grandes e de base elipsoidal - Guto - 04.05.09 
			while(getInstanceAtDepth(depth) != undefined) {
				depth++;
			}
			duplicateMovieClip("objeto_link","objeto_link"+id,depth);
			this["objeto_link"+id].gotoAndStop(objReg[i][0]);
			this["objeto_link"+id]._x = Number(objReg[i][1]) - objeto_link.sombra._x + ajustX;
			this["objeto_link" + id]._y = Number(objReg[i][2]) - objeto_link.sombra._y + ajustY;

			mapa.attachMovie( "indicador", "indicadorLink" + id, mapa.getNextHighestDepth() );	
			
			eval("mapa.indicadorLink"+id).gotoAndStop(2);												//define a cor do indicador - Jean 
			eval("mapa.indicadorLink"+id)._x = mapa.mapaEscala._x + ((Number(objReg[i][1] +((eval("objeto_link"+id)._width)/2)))/terrDimX) * (mapa.mapaEscala._width - mapa.indicadorMp._width) - eval("mapa.indicadorLink"+id)._width/2;		//Posiciona, no mapa, o centro dos objetos_links presentes no cenário  -  Jean - 05.07.10
			eval("mapa.indicadorLink"+id)._y  = mapa.mapaEscala._y + ((Number(objReg[i][2] +((eval("objeto_link"+id)._height)/2)))/terrDimY) * (mapa.mapaEscala._height - mapa.indicadorMp._height) - eval("mapa.indicadorLink"+id)._height/2;

		break;
		default:
						
		break;
		}
	}
}

/*---------------------------------------------------
*	Funções de carregar, controlar a movimentação dos ops e enviar a posição do mp
---------------------------------------------------*/
function carregar_bd_posicoes():Void {
	var envia:LoadVars = new LoadVars;
	var recebe:LoadVars = new LoadVars;
	
	if ((funcionalidade != "true") and (linkExterno != "true")) {
		envia.posicao_x = usuario_status.personagem_posicao_x;
		envia.posicao_y = usuario_status.personagem_posicao_y;
		envia.posicao_x_auxiliar = usuario_status.personagem_posicao_x_auxiliar;
		envia.posicao_y_auxiliar = usuario_status.personagem_posicao_y_auxiliar;
	}	
		
	envia.terreno_id = terreno_status.terreno_id;
	
	envia.personagem_id = usuario_status.personagem_id;
	if (usuario_status.velocidade == false){
		envia.personagem_velocidade = 0;
	} else {
		envia.personagem_velocidade = 1;
	}
	
	//endereco = "http://www.nuted.edu.ufrgs.br/planeta2_edicao/planeta2_roger/desenvolvimento/interface_bd_personagem.php";
	envia.action = "3";
	//mp.fala.text = "akicaralho";
	
	recebe.onLoad = carregar_flash_posicoes;							//A definição do evento onLoad deve vir antes da chamada sendAndLoad, visto que o evento onLoad deve estar definido quando a instância recebe é atribuida aos dados recebidos do php - Guto - 08.07.10
	envia.sendAndLoad("interface_bd_personagem.php", recebe, "POST");
	
	
	usuario_status.rotaMp = "";//[]; //Deve zerar o array para guardar a próxima rota, bem como seu registrador de rota - Guto - 18.02.09
	passoOp = 0;
	
	if (transicaoTerreno == true){
	    transicaoTerreno = false;
	    atualiza_chat();	
	}
}

//funcao que recebe como parametro um personagem_id e retorna true se esse personagem esta no array 
//de personagens online - Roger - 10.02.10
function isOnline(personagem_id:Number):Boolean{
    var I:Number = 0;
    
    for (I=0 ; I <= online.length - 1 ; I++){
	    if (online[I]==personagem_id)
	        return true;
    }
    return false;
		
}

//recebe como parametro uma string de ids de personagens separados por virgula e guarda
//em no array online como numbers - Roger - 10.02.10
function guardar_personagens_online(lista_online:String){
	var stringTemp:String = new String(lista_online);
    var idTemp:String ; 
    var posAntes:Number = 0;
    var posDepois:Number = 0;
	online = new Array();
    var K:Number = 0;    	
	
	while(posDepois != -1){	    
	    posDepois = stringTemp.indexOf(String.fromCharCode(44) , posAntes);	   
	    if (posDepois != -1){
	        online.push( parseInt(stringTemp.substring(posAntes, posDepois)) );
        }	        
	    posAntes = posDepois + 1 ;
    }
}
/*
function apagar_fala_op(text:TextField):Void {
	text.htmlText = "";
}*/
/*---------------------------------------------------
	Carrega e manipula dados enviados do banco (função anterior) para o flash
---------------------------------------------------*/
function carregar_flash_posicoes(success):Void {
	if(success) {		
		//-------------------------------------------------------------
		//checar aki quem esta online em this.online
		//-------------------------------------------------------------
		
		guardar_personagens_online(this.online);		
		
		//gerando cópia da matriz antiga para comparar com a atual e extrair os personagem que foram deslogados - Guto - 13.01.09
		matriz_op_temp = matriz_op;
		
		//Zerando a matriz de ops para ser novamente atualizada e comparada com a matriz anterior, a fim de retirar os personagem que foram deslogados - Guto - 13.01.09
		matriz_op = [];
		for (var n:Number = 0; n < this.nLoop - 1; n++) {			
			id = this['id' + n];
		
			//verifica a existencia de op no flash
			verificar = getProperty("op"+id,_x);			 
			if(verificar == undefined) {
				//se não existe, cria Op				 
				duplicateMovieClip("op","op"+id,depthBaseObj+n);				
				eval("op"+id)._y  = Number(this['personagem_posicao_y' + n]) + Number(ajustY) - op.sombra._y;
				eval("op"+id)._x  = Number(this['personagem_posicao_x' + n]) + Number(ajustX) - op.sombra._x;
				//eval("op"+id].fala.text = this['personagem_fala' + n];
				//eval("op"+id].fala.textColor = '0x' + this['personagem_cor_texto' + n];
				eval("op" + id).fala.html = true;
				eval("op" + id).fala.htmlText = formata_linha(this['personagem_fala' + n]);				
				eval("op"+id).nome.text = this['personagem_nome' + n];
				eval("op"+id).nome.textColor = '0x' + this['personagem_cor_texto' + n]; //pega cor da fonte do banco de dados
				matriz_op.push([ id , this['personagem_posicao_x' + n], this['personagem_posicao_y' + n], 1, this['personagem_velocidade' + n], this['personagem_avatar_1' + n]]);
				eval("op" + id).personagem.mpBaixo.cabeloOlhoFrente.gotoAndStop(matriz_op[n][5]);
				//Quando o objeto é criado no palco, o mesmo deve ter o depth acertado aqui. Porém, caso o objeto já exista, o mesmo deve ter o seu depth acertado na função de movimentação dos ops - Guto - 13.02.09
				depth = depthBaseObj + Number(this['personagem_posicao_y' + n])+ Number(op.sombra._height)/2 ;
				if((depth > 5000) and (depth < 15000)){									//Verifica se depth é um valor válido - Guto - 19.01.09
					while(getInstanceAtDepth(depth) != undefined) {							
						depth = Number(depth) + 1;
					}
				}
				eval("op"+id).swapDepths(depth);
				
				//Escreve as tiles que o op ocupa na matriz de tiles - 15.07.09
				insereOpTile(Number(matriz_op[n][1]) + margemTilesX*usX, Number(matriz_op[n][2]) + margemTilesY*usY, usX, usY, op.sombra, cenaTiles, Number(0x900 + Number(id)));				
				//Cria o indicador, no mapa, correspondente ao op que acabou de ser criado
				mapa.attachMovie( "indicador", "indicadorOP" + id, mapa.getNextHighestDepth() );									//Anexa o indicador dos  Op's - Jean - 15.07.10
				eval("mapa.indicadorOP"+id).gotoAndStop(3);
			} else {
				//se existe
				//fala_buffer = eval("op"+id].fala.text;
				//eval("op"+id].fala.text = this['personagem_fala' + n];
				eval("op" + id).fala.html = true;
				eval("op" + id).fala.htmlText = formata_linha(this['personagem_fala' + n]);
				/*
				if(!(fala_buffer == eval("op"+id].fala.text)) {
					chat = '<font color="'+ '#' + this['personagem_cor_texto' + n] +'">'+  this['personagem_nome' + n] + ' ('+ this.ultima_atualizacao3
							+') : '+ eval("op"+id].fala.text + '</font><br>' + chat;
					chat_box_main.gotoAndStop(2);						
				}*/
				matriz_op.push([ id , Number(this['personagem_posicao_x' + n]) , Number(this['personagem_posicao_y' + n]), 1, this['personagem_velocidade' + n]/*, this['personagem_rota' + n]*/] );
				
			}
			
			if (Number(this['personagem_posicao_x_auxiliar' + n]) == -1000) {
				eval("op" + id)._visible = false;
			} else {
				eval("op" + id)._visible = true;
			}
		}
		
		//Registra o número de OPs para testes futuros - Guto - 20.01.09
		n_matriz_op = matriz_op.length;
		
		//eliminando personagem que sairam do terreno
		k1 = [];
		for(var n:Number = 0; n <matriz_op_temp.length; n++){
			k0 = 0;
			for(var m:Number = 0; m <n_matriz_op; m++){
				if(matriz_op_temp[n][0] == matriz_op[m][0]) {k0 = 1; break;}
			}//for(var m:Number = 0; m <n_matriz_op; m++)				
			
			if(k0 == 0) {
				id = matriz_op_temp[n][0];
				k1[n] = "!" + matriz_op_temp[n][0];
				//Apaga as tiles que o op ocupava na matriz de tiles - 15.07.09
				limpaOpTile(Number(matriz_op[n][1]) + margemTilesX*usX, Number(matriz_op[n][2]) + margemTilesY*usY, usX, usY, op.sombra, cenaTiles, Number(0x900 + Number(matriz_op[n][1])));
				eval("op"+id).removeMovieClip();
				eval("mapa.indicadorOP"+id).removeMovieClip();									//Retira do mapa o indicador do op que saiu da cena - Jean - 15.07.10
			} //if(k0 == 0)
		}
	}
}
