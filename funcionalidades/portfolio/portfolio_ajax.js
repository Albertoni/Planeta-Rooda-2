var deletePost = (function () {
	function handler() {
		var t, res, e, post_div;
		if (this.readyState !== this.DONE) {
			// requisição em andamento, não fazer nada.
			return;
		}
		if (this.status !== 200) {
			if (this.status >= 500) {
				ROODA.ui.alert("Problema no servidor");
				return;
			} else {
				ROODA.ui.alert("Não foi possivel contatar o servidor.\nVerifique sua conexão com a internet.");
			}
			return;
		}
		// OK
		t = this.responseText;
		if (t) {
			try {
				res = JSON.parse(t);
			}
			catch (e) {
				ROODA.ui.alert("Erro desconhecido (0xTTYLGB)");
				console.log("JSON: " + e.message + ":\n" + t);
				return;
			}
			if (res.ok) {
                // pega o elemento do post que foi apagado
				post_div = document.getElementById("postDiv" + res.id);
                // se ele existir, apaga do DOM.
				if (post_div) {
					ROODA.dom.purgeElement(post_div);
					post_div = null;
				}
			} else {
				if (res.errors) {
					ROODA.ui.alert(res.errors.join("<br>\n"));
				} else {
					ROODA.ui.alert("Não deu certo.");
				}
			}
		}
	};
	return function (id) {
		var url = "deletePost.php?id=" + encodeURIComponent(id);
		AJAXOpen(url,handler);
	};
}());
/* link template */
var linkHTML = function (id,url) {
    return "<li class=\"tabela_port\" id=\"liLink"+id+"\"><a href=\""+url+"\" target=\"_blank\" align=\"left\">"+url+"</a><button type=\"button\" class=\"bt_excluir\" onclick=\"ROODA.ui.confirm('Tem certeza que deseja apagar este link?',function(){deleteLink("+id+");});\" align=\"right\"></button></li>";
}
/* ADD LINK AJAX */
var submitLinkForm = (function () {
    function handler() {
        var loading, t, res, e, link_box;
        if (this.readyState !== this.DONE) {
            // requisição em andamento, não fazer nada.
            return;
        }
        // Fim do request, remover tela de loading
        if (loading = document.getElementById('loading')) {
            loading.style.display = 'none';
        }
        if (this.status !== 200) {
            if (this.status >= 500) {
                ROODA.ui.alert("Problema no servidor");
            } else {
                ROODA.ui.alert("Não foi possivel contatar o servidor.<br>\nVerifique sua conexão com a internet.");
            }
            return;
        }
        t = this.responseText;
        if(t) {
            try {
                res = JSON.parse(t);
            }
            catch (e) {
                ROODA.ui.alert("Erro desconhecido (0xTTYLGB)");
                console.log("JSON: " + e.message + ":\n" + t);
                return;
            }
            if (res.errors) {
                ROODA.ui.alert(res.errors.join("\n"));
            } else if (res.ok) {
                link_box = document.getElementById("caixa_link");
                if (link_box) {
                    link_box.innerHTML += linkHTML(res.id,res.endereco);
                }
            }
        } else {
            console.log("Sem resposta do servidor.");
        }
    };
    var submitForm = submitFormFunction(handler);
    return (function (f) {
        var e = document.getElementById('loading');
        if (e) {
            e.style.display = 'block';
        }
        submitForm(f);
    });
}());
// DELETE LINK AJAX
var deleteLink = (function () {
    function deleteLinkHandler() {
        var t, res, e;
        if (this.readyState !== this.DONE) {
            // requisição em andamento, não fazer nada.
            return;
        }
        if (this.status !== 200) {
            if (this.status >= 500) {
                ROODA.ui.alert("Problema no servidor");
            } else {
                ROODA.ui.alert("Não foi possivel contatar o servidor.<br>\nVerifique sua conexão com a internet.");
            }
            return;
        }
        // OK
        t = this.responseText;
        if (t) {
            try {
                res = JSON.parse(t);
            }
            catch (e) {
                console.log("JSON: " + e.message);
                ROODA.ui.alert("Algo de errado aconteceu");
                return;
            }
            if (res.ok) {
                elem = document.getElementById("liLink" + res.id);
                if (elem) {
                    ROODA.dom.purgeElement(elem);
                }
            } else {
                if (res.errors) {
                    ROODA.ui.alert(res.errors.join("<br>\n"));
                } else {
                    ROODA.ui.alert("Não deu certo.");
                }
            }
        }
    };
    return deleteLinkFunction(deleteLinkHandler);
}());
/* UPLOAD FILE AJAX */
var submitFileForm = (function () {
    function uploadFormHandler(){
        var loading, file_list, t, res, newfile;
        if (this.readyState !== this.DONE) {
            // requisição em andamento, não fazer nada.
            return;
        }
        // Fim do request, remover tela de loading
        loading = document.getElementById('loading');
        if (loading) {
            loading.style.display = 'none';
        }
        if (this.status !== 200) {
            if (this.status >= 500) {
                ROODA.ui.alert("Problema no servidor");
            } else {
                ROODA.ui.alert("Não foi possivel contatar o servidor.<br>\nVerifique sua conexão com a internet.");
            }
            return;
        }
        // OK
        file_list = document.getElementById("caixa_arq");
        t = this.responseText;
        if(t) {
            try {
                res = JSON.parse(t);
            }
            catch (e) {
                ROODA.ui.alert("Erro desconhecido (0xTTYLGB");
                console.log("JSON: " + e.message + ":\n" + t);
                return;
            }
            if (res.errors) {
                ROODA.ui.alert(res.errors.join("\n"));
            } else if (res.file_id && res.file_name) {
                newfile = document.createElement("li");
                newfile.id = "liFile" + res.file_id;
                newfile.innerHTML = "<a href=\"../../downloadFile.php?id=" + res.file_id + "\">" + res.file_name +'</a><button type="button" class="bt_excluir" onclick="ROODA.ui.confirm(\'Tem certeza que deseja excluir este arquivo?\',function(){deleteFile(' + res.file_id + ');});"></button>';
                if (file_list) {
                    file_list.appendChild(newfile);
                }
                newfile = null;
            } else {
                ROODA.ui.alert("Não sabemos o que aconteceu, mas estamos trabalhando para descobrir");
            }
        } else {
            console.log("Sem resposta");
        }
    }
    var submitForm = submitFormFunction(uploadFormHandler);
    return (function (formElement) {
        var e = document.getElementById('loading');
        if (e) {
            e.style.display = 'block';
        }
        submitForm(formElement);
    });
}());
// DELETE FILE AJAX
var deleteFile = (function () {
    function deleteFileHandler() {
        var t, res, e, elem;
        if (this.readyState !== this.DONE) {
            // requisição em andamento, não fazer nada.
            return;
        }
        if (this.status !== 200) {
            if (this.status >= 500) {
                ROODA.ui.alert("Problema no servidor");
            } else {
                ROODA.ui.alert("Não foi possivel contatar o servidor.\nVerifique sua conexão com a internet.");
            }
            return;
        }
        // OK
        t = this.responseText;
        if (t) {
            try {
                res = JSON.parse(t);
            }
            catch (e) {
                console.log("JSON: " + e.message);
                ROODA.ui.alert("Algo de errado aconteceu.");
                return;
            }
            if (res.ok) {
                elem = document.getElementById("liFile" + res.id);
                if (elem) {
                    ROODA.dom.purgeElement(elem);
                }
            } else {
                if(res.error) {
                    ROODA.ui.alert(res.error);
                } else {
                    ROODA.ui.alert("Nao deu certo.");
                }
            }
        }
    };
    return deleteFileFunction(deleteFileHandler);
}());