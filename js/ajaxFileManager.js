var submitUploadForm = (function () {
	var uploadHandler = function(){
		var file_list = document.getElementById("caixa_arq");
		if(t = this.responseText) {
			try {
				var res = JSON.parse(t);
			}
			catch (e) {
				alert("Problema no JSON");
				console.log("JSON: " + e.message + "'"+t+"'");
				return;
			}
			if (res.erros) {
				var erro = res.erros[0];
				for(var i=1;i<res.erros.length;i+=1) {
					erro += "\n" + res.erros[i];
				};
				alert(erro);
			} else if (res.file_id && res.file_name) {
				var newfile = document.createElement("li");
				newfile.id = "liFile" + res.file_id;
				newfile.innerHTML = "<a href=\"../../downloadFile.php?id=" + res.file_id + "\">" + res.file_name +"</a>" +
					'<img align="right" src="../../images/botoes/bt_x.png" onclick="deleteFile(' + res.file_id + ');" />';
				file_list.appendChild(newfile);
			} else { 
				alert("Nao sabemos o que aconteceu, mas estamos trabalhando para descobrir");
			}
		} else {
			console.log("Sem resposta");
		}
	}

	return function (oFormElement) {
		AJAXSubmit(oFormElement,uploadHandler);
	}
})();

var deleteFile = (function () {
	var handler = function () {
		if (t = this.responseText) {
			try {
				res = JSON.parse(t)
			}
			catch (e)
			{
				console.log("JSON: " + e.message);
				alert("Ocorreu um problema");
				return;
			}
			if (res.ok) {
				if(this.elemToRemove) {
					this.elemToRemove.parentElement.removeChild(this.elemToRemove);
				}
			} else {
				if(res.error) {
					alert(res.error);
				} else {
					alert("Nao deu certo");
				}
			}
		}
	}
	return function (id) {
		if (!confirm("Tem certeza que deseja excluir o arquivo?")) {
			return;
		}
		var oAjaxReq = new XMLHttpRequest();
		oAjaxReq.elemToRemove = document.getElementById('liFile'+id);
		oAjaxReq.open("GET","../../deleteFile.php?id="+id);
		oAjaxReq.onload = handler;
		oAjaxReq.send();
	};
})();
