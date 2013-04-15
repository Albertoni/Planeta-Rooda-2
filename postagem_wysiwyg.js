var txt_font_size = new Array(1, 2, 4, 6, 8, 12);
var txt_font_size_index = 0;
var viewMode = 1; // WYSIWYG
var objContent;

var modo = 1;
var id = false;
var imageurl = false;

function enviaForm(action_url,confirma) {
	var envia = true;
	if(confirma)
		envia = confirm("Tem certeza?");
	if(envia)
		window.location = action_url;
}

function checar(){
	document.getElementById('troca_img1').checked='checked';
	document.getElementById('troca_arq1').checked='checked';
}

function gravaConteudo() {
	document.fConteudo.text.value=objContent.body.innerHTML;
	return true;
}


function doBold() {
	objContent.execCommand('bold', false, null);
}

function doItalic() {
	objContent.execCommand('italic', false, null);
}

function doUnderline() {
	objContent.execCommand('underline', false, null);
}

function doLink() {
	var wLink = window.open('selLink.php','MyWin','width=400,height=200,toolbar=0,menubar=0,status=1,scrollbars=1,resizable=1');
}

function doImage() {
	var wImagem = window.open('selImagem.php','MyWin','width=600,height=450,toolbar=0,menubar=0,status=1,scrollbars=1,resizable=1');
}

function doHTML() {
	var wImagem = window.open('insHTML.php','MyWin','width=730,height=420,toolbar=0,menubar=0,status=1,scrollbars=0,resizable=1');
}

function doSize() {
		txt_font_size_index++;
		objContent.execCommand('fontsize', false, txt_font_size[txt_font_size_index%(txt_font_size.length-1)]);
}

function doHead(hType) {
	if(hType != '')
	{
		objContent.execCommand('formatblock', false, hType);  
		doFont(selFont.options[selFont.selectedIndex].value);
	}
}

 function doTable(html) {
	html = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="top"><div align="justify"><span style="font-size:11px;">Coluna 1</span></div></td ><td  valign="top" style="padding-left:20px;"><div align="justify"><span style="font-size:11px;">Coluna 2</span></div></td></tr></table><br>';
	if(html != null)	
		var ua = navigator.appName; 
		if(ua == "Netscape") 
			objContent.execCommand('inserthtml', false, html);
		else {
			objHolder.focus();
			var range = objContent.selection.createRange();
			range.pasteHTML(html);
		}
}

function doTable2(html) {
	html = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="top"><div align="justify"><span style="font-size:11px;">Coluna 1</span></div></td ><td  valign="top" style="padding-left:10px;"><div align="justify"><span style="font-size:11px;">Coluna 2</span></div></td><td valign="top" style="padding-left:10px;"><div align="justify"><span style="font-size:11px;">Coluna 3</span></div></td></tr></table><br>';
	if(html != null)	
		var ua = navigator.appName; 
		if(ua == "Netscape") 
			objContent.execCommand('inserthtml', false, html);
		else {
			objHolder.focus();
			var range = objContent.selection.createRange();
			range.pasteHTML(html);
		}
}

function doTable3(html) {
	html = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="33%" valign="top"><div align="justify"><span style="font-size:11px;">Coluna 1</span></div></td ><td width="67%" valign="top" style="padding-left:10px;"><div align="justify"><span style="font-size:11px;">Coluna 2</span></div></td></tr></table><br>';
	if(html != null)	
		var ua = navigator.appName; 
		if(ua == "Netscape") 
			objContent.execCommand('inserthtml', false, html);
		else {
			objHolder.focus();
			var range = objContent.selection.createRange();
			range.pasteHTML(html);
		}
 }
 
 function doTable4(html) {
	html = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="67%" valign="top"><div align="justify"><span style="font-size:11px;">Coluna 1</span></div></td ><td width="33%" valign="top" style="padding-left:10px;"><div align="justify"><span style="font-size:11px;">Coluna 2</span></div></td></tr></table><br>';
	if(html != null)	
		var ua = navigator.appName; 
		if(ua == "Netscape") 
			objContent.execCommand('inserthtml', false, html);
		else {
			objHolder.focus();
			var range = objContent.selection.createRange();
			range.pasteHTML(html);
		}
}
// Fim do código do Pato para ativar HTML na área de edição



function ajusta_img() {
	if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
		$('#cont_img3').css('width','436px');
		$('#cont_img3').css('padding-right','20px');
		$('#cont_img').css('height','170px');
	}
}

