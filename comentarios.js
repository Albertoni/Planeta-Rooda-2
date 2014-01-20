/**
 * COMENTARIOS.abrir(idRef, callback)
 *  idRef    := number
 *  callback := function (jQuery)

 * COMENTARIOS.enviar(idRef, mensagem)
 *  idRef    := number
 *  mensagem := string
 */
 /*
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
	,	'numComentarios' : null
	,	'html' : null
	,	'intervalId' : 0
	}
	BoxComentarios.prototype.baixaComentarios = function () {
		var that = this;
		AJAX.get("comentarios.json.php?acao=listar&idRef=" + parseInt(this.idRef),
		{ 
			success: function(e) {} 
		});
	};
	BoxComentarios.prototype.carregaStats = function () {
		var that = this;
		AJAX.get('comntarios.json.php?acao=stats&refId=' + parseInt(this.refId),
		{
			success: function (e) {}
		});
	};
	BoxComentarios.prototype.statsReqHandler = function () {};
	BoxComentarios.baseHTML = $('<div>').addClass('comentarios')
		.append($('<h1>').text('Comentarios'))
		.append($('<ul>'))
		.append(
			$('<form>')
			.append($('<input>').attr('type', 'text'))
			.append($('<button>').text('Enviar').attr('type', 'submit').addClass('comentar'))
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
*/
var COMM = {};
(function (exports) {
	function Comentario(obj) {
		this.usuario = obj.usuario;
		this.mensagem = obj.mensagem;
		this.data = new Date(obj.data * 1000); // transformanado em milisegundos
		this.htmlDinamico = {};
		this.htmlDinamico.usuario = $("<span>")
	}
	Comentario.prototype = {
		'id': 0
	,	'usuario' : null
	,	'mensagem' : ''
	,	'data' : 0
	,	'html' : null
	,	'htmlDinamico' : null
	}
	function Comentarios(idRef) {
		var comentarios = this;
		this.idRef = parseInt(idRef,10);
		this.comenarios = [];
		this.atualizar();
		this.htmlNumComentarios = $('<span>');
		this.link = $('<a>').text('Ver comentários ').append(this.htmlNumComentarios);
		this.link.on("click") = this.abrir.bind(this);
		this.permissoes = { ver: false, comentar: false, excluir: false };
	}
	// partes do layout que mudam de conteudo
	Comentarios.htmlDinamico = {
		titulo : $('<span>')
	,	listaComentarios : $('<ul>')
	,	formulario : $('<form>')
	,	inputMensagem : $('<input type="text">')
	,	botaoFechar : $('<button type="button" class="bt_fechar">fechar</button>')
	};
	Comentarios.htmlDinamico.botaoFechar.on('click', function () {
		Comentarios.html.hide();
	});
	Comentarios.html = $('<div>').addClass('comentarios')
			.append($('<h1>').append('Comentarios - ')
			                 .append(Comentarios.htmlDinamico.titulo)
			                 .append(Comentarios.htmlDinamico.botaoFechar))
			.append(Comentarios.htmlDinamico.listaComentarios)
			.append($("<form>").append(Comentarios.htmlDinamico.inputMensagem));
	$(document.body).append(Comentarios.html);
	Comentarios.html.hide();
	Comentarios.prototype = {
		'idRef': 0
	,	'idTurma': 0
	,	'idUsuario': 0
	,	'permissoes': null
	,	'titulo' : ''
	,	'numComentarios': 0
	,	'ultimoComentario': 0
	,	'comentarios': null
	,	'link': null
	}
	Comentarios.prototype.atualizar = function () {
		var that = this;
		AJAX.get('comentarios.json.php?acao=stats&idRef=' + this.idRef,
			{
				success: function() {
					var e, j;
					try {
						var j = JSON.parse(this.responseText);
						that.atualizarHandler(j);
					}
					catch (e) {
						ROODA.alert('Erro no servidor.');
						console.dir(e);
						return;
					}
				},
				fail: function() {
					setTimeout(that.atualizar.bind(that), 1000);
					if (Comentarios.aberto === that) {
						// este é o comentario sendo visualizado no momento
						// mostrar algum aviso de que à falha na conexão.
					}
				}
			});
	};
	Comentarios.prototype.atualizarHandler = function (response) {
		if (!response.usuario
			|| !response.usuario.permissoes
			|| !response.usuario.permissoes.ver) {
			this.link.empty();
			return;
		}
		if (this.titulo !== response.titulo) {
			this.titulo = response.titulo;
		}
		// essa função pede para carregar os comentarios se precisar
		var n = response.numComentarios;
		// if (typeof n !== 'number') throw new Error("numComentarios precisa ser numero.");
		this.numComentarios = n;
		this.htmlNumComentarios.text('(' + n + ')');
		if (Comentarios.aberto === this) {
			// se esses comentarios estão sendo atualizados, verificar se precisa carregar novos comentários...
			if (this.comentarios.length < n) {
				// precisa carregar mais comentários
			} else if (this.comantarios.length > n) {
				// algum comentário foi apagado
			} else {
				// parece que tudo está certo...
			}
		}
	};
	Comentarios.prototype.enviar = function (mensagem) {
		if (!mensagem || typeof mensagem !== 'string') return;
		AJAX.post("comentarios.json.php?acao=enviar&idRef=" + this.idRef, 
			{ 'mensagem' : mensagem }
		,	{
				success:function () {
					var e, j;
					try {
						var j = JSON.parse(this.responseText);
						that.atualizarHandler(j);
					}
					catch (e) {
						ROODA.alert('Erro no servidor.');
						console.dir(e);
						return;
					}
				}
			}
		);
		// AJAX.post();
	};
	Comentarios.prototype.abrir = function () {
		Comentarios.html.hide();
		Comentarios.aberto = this;
		// esvaziar a janela de comentarios
		Comentarios.htmlDinamico.listaComentarios.empty();
		// atualizar titulo
		Comentarios.htmlDinamico.titulo.text(this.titulo);
		// 
		this.atualizar();
		Comentarios.html.show();
	}
	exports.Comentarios = Comentarios;
}(ROODA));