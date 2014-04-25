var listaUsuariosSelecionados = [];

function filtrar(){
	var modoFiltragem = document.querySelector('input[name="tipoPesquisa"]:checked');
	modoFiltragem = (modoFiltragem == null) ? "nome" : modoFiltragem.value;
	// Caso não tenha nenhum marcado retorna null e a linha acima conserta.

	var input = document.getElementById("filtro");

	var listaFiltrada = listaUsuarios.filter(function(usuario){
		/*
			Vamos por partes:
			Primeiro, a função filter loopa por todos os elementos, chamando essa função anonima que declarei agora uma vez sobre cada elemento do array. Se e somente se retornar true, o elemento vai pro array filtrado.

			usuario[modoFiltragem] quer dizer acessar a propriedade do objeto usuario contida em modoFiltragem.
			Exemplo:modoFiltragem = "nome";
					usuario[modoFiltragem] == usuario["nome"] == usuario.nome;

			Passa-se tudo para minúscula para facilitar a vida do usuário.

			E por fim, um indexOf para ver se a string sendo buscada é contida na propriedade.
		*/
		return ((usuario[modoFiltragem].toLowerCase().indexOf(input.value.toLowerCase())) != -1);
	});

	setaListaDeUsuarios(listaFiltrada);
}

function setaListaDeUsuarios(lista){
	var tamanhoLista = lista.length;
	var elementoLista = document.getElementById('lista_usuarios');

	elementoLista.innerHTML = ""; // Precisa limpar ela pra inserir os dados atualizados

	function imprime(estruturaDados){
		function geraTd(textoLink, id){
			var link = document.createElement('a');
			link.href = "edita_usuario-Novo.php?id="+id;
			link.innerHTML = textoLink;

			var td = document.createElement('td');
			td.appendChild(link);

			return td;
		};
		var tr = document.createElement('tr');
		tr.className = 'trTabelaAlunos';

		var tdCheckbox = document.createElement('td');
			var checkbox = document.createElement('input');
				checkbox.type = "checkbox";
				checkbox.value = estruturaDados['idUsuario'];
				checkbox.addEventListener("click", function(){
					listaUsuariosSelecionados[this.value] = this.checked;
				}, false);
				checkbox.checked = (((listaUsuariosSelecionados[estruturaDados['idUsuario']] == false) ||
									((listaUsuariosSelecionados[estruturaDados['idUsuario']] == undefined))) // undefined para caso nunca tenha sido clicado, e não, undefined não é igual a false
				 					? false : true);
			tdCheckbox.appendChild(checkbox);

		var tdNome = geraTd(estruturaDados['nome'], estruturaDados['idUsuario']);
		var tdEmail = geraTd(estruturaDados['email'], estruturaDados['idUsuario']);
		var tdLogin = geraTd(estruturaDados['login'], estruturaDados['idUsuario']);
		
		
		tr.appendChild(tdCheckbox);
		tr.appendChild(tdNome);
		tr.appendChild(tdEmail);
		tr.appendChild(tdLogin);
		elementoLista.appendChild(tr);
	};

	for(var i=0; i < tamanhoLista; i++){
		imprime(lista[i]);
	};
}