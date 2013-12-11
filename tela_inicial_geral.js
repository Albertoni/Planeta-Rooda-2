/**
* Redireciona para a tela de desenvolvimento, iniciando o flash e entrando em um terreno.
* @param _idPlanetaDestino O id, no banco de dados, do planeta para o qual deseja-se ir. 
* Em caso de omiss�o, o usu�rio ir� para o �ltimo planeta que acessou.
*/
function redirecionarParaDesenvolvimento(_idPlanetaDestino){
	if(0 < arguments.length){
		document.location = 'listaFuncionalidades.php?terreno='+_idPlanetaDestino;
		//document.location = 'desenvolvimento/index.php?terreno_id_tela_inicial_geral='+_idPlanetaDestino;
	} else {
		//document.location = 'desenvolvimento/index.php';
	}
}

/**
* Acessa uma funcionalidade.
* @param _funcionalidade Nome da funcionalidade que ser� acessada.
* @param _usarFlash Booleano indicando se a funcionalidade deve abrir no flash ou n�o.
* @param _parametroUm Caso haja par�metro a ser passado para a funcionalidade, vir� aqui.
*/
function redirecionarParaFuncionalidade(_funcionalidade, _usarFlash, _parametroUm){
	var linkFuncionalidade = "";
	
	switch(_funcionalidade){
		case 'criarAvatar': linkFuncionalidade = 'funcionalidades/criar_personagem/criar_personagem.php?id_char_as='+_parametroUm;
			break;
	}
	
	if(_usarFlash){
		document.location = 'desenvolvimento/index.php?funcionalidade=true&linkColorBox='+linkFuncionalidade;
	} else {
		document.location = linkFuncionalidade;
	}
}
