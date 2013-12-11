this._lockroot = this;

import flash.external.*;


/*
* Criar barra de progresso para escutar o carregamento até que possa iniciar.
*/
attachMovie(c_barra_progresso_foguete.LINK_BIBLIOTECA, "barraProgressoDownload", 5000, {_x:0, y:0, _visible:false, _alpha:0});
_root.barraProgressoDownload.definirPorcentagem(0);

_root.gMask._alpha = 0;

barraProgressoDownload.onEnterFrame = function() {				
	arquivos_carregados = this.getBytesLoaded()/this.getBytesTotal()*100;
	barraProgressoDownload.definirPorcentagem(arquivos_carregados);
	if (arquivos_carregados == 100) {
		barraProgressoDownload.removeMovieClip();
		nextScene();
	} 
}