// Abaixo desta linha, o João reinventou a roda.

function addLink(){
	var url = document.getElementById('addlinkurl').value;
	var text = document.getElementById('addlinktext').value;
	var link = '<a target="_blank" href="'+url+'">'+text+'</a>';
	objContent.execCommand('inserthtml', false, link); // Insere o link. Tente clicar com o botão do meio no Firefox for lulz.
	abreFechaLB();
}

function imageHTML (id){
	return '<img src="../../image_output.php?noresize=1&amp;file='+id+'" />';
}
function fileHTML(id,text){
	return '<a href="../../downloadFile.php?id='+id+'">'+text+'</a>';
}
/////// Adicionador de imagens pra galeria de postagem do blog
function addImage(){
	var e = document.getElementsByName("select_img"),l,i;
	objHolder.focus();

	l = e.length;
	for (i=0;i<l;i+=1) {
		if (e[i].checked) {
			modo = parseInt(e[i].value);
			console.log("modo:"+modo);
		}
	}
	
	switch(modo){
		case 1: // Caso 1 a id já vem na URL.
			console.log("modo1:"+modo);
			if (e = document.getElementById('cont_img1')) {

				if (f = e.getElementsByTagName("form")) {
					if (uploadAttImage) {
						uploadAttImage(f[0]);
						return;
					}
				}
			}
			abreFechaLB();
			break;
		case 2: // Pegando da textbox do link.
			var imageurl = document.getElementById('imagefromurl').value;
			imageurl = '<img src="' + encodeURI(imageurl) + '" />'; // TIRE TODOS OS " PRA NÃO DEIXAR O CARA FAZER O QUE QUISER COM A TAG
			objContent.execCommand('inserthtml', false, imageurl); // Insere a imagem!
			abreFechaLB();
			break;
		case 3:
			if (id != false){
				var image = imageHTML(id);
				objContent.execCommand('inserthtml', false, image); // Insere a imagem!
			
				abreFechaLB(); // Uma vez na vida o código do Giovani me deixou feliz.
			} else {
				alert("Por favor clique em uma imagem da galeria para selecionar ela antes.");
			}
			break;
		default:
			alert("Por favor avise os desenvolvedores que alguma coisa deu errado em 'addImage@postagem_wysiwyg.js'.");
	}
}

function mostraPreviewImagem(falha, filename, location){
	if (falha == "0"){
		editavel.document.getElementsByTagName("body")[0].innerHTML = '<p>Preview da imagem '+filename+'</p><img src="'+location+'" />';
		editavel.document.getElementsByTagName("body")[0].style = "display:block";
		imageurl = '<img src="'+location+'" />';
		objContent.execCommand('inserthtml', false, imageurl);
	} else {
		editavel.document.getElementsByTagName("body")[0].innerHTML = falha;
	}
}

function fromgallery(imageid){
	if (id != false){
		var img = document.getElementById('galeria'+id);
		img.style.borderColor = "#AAAAAA";
		img.style.borderWidth = "1px"; // Devolve o antigo ao estado normal. Sim, dá um erro na primeira vez que selecionar a imagem, mas ninguém vai notar.
		img.style.margin = "3px";
	}
	id = imageid;
	var img = document.getElementById('galeria'+id);
	img.style.borderStyle = "solid";
	img.style.borderColor = "red";
	img.style.borderWidth = "3px"; // Pinta o novo.
	img.style.margin = "1px";
}

///////////////////////////////////////////////////////// Arquivos!

var arquivos = [];
var arquivos_mode = 1;

function addRemove(arquivo_id, nome_arquivo) { // Funciona no Firefox. 
	if (document.getElementById('file'+arquivo_id).checked == true) {
		arquivos.push(new Array(arquivo_id, nome_arquivo)); // Matrix within a matrix. Matrinception.
	} else if (document.getElementById('file'+arquivo_id).checked == false) {
		for (i=0; i<arquivos.length; i++) {
			if (arquivos[i][0] == arquivo_id)
				arquivos.splice(i, 1);
		}
	} else
		alert("ERRO na addRemove do postagem_wysiwyg.js! Culpe os raios cósmicos!");
}

