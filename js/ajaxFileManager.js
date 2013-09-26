// faz com que todos os 'file input' fiquem com estilo moderno baseado em 'label'
Array.prototype.forEach.call(document.getElementsByTagName("input"), function (input) {
    var label, placeholder;
    if (input.type === 'file') {
        // verificar se o elemento já tem label.
        label = input.parentElement;
        if (label.tagName.toUpperCase() === 'LABEL') {
            // guardar o input para preservar handlers associados
            label.removeChild(input);
        } else {
            // se nao tiver, criar um label.
            label = document.createElement("label");
            // guardar o input para preservar handlers associados
            input.parentElement.replaceChild(label,input);
        }
        // adiciona texto de auxilio
        label.classList.add("file_label")
        label.innerHTML = '<span class="text">Selecionar arquivo:</span>';
        // placeholder de arquivos selecionados
        placeholder = document.createElement("span");
        // atualiza conteudo do placeholder
        input.addEventListener('change', function () {
            var i, fileList = [];
            for (i = 0; i < this.files.length; i++) {
                fileList.push(this.files[i].name);
            }
            placeholder.innerHTML = fileList.join(", ");
        });
        // adiciona placeholder ao label
        label.appendChild(placeholder);
        // esconde o 'file input' e adiciona ele ao label.
        input.hidden = false;
        label.style.position = "relative";
        input.style.position = "fixed";
        input.style.opacity = '0';
        label.appendChild(input);
    }
});

var submitFormFunction = (function (successHandler, failHandler) {
    return (function (oFormElement) {
        AJAXSubmit(oFormElement, successHandler, failHandler);
    });
});


// Function that generates a function to delete a file with a custom handler
var deleteFileFunction = (function (handler) {
    var handler = handler || function () {
        if (this.readyState !== this.DONE) {
            // requisição em andamento, não fazer nada.
            return;
        }
        if (this.status !== 200) {
            alert("Não foi possivel contatar o servidor.\nVerifique sua conexão com a internet.");
            return;
        }
        if (t = this.responseText) {
            try {
                res = JSON.parse(t)
            }
            catch (e)
            {
                console.log("JSON: " + e.message + ":\n"+t);
                //alert("Ocorreu um problema");
                return;
            }
            if (res.ok) {
                alert("Arquivo excluído com sucesso.");
            } else {
                if(res.error) {
                    //alert(res.error);
                } else {
                    //alert("Não deu certo: " + res.error);
                }
            }
        }
    }
    return (function (id) {
        var url = "../../deleteFile.php?id=" + encodeURIComponent(id);
        AJAXOpen(url,handler);
    });
});

// Function that generates a function to delete a link with a custom handler
var deleteLinkFunction = (function (handler) {
    var handler = handler || function () {
        if (this.readyState !== this.DONE) {
            // requisição em andamento, não fazer nada.
            return;
        }
        if (this.status !== 200) {
            alert("Não foi possivel contatar o servidor.\nVerifique sua conexão com a internet.");
            return;
        }
        if (t = this.responseText) {
            try {
                res = JSON.parse(t)
            }
            catch (e)
            {
                console.log("JSON: " + e.message + ":\n"+t);
                alert("Ocorreu um problema");
                return;
            }
            if (res.ok) {
                alert("Arquivo excluído com sucesso.");
            } else {
                if(res.error) {
                    alert(res.error);
                } else {
                    alert("Não deu certo: " + res.error);
                }
            }
        }
    }
    return (function (id) {
		  var url = "../../deleteLink.php?id=" + encodeURIComponent(id);
        AJAXOpen(url,handler);
    });
});

// Function that generates a function to get a list of files.
var getFileListFunction = (function(handler, func_id, func_tipo, mime_type) {
    var handler = handler || function () {};
    return (function () {
        var url = "../../fileList.php?funcionalidade_id=" + encodeURIComponent(func_id);
        url += "&funcionalidade_tipo=" + encodeURIComponent(func_tipo);
        if (mime_type) {
            url += "&arquivo_tipo=" + encodeURIComponent(mime_type);
        }
        AJAXOpen(url,handler);
    });
});
