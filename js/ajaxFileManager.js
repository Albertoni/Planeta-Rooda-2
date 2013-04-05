var submitUploadForm = (function () {
	var uploadHandler = function(){
		var file_list = document.getElementById("caixa_arq");
		if(t = this.responseText) {
			try {
				var res = JSON.parse(t);
			}
			catch (e) {
				alert("Problema no JSON");
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
			alert("nada");
		}
	}

	return function (oFormElement) {
		AJAXSubmit(oFormElement,uploadHandler);
	}
})();
