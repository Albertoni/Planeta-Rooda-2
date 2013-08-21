var BIBLIOTECA = (function () {
	var ulDinamica = document.getElementById("ul_materiais");
	var mais_novo = 0;
	var mais_velho = 0;
	var materiais = [];
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
						turma = param, 10;
					}
				}
			}
			return turma;
	}());
	// funcção de organização para chamar materiais.sort(compararMateriais)
	function compararMateriais(a, b) {
		return a.id - b.id;
	}
	//console.log(typeof turma + turma);
	function Material(obj) {
		this.id = obj.id;
		this.titulo = obj.titulo;
		this.tipo = obj.tipo;
		this.autor = obj.autor;
		this.usuario = obj.usuario;
		this.tags = obj.tags;
		this.data = new Date(1000 * obj.data);
		this.aprovado = obj.aprovado;
		this.HTMLElemento = document.createElement('li');
		this.atualizarHTML();
	}
	Material.prototype.atualizarHTML = function() {
		this.HTMLElemento.innerHTML = '<h1>' + this.titulo + '</h1><small>Enviado por ' 
		+ this.usuario.nome + ' em ' + this.data.toLocaleString() 
		+ '</small><p>Autor:' + this.autor + '</p><a href="abrirMaterial.php?id=' + this.id + '" target="_blank">baixar</a>';
	};
	function addMaterial(obj) {
		if (!Material.prototype.isPrototypeOf(obj)) {
			obj = new Material(obj);
		}

	}
	function eventRequisicaoFalha() {}
	function eventRequisicaoSucesso() {}
	function solicitarMaisNovos() {
		var request = AJAXGet("biblioteca.json.php?turma=" + turma + "&acao=listar&mais_novo=" + mais_novo, {
			success: function () {
				var json;
				try {
					json = JSON.parse(this.responseText);
				} catch (e) {
					console.log(e.message);
				}
				console.log(json);
				if (!json.session) {
					console.log("nâo está logado");
					return;
				}
				for (var i in json.materiais) {
					json.materiais[i]
				}
			},
			fail: function () {}
		});
		request
	}
	return {
		key : 'value',
		s : solicitarMaisNovos,
		t : turma
	}
}());
//console.log(document.location.search);