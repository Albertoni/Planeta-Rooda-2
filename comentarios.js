(function (exports) {
	'use strict';
	function Comentario(obj, botaoApagar) {
		this.id = obj.id;
		this.usuario = new ROODA.Usuario(obj.usuario.id, obj.usuario.usuario, obj.usuario.nome);
		this.data = new Date(obj.data * 1000); // transformanado em milisegundos
		this.mensagem = obj.mensagem;
		this.html = $('<li>').addClass('comentario')
			.append($("<p>").addClass('info')
				.append(botaoApagar ? $('<button type="button" name="excluir">').click(this.excluir.bind(this)) : null)
				.append(this.usuario.toHTML(),
					$("<span>").addClass("data").text(this.data.toLocaleString())),
				$('<p>').addClass('mensagem').text(this.mensagem));
	}
	Comentario.prototype = {
		'id': 0
	,	'usuario' : null
	,	'mensagem' : ''
	,	'data' : 0
	,	'html' : null
	,	'excluir' : function () {
			var url = 'comentarios.json.php?acao=excluir&id=' + this.id;
			var that = this;
			that.html.slideUp(function () {
				AJAX.get(url, {
					success: function () {
						var j, e;
						try {
							j = JSON.parse(this.responseText);
						}
						catch (e) {
							that.html.slideDown();
							ROODA.ui.alert("Comentário não excluído: erro no servidor.");
							console.dir(e);
							return;
						}
						if (j.erro) {
							that.html.slideDown();
							ROODA.ui.alert("Comentário não excluído: " + j.erro);
							return;
						}
						// deu certo.
						if (j.comentarioExcluido === that.id) {
							that.html.remove();
							return;
						}
						ROODA.ui.alert("Ops. Aconteceu um errinho, não sei se o comentário foi excluído ou não.");
					},
					fail: function () {
						that.html.slideDown();
						ROODA.ui.alert("Comentário não excluído: servidor não respondeu.")
					}
				});
			});
		}
	}
	function Comentarios(idRef) {
		//var comentarios = this;
		this.idRef = parseInt(idRef,10);
		this.comentarios = [];
		this.htmlNumComentarios = $('<span>');
		this.link = $('<a>').text('Ver comentários ').append(this.htmlNumComentarios);
		this.link.on("click", this.abrir.bind(this));
		this.permissoes = { ver: true, comentar: false, excluir: false };
		// precisa adicionar à essa lista para ele carregar.
		Comentarios.carregados.push(this);
	}
	// comentarios de referencias que podem ser abertos
	Comentarios.carregados = [];
	// partes do layout que mudam de conteudo
	Comentarios.htmlDinamico = {
		titulo : $('<span>')
	,	listaComentarios : $('<ul>')
	,	formulario : $('<form>')
	,	inputMensagem : $('<input type="text">')
	,	botaoFechar : $('<button type="button" class="bt_fecharComentarios">fechar</button>')
	};
	Comentarios.htmlDinamico.botaoFechar.on('click', function () {
		Comentarios.fechar();
	});
	Comentarios.htmlDinamico.formulario.submit(function (event) {
		var mensagem;
		event.preventDefault();
		if (Comentarios.aberto) {
			mensagem = Comentarios.htmlDinamico.inputMensagem.val();
			console.log(mensagem);
			if (mensagem) {
				Comentarios.aberto.enviar(mensagem);
			}
			Comentarios.htmlDinamico.inputMensagem.val('');
		}
	});
	Comentarios.html = $('<div>').addClass('comentarios')
			.append($('<h1>').append('COMENTÁRIOS - ')
			                 .append(Comentarios.htmlDinamico.titulo)
			                 .append(Comentarios.htmlDinamico.botaoFechar))
			.append(Comentarios.htmlDinamico.listaComentarios)
			.append(Comentarios.htmlDinamico.formulario.append(Comentarios.htmlDinamico.inputMensagem));
	$(document.body).append(Comentarios.html);
	Comentarios.html.hide();
	Comentarios.fechar = function () {
		Comentarios.html.hide();
		Comentarios.aberto.fechar();
		Comentarios.aberto = null;
		Comentarios.htmlDinamico.titulo.empty();
		Comentarios.htmlDinamico.listaComentarios.empty();
	}
	Comentarios.scroll = function () {
		var e = Comentarios.htmlDinamico.listaComentarios;
		e.scrollTop(e[0].scrollHeight);
	}
	Comentarios.prototype = {
		'idRef': 0
	,	'idTurma': 0
	,	'idDono': 0 // autor/dono da referencia que tem comentários
	,	'usuario': null // usuario logado
	,	'permissoes': null
	,	'titulo' : ''
	,	'numComentarios': 0
	,	'ultimoComentario': 0
	,	'comentarios': null
	,	'link': null
	}
	Comentarios.prototype.atualizar = function () {
		var that = this;
		var url = 'comentarios.json.php?acao=stats&idRef=' + this.idRef
			+ '&ultimo=' + this.ultimoComentario;
		AJAX.get(url, {
			success: function() {
				var j, e;
				try {
					j = JSON.parse(this.responseText);
				}
				catch (e) {
					ROODA.ui.alert('Erro nos comentarios.');
					that.atualizar = function () {};
					console.dir(e);
					return;
				}
				that.atualizarHandler(j);
			},
			fail: function() {
				//setTimeout(that.atualizar.bind(that), 1000);
				if (Comentarios.aberto === that) {
					// este é o comentario sendo visualizado no momento
					// mostrar algum aviso de que à falha na conexão.
				}
			}
		});
	};

	Comentarios.prototype.atualizarHandler = function (response) {
		if (!response.permissoes
			|| !response.permissoes.ver) {
			this.link.empty();
			this.permissoes.ver = false;
			this.permissoes.comentar = false;
			this.permissoes.excluir = false;
			return;
		}
		this.idDono = response.idUsuario;
		if (!this.usuario || this.usuario.id !== response.usuario.id) {
			this.usuario = new ROODA.Usuario(response.usuario.id, response.usuario.usuario, response.usuario.nome);
		}
		this.permissoes.ver = response.permissoes.ver;
		this.permissoes.comentar = response.permissoes.comentar;
		this.permissoes.excluir = response.permissoes.excluir;
		if (this.titulo !== response.titulo) {
			this.titulo = response.titulo;
			if (this === Comentarios.aberto)
				Comentarios.htmlDinamico.titulo.text(this.titulo);
		}
		// essa função pede para carregar os comentarios se precisar
		var n = response.numComentarios;
		// if (typeof n !== 'number') throw new Error("numComentarios precisa ser numero.");
		this.numComentarios = n;
		this.htmlNumComentarios.text('(' + n + ')');

		// se esses comentarios estão abertos, verificar se precisa carregar novos comentários...
		if (Comentarios.aberto === this) {
			if (this.permissoes.comentar) {
				Comentarios.htmlDinamico.inputMensagem.attr('placeholder','Digite uma mensagem');
				Comentarios.htmlDinamico.inputMensagem.attr('disabled',false);
			} else {
				Comentarios.htmlDinamico.inputMensagem.attr('placeholder','Sem permissão para comentar.');
				Comentarios.htmlDinamico.inputMensagem.attr('disabled',true);
			}
			if (response.novosComentarios > 0) {
				// tem comentario(s) novo(s)
				this.carregar();
			}
			if (this.comentarios.length > (response.numComentarios - response.novosComentarios)) {
				// algum comentário foi apagado.
				this.verificarApagados();
			}
			// ja que está aberto, continuar atualizando com mais frequência.
			setTimeout(this.atualizar.bind(this), 2000);
		}
	};

	Comentarios.prototype.carregar = function () {
		var that = this;
		var url = 'comentarios.json.php?acao=listar&idRef=' + this.idRef
			+ '&ultimo=' +this.ultimoComentario;
		AJAX.get(url, {
			success : function () {
				var j, e;
				try {
					j = JSON.parse(this.responseText);
				}
				catch (e) {
					ROODA.ui.alert('Erro no servidor.');
					console.dir(e);
					return;
				}
				that.carregarHandler(j);
			}
		});
	}
	Comentarios.prototype.carregarHandler = function (response) {
		var i, c, podeExcluir, e;
		if (response.comentarios) {
			for (i = 0; i < response.comentarios.length; i += 1) {
				c = response.comentarios[i];
				podeExcluir = this.permissoes.excluir
					|| this.usuario.id === this.idUsuario
					|| c.usuario.id === this.usuario.id;
				c = new Comentario(c, podeExcluir);
				this.comentarios.push(c);
				if (this.ultimoComentario < c.id) {
					this.ultimoComentario = c.id;
				}
				if (Comentarios.aberto === this) {
					Comentarios.htmlDinamico.listaComentarios.append(c.html);
					Comentarios.scroll();
				}
			}
		}
	}

	// Comentarios.prototype.verificarApagados()
	//    Pede os ids dos comentarios no servidor para verificar
	//    quais comentários foram apagados. e deve ser chamado
	//    automaticamente por 'Comentarios.prototype.atualizar'.
	Comentarios.prototype.verificarApagados = function () {
		var that = this;
		var url = 'comentarios.json.php?acao=listarIds&idRef=' + this.idRef;
		AJAX.get(url, {
			success : function () {
				// resposta do servidor veio com sucesso, parsear o json...
				var j, e;
				try {
					j = JSON.parse(this.responseText);
				}
				catch (e) {
					ROODA.ui.alert('Erro no servidor.');
					console.dir(e);
					return;
				}
				that.verificarApagadosHandler(j);
			},
			fail : function () {}
		});
	}

	Comentarios.prototype.verificarApagadosHandler = function (response) {
		var i;
		if (response.ids) {
			// procurar em todos os comentarios carregados...
			for (i = 0; i < this.comentarios.length; i += 1) {
				// se o id dele nao estiver na lista, ele foi apagado
				if (-1 === response.ids.indexOf(this.comentarios[i].id)) {
					// remover da DOM
					this.comentarios[i].html.slideUp(function() { $(this).remove(); });
					// remover da lista em memória
					this.comentarios.splice(i,1);
				}
			}
		}
	}

	Comentarios.prototype.enviar = function (mensagem) {
		var that = this;
		if (!mensagem || typeof mensagem !== 'string') return;
		AJAX.post("comentarios.json.php?acao=enviar&idRef=" + this.idRef, {
			'mensagem' : mensagem
		}, {
			success:function () {
				var e, j;
				try {
					var j = JSON.parse(this.responseText);
				}
				catch (e) {
					ROODA.alert('Erro no servidor.');
					console.dir(e);
					return;
				}
				that.enviarHandler(j);
			}
		});
		// AJAX.post();
	};
	Comentarios.prototype.enviarHandler = function (response) {}
	Comentarios.prototype.abrir = function () {
		var i;
		if (!this.permissoes.ver) {
			ROODA.ui.alert("Você não tem permissão para ver estes comentários.");
			return;
		}
		Comentarios.html.hide();
		Comentarios.aberto = this;
		// esvaziar a janela de comentarios
		Comentarios.htmlDinamico.listaComentarios.empty();
		// atualizar titulo
		Comentarios.htmlDinamico.titulo.text(this.titulo);
		// atualizar conteúdo
		for (i = 0; i < this.comentarios.length; i++) {
			Comentarios.htmlDinamico.listaComentarios.append(this.comentarios[i].html);
		}
		this.atualizar();
		Comentarios.html.show();
		Comentarios.scroll();
	}

	Comentarios.prototype.fechar = function () {}

	// o que fazer quando a pagina tiver carregada:
	$(document).ready(function () {
		// indice dos comentarios que vao
		var i = 0;
		// a cada 2 segundos atualiza a quantidade de comentarios de 1 post
		var interval = 2000;
		// manter atualizado os comentários sem sobrecarregar o servidor.
		var intervalId = setInterval(function () {
			if (i >= Comentarios.carregados.length) {
				i = 0;
				// descomente abaixo caso só queira atualizar o numero de comentarios
				// uma vez (a não ser que os comentários sejam abertos pelo usuário):
				//clearInterval(intervalId);
			}
			if (Comentarios.carregados[i]) {
				// se ele está aberto, ele já esta sendo atualizado.
				if (Comentarios.aberto !== Comentarios.carregados[i])
					Comentarios.carregados[i].atualizar();
			}
			i += 1;
		}, interval);
		// Substituir os placeholders colocados pelo php (se tiver)
		// por links para os comentários.
		// Placeholders devem estar no formato:
		//   <input type="hidden" name='comentarios" value="idRef">
		$('input[name=comentarios]').replaceWith(function() {
			var c, elem = $(this);
			// pegar id do comentario
			c = parseInt(elem.val(), 10);
			// inicializar objeto
			c = new Comentarios(c);
			// retornar o link que vai substituir o placeholder.
			return c.link;
		});
	});

	exports.Comentarios = Comentarios;
}(ROODA));