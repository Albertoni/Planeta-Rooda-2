numquestoes = 1;

function addQuestion(){
	//Pessoalmente, não gostei dessa função, acho que daria pra fazer melhor, mas...
	//Tá funcionando, fica assim.
	
	pai = document.getElementById("adicione_questoes_aqui");
	item1 = document.createElement("li");
	item1.className = "espaco_linhas";
	
	item2 = document.createElement("pre");
	item2.className = "fonte_pre";
	item2.innerHTML = "A " + (numquestoes+=1) + "ª questão será:";
	
	item3 = document.createElement("select");
	item3.name = "quest" + numquestoes;
		filho1_do_item3 = document.createElement("option");
		filho2_do_item3 = document.createElement("option");
		filho3_do_item3 = document.createElement("option");
		filho1_do_item3.innerHTML = "Múltipla Escolha";
		filho2_do_item3.innerHTML = "Subjetiva";
		filho3_do_item3.innerHTML = "Verdadeiro ou Falso";
		filho1_do_item3.value = "1";
		filho2_do_item3.value = "2";
		filho3_do_item3.value = "3";
	item3.appendChild(filho1_do_item3);
	item3.appendChild(filho2_do_item3);
	item3.appendChild(filho3_do_item3);
	
	wrapper = document.createElement("div");
	wrapper.id = "bala_de_caramelo"+numquestoes;
	wrapper.appendChild(item1);
	wrapper.appendChild(item2);
	wrapper.appendChild(item3);
	pai.appendChild(wrapper);
}

function removeQuest(){
	die = document.getElementById("bala_de_caramelo"+numquestoes);
	die.parentNode.removeChild(die);
	numquestoes-=1;
}


cor1VF = false; // false == par and true == impar
function maisPerguntasVF(id, numquestoesVF) {
	// Cria.
	pai = document.getElementById("adicione_questoes_aqui"+id);
	filho = document.createElement("tr");
	filho.id = "bala_7belo"+numquestoesVF+"_"+id; // O PRIMEIRO NÃO TEM ESSA ID E PORTANTO NÃO PODE SER DELETADO! É UM BUG QUE CONSERTA UM BUG!
	
	numquestoesVF += 1; // INCREMENTE A VARIÁVEL ANTES DE GERAR O AGÁ-TÊ-ÊME-ÉLE
	
	neto1 = document.createElement("td");
		bisneto1 = document.createElement("input");
	neto2 = document.createElement("td");
		bisneto2 = document.createElement("div");
			tataraneto2 = document.createElement("input");
	neto3 = document.createElement("td");
		bisneto3 = document.createElement("div");
			tataraneto3 = document.createElement("input");
	
	neto1.innerHTML = "Opção "+numquestoesVF+":";
	
	bisneto1.type = "text";
	bisneto1.size = 60;
	bisneto1.style.cssText = "width:450px";
	bisneto1.name = "opvf"+numquestoesVF+"_"+id;
	
	neto2.style.cssText = "width:65px; border-left: 2px solid white";
	neto3.style.cssText = "width:65px; border-left: 2px solid white";
	
	bisneto2.align = "center";
	bisneto3.align = "center";
	
	tataraneto2.type = "radio";
	tataraneto2.name = "radio"+numquestoesVF+"_"+id;
	tataraneto2.value= "v";
	
	tataraneto3.type = "radio";
	tataraneto3.name = "radio"+numquestoesVF+"_"+id;
	tataraneto3.value= "f";
	
	
	bisneto2.appendChild(tataraneto2);
	bisneto3.appendChild(tataraneto3);
	
	neto1.appendChild(bisneto1);
	neto2.appendChild(bisneto2);
	neto3.appendChild(bisneto3);
	
	filho.appendChild(neto1);
	filho.appendChild(neto2);
	filho.appendChild(neto3);
	

	// Cria o botão de opções.
	maisopcoes = document.createElement("tr");
	maisopcoes.id = "bala_de_canela"+id;
	maisopcoes.innerHTML = "\
<td>\
	<a onclick=\"maisPerguntasVF("+id+","+numquestoesVF+")\"><b>Mais opções</b></a>\
	<div style=\"float:right\">\
		<a onclick=\"menosPerguntasVF("+id+","+numquestoesVF+")\"><b>Menos opções</b></a>\
	</div>\
</td>\
<td></td>\
<td></td>";

	// Remove as opções
	bala = document.getElementById("bala_de_canela"+id);
	bala.parentNode.removeChild(bala);

	// Adiciona de novo na ordem certa.
	pai.appendChild(filho);
	pai.appendChild(maisopcoes);
	
	// Seta as cores dos elementos
	if (cor1VF == false) {
		filho.bgColor = "#E7C7ED";
		maisopcoes.bgColor = "#EEE8EF";
		cor1VF = true;
	} else {
		filho.bgColor = "#EEE8EF";
		maisopcoes.bgColor = "#E7C7ED";
		cor1VF = false;
	}
	
	// Por fim, altera a quantidade de opções.
	document.getElementsByName("numop_"+id)[0].value = numquestoesVF;
}