function previewArquivo(falha, filename, id){
	if (falha == "0"){
		previewarquivos.document.getElementsByTagName("body")[0].innerHTML = '<br /><p>Arquivo será exibido em um link como o a seguir:</p><a href="downloadFile.php?id='+id+'">'+filename+'</a>';
		previewarquivos.document.getElementsByTagName("body")[0].style = "display:block";
		imageurl = '<a href="../../downloadFile.php?id='+id+'">'+filename+'</a>';
		objContent.execCommand('inserthtml', false, imageurl);
	} else {
		previewarquivos.document.getElementsByTagName("body")[0].innerHTML = falha;
	}
}

function arquivoInsert(){
	var arq,l,i,name,id;
	objHolder.focus();
	if (arquivos_mode === 1) { // Vindo do upload, faço isso mais tarde
		e = document.getElementById('cont_arq1');
		if(e) {
			f = e.getElementsByTagName('form');
			if (f && (f.length > 0)) {
				if (uploadAttFile) {
					console.log(f[0]);
					uploadAttFile(f[0]);
					return;
				}
			}
		}
		abreFechaLB();
	} else { // Postando link já uploadeado.
		arquivos = [];
		
		arq = document.getElementsByName("arquivo");
		l = arq.length;
		
		for (i=0;i<3;i+=1) {
			_id = null;
			_name = null;
			if (arq[i].checked) {
				_id = arq[i].value;
				_name = document.getElementById("fileN"+_id).innerText;
				console.log(_name);
				arquivos.push([_id, _name]);
			}
		}
		if (arquivos.length <= 0) {
			alert("Por favor, selecione pelo menos um arquivo para ser inserido.");
		}
		else {
			url = [];
			for (i=0; i<arquivos.length; i++) {
				url.push(fileHTML(arquivos[i][0], arquivos[i][1]))
			}
			objContent.execCommand('inserthtml', false, url.join("<br /> "));
			abreFechaLB();
		}
	}
}

/*\
 *	Criador de inputs de arquivos fake.
 *
 *	3 parâmetros, sendo 2 obrigatórios: a imagem o <input file> e um terceiro 
 *	que recebe um input file para receber o caminho para o arquivo a cada mudança no input file original.
 *	Vide uploadfileform.php para exemplo
\*/

function fakeFile(imagename, input, pathfield) {
	var image = document.getElementById(imagename);
	var input = document.getElementById(input);
	var pathfield = document.getElementById(pathfield);
	
	var w = image.width, h = image.height, s = parseInt(w/4.2), wrapper = document.createElement('div');
	wrapper.style.cssText = "position:absolute; width:"+w+"px; height:"+h+"px; z-index:100; overflow:hidden;";
	wrapper.onmouseover = function(){document.getElementById(imagename).src ='../../images/botoes/bt_procurar_arquivo2.png';}; // Seta a troca de aparência do botão
	wrapper.onmouseout= function(){document.getElementById(imagename).src ='../../images/botoes/bt_procurar_arquivo.png';};
	input.style.cssText = "position:absolute; width:"+w+"px; height:"+h+"px; top:0; right:0; font-size:"+s+"px; filter:alpha(opacity=0); opacity:0; z-index:101;";
	image.parentNode.insertBefore(wrapper, image);
	wrapper.appendChild(image);
	wrapper.appendChild(input);
	return wrapper;
}

// Troca os valores pra caixa falsa ficar atualizada.
function trocador(falso, original) {
	document.getElementById(falso).value = document.getElementById(original).value;
}

// Adicionador de eventos para a fakeFile funcionar.
function addEvent(elm, evType, fn, useCapture) {
	if (elm.addEventListener) { 
		elm.addEventListener(evType, fn, useCapture); 
		return true; 
	}
	else if (elm.attachEvent) { 
		var r = elm.attachEvent('on' + evType, fn); 
		return r; 
	}
	else {
		elm['on' + evType] = fn;
	}
}

/////////////////////////// FIM CRIADOR DE INPUTS FAKE ///////////////////////////
var x=0,y=0;

document.onmousemove = showMousePos;

function mostraDescri(){
	$('#descricao').css('display','block');
	$('#descricao').css('left',x + 10 +'px');
	$('#descricao').css('top',y - 10 +'px');
}

function getMousePosition(e){
	try{
	return e.pageX ? {'x':e.pageX, 'y':e.pageY} : {'x':e.clientX + document.documentElement.scrollLeft + document.body.scrollLeft, 'y':e.clientY + document.documentElement.scrollTop + document.body.scrollTop};
	}catch(erro){
	return {'x':0, 'y':0};
	}
}

