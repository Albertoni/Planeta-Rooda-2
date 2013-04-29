/**
* Redireciona para a tela de desenvolvimento, iniciando o flash e entrando em um terreno.
* @param _idPlanetaDestino O id, no banco de dados, do planeta para o qual deseja-se ir. 
* Em caso de omissão, o usuário irá para o último planeta que acessou.
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
* @param _funcionalidade Nome da funcionalidade que será acessada.
* @param _usarFlash Booleano indicando se a funcionalidade deve abrir no flash ou não.
* @param _parametroUm Caso haja parâmetro a ser passado para a funcionalidade, virá aqui.
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
