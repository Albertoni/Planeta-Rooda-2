var BIBLIOTECA = (function () {
	var mais_novo = 0;
	var mais_velho = 0;
	var materiais = [];
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
		+ '</small><p>Autor:' + this.autor + '</p><a href="abre_material.php?id='+this.id+'">';
	};
	function addMaterial(obj) {
	}
	function eventRequisicaoFalha() {}
	function eventRequisicaoSucesso() {}
	function solicitarNovos() {
	}
	return {
		key : 'value'
	}
}());
console.log(document.location.search);