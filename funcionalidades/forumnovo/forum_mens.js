var http_mens = false;

if (navigator.appName == "Microsoft Internet Explorer"){
	http_mens = new ActiveXObject("Microsoft.XMLhttp_mens");
}else{
	http_mens = new XMLHttpRequest();
}

function enviaMens(){
	var parametros = "topico=" + encodeURI("-1");
	parametros += "&pai=" + encodeURI(document.getElementById('msg_pai').value);
	parametros += "&turma=" + encodeURI(document.getElementById('msg_fid').value);
	parametros += "&criador=" + encodeURI(document.getElementById('msg_criador').value);
	parametros += "&msg_conteudo=" + encodeURI(document.getElementById('msg_txt').value);
	parametros += "&ajax=1";

	http_mens.abort();
	http_mens.open("POST", "forum_salva_topico.php", true);
	http_mens.onreadystatechange=function() {
		if ((http_mens.readyState == 4)&& (http_mens.status == 200 )) {
			document.getElementById("dinamica").innerHTML = http_mens.responseText;
		}
	}
	http_mens.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_mens.setRequestHeader("Content-length", parametros.length);
	http_mens.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
	http_mens.send(parametros);
}

function escondeNMsg(){
	document.getElementById('msg_txt').value = "";
	document.getElementById('msg_txt').innerHTML = "";
	document.getElementById('nova_mensagem').style.display = "none";

}

function responder(id){
	if (document.getElementById('li_resposta_'+id).style.display == "none")
		document.getElementById('li_resposta_'+id).style.display = "block";
	else
		document.getElementById('li_resposta_'+id).style.display = "none";
}

function cancelarRsp(id){
		document.getElementById('li_resposta_'+id).style.display = "none";
}

function enviarRsp(forumId,id){
	var parametros = "topico=" + encodeURI("-1");
	parametros += "&pai=" + encodeURI(id);
	parametros += "&turma=" + encodeURI(forumId);
	parametros += "&msg_conteudo=" + encodeURI(document.getElementById('msg_txt_'+id).value);
	parametros += "&ajax=1";
	http_mens.abort();
	http_mens.open("POST", "forum_salva_topico.php", true);
	http_mens.onreadystatechange=function() {
		if ((http_mens.readyState == 4)&& (http_mens.status == 200 )) {
			cancelarRsp(id);
			document.getElementById("dinamica").innerHTML = http_mens.responseText;
		}
	}
	http_mens.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_mens.setRequestHeader("Content-length", parametros.length);
	http_mens.setRequestHeader('Content-Type', "application/x-www-form-urlencoded; charset=utf-8");
	http_mens.send(parametros);
}
