var COMENTARIOS = {};
(function (exports) {
	'use strict';
	var loaded {}
	var usuario = {
		id: 0
	,	nome: ''
	};
	var janelaComentarios = $('<div>').addClass('comentarios');
	janelaComentarios.append($('<h1>').text(''));
	function Comentario (obj) {
		this.html = Comentario.baseHTML.clone();
	}
	Comentario.prototype = {
		'id' : 0
	,	'idRef' : 0
	,	'usuario' : null
	,	'data' : 0
	,	'mensagem' : ''
	,	'html' : null
	}
	Comentario.prototype.toString = function () {
		return ('[Comentario ' + this.id + '] ('this.usuario.nome + ': ' + this.mensagem + ')');
	}
	Comentario.prototype.updateHTML = function () {
		this.html.contents('.usuario').text(this.usuario.nome);
		this.html.contents('.data').text('(' + this.data.toLocaleString() + ')');
		this.html.contents('.mensagem').text(this.mensagem);
	}
	Comentario.baseHTML = $('<div>')
	.addClass('comentario')
	.append(
		$('p')
		.addClass('info')
		.append(
			$('<span>').addClass('usuario')
		)
		.append(
			$('<span>').addClass('data')
		)
	)
	.append($('<p>').addClass('mensagem'));
	function load(idRef) {
		AJAX.get("comentarios.json.php?acao=listar&idRef=" + parseInt(idRef),
			{
				success: function (e) {
					var json;
					try {
						json = JSON.parse(this.responseText);
					}
					catch (e) {
						console.dir(e);
						console.log('resposta:', this.responseText);
						return;
					}
					if (!json.erro) {
						usuario = json.usuario;
					} else {
						console.dir(json);
					}
				}
			}
		);
	}
	function carregaPermissoes(turma) {
		AJAX.post(
			"comentarios.json.php?acao=verifica&turma=" + turma,
			{
				success: function (e) {
					var json;
					try {
						json = JSON.parse(this.responseText);
					}
					catch (e) {
						console.dir(e);
						console.log('resposta:', this.responseText);
						return;
					}
					if (!json.erro) {
						usuario = json.usuario;
					} else {
						console.dir(json);
					}
				}
			}
		);
	}
	exports.load = load;
}(COMENTARIOS));