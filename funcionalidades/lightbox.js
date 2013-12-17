/**
* Fecha a colorbox que contém funcionalidades, redirecionando para o link absoluto de parâmetro.
* @param _linkRedirecionamento O link de caminho absoluto para onde será redirecionado.
* @param _tipoLink O tipo de link, se absoluto ou concatenado. No link absoluto, redireciona-se para o link. No concatenado, concatena-se o link ao final da url atual.
*/
function fecharColorBox(_linkRedirecionamento, _tipoLink){
	if(parent.location != window.location){
		parent.$.fn.colorbox.close();
		if(_tipoLink=='absoluto'){
			parent.location.href = _linkRedirecionamento;
		} else if(_tipoLink=='concatenado'){
			parent.location.replace(_linkRedirecionamento);
		}
	} else {
		if(_tipoLink=='absoluto'){
			location.href = _linkRedirecionamento;
		} else if(_tipoLink=='concatenado'){
			location.replace(_linkRedirecionamento);
		}
	}
}

/**
* Redireciona para um link sem fechar a colorbox.
* @param _linkRedirecionamento O link de caminho absoluto para onde será redirecionado.
* @param _tipoLink O tipo de link, se absoluto ou concatenado. No link absoluto, redireciona-se para o link. No concatenado, concatena-se o link ao final da url atual.
*/
function redirecionar(_linkRedirecionamento, _tipoLink){
	if(_tipoLink=='absoluto'){
		location.href = _linkRedirecionamento;
	} else if(_tipoLink=='concatenado'){
		location.replace(_linkRedirecionamento);
	}
}
