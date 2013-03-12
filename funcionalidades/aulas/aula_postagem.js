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


function gravaConteudo() {
	document.fConteudo.aula.value=objContent.body.innerHTML;
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
			objContent.focus();
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
			objContent.focus();
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
			objContent.focus();
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
			objContent.focus();
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
}

/////// Adicionador de imagens pra galeria de postagem do blog
function addImage(){
	switch(modo){
		case 1: // Caso 1 a id já vem na URL.
			abreFechaLB();
			break;
		case 2: // Pegando da textbox do link.
			var imageurl = document.getElementById('imagefromurl').value;
			imageurl = '<img src="' + imageurl.replace('"', '&quot;') + '" />'; // TIRE TODOS OS " PRA NÃO DEIXAR O CARA FAZER O QUE QUISER COM A TAG
			objContent.execCommand('inserthtml', false, imageurl); // Insere a imagem!
			abreFechaLB();
			break;
		case 3:
			if (id != false){
				var image = '<img src="image_output.php?file=' + String(id).replace('"', '&quot;') + '" />';
				objContent.execCommand('inserthtml', false, image); // Insere a imagem!
				
				abreFechaLB(); // Uma vez na vida o código do Giovani me deixou feliz.
			} else {
				alert("Por favor clique em uma imagem da galeria para selecionar ela antes.");
			}
			break;
		default:
			alert("Por favor avise os desenvolvedores que alguma coisa deu errado em 'addImage@blog_postagem.js'.");
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
		document.getElementById('galeria'+id).style.borderColor = "#AAAAAA";
		document.getElementById('galeria'+id).style.borderWidth = "1px"; // Devolve o antigo ao estado normal. Sim, dá um erro na primeira vez que selecionar a imagem, mas ninguém vai notar.
	}
	id = imageid;
	document.getElementById('galeria'+id).style.borderColor = "red";
	document.getElementById('galeria'+id).style.borderWidth = "thick"; // Pinta o novo.
}

///////////////////////////////////////////////////////// Arquivos!

var arquivos = new Array();
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
		alert("ERRO na addRemove do blog_postagem.js! Culpe os raios cósmicos!");
}

function previewArquivo(falha, filename, id){
	if (falha == "0"){
		previewarquivos.document.getElementsByTagName("body")[0].innerHTML = '<br /><p>Arquivo será exibido em um link como o a seguir:</p><a href="downloadFile.php?id='+id+'">'+filename+'</a>';
		previewarquivos.document.getElementsByTagName("body")[0].style = "display:block";
		imageurl = '<a href="downloadFile.php?id='+id+'">'+filename+'</a>';
		objContent.execCommand('inserthtml', false, imageurl);
	} else {
		previewarquivos.document.getElementsByTagName("body")[0].innerHTML = falha;
	}
}

function arquivoInsert(){
	if (arquivos_mode == 1) { // Vindo do upload. Will make it later k?
		abreFechaLB();
	} else { // Postando link já uploadeado.
		if (arquivos.length <= 0)
			alert("Por favor, selecione pelo menos um arquivo para ser inserido.");
		else {
			for (i=0; i<arquivos.length; i++) {
				url = '<a href="downloadFile.php?id=' + arquivos[i][0] + '">' + arquivos[i][1] + "</a><br />";
				objContent.execCommand('inserthtml', false, url);
			}
			abreFechaLB();
		}
	}
}