function showMousePos(e){
	if (!e) e = event;
	var mp = getMousePosition(e);
	x=mp.x;
	y=mp.y;
}

$(document).ready(function(){
	$('.tool_bt').mouseover(function(){
		mostraDescri();
		switch(this.id){
			case 'alt_negrito':
				$('#descricao').html('Negrito');
				break;
			case 'alt_italico':
				$('#descricao').html('Itálico');
				break;
			case 'alt_sublinhado':
				$('#descricao').html('Sublinhado');
				break;
			case 'alt_tamanho':
				$('#descricao').html('Tamanho');
				break;
			case 'alt_imagem':
				$('#descricao').html('Inserir imagem');
				break;
			case 'alt_link':
				$('#descricao').html('Inserir link');
				break;
			case 'alt_arquivo':
				$('#descricao').html('Anexar arquivo');
				break;
			
			// USADOS NO CONSTRUTOR DE PÁGINAS DO AULAS
			case 'par_tit':
				$('#descricao').html('Parágrafo com Título');
				break;
			case 'par_img':
				$('#descricao').html('Parágrafo com Título e Imagem');
				break;
			case 'only_img':
				$('#descricao').html('Somente imagem');
				break;
			case 'only_tit':
				$('#descricao').html('Somente título');
				break;
			case 'only_par':
				$('#descricao').html('Somente parágrafo');
				break;
			case 'cust_html':
				$('#descricao').html('HTML customizado');
				break;
		}
	});
	
	$('.tool_bt').mouseout(function(){
		$('#descricao').css('display','none');
	});
	
	var marcado = true;
	var marcado2 = true;
	
	$('.tool_bt').click(function(){
		$('#light_box').css('height','360px');
		$('#light_box').css('width','500px');
		switch (this.id){
			case 'alt_imagem':
				limpaLbox();
				$('#imagem_lbox').css('display','block');
				mode=1;
				abreFechaLB();
				if (marcado){
					limpaContImg();
					$('#cont_img1').css('display','block');
				}
				marcado = false;
				break;
		
			case 'alt_link':
				limpaLbox();
				$('#link_lbox').css('display','block');
				abreFechaLB();
				break;
		
			case 'alt_arquivo':
				limpaLbox();
				$('#arquivo_lbox').css('display','block');
				abreFechaLB();
				if (marcado2){
					limpaContArq();
					$('#cont_arq1').css('display','block');
				}
				marcado2 = false;
				break;
			
			case 'cust_html':
				limpaLbox();
				document.getElementById('customHTML').value = objContent.body.innerHTML;
				$('#customHTML_lbox').css('display','block');
				abreFechaLB();
				break;
		}
	});
	
	function limpaLbox(){
		$('#imagem_lbox').css('display','none');
		$('#link_lbox').css('display','none');
		$('#arquivo_lbox').css('display','none');
	}
	
	
	function limpaContImg(){
		$('#cont_img1').css('display','none');
		$('#cont_img2').css('display','none');
		$('#cont_img3').css('display','none');
	}
	
	function limpaContArq(){
		$('#cont_arq1').css('display','none');
		$('#cont_arq2').css('display','none');
	}
	
	$('.select_img').click(function(){
		if (this.id == 'troca_img1'){
			limpaContImg();
			$('#cont_img1').css('display','block');
		}
		if (this.id == 'troca_img2'){
			limpaContImg();
			$('#cont_img2').css('display','block');
		}
		if (this.id == 'troca_img3'){
			limpaContImg();
			$('#cont_img3').css('display','block');
		}
	});
	
	$('.select_arq').click(function(){
		if (this.id == 'troca_arq1'){
			limpaContArq();
			$('#cont_arq1').css('display','block');
		}
		if (this.id == 'troca_arq2'){
			limpaContArq();
			$('#cont_arq2').css('display','block');
		}
	});
	
	$('#divLinkAdicionarArquivo').click(function() {
		$('#liAdicionarArquivo').css('display','block');
	});
	
	$('#divLinkAdicionarArquivo').toggle(
	function() {
		$('#liAdicionarArquivo').css('display','block');
		$('#divLinkAdicionarArquivo').html('ocultar');
	},
	function() {
		$('#liAdicionarArquivo').css('display','none');
		$('#divLinkAdicionarArquivo').html('adicionar');
	});
});


