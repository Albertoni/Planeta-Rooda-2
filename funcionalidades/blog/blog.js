var guto = 0; //a varivel guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'

function deletar_post (blog, id, turma) {
	if (confirm("Você realmente quer deletar esse post?")) {
		window.location = "deletar_post.php?blog_id=" + blog + "&post_id=" + id + "&turma=" + turma;
	}
};

// Vide lista de posts na sidebar do blog
function abre_topico(id) {
	if ($('#topico_oculto' + id).css('display') == 'none'){
		$('#topico_oculto' + id).css('display','block');
	}
	else{
		$('#topico_oculto' + id).css('display','none');
	}
};


/*\
 *	Atualizador de página via AJAX
 *	Autor: João
 *	Data: 17-18/03/11
\*/
function imprimeNomeArquivo (sucessoUpload, nome, link) {
	if (sucessoUpload == 1) {
		// botar na página
		//alert(nome+' , '+link);
		$("#caixa_arq").append("<a href='"+link+"' target='_blank'> "+nome+" </a> <div class='bts_caixa'><img class='apagar' src='images/botoes/bt_x.png' /></div>");
	} else {
		// sei lá o que faz com upload falhado, ver com o Bernardo?
		// 6 meses depois: Fica assim mesmo. baiano.jpg
		alert('O upload do arquivo '+nome+' falhou. Possivelmente ele já existe ou a conexão está com problemas. Tente renomear o arquivo.');
	}
	return true;
}



function killFile(vaimorre){
	if (confirm("Você realmente quer deletar esse arquivo?")) {
		window.location = "deletar_arquivo.php?kill=" + vaimorre;
	}
}



$(document).ready(function(){
	var cor_original = $(".bloco").css("border-top-color");
	try{
		var fala_original = document.getElementById("balao").innerHTML;
	}catch(e){
	}
	
	//funes para trocar as falas do ajudante e pintar o bloco selecionado
	$("body").click(function(){ 
			if (guto != 1){ //a varivel guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'
				$(".bloco").css("border-top-color",cor_original);
				$("#balao").html(fala_original);
			}
			guto = 0; //a varivel guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'
	});
	
	$('#abre_mes').click(function(){
		if ($('#mes_oculto').css('display') == 'none'){
			$('#mes_oculto').css('display','block');
		}
		else{
			$('#mes_oculto').css('display','none');
		}
	});
	
	$('#abre_topico').click(function(){
		if ($('#topico_oculto').css('display') == 'none'){
			$('#topico_oculto').css('display','block');
		}
		else{
			$('#topico_oculto').css('display','none');
		}
	});
	
	$(".bloco").click(function(){
			switch (this.id){
				case "ident":
					$("#balao").html("Aqui, você pode encontra um espaço para escrita pessoal onde pode compartilhar diversos assuntos com seus colegas e permitir que eles, além de visualizar, publiquem comentários em suas postagens.");
				break;
				case "perfil":
					$("#balao").html("Aqui você encontra o nome e o avatar do dono ou os donos do blog.");
				break;
				case "post":
					$("#balao").html("São pequenos textos escritos pelo autor do blog que podem conter imagens, vídeos, arquivos anexados e <i>links</i>. Podem ser comentados por outras pessoas, desde que estas façam login.");
				break;
				case "link":
					$("#balao").html("Nos <i>links</i>, é possível ver os endereços de sites que foram adicionados como sugestão para visitação pelo autor do blog.");
				break;
				case "tag":
					$("#balao").html("Nas tags, você encontra as palavras-chave que foram preenchidas na criação de um <i>post</i> e aparecem em diferentes <i>posts</i>");
				break;
				case "arquivos":
					$("#balao").html("Nos arquivos, pode-se visualizar os arquivos que foram, ou não, anexados em postagens anteriores. Além disso, é possível ver a que postagem esse arquivo está relacionado e excluí-lo.");
				break;
			}
			$("#perfil").css("border-top-color",cor_original);
			$("#post").css("border-top-color",cor_original);
			$("#ident").css("border-top-color",cor_original);
			$("#link").css("border-top-color",cor_original);
			$("#tag").css("border-top-color",cor_original);
			$("#arquivos").css("border-top-color",cor_original);
			$(this).css("border-top-color","#00A99D");
			guto = 1;			//a varivel guto serve pra que o evento de click do 'body' seja ativado sem influenciar no click do '.bloco'
	});
	
	$("#toggle_post").click(function(){
		$("#caixa_post").toggle('blind');
		$('#toggle_post').text($('#toggle_post').text() == '▼' ? '►' : '▼');
	});	
	$("#toggle_perfil").click(function(){
		$("#caixa_perfil").toggle('blind');
		$('#toggle_perfil').text($('#toggle_perfil').text() == '▼' ? '►' : '▼');
	});	
	$("#toggle_arq").click(function(){
		$("#caixa_arq").toggle('blind');
		$('#toggle_arq').text($('#toggle_arq').text() == '▼' ? '►' : '▼');
	});	
	$("#toggle_link").click(function(){
		$("#caixa_link").toggle('blind');
		$('#toggle_link').text($('#toggle_link').text() == '▼' ? '►' : '▼');
	});	
	$("#toggle_tag").click(function(){
		$("#caixa_tag").toggle('blind');
		$('#toggle_tag').text($('#toggle_tag').text() == '▼' ? '►' : '▼');
	});
	
	try{
		$("#dir").sortable({distance: 15, placeholder: 'move_bloco', handle: 'h1'});
	}catch(e){
	}
	
	$('.apagar').mouseout(function(){
		$('#descricao').css('display','none');
	});
	$('#caixa_arq a').mouseout(function(){
		$('#descricao').css('display','none');
	});
	$('#caixa_tag a').mouseout(function(){
		$('#descricao').css('display','none');
	});
	$('#caixa_link a').mouseout(function(){
		$('#descricao').css('display','none');
	});
	
	$('.apagar').mouseover(function(){
		$('#descricao').html('excluir');
		mostraDescri();
		$('#descricao').css('display','block');
	});
	$('#caixa_tag a').mouseover(function(){
		mostraDescri();
		$('#descricao').html('ver somente posts com essa tag');
		$('#descricao').css('display','block');
	});
	$('#caixa_arq a').mouseover(function(){
		mostraDescri();
		$('#descricao').html('baixar arquivo');
		$('#descricao').css('display','block');
	});
	$('#caixa_link a').mouseover(function(){
		mostraDescri();
		$('#descricao').html('acessar link');
		$('#descricao').css('display','block');
	});
});

function deletaBlog(id){
	if (confirm("Tem certeza que deseja deletar o blog? Essa ação não pode ser desfeita.")){
		if (window.XMLHttpRequest){ 
			ajax = new XMLHttpRequest();
			ajax.open("GET", "deletablog.php?id="+id, false);
			ajax.send();
			if (ajax.responseText == "el blog esta muerto"){
				tigre = document.getElementById("blog"+id); // no gambi here ok
				tigre.parentNode.removeChild(tigre);
			} else {
				alert(ajax.responseText);
			}
		} else {
			alert("Você está usando um navegador não suportado. O blog poderá não ser deletado, por favor utilize Firefox para ter certeza da deleção.");
			dani = document.createElement("iframe"); // NÃO CHAME ELE DE GAMBI
			dani.src = "deletablog.php?id="+id;
			tigre = document.getElementById("blog"+id); // no gambi here ok
			tigre.parentNode.removeChild(tigre);
		}
	}
}

function editaBlog(id){
	window.location = "criar_blog_coletivo.php?editId="+id;
}
