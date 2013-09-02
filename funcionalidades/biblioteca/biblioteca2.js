var BIBLIOTECA = (function () {
	var ulDinamica = document.getElementById("ul_materiais");
	var btCarregar = document.getElementById("bt_carregar_mais");
	var mais_novo = 0;
	var mais_velho = 0;
	var materiais = [];
	var pode_aprovar = false;
	var pode_editar = false;
	var pode_excluir = false;
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
	//console.log(typeof turma + turma);
	function Material(obj) {
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
		if (!this.aprovado) {
			this.HTMLElemento.classList.add('nao_aprovado');
		}
		this.atualizarHTML();
	}
	Material.prototype.atualizarHTML = function() {
		this.HTMLElemento.innerHTML = '<h2>' + this.titulo + '</h2><small>Enviado por ' 
		+ this.usuario.nome + ' (' + this.data.toLocaleString()
		+ ')</small><p>Autor:' + this.autor + '</p><p><a href="abrirMaterial.php?id=' + this.id + '" target="_blank" class="abrir_material">Abrir material<span class="icon">&nbsp;</span></a></p>';
		if (pode_aprovar && !this.aprovado) {
			this.HTMLElemento.innerHTML += '<button type="button" name="aprovar" class="aprovar" value="'
			+ this.id + '">Aprovar</button>';
		}
		if (pode_editar) {
			this.HTMLElemento.innerHTML += '<button type="button" name="editar" class="editar" value="' 
			+ this.id + '">Editar</button>';
		}
		if (pode_excluir) {
			this.HTMLElemento.innerHTML += '<button type="button" name="excluir" class="excluir" value="' 
			+ this.id + '">Excluir</button>';
		}
	};
	ulDinamica.addEventListener('click', function(e) {
		e = e || event;
		var elem = e.target;
		switch (elem.name) {
			case 'aprovar':
				console.log(elem.value);
				break;
			case 'excluir':
				break;
			default:
				break;
		}
	});
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
	}
	// remove material da lista de materiais
	function removeMaterial(id) {
		materiais = materiais.filter(function (material) { return (material.id !== id); });
	}
	// atualiza lista de materiais (HTML) de acordo com a lista de materiais (JS)
	function atualizaLista() {
		var i;
		while (ulDinamica.firstElementChild) {
			console.log('removendo');
			ulDinamica.removeChild(ulDinamica.firstElementChild);
		}
		for (i in materiais) {
			console.log('add');
			ulDinamica.appendChild(materiais[i].HTMLElemento);
		}
	}
	// solicita novos materiais ao servidor.
	var ajax = (function () {
		var intervalToken;
		var failCount;
		// função executada quando falha a requisição de novos materiais
		function onSuccess() {
			var json;
			failCount = 0;
			try {
				json = JSON.parse(this.responseText);
			} catch (e) {
				ROODA.ui.alert("Servidor não respondeu.");
				console.log(e);
				console.log(this.responseText);
			}
			if (!json.session) {
				ROODA.ui.alert("Sua sessão expirou.");
				return;
			}
			pode_aprovar = json.pode_aprovar ? true : false;
			pode_editar  = json.pode_editar  ? true : false;
			pode_excluir = json.pode_excluir ? true : false;
			//console.log(json);
			json.materiais.forEach(addMaterial);
			if (json.todos) {
				btCarregar.disabled = true;
			}
			atualizaLista();
		}
		// função que é executada quando a requisição de novos materiais é bem sucedida
		function onFail_new() {
			failCount += 1;
			if (failCount > 2) {
				clearInterval(intervalToken);
				ROODA.ui.alert("Servidor não está mais respondendo.<br>Verifique sua conexão com a internet.")
			}
		}
		function onFail_old() {
			ROODA.ui.alert("Servidor não respondeu.");
		}
		function request_newer() {
			AJAXGet("biblioteca.json.php?turma=" + turma + "&acao=listar&mais_novo=" + mais_novo, {
				'success': onSuccess,
				'fail': onFail_new
			});
		}
		function request_older() {
			AJAXGet("biblioteca.json.php?turma=" + turma + "&acao=listar&mais_velho=" + mais_velho, {
				'success': onSuccess,
				'fail': onFail_old
			});
		}
		function submit_success() {
			try {
				json = JSON.parse(this.responseText);
			}
			catch (e) {
				ROODA.ui.alert("Erro na resposta do servidor.");
				console.log(e);
				console.log(this.responseText);
			}
		}
		function init()
		{
			mais_novo = 0;
			mais_velho = 0;
			materiais = [];
			request_newer();
			btCarregar.onclick = request_older;
			intervalToken = setInterval(request_newer, 60000);
		}
		return { 'init' : init, 'request_older' : request_older };
	}());
	ajax.init();
	return { init : ajax.init };
}());