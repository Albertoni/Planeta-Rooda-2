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
var abreComentarios = (function () {
    var comentarios = {};
    var box_comentarios = document.getElementById('box_comentarios'); // toda a caixa de comentarios, incluindo formulario
    var container_comentarios = document.getElementById('container_comentarios'); // ul contendo comentarios
    var container_titulo = document.getElementById('tituloComentarios');
    var loading_screen = document.getElementById('loading');
    var form = document.getElementById('formComentario');
    var formBotaoEnviar = document.getElementById('formComentarioBotaoEnviar');
    var formCodPost = document.getElementById('formComentarioCodPost');
    var formMensagem = document.getElementById('formComentarioMensagem');
    if (!box_comentarios) {
        // nao tem comentarios nesta página...
        return;
    }
    form.onsubmit = function () {
        return false;
    }
    box_comentarios.onclick = function (e) {
        e = e || event;
        if (e.target.className === "bt_fechar") {
            this.style.display = "none";
        }
    }
    function autoScroll() {
        container_comentarios.scrollTop = container_comentarios.scrollHeight - container_comentarios.clientHeight;
    }
    var apagaComentario = (function () {
        function ajaxHandler() {
            var t, res, e, post_div, i, n;
            if (this.readyState !== this.DONE) {
                // requisição em andamento, não fazer nada.
                return;
            }
            if (this.status !== 200) {
                if (this.status >= 500) {
                    ROODA.ui.alert("Problema no servidor.");
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
                    ROODA.ui.alert("Erro desconhecido (0xTTYLGB)");
                    console.log("JSON: " + e.message + ":\n"+t);
                    return;
                }
                if (res.ok) {
                    // remove mensagem da memória.
                    if (comentarios[res.codPost]) {
                        comentarios[res.codPost].mensagens = comentarios[res.codPost].mensagens.filter(function (m) {
                            return (m.codComentario !== res.codComentario);
                        });
                    }
                    // pega o elemento do comentario que foi apagado.
                    comentario_element = document.getElementById("comentario_" + res.codComentario.toString());
                    // se ele existir, apaga do DOM.
                    if (comentario_element) {
                        ROODA.dom.purgeElement(comentario_element);
                        comentario_element = null;
                    }
                } else {
                    if (res.errors) {
                        ROODA.ui.alert(res.errors.join("<br>\n"));
                    } else {
                        ROODA.ui.alert("Não deu certo.");
                    }
                }
            }
        }
        return function (codComentario) {
            var url = "apagaComentario.php?comentario=" + codComentario;
            AJAXOpen(url,ajaxHandler);
        };
    }());
    // mensagemToElement: -> HTMLElement
    // Deve ser executada como metodo no objeto de mensagem
    // ex: mensageToElement.apply(mensagem);
    function mensagemToElement(mensagem) {
        var elem = document.createElement("li");
        elem.id = "comentario_" + (mensagem.codComentario).toString();
        elem.className = "postComentario";
        if (mensagem.podeApagar) {
            // se o usuario pode apagar o comentario, incluir botão de excluir comentário.
            elem.innerHTML += '<button type="button" class="bt_excluir">excluir</button>';
            elem.onclick = function (e) {
                e = e || event;
                function apaga() {
                    apagaComentario(mensagem.codComentario); // dat closure
                }
                if (e.target.className === "bt_excluir") {
                    ROODA.ui.confirm("Tem certeza que deseja apagar este comentário?", apaga);
                }
            }
        }
        elem.innerHTML += mensagem.nomeUsuario.bold() + " - " + ("(" + mensagem.data + ")").small() + ":<br>\n";
        elem.innerHTML += "<p>" + mensagem.texto + "</p>";
        return elem;
    }
    function colocaMensagens(post) {
        formCodPost.value = post.codPost;
        var i = container_comentarios.childNodes.length - 1;
        container_titulo.innerHTML = post.tituloPost;
        // remover as mensagens antigas
        for(; i>=0; i -= 1){
            // percorrendo elementos em ordem inversa, para remove-los.
            ROODA.dom.purgeElement(container_comentarios.childNodes[i]);
        }
        // colocar as mensagens novas
        post.mensagens.forEach(function(mensagem){
            container_comentarios.appendChild(mensagemToElement(mensagem));
        });
        // mostrar caixa de comentarios
        box_comentarios.style.display = "block";
        autoScroll();
    }
    var insereComentario = (function () {
        function ajaxHandler() {
            var t, res, e;
            if (this.readyState !== this.DONE) {
                // requisição em andamento, não fazer nada.
                return;
            }
            if (this.status !== 200) {
                if (this.status >= 500) {
                    ROODA.ui.alert("Problema no servidor.");
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
                    ROODA.ui.alert("Erro desconhecido (0xTTYLGB)");
                    console.log("JSON: " + e.message + ":\n" + t);
                    return;
                }
                if (!res.ok) {
                    if (Array.prototype.isPrototypeOf(res.errors)) {
                        ROODA.ui.alert(res.errors.join("<br>\n"));
                    } else {
                        ROODA.ui.alert("O comentário não pôde ser enviado. (Erro desconhecido)");
                    }
                } else {
                    comentarios[res.codPost].mensagens.push(res.mensagem);
                    colocaMensagens(comentarios[res.codPost]);
                    formMensagem.value = "";
                }
            }
        }
        var url = "insereComentario.php";
        return function (codPost,mensagem) {
            AJAXPost(url, ajaxHandler, { 'codPost' : codPost , 'mensagem' : mensagem });
        };
    }());
    function enviarComentario() {
        insereComentario(formCodPost.value,formMensagem.value);
        return false;
    }
    form.onsubmit = enviarComentario;
    formBotaoEnviar.onclick = enviarComentario;
    var carregaComentariosPost = (function () {
        function ajaxHandler() {
            var t, res, e;
            if (this.readyState !== this.DONE) {
                // requisição em andamento, não fazer nada.
                return;
            }
            // Fim do request, remover tela de loading
            if (loading_screen) {
                loading_screen.style.display = 'none';
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
            try {
                res = JSON.parse(t);
            }
            catch (e) {
                ROODA.ui.alert("Erro desconhecido (0xTTYLGB)");
                console.log("JSON: " + e.message + ":\n" + t);
                return;
            }
            if (res.ok) {
                comentarios[res.codPost] = res;
                colocaMensagens(res);
                box_comentarios.style.display = "block";
                return;
            }
            if (res.errors) {
                ROODA.ui.alert(res.errors.join("\n"));
            }
        } // fim do ajaxHandler
        // carregaComentariosPost:
        return function (codPost) {
            var url = "carregaComentarios.php?post=" + encodeURIComponent(codPost);
            if (loading_screen) {
                loading_screen.style.display = 'block';
            }
            AJAXOpen(url,ajaxHandler);
        }
    }());
    return function (codPost) {
        if (parseInt(codPost) === codPost) {
            if(!comentarios[codPost]) {
                carregaComentariosPost(codPost);
            } else {
                colocaMensagens(comentarios[codPost]);
            }
        }
    };
}()); // fim do abreCometarios
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