function menosPerguntasVF(id, numquestoesVF){
	bala = document.getElementById("bala_de_canela"+id);
	bai = document.getElementById("bala_7belo"+(numquestoesVF-1)+"_"+id);
	pai = document.getElementById("adicione_questoes_aqui"+id);
	
	if (bai == null){
		return 0;
	}
	
	maisopcoes = document.createElement("tr");
	maisopcoes.bgColor = bai.bgColor;
	
	bala.parentNode.removeChild(bala);
	bai.parentNode.removeChild(bai); // Se a função travar aqui, não faz mais nada e não afeta nada.
	
	numquestoesVF -= 1;
	
	
	maisopcoes.id = "bala_de_canela"+id;
	maisopcoes.innerHTML = "\
<td>\
	<a onclick=\"maisPerguntasVF("+id+","+numquestoesVF+")\"><b>Mais opções</b></a>\
	<div style=\"float:right\">\
		<a onclick=\"menosPerguntasVF("+id+","+numquestoesVF+")\"><b>Menos opções</b></a>\
	</div>\
</td>\
<td></td>\
<td></td>";
	pai.appendChild(maisopcoes);
	
	document.getElementsByName("numop_"+id)[0].value = numquestoesVF;
}

corME = false; // false == par and true == impar
function maisPerguntasME(id, numquestoesME) {
	// Cria.
	pai = document.getElementById("adicione_questoes_aqui"+id);
	filho = document.createElement("tr");
	filho.id = "bala_de_cafe"+numquestoesME+"_"+id;
	
	numquestoesME += 1; // INCREMENTE A VARIÁVEL ANTES DE GERAR O AGÁ-TÊ-ÊME-ÉLE
	
	// Bota o HTML do elemento.
	filho.innerHTML = "\
<td>Opção "+numquestoesME+":\
	<input type=\"text\" size=60 style=\"width:550px\" name=\"opmul"+numquestoesME+"_"+id+"\"/>\
</td>\
<td></td>\
<td style=\"width:65px; border-left: 2px solid c#EEE8EF\">\
	<div align=\"center\"><input type=\"radio\" name=\"radio_"+id+"\" value=\""+numquestoesME+"\"/></div>\
</td>";

	maisopcoes = document.createElement("tr");
	maisopcoes.id = "bala_de_cocacola"+id;
	maisopcoes.innerHTML = "\
<td>\
	<a onclick=\"maisPerguntasME("+id+","+numquestoesME+")\"><b>Mais opções</b></a>\
	<div style=\"float:right\">\
		<a onclick=\"menosPerguntasME("+id+","+numquestoesME+")\"><b>Menos opções</b></a>\
	</div>\
</td>\
<td></td>\
<td></td>";

	bala = document.getElementById("bala_de_cocacola"+id);
	bala.parentNode.removeChild(bala);

	pai.appendChild(filho);
	pai.appendChild(maisopcoes);
	
	if (cor1VF == false) {
		filho.bgColor = "#E7C7ED";
		maisopcoes.bgColor = "#EEE8EF";
		cor1VF = true;
	} else {
		filho.bgColor = "#EEE8EF";
		maisopcoes.bgColor = "#E7C7ED";
		cor1VF = false;
	}
	
	document.getElementsByName("numop_"+id)[0].value = numquestoesME;
}

function menosPerguntasME(id, numquestoesME){
	bala = document.getElementById("bala_de_cocacola"+id);
	bai = document.getElementById("bala_de_cafe"+(numquestoesME-1)+"_"+id);
	pai = document.getElementById("adicione_questoes_aqui"+id);
	
	if (bai == null){
		return 0;
	}
	
	maisopcoes = document.createElement("tr");
	maisopcoes.bgColor = bai.bgColor;
	
	bala.parentNode.removeChild(bala);
	bai.parentNode.removeChild(bai); // Se a função travar aqui, não faz mais nada e não afeta nada.
	
	numquestoesME -= 1;
	
	
	maisopcoes.id = "bala_de_cocacola"+id;
	maisopcoes.innerHTML = "\
<td>\
	<a onclick=\"maisPerguntasME("+id+","+numquestoesME+")\"><b>Mais opções</b></a>\
	<div style=\"float:right\">\
		<a onclick=\"menosPerguntasME("+id+","+numquestoesME+")\"><b>Menos opções</b></a>\
	</div>\
</td>\
<td></td>\
<td></td>";
	pai.appendChild(maisopcoes);
	
	document.getElementsByName("numop_"+id)[0].value = numquestoesME;
}

function validaData(d, m){
	var e="";
	switch (m){
		case "2":
			if (d>28)
				e="Por favor, troque a data. 29 de fevereiro ou acima não é válida. Desculpe o transtorno, mas não suportamos anos bissextos.\n";
			break;
		case "4":
		case "6":
		case "9":
		case "11":
			if (d>30)
				e="Confira as datas. Voce entrou com o dia 31 de um mês que não tem dia 31.\n";
			break;}
	return e;
}

function valida1() {
	var erros = new Array();
	
	if (document.getElementsByName("titulo")[0].value == "") // titulo vazio
		erros.push("O título está em branco.\n");
	
	if (document.getElementsByName("descrição")[0].value == "") // desc vazia
		erros.push("A descrição está em branco.\n");
	
	// Preciso consertar essa função abaixo. Adicionado: 24/11/11
	
	// As linhas abaixo pegam as datas para fazer validação.
	// Depois no PHP tem outra layer de validação para consertar casos de inclusão digital tentando hackear ou gente com JS desligado.
	var dia1 = document.getElementsByName("dia1")[0].options[document.getElementsByName("dia1")[0].selectedIndex].value;
	var mes1 = document.getElementsByName("mes1")[0].options[document.getElementsByName("mes1")[0].selectedIndex].value;
	
	// Caso queira se divertir, no primeiro getElementsByName você pode trocar o 2 do argumento por um 1 que continua funcionando.
	var dia2 = document.getElementsByName("dia2")[0].options[document.getElementsByName("dia2")[0].selectedIndex].value;
	var mes2 = document.getElementsByName("mes2")[0].options[document.getElementsByName("mes2")[0].selectedIndex].value;
	
	errotemp = validaData(dia1, mes1)
	//alert(errotemp);
	if (errotemp != "")
		erros.push(errotemp);
	
	errotemp = validaData(dia2, mes2)
	if (errotemp != "")
		erros.push(errotemp);
	
	
	if (erros.length != 0){
		alert(erros);
		return false;
	}
	else
		return true;
}

function insere_imagem(id){
	/*\
	 *	Ó, a bagaça é a seguinte:
	 *	1- Ele seta o id da questão pro form de upload pra ser enviado ao servidor.
	 *	2- Ele pega o input seletor de arquivo e clica usando a função .click();
	 *	Quando a pessoa confirmar a seleção do arquivo, o onchange do input
	 *	que ele clicou roda a confirmaEnvio.
	 *
	 *	Isso foi feito porque o resto da função continua rodando enquanto se
	 *	escolhe o arquivo, o que faz com que ele envie antes da pessoa poder
	 *	selecionar o arquivo.
	\*/
	
	document.getElementById("gambiid").value = id;
	document.getElementById("gambiselector").click();
}

function confirmaEnvio() {
	if (confirm("Deseja realmente enviar este arquivo?")){
		document.formgambi.submit();
	}
}

function previewArquivo(falha, file_id, id) {
	if (falha != 0) {
		//alert("FALHA NO UPLOAD");
		alert(falha);
	} else {
		//alert(nome + " uploadeado com sucesso na id: "+id); //DEBUG
		
		paragrafo = document.createElement("p"); // Cria e seta o HTML pra imagem.
		paragrafo.innerHTML = "<center>Preview da imagem:<br /><img src=\"imageOutput.php?id="+file_id+"\" /></center>";
		
		document.getElementById("mostraArquivo"+id).appendChild(paragrafo); // insere
		
		document.getElementsByName("idimg_"+id)[0].value = file_id; // Seta pra botar a imagem nos dados
	}
}

function verRespostas(idsel, turma) {
	select = document.getElementById("lista_alunos_"+idsel);
	var valor = 1;
	
	for (i=0; i < select.options.length; i++){
		if (select.options[i].selected == true){
			valor = select.options[i].value.split(";");
			if (valor.length != 2){
				alert("Algo deu errado. Não sabemos o que, mas estamos felizes em lhe dizer isso em vez de falhar silenciosamente.");
				return "poop";
			} else {
				break;
			}
		}
	}
	
	if (valor == 1) { // Se nenhum está selecionado...
		alert("Nos desculpe, mas aparentemente o seu navegador não é suportado ou o código tem um defeito pernicioso.");
	}
	
	id = valor[0];
	quest = valor[1];
	
	window.location.href = "ver_respostas.php?user="+id+"&quest="+quest+"&turma="+turma;
}

function insere_video(id){
	vid = document.getElementById("video_"+id).value;
	i = vid.indexOf("?v=");
	if (i == -1) { // Se a id do vídeo não é o primeiro argumento:
		i = vid.indexOf("&v=");
		if (i == -1) {
			alert("Nosso RIdEV (Reconhecedor Inteligente de Endereços Válidos) não conseguiu descobrir a ID do seu vídeo. Por favor, envie o endereço que estava tentando usar para um desenvolvedor.");
			return 0;
		}
	}
	
	trudor = document.getElementById("vid_"+id); // NUNCA, NUNCA CHAME O TRUDOR DE GAMBI
	
	if (trudor != null){
		trudor.parentNode.removeChild(trudor);
	}
	
	// Chegou aqui? Beleza, passa pra um array pra tratar a la C!
	v = vid.split("");
	i += 3; // pula o ?v=
	endereco = ""; // não sei se precisa declarar, mas...
	while(v[i] != "&" && i < v.length){
		endereco += v[i]; // monta o endereço
		i++;
	}
	
	frame = document.createElement("iframe");
	frame.className = "youtube-player"
	frame.id = "vid_"+id;
	frame.frameBorder = 0;
	frame.height = 385;
	frame.width = 640;
	frame.src = "http://www.youtube.com/embed/" + endereco;

	document.getElementsByName("idvid_"+id)[0].value = endereco; // Seta pra botar o vidjo nos dados

	document.getElementById("mostraArquivo"+id).appendChild(frame);
}

function validaRespostas(){ // lasciate ogni speranza, voi che farà manutenzione
	form = document.forms[0];
	for (i=0; i < document.forms[0].length; i++){
		switch(form.elements[i].type){ // pra cada tipo tem uma coisa deferente
			case "radio":
				if (!form.elements[i].checked){ // se não tá marcado em algum lugar
					return confirm("Existem perguntas não respondidas ainda.\nTem certeza que deseja completar o questionário?");
				}
				break;
			case "text":
				if (form.elements[i].value == ""){
					return confirm("Existem perguntas não respondidas ainda.\nTem certeza que deseja completar o questionário?");
				}
			//case "hidden": // não é necessário
		}
	}
	return false;
}

function mudaQuest(select){
	document.getElementById("questao1").style.display = "none";
	document.getElementById("questao2").style.display = "none";
	document.getElementById("questao3").style.display = "none";
	
	document.getElementById("questao"+select.value).style.display = "block";
}

function deletarQuestao(idQuestao, turma, idDiv){
	if(confirm("Deseja realmente apagar esta questão?")){
		if (navigator.appName == "Microsoft Internet Explorer"){
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}else{
			http = new XMLHttpRequest(); // vou assumir que isso nunca vai falhar, ok?
		}

		http.abort();
		http.open("POST", "_deletaUmaUnicaPergunta.php", true);
		http.onreadystatechange=function() {
			if ((http.readyState == 4) && (http.status == 200))
				if (http.responseText != "ok"){
					alert(http.responseText);
				}else{
					a=document.getElementById("pergunta"+idDiv);
					a.style.display = "none";
				}
		}
		parametros = "questao="+idQuestao+"&turma="+turma;
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http.setRequestHeader("Content-length", parametros.length);
		http.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
		http.send(parametros);
	}
}

