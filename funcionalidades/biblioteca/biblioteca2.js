var BIBLIOTECA = (function () {
	var ulDinamica;
	var formEnvioMaterial;
	var formEdicaoMaterial;
	var editorMaterial;
	var mais_novo = 0;
	var mais_velho = 0;
	var materiais = [];
	var pode_aprovar = false;
	var pode_editar = false;
	var pode_excluir = false;
	var busca = {}
	var sessao = {'id' : 0, 'usuario' : '', 'nome' : ''};
	var token_atualizador; // valor retornado por setInterval()
	var falhasSucessivas = 0; // numero de requisições que falharam
	var turma = (function () {
			var strParams = document.location.search.slice(1);
			var params = strParams.split("&");
			var param;
			var turma = 0;
			for (var i in params) {
				param = params[i].split("=");
				if (param[0] === 'turma') {
					param = parseInt(param[1], 10);
					if (param) {
						turma = param;
					}
				}
			}
			return turma;
	}());
	// definição da relaçao de ordem para chamar materiais.sort(organizaMateriais)
	function organizaMateriais(a, b) {
		return b.id - a.id;
	}
	var busca = (function () {
		var selecionada, ativa, dom = { 
		    	'container' : document.createElement("span"),
		    	'title' : document.createElement("span"),
		    	'typeLabel' : document.createElement("label"),
		    	'type' : document.createElement("select"),
		    	'queryLabel' : document.createElement("label"),
		    	'submit' : document.createElement("button"),
		    	'tags' : document.createElement("select")
		    },
		    // dicionarios de indexação
		    indices = {
		    	'tags' : {}, 
		    	'autores' : {}, 
		    	'usuarios' : {}
		    },
		    // tipos de busca
		    searchTypes = {
		    	// BUSCA POR TITULO
		    	'all' : {
		    		'text' : 'mostrar todos',
		    		'field' : document.createElement('span'),
		    		'lists' : {},
		    		'def' : true, 
		    		'onselect' : function () {
		    			while (dom.queryLabel.firstElementChild) {
		    				dom.queryLabel.removeChild(dom.queryLabel.firstElementChild);
		    			}
		    		},
		    		'apply' : function () {
		    			atualizaLista(materiais);
		    		}
		    	},
		    	'title' : { 
		    		'text' : 'título',
		    		'field' : document.createElement('input'),
		    		'lists' : {},
		    		'onselect' : function () {
		    			while (dom.queryLabel.firstElementChild) {
		    				dom.queryLabel.removeChild(dom.queryLabel.firstElementChild);
		    			}
		    			dom.queryLabel.appendChild(this.field);
		    		},
		    		'apply' : function () {
		    			var that = this;
		    			atualizaLista(materiais.filter(function (m) {
		    				return (m.titulo.toLowerCase().search(that.field.value.toLowerCase()) !== -1);
		    			}));
		    		}
		    	},
		    	// BUSCA POR AUTOR
		    	'author' : { 
		    		'text' : 'autor', 
		    		'field' : document.createElement('select'),
		    		'lists' : {},
		    		'onselect' : function () {
		    			var that = this;
		    			while (this.field.firstElementChild) {
		    				this.field.removeChild(this.field.firstElementChild);
		    			}
		    			Object.keys(this.lists).forEach(function (autor) {
		    				var opt = document.createElement("option");
		    				opt.value = autor;
		    				opt.text = autor;
		    				that.field.add(opt);
		    			});
		    			while (dom.queryLabel.firstElementChild) {
		    				dom.queryLabel.removeChild(dom.queryLabel.firstElementChild);
		    			}
		    			dom.queryLabel.appendChild(this.field);
		    		},
		    		'apply' : function () {
		    			atualizaLista(this.lists[this.field.value]);
		    		}
		    	},
		    	// BUSCA POR TAGS
		    	'tags' : { 
		    		'text' : 'palavras chave', 
		    		'field' : dom.tags,
		    		'lists' : {},
		    		'onselect' : function () {
		    			var that = this;
		    			while (this.field.firstElementChild) {
		    				this.field.removeChild(this.field.firstElementChild);
		    			}
		    			Object.keys(this.lists).forEach(function (tag) {
		    				var opt = document.createElement("option");
		    				opt.value = tag;
		    				opt.text = tag;
		    				that.field.add(opt);
		    			});
		    			while (dom.queryLabel.firstElementChild) {
		    				dom.queryLabel.removeChild(dom.queryLabel.firstElementChild);
		    			}
		    			dom.queryLabel.appendChild(this.field);
		    		},
		    		'apply' : function () {
		    			atualizaLista(this.lists[this.field.value]);
		    		}
		    	},
		    	// BUSCA POR USUARIO
		    	'user' : { 
		    		'text' : 'usuário', 
		    		'field' : document.createElement('select'),
		    		'lists' : {},
		    		'onselect' : function () {
		    			var that = this;
		    			while (this.field.firstElementChild) {
		    				this.field.removeChild(this.field.firstElementChild);
		    			}
		    			Object.keys(this.lists).forEach(function (usuario) {
		    				var opt = document.createElement("option");
		    				opt.value = usuario;
		    				opt.text = usuario;
		    				that.field.add(opt);
		    			});
		    			while (dom.queryLabel.firstElementChild) {
		    				dom.queryLabel.removeChild(dom.queryLabel.firstElementChild);
		    			}
		    			dom.queryLabel.appendChild(this.field);
		    		},
		    		'apply' : function () {
		    			atualizaLista(this.lists[this.field.value]);
		    		}
		    	}
		    };
		// estrutura
		//dom.container.classList.add("bloco");
		dom.container.appendChild(dom.title);
		dom.container.appendChild(dom.typeLabel);
		dom.typeLabel.appendChild(dom.type);
		dom.container.appendChild(dom.queryLabel);
		dom.container.appendChild(dom.submit);
		var b = document.getElementById("botao_buscar_material");
		b.parentElement.replaceChild(dom.container,b);
		// conteudo
		dom.title.innerHTML = "Buscar: ";
		Object.keys(searchTypes).forEach(function (key) {
			var opt = document.createElement("option");
			opt.value = key;
			opt.text = searchTypes[key].text;
			if (searchTypes[key].def) {
				opt.selected = true;
				ativa = selecionada = key;
				searchTypes[key].onselect();
			}
			dom.type.add(opt, null);
		});
		dom.type.onchange = function () {
			selecionada = this.value;
			if (searchTypes[this.value]) {
				if (typeof searchTypes[this.value].onselect === 'function') {
					searchTypes[this.value].onselect();
				}
			}
		}
		dom.submit.innerHTML = "buscar";
		dom.submit.onclick = function () {
			ativa = selecionada;
			searchTypes[selecionada].apply();
		}
		return {
			'indexar' : function (material) {
				// adiciona objeto as tags que ele contem
				material.tags.forEach(function (tag) {
					if (!Array.isArray(searchTypes.tags.lists[tag])) {
						searchTypes.tags.lists[tag] = [];
					}
					searchTypes.tags.lists[tag].push(material);
				});
				// adiciona objeto ao usuario
				if (!Array.isArray(searchTypes.user.lists[material.usuario.nome])) {
					searchTypes.user.lists[material.usuario.nome] = [];
				}
				searchTypes.user.lists[material.usuario.nome].push(material);
				// adiciona objeto ao autor
				if (!Array.isArray(searchTypes.author.lists[material.autor])) {
					searchTypes.author.lists[material.autor] = [];
				}
				searchTypes.author.lists[material.autor].push(material);
			},
			'desindexar' : function (material) {
				// remove material do indice de tags
				material.tags.forEach(function (tag) {
					searchTypes.tags.lists[tag].splice(searchTypes.tags.lists[tag].indexOf(material), 1);
					// remove tag do dicionario se estiver vazia
					if (searchTypes.tags.lists[tag].length === 0) {
						delete searchTypes.tags.lists[tag];
					}
				});
				// remove material do indice de usuarios
				searchTypes.user.lists[material.usuario.nome].splice(searchTypes.user.lists[material.usuario.nome].indexOf(material), 1);
				if (searchTypes.user.lists[material.usuario.nome].length === 0) {
					delete searchTypes.user.lists[material.usuario.nome];
				}
				// remove material do indice de autores
				searchTypes.author.lists[material.autor].splice(searchTypes.author.lists[material.autor].indexOf(material), 1);
				if (searchTypes.author.lists[material.autor].length === 0) {
					delete searchTypes.author.lists[material.autor];
				}
			},
			'atualizar' : function () {
				searchTypes[ativa].apply();
			},
			's' : searchTypes
		};
	}());
	//console.log(typeof turma + turma);
	function Material(obj) {
		var i;
		this.id = obj.id;
		this.titulo = obj.titulo;
		this.tipo = obj.tipo;
		this.autor = obj.autor;
		this.usuario = obj.usuario;
		this.tags = obj.tags;
		this.data = new Date(1000 * obj.data); // javascript trabalha com milissegundos
		this.aprovado = obj.aprovado;
		this.HTMLElemento = document.createElement('li');
		if (this.tipo === 'arquivo') {
			var classes = obj.arquivo.tipo.split('/');
			classes = classes.map(function(e) { return e.split(".").join("-"); });
			this.arquivo = obj.arquivo;
			this.HTMLElemento.classList.add('arquivo');
			for (i in classes) {
				this.HTMLElemento.classList.add(classes[i]);
			}
		}
		else if (this.tipo === 'link') {
			this.link = obj.link;
			this.HTMLElemento.classList.add('link');
			// adiciona mimetype à classe do elemento (para icones de tipo de arquivo via css)
		}
		this.HTMLTitulo = document.createElement("h2");
		this.HTMLInfo = document.createElement("small");
		this.HTMLLink = document.createElement("a");
		this.HTMLBotaoAprovar = document.createElement("button");
		this.HTMLBotaoAprovar.type = 'button';
		this.HTMLBotaoAprovar.name = 'aprovar';
		this.HTMLBotaoAprovar.className = 'aprovar';
		this.HTMLBotaoAprovar.value = this.id.toString();
		this.HTMLBotaoAprovar.innerHTML = "Aprovar";
		this.HTMLBotaoEditar = document.createElement("button");
		this.HTMLBotaoEditar.type = 'button';
		this.HTMLBotaoEditar.name = 'editar';
		this.HTMLBotaoEditar.className = 'editar';
		this.HTMLBotaoEditar.value = this.id.toString();
		this.HTMLBotaoEditar.innerHTML = "Editar"; 
		this.HTMLBotaoExcluir = document.createElement("button");
		this.HTMLBotaoExcluir.type = 'button';
		this.HTMLBotaoExcluir.name = 'excluir';
		this.HTMLBotaoExcluir.className = 'excluir';
		this.HTMLBotaoExcluir.value = this.id.toString();
		this.HTMLBotaoExcluir.innerHTML = "Excluir";
		this.HTMLAutor = document.createElement("p");
		this.HTMLLink = document.createElement("a");
		this.HTMLLink.classList.add("abrir_material");
		this.HTMLLink.innerHTML = 'Abrir Material <span class="icon">&nbsp;</span>'
		this.HTMLLink.target = '_blank';
		this.atualizarHTML();
	}
	Material.prototype = {titulo:'',autor:''};
	Material.prototype.atualizarHTML = function() {
		if (!this.aprovado) {
			this.HTMLElemento.classList.add('nao_aprovado');
		} else {
			this.HTMLElemento.classList.remove('nao_aprovado');
		}
		while (this.HTMLElemento.firstElementChild) {
			this.HTMLElemento.removeChild(this.HTMLElemento.firstElementChild);
		}
		this.HTMLElemento.appendChild(this.HTMLTitulo);
		this.HTMLElemento.appendChild(this.HTMLInfo);
		this.HTMLElemento.appendChild(this.HTMLAutor);
		this.HTMLElemento.appendChild(this.HTMLLink);
		if (pode_aprovar && !this.aprovado) {
			this.HTMLElemento.appendChild(this.HTMLBotaoAprovar);
		}
		if (pode_editar || this.usuario.id ===  sessao.id) {
			this.HTMLElemento.appendChild(this.HTMLBotaoEditar);
		}
		if (pode_excluir || this.usuario.id ===  sessao.id) {
			this.HTMLElemento.appendChild(this.HTMLBotaoExcluir);
		}
		this.HTMLTitulo.innerHTML = this.titulo
			.replace(/</g,"&lt;").replace(/>/g,"&gt;");
		this.HTMLInfo.innerHTML = 'Enviado por ' + this.usuario.nome + ' (' + this.data.toLocaleString() + ')'
			.replace(/</g,"&lt;").replace(/>/g,"&gt;");
		this.HTMLAutor.innerHTML = 'Autor: ' + this.autor
			.replace(/</g,"&lt;").replace(/>/g,"&gt;");
		this.HTMLLink.href = 'abrirMaterial.php?id=' + this.id + '';
	};
	//var form_edicao_material = document.getElementById('editar_material');
	function ulDinamica_onclick(e) {
		e = e || event;
		var elem = e.target;
		switch (elem.name) {
			case 'aprovar':
				console.log('aprovar: ' + elem.value);
				ROODA.ui.confirm("Tem certeza que deseja aprovar este material?", function () { ajax.aproveMaterial(elem.value); });
				break;
			case 'excluir':
				console.log('excluir: ' + elem.value);
				ROODA.ui.confirm("Tem certeza que deseja excluir este material?", function () { ajax.deleteMaterial(parseInt(elem.value, 10)); });
				break;
			case 'editar':
				console.log('editar: ' + elem.value);
				var material = materiais.filter(function (material) { return (parseInt(elem.value, 10) === material.id); })[0];
				Array.prototype.forEach.call(formEdicaoMaterial.elements, function (elem) {
					console.log(material);
					console.log(elem.name);
					switch (elem.name) {
						case 'id':
							elem.value = material.id.toString();
							break;
						case 'titulo':
							elem.value = material.titulo;
							break;
						case 'autor':
							elem.value = material.autor;
							break;
						case 'tags':
							elem.value = material.tags.join(',');
					}
					console.log(elem);
				});
				$(editorMaterial).fadeIn();
				break;
			default:
				break;
		}
	}
	// adicionar novo material à lista de materiais
	function addMaterial(obj) {
		// verifica se o material já está na lista
		if (materiais.filter(function (material) { return (obj.id === material.id); }).length !== 0) {
			// material ja foi adicionado
			console.log(material);
			return;
		}
		// verifica se o material herda de Material.prototype.
		if (!Material.prototype.isPrototypeOf(obj)) {
			obj = new Material(obj);
			console.log(obj);
		}
		// adiciona material à lista
		materiais.push(obj);
		// organiza lista
		materiais.sort(organizaMateriais);
		if (mais_novo < obj.id) {
			mais_novo = obj.id;
		}
		if (mais_velho === 0 || mais_velho > obj.id) {
			mais_velho = obj.id;
		}
		busca.indexar(obj);
	}
	// remove material da lista de materiais
	function getMaterial(id) {
		return materiais.filter(function (material) { return (material.id === id); })[0];
	}
	function removeMaterial(id) {
		var material = getMaterial(id),
		    idx = materiais.indexOf(material);
		// remove material
		if (idx !== -1) {
			busca.desindexar(material);
			materiais.splice(idx, 1);
		}
	}
	function aprovaMaterial(id) {
		var tmp = materiais.filter(function (material) { return (material.id === id); });
		console.log(tmp);
		if (tmp[0]) {
			tmp[0].aprovado = true;
			tmp[0].atualizarHTML();
		}
	}
	// atualiza lista de materiais (HTML) de acordo com a lista de materiais (JS)
	function atualizaLista(lista) {
		var i;
		while (ulDinamica.firstElementChild) {
			console.log('removendo');
			ulDinamica.removeChild(ulDinamica.firstElementChild);
		}
		for (i in lista) {
			console.log('add');
			ulDinamica.appendChild(lista[i].HTMLElemento);
		}
	}
	// solicita novos materiais ao servidor.
	var ajax = (function () {
		var intervalToken;
		var failCount;
		// função executada quando falha a requisição de novos materiais
		var request_newer = (function () {
			function onSuccess() {
				var json;
				failCount = 0;
				try {
					json = JSON.parse(this.responseText);
				} catch (e) {
					ROODA.ui.alert("Erro no servidor.");
					console.log(e);
					console.log(this.responseText);
					return;
				}
				if (!json.session) {
					ROODA.ui.alert("Sua sessão expirou.");
					return;
				}
				sessao.id = json.session.id;
				sessao.usuario = json.session.usuario;
				sessao.nome = json.session.nome;
				pode_aprovar = json.pode_aprovar ? true : false;
				pode_editar  = json.pode_editar  ? true : false;
				pode_excluir = json.pode_excluir ? true : false;
				//console.log(json);
				if (json.materiais.length > 0) {
					json.materiais.forEach(addMaterial);
					//atualizaLista(materiais);
					busca.atualizar();
				}
				setTimeout(request_newer, 60000);
			}
			// função que é executada quando a requisição de novos materiais é bem sucedida
			function onFail() {
				failCount += 1;
				if (failCount > 2) {
					ROODA.ui.alert("Servidor não está mais respondendo.<br>Verifique sua conexão com a internet.")
				} else {
					setTimeout(request_newer, 60000);
				}
			}
			return function () {
				AJAXGet("biblioteca.json.php?turma=" + turma + "&acao=listar&mais_novo=" + mais_novo, {
					'success': onSuccess,
					'fail': onFail
				});
			}
		}());
		var request_older = (function () {
			var waiting = false;
			function onSuccess() {
				var json;
				waiting = false;
				failCount = 0;
				try {
					json = JSON.parse(this.responseText);
				} catch (e) {
					ROODA.ui.alert("Erro no servidor.");
					console.log(e);
					console.log(this.responseText);
				}
				if (!json.session) {
					ROODA.ui.alert("Sua sessão expirou.");
					return;
				}
				//console.log(json);
				// if (json.todos) {
				// 	// sinal indicando que todos os posts mais antigos já foram carregados.
				// 	window.removeEventListener("scroll", scrollHandler);
				// 	console.log("handler removido");
				// }
				if (json.materiais.length > 0) {
					json.materiais.forEach(addMaterial);
					//atualizaLista(materiais);
					busca.atualizar();
				}
			}
			function onFail_old() {
				waiting = false;
				ROODA.ui.alert("Servidor não está mais respondendo.<br>Verifique sua conexão com a internet.");
			}
			return function () {
				if (!waiting) {
					waiting = true;
					AJAXGet("biblioteca.json.php?turma=" + turma + "&acao=listar&mais_velho=" + mais_velho, {
						'success': onSuccess,
						'fail': onFail_old
					});
				}
			};
		}());
		// submitNewMaterial(formulario) : faz request de submissão de material
		var submitNewMaterial = (function() {
			function submit_success() {
				var json;
				try {
					json = JSON.parse(this.responseText);
				}
				catch (e) {
					ROODA.ui.alert("Erro na resposta do servidor.");
					console.log(e);
					console.log(this.responseText);
					return;
				}
				//console.log(json);
				if (!json.session) {
					ROODA.ui.alert("Você não está logado.")
					return;
				}
				if (!json.success) {
					ROODA.ui.alert(json.errors.join("<br />\n"));
				}
				formEnvioMaterial.reset();
				toggleEnviar();
				request_newer();
			}
			return submitFormFunction(submit_success);
		}());
		var submitEditMaterial = (function () {
			function submit_success() {
				var json;
				try {
					json = JSON.parse(this.responseText);
				}
				catch (e) {
					ROODA.ui.alert("Erro na resposta do servidor.");
					console.log(e);
					console.log(this.responseText);
					return;
				}
				console.log(json);
				if (!json.session) {
					return
				}
				if (json.errors) {
					return
				}
				if (json.material) {
					var m = getMaterial(json.material.id);
					m.titulo = json.material.titulo;
					m.autor = json.material.autor;
					m.tags = json.material.tags;
					m.atualizarHTML();
				}
				formEdicaoMaterial.reset();
				$(editorMaterial).fadeOut();

			}
			return submitFormFunction(submit_success);
		}());
		var deleteMaterial= (function () {
			function req_success() {
				var res, json, id;
				res = this.responseText;
				if (!res) {
					return;
				}
				try {
					json = JSON.parse(res);
				}
				catch (e) {
					ROODA.ui.alert("Erro no servidor.");
					console.log(e);
					console.log(res);
					return;
				}
				if (json.success) {
					id = json.id;
					removeMaterial(id);
					//atualizaLista(materiais);
					busca.atualizar();
				} else {
					if (json.errors) {
						ROODA.ui.alert("Não foi possivel excluir:<br>" + json.errors.join("<br>"));
					} else {
						ROODA.ui.alert("Não foi possivel excluir:<br>Motivo desconhecido.")
					}
				}
			}
			function req_fail() {
				ROODA.ui.alert("Não foi possivel excluir o material: o servidor não respondeu.");
			}
			return function (id) {
				AJAXGet("biblioteca.json.php?turma=" + turma + "&acao=excluir&id=" + id, {
					'success': req_success,
					'fail': req_fail
				});
			}
		}());
		var aproveMaterial= (function () {
			// handler a executar quando o request ao servidor é executado com sucesso
			function req_success() {
				var res, json, id;
				res = this.responseText;
				if (!res) {
					return;
				}
				try {
					json = JSON.parse(res);
				}
				catch (e) {
					ROODA.ui.alert("Erro no servidor.");
					console.log(e);
					console.log(res);
					return;
				}
				if (json.success) {
					id = json.id;
					aprovaMaterial(id);
				} else {
					if (json.errors) {
						ROODA.ui.alert("Não foi possivel aprovar:<br>" + json.errors.join("<br>"));
					} else {
						ROODA.ui.alert("Não foi possivel aprovar:<br>Motivo desconhecido.")
					}
				}
			}
			// handler para executar quando a requisição ao servidor falha
			function req_fail() {
				ROODA.ui.alert("Não foi possivel excluir o material: o servidor não respondeu.");
			}
			return function (id) {
				AJAXGet("biblioteca.json.php?turma=" + turma + "&acao=aprovar&id=" + id, {
					'success': req_success,
					'fail': req_fail
				});
			}
		}());

		// var scrollHandler = function () {
		// 	if ((window.document.body.scrollHeight - document.documentElement.clientHeight - window.pageYOffset) < 80) {
		// 		request_older();
		// 	}
		// };
		function init()
		{
			mais_novo = 0;
			mais_velho = 0;
			materiais = [];
			request_newer();
			// setTimeout(function () {
			// 	window.addEventListener("scroll", scrollHandler);
			// }, 2000);
		}
		// submitEditMaterial(formulario)
		return {
			'init' : init,
			'request_older' : request_older,
			'submitNewMaterial' : submitNewMaterial,
			'submitEditMaterial' : submitEditMaterial,
			'deleteMaterial' : deleteMaterial,
			'aproveMaterial' : aproveMaterial
		};
	}());
	function init() { 
		ulDinamica = document.getElementById("ul_materiais");
		formEnvioMaterial = document.getElementById("form_envio_material");
		formEdicaoMaterial = document.getElementById("form_edicao_material");
		editorMaterial = document.getElementById("editar_material");
		ulDinamica.addEventListener('click', ulDinamica_onclick);
		$(editorMaterial).on('click', function (e) {
			if (e.target.name === 'fechar') {
				$(editorMaterial).fadeOut();
			}
		});
		formEnvioMaterial.onsubmit = function () {
			setTimeout(function () { ajax.submitNewMaterial(formEnvioMaterial); }, 5);
			return false;
		};
		form_edicao_material.onsubmit = function() {
			setTimeout(function () { ajax.submitEditMaterial(formEdicaoMaterial); }, 5);
			return false;
		}
		ajax.init();
	}
	return { 'init' : init, form: formEnvioMaterial, 'tags': busca.tags, 'busca' : busca };
}());
/*
$(document).ready(function () {
	function updateList() {
		AJAXGet('../../arquivo.json.php?acao=listar', {
			success: function () {},
			fail: function () {}
		});
	}
}); */