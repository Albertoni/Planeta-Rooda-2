/**
 * COMENTARIOS.abrir(idRef, callback)
 *  idRef    := number
 *  callback := function (jQuery)

 * COMENTARIOS.enviar(idRef, mensagem)
 *  idRef    := number
 *  mensagem := string
 */
var COMENTARIOS = {};
(function (exports) {
	'use strict';
	var loaded = {};
	var usuario = {
		id: 0
	,	nome: ''
	};
	var janelaComentarios = $('<div>').addClass('comentarios')
		.append($('<h1>').text('Comentarios'))
		.append($('<ul>'))
		.append(
			$('<form>')
			.append($('<input>').attr('type', 'text'))
			.append($('<button>').attr('type', 'submit').addClass('comentar'))
		);


	function BoxComentarios (idRef) {
		this.idRef = idRef;
		this.Comentarios = [];
		this.html = BoxComentarios.baseHTML.clone();
	}
	BoxComentarios.prototype = {
		'idRef' : 0
	,	'ultimo' : 0 // id do ultimo comentario carregado
	,	'comentarios' : null
	,	'html' : null
	,	'intervalId' : 0
	}
	BoxComentarios.prototype.baixaComentarios = 
	function () {
		var that = this;
		AJAX.get("comentarios.json.php?acao=listar&idRef=" + parseInt(this.idRef),
		{ 
			success: function(e) {} 
		});
	}
	BoxComentarios.prototype.carregaStats = function () {
		var that = this;
		AJAX.get('comntarios.json.php?acao=stats&refId=' + parseInt(this.refId),
		{
			success: function (e) {}
		});
	}
	BoxComentarios.baseHTML = $('<div>').addClass('comentarios')
		.append($('<h1>').text('Comentarios'))
		.append($('<ul>'))
		.append(
			$('<form>')
			.append($('<input>').attr('type', 'text'))
			.append($('<button>').attr('type', 'submit').addClass('comentar'))
		);

	function Comentario (obj) {
		this.html = Comentario.baseHTML.clone();
		this.id = obj.id;
		this.idRef = obj.idRef;
		this.usuario = obj.usuario;
		this.data = new Date(obj.data*1000);
		this.mensagem = obj.mensagem;
		this.atualizaHTML();
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
		return ('[Comentario ' + this.id + '] (' + this.usuario.nome + ': ' + this.mensagem + ')');
	}

	// atualiza os dados no html do comentario
	Comentario.prototype.atualizaHTML = function () {
		this.html.attr('id', 'comentario_' + this.id.toString());
		this.html.contents('.info').contents('.usuario').text(this.usuario.nome);
		this.html.contents('.info').contents('.data').text(this.data.toLocaleString());
		this.html.contents('.mensagem').text(this.mensagem);
		if (usuario.permissoes.excluir)
			this.html.contents('.info').contents('.excluir').val(this.id);
		else
			this.html.contents('.info').contents('.excluir').hide();
	}
	Comentario.baseHTML = $('<li>')
		.addClass('comentario')
		.append(
			$('<p>')
				.addClass('info')
				.append(
					$('<button>').addClass('excluir')
						.text('excluir').attr('name', 'excluir')
				)
				.append(
					$('<span>').addClass('data')
				)
				.append(
					$('<span>').addClass('usuario')
				)
		)
		.append($('<p>').addClass('mensagem'));

	exports.abrir = (function () {
		var callbacks = {};
		function successHandler() {
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
				loaded[json.idRef] = { html: janelaComentarios.clone(), comentarios: [] };
				loaded[json.idRef].html.contents('form').submit(function (event) {
					event.preventDefault();
					var input = $(this).contents('input');
					console.log(input.val());
					input.val('');
					input.focus();
				})
				json.comentarios.forEach(function (obj) {
					loaded[json.idRef].comentarios.push(new Comentario(obj));
				});
				loaded[json.idRef].comentarios.forEach(function (comentario) {
					loaded[json.idRef].html.contents('ul').append(comentario.html);
				})
				callbacks[json.idRef](loaded[json.idRef].html);
			} else {
				console.dir(json.erro);
			}
		}
		return function (idRef, callsback) {
			callbacks[idRef] = callsback;
			AJAX.get("comentarios.json.php?acao=listar&idRef=" + parseInt(idRef), 
				{ success: successHandler });
		}
	}());
	exports.enviar(idRef, mensagem)
	exports.Comentario = Comentario;
}(COMENTARIOS));