//Constantes. Esteja certo de que estão de acordo com o HTML.
var CLASSE_DAS_DIVS_TABELAS = "gerenciamentoFuncionalidade";
var CLASSE_DAS_LINHAS_DAS_DIVS_TABELAS = "opcaoGerenciamento";


/****************************************************************************************************
*									CRIAÇÃO DO HTML
***************************************************************************************************/

/**
* Com base em um array cujos elementos são arrays, esta função seta os estados dos checkboxes cujos nomes estão no array para o estado que se quer.
* @param _dadosCheckboxes Array cujos elementos são pareados. Todo elemento par, iniciando em 0, é o nome de uma checkbox do HTML.
*						  Todo elemento ímpar é o valor desta checkbox, se está marcada (true) ou não (false).
*/
function definirEstadosCheckboxes(_dadosCheckboxes){
	var index;
	var checkbox;
	var dadosCheckboxes = _dadosCheckboxes.split(",");
    console.log(dadosCheckboxes);
	for(index=0; index<dadosCheckboxes.length; index+=2){
		checkbox = getElementoPorNome(dadosCheckboxes[index]);
		if(dadosCheckboxes[index+1] == 'true'){
			checkbox.checked = true;
		} else {
			checkbox.checked = false;
		}
	}
}

/**
* Cria todas as checkboxes com base nas classes do HTML.
* O funcionamento é o seguinte:
*	(1) Será criada uma tabela para cada div da classe gerenciamentoFuncionalidade, o nome da classe será o nome base das checkboxes desta tabela.
*	(2) Todos os elementos th da tabela serão considerados colunas e as checkboxes nessas colunas terão no tipo o nome da classe do th ao qual pertencem.
*	(3) Para cada tr é criada uma linha de checkboxes, cujos nomes serão o conteúdo das tds da classe opcaoGerenciamento dentro da tr.
*
* @param _checkboxesQuePodemExistir  Um objeto JavaScript que contém as regras de quais
*           elementos podem ou não ser oferecidos para o professor escolher. Vide ata
*           de 8-9 de maio de 2014.
*/
function criarCheckboxes(_checkboxesQuePodemExistir){
	var divsDaClasse = getTagsFilhasDeComClasse("div", document, CLASSE_DAS_DIVS_TABELAS);
	var divDasLinhas;
	var linhasPreenchiveis;
	var linhaDasCelulas;
	var celulasCabecalhosColunas;
	
	var index;
	var index2;
	for(index=0; index<divsDaClasse.length; index++){
		linhasPreenchiveis = getTagsFilhasDeComClasse("tr", divsDaClasse[index], "");
		
		celulasCabecalhosColunas = getTagsFilhasDeComClasse("th", divsDaClasse[index], "");
		
		var classesCelulasCabecalhosColunas = new Array();
		for(index2=0; index2<celulasCabecalhosColunas.length; index2++){
			celulaCabecalhoColuna = celulasCabecalhosColunas[index2];
			if(celulaCabecalhoColuna.className != ''){
				classesCelulasCabecalhosColunas.push(celulaCabecalhoColuna.className);
			}
		}
		
		var linha=0;
		for(linha=1; linha<linhasPreenchiveis.length; linha++){
			linhaDasCelulas = linhasPreenchiveis[linha];
			
			var celulaTipoCelulas = getTagsFilhasDeComClasse("td", linhaDasCelulas, CLASSE_DAS_LINHAS_DAS_DIVS_TABELAS)[0];
			var tipoCelulas = celulaTipoCelulas.innerHTML;
			
			var estadosCheckboxesLinhas = new Array();
			for(index2=0; index2<celulasCabecalhosColunas.length; index2++){
				nomeCheckbox = celulasCabecalhosColunas[index2];
				estadosCheckboxesLinhas.push(false);
			}
			
			var base = divsDaClasse[index].id;
			var tipo = tipoCelulas;
			var nomes = classesCelulasCabecalhosColunas;
			var estados = estadosCheckboxesLinhas;
			criarLinhaCheckboxesComNomes(linhaDasCelulas, base, tipo, nomes, estados, _checkboxesQuePodemExistir);
		}
	}
}

/**
* Cria uma checkbox no HTML em que é chamada.
* A checkbox é criada com um nome do tipo '_base'_'_tipo'_'nome da checkbox'.
* Exemplo: base_tipo_nome
* A checkbox é criada em uma nova coluna de uma tabela.
* @param _base Início da propriedade name da checkbox.
* @param _tipo Meio da propriedade name da checkbox.
* @param _nome Fim da propriedade name da checkbox.
* @param _temCheck Booleano indicando se a checkbox deve ter check.
*/
function criarCheckbox(_base, _tipo, _nome, _temCheck){
	var nomeCheckbox = _base+"_"+_tipo+"_"+_nome;
	var celulaTabela = document.createElement('td');
	var elementoInput = document.createElement('input');
	
	celulaTabela.align = "center";
	elementoInput.type = 'checkbox';
	elementoInput.name = nomeCheckbox;


	if(_temCheck){
		elementoInput.checked = true;
	}else{
		elementoInput.checked = false;
	}
	
	if(_nome === "todos"){
		elementoInput.setAttribute("onclick", "marcaLinha('"+_base+"_"+_tipo+"_')");
	}
	
	celulaTabela.appendChild(elementoInput);
	return celulaTabela;
}

/**
* Cria um grupo de checkboxes com os nomes passados.
* Todas checkboxes são criadas como colunas de uma mesma linha de uma tabela.
* @param _pai Elemento em que são jogadas as checkboxes.
* @param _base Nome base. Vide criarCheckbox.
* @param _tipo Nome do tipo. Vide criarCheckbox.
* @param _nomes Array de nomes de checkboxes. Serão criadas tantas checkboxes
*			quantos nomes houver neste array.
* @param _estadoCheckboxes Array de booleanos que indicam se as checkboxes devem
*			estar 'checadas'. A relação com _nomes é direta. Assim, _estadoCheckboxes[0]
*			indica o estado da checkbox que tem o nome _nomes[0].
* @param _checkboxesQuePodemExistir Um objeto JavaScript que contém as regras de quais
*           elementos podem ou não ser oferecidos para o professor escolher. Vide ata
*           de 8-9 de maio de 2014.
* @see criarCheckbox
*/
function criarLinhaCheckboxesComNomes(_pai, _base, _tipo, _nomes, _estadoCheckboxes, _checkboxesQuePodemExistir){
	var posicaoCheckbox = 0;
	for(posicaoCheckbox=0; posicaoCheckbox<_nomes.length; posicaoCheckbox++){
        /*console.log(_pai);
        console.log(_base);
        console.log(_tipo);
        console.log(_nomes);*/
        console.log(_checkboxesQuePodemExistir[_base + "_" + _tipo].indexOf(_nomes[posicaoCheckbox]));
        if(_checkboxesQuePodemExistir[_base + "_" + _tipo] !== undefined)
        if(_checkboxesQuePodemExistir[_base + "_" + _tipo].indexOf(_nomes[posicaoCheckbox]) != -1){
		    _pai.appendChild(criarCheckbox(_base, _tipo, _nomes[posicaoCheckbox], _estadoCheckboxes[posicaoCheckbox]));
        }else{
            _pai.appendChild(document.createElement('td'));
        }
    }
}

function verificaPossibilidadeAcessoFuncionalidade(){

}
/**
* Dada a id de um objeto, troca sua visibilidade. Isto é:
*	+ se o objeto estiver visível, torna-se invisível
*	+ se o objeto estiver invisível, torna-se visível
* @param _idObjeto Id do objeto cuja visibilidade será trocada.
*/
function toggleVisibilidade(_idObjeto){
	var elementoComId = document.getElementById(_idObjeto);
	if(elementoComId.style.display == 'none'){
		elementoComId.style.display = '';
	} else {
		elementoComId.style.display = 'none';
	}
}

/****************************************************************************************************
*									PREPARAÇÃO PARA GRAVAR NO BANCO DE DADOS
***************************************************************************************************/

