var submitFormFunction = (function (handler) {
    var uploadHandler = handler || function(){
        if (this.readyState !== this.DONE) {
            // requisição em andamento, não fazer nada.
            return;
        }
        if (this.status !== 200) {
            alert("Não foi possivel contatar o servidor.\nVerifique sua conexão com a internet.");
            return;
        }
        if(t = this.responseText) {
            try {
                var res = JSON.parse(t);
            }
            catch (e) {
                alert("Erro desconhecido (0xGLHF42)");
                console.log("JSON: " + e.message + ":\n"+t);
                return;
            }
            if (res.errors) {
                alert(res.errors.join("\n"));
            } else if (res.file_id && res.file_name) {
                alert("Arquivo enviado com sucesso.");
            } else { 
                alert("Não sabemos o que aconteceu, mas estamos trabalhando para descobrir");
            }
        } else {
            console.log("Sem resposta");
        }
    }
    return (function (oFormElement) {
        AJAXSubmit(oFormElement,uploadHandler);
    });
});

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
        var oAjaxReq = new XMLHttpRequest();
        oAjaxReq.fileId = id;
        oAjaxReq.open("GET","../../deleteFile.php?id=" + encodeURIComponent(id));
        oAjaxReq.onload = handler;
        oAjaxReq.send();
    });
});

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
        var oAjaxReq = new XMLHttpRequest();
        oAjaxReq.linkId = id;
        oAjaxReq.open("GET","../../deleteLink.php?id=" + encodeURIComponent(id));
        oAjaxReq.onreadystatechange = handler;
        oAjaxReq.send();
    });
});

var getFileListFunction = (function(handler, func_id, func_tipo, mime_type) {
    var getFileListHandler = handler || function () {};
    return (function () {
        var oAjaxReq = new XMLHttpRequest();
        var url = "../../fileList.php?funcionalidade_id=" + encodeURIComponent(func_id);
        url += "&funcionalidade_tipo="+encodeURIComponent(func_tipo);
        if (mime_type) {
            url += "&arquivo_tipo="+encodeURIComponent(mime_type);
        }
        oAjaxReq.open("GET",url);
        oAjaxReq.onreadystatechange = handler;
        oAjaxReq.send();
    });
});