/**
* Cria um array com os nomes das checkboxes existentes no HTML.
* @return Array em que cada elemento contém o nome de uma checkbox do HTML.
* ATENÇÃO: só irá retornar os nomes das checkboxes DEPOIS que elas forem criadas!
*/
function getNomesCheckboxesCriadas(){
	var nomesCheckboxes = new Array();
	var inputs = getTagsFilhasDeComClasse("input", document, "");
	var checkboxes = new Array();
	var index;
	for(index=0; index<inputs.length; index++){
		if(inputs[index].type == "checkbox"){
			checkboxes.push(inputs[index]);
		}
	}
	for(index=0; index<checkboxes.length; index++){
		nomesCheckboxes.push(checkboxes[index].name);
	}
	return nomesCheckboxes;
}

/**
* Lê as checkboxes criadas, relacionando seus nomes com seus estados.
* @return Array em que cada elemento é um array composto por dois elementos:
*			- O primeiro é o nome da checkbox (atributo name)
*			- O segundo é seu estado. True se estiver marcada, false caso contrário.
*/
function getValoresCheckboxes(){
	var valoresCheckboxes = new Array();
	var nomesCheckboxes = getNomesCheckboxesCriadas();
	var index;
	var checkbox;
	for(index=0; index<nomesCheckboxes.length; index++){
		checkbox = getElementoPorNome(nomesCheckboxes[index]);
		valoresCheckboxes.push(new Array(checkbox.name, checkbox.checked));
	}
	return valoresCheckboxes;
}

/**
* Cria um input cujo type é o conteúdo de todas checkboxes.
* @see getValoresCheckboxes().
*/
function criarInputComConteudoDasCheckboxes(){
	var elementoInput = document.createElement('input');
	elementoInput.name = "conteudoCheckboxes";
	elementoInput.type = "hidden";
	elementoInput.value = getValoresCheckboxes();
	var tagForm = getTagsFilhasDeComClasse("form", document, "")[0];
	tagForm.appendChild(elementoInput);
	document.forms['salvar_BD'].submit()
}


/****************************************************************************************************
*												UTILIDADES
***************************************************************************************************/

/**
* @param _tag Tags a serem procuradas.
* @param _elementoRaiz Elemento a partir do qual faz-se a busca.
* @param _nomeClasse Nome da classe das tags procuradas. Se for "", serão retornadas tags de qualquer classe.
* @return Array com todas as divs da classe dada no documento.
* ATENÇÃO: esta função deve ser chamada DEPOIS de declaração de todas as tags do tipo passado (_tag) no documento html.
*/
function getTagsFilhasDeComClasse(_tag, _elementoRaiz, _nomeClasse){
	var divs = _elementoRaiz.getElementsByTagName(_tag);
	var divsDaClasse = new Array();
	
	var index;
	for(index=0; index<divs.length; index++){
		if(_nomeClasse == "" || divs.item(index).className == _nomeClasse){
			divsDaClasse.push(divs.item(index));
		}
	}
	
	return divsDaClasse;
}

/**
* @param _nome Atributo name de elemento do HTML a ser procurado.
* @return Se houver elemento com o name passado, retorna o próprio. Caso contrário, retorna "".
*		  Quando há, sempre retorna a primeira ocorrência.
* ATENÇÃO: retorar somente os elementos que já foram lidos no momento da chamada!
*/
function getElementoPorNome(_nome){
	var elementoEncontrado = "";
	var elementosComNome = document.getElementsByName(_nome);
	if(0 < elementosComNome.length){
		elementoEncontrado = elementosComNome[0];
	}
	return elementoEncontrado;
}

/**
* @param _base Nome da funcionalidade concatenado com a ação. Ex: "pergunta_Criar Questionário_"
*/
function marcaLinha(_base){
	var possibilidades = ["professor", "monitor", "aluno"];
	trocaPara = document.getElementsByName(_base+"todos")[0].checked; // sempre existe só um com o mesmo nome
	
	for(indice in possibilidades){
		document.getElementsByName(_base + possibilidades[indice])[0].checked = trocaPara;
	}
}
