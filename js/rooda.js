// faz com que todos os 'file input' fiquem com estilo moderno baseado em 'label'
function formatFileInput(input) {
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
}
Array.prototype.forEach.call(document.getElementsByTagName("input"), formatFileInput);

var ROODA = {};
/*
 * ROODA.dom
 *    All the dom related functions are here.
 */
ROODA.dom = {};
ROODA.dom.simulateClick =  function (node) {
    var clickevent = new MouseEvent('click', {
        'view': window,
        'bubbles': true,
        'cancelable': true
    });
    // returna falso se o evento foi cancelado
    return node.dispatchEvent(clickevent);
};
/*
 * ROODA.dom.walkTheDom(node,func)
 * Desc: 
 *    Applies function 'func' on DOM node 'node' and all its children.
 * Params:
 *    node: DOM node
 *    func: function that will be called passing node and all its children.
 */
ROODA.dom.walkTheDOM = function (node, fun) {
    'use strict';
    fun(node);
    node = node.firstChild;
    while (node) {
        ROODA.dom.walkTheDOM(node, fun);
        node = node.nextSibling;
    }
};
/*
 * ROODA.dom.purgeElement(node)
 * Desc:
 *    Remove all handlers from a node and all its children
 *    and detach it from the document (if attached).
 */
ROODA.dom.purgeElement = function (node) {
    'use strict';
    ROODA.dom.walkTheDOM(node, function (e) {
        var i, l;
        if (e.attributes) {
            l = e.attributes.length;
            for (i = 0; i < l; i += 1) {
                if (typeof e.attributes[i] === "function") {
                    e.attributes[i] = null;
                }
            }
        }
    });
    if (node.parentElement) {
        node.parentElement.removeChild(node);
    }
};

ROODA.dom.onScrollToBottom = (function () {
    var handlers = [];
    function exec(f) { f() }
    window.addEventListener("scroll", function () {
        if (window.scrollY >= window.scrollMaxY)
        handlers.forEach(function (f) {
            f();
        });
    });
    return {
        addHandler: function (fun) {
            if (typeof fun === 'function') {
                if (handlers.indexOf(fun) === -1) {
                    handlers.push(fun);
                }
            }
        },
        removeHander: function (fun) {
            if (handlers.indexOf(fun) !== -1) {
                handlers = handlers.filter(function (f) {
                    return f !== fun;
                });
            }
        }
    };
}());
/*
 * ROODA.ui
 *    All user interface related functions are here.
 */
ROODA.ui = {};
/* 
 * ROODA.ui.alert(str)
 * Desc:
 *    A non-blocking javascript alert interface
 * Params:
 *    str: string message
 */
ROODA.ui.alert = function(str) {
    'use strict';
    var html = [], div = document.createElement("div");
    div.onclick = function(e) {
        // User clicked on the alert window!
        e = e || event;
        var target = e.target || e.srcElement;
        if (target.classList.contains("alert_ok")) {
            // User clicked "OK", remove the alert window.
            ROODA.dom.purgeElement(this);
        }
    };
    div.className = "alert";
    html.push("<div class=\"spacer_50\"></div>");
    html.push("<div class=\"alert_window\">");
    html.push("<div class=\"alert_img\"></div>");
    html.push("<p class=\"alert_text\">" + str + "</p>");
    html.push("<button class=\"alert_ok\">OK</button>");
    html.push("</div>");
    div.innerHTML = html.join("\n");
    document.body.appendChild(div);
};


/* 
 * ROODA.ui.alert( str [, fun_yes [, fun_no ]] )
 * Desc:
 *    A non-blocking javascript confirm interface
 * Params:
 *    str: string message
 *    fun_yes: function to be executed if user click "ok" button
 *    fun_no: function to be executed if user click "no" button
 */
ROODA.ui.confirm = function(str, fun_yes, fun_no) {
    'use strict';
    var html = [], div = document.createElement("div");
    div.onclick = function(e) {
        // User clicked on the confirm window!
        e = e || event;
        var target = e.target || e.srcElement;
        if (target.classList.contains("confirm_yes")) {
            // User clicked "Yes", execute fun_yes
            if (typeof fun_yes === "function") { fun_yes(); }
            // and remove the confirm window
            ROODA.dom.purgeElement(this);
        } else if (target.classList.contains("confirm_no")) {
            // User clicked "No", execute fun_no
            if (typeof fun_no === "function") { fun_no(); }
            // and remove the confirm window
            ROODA.dom.purgeElement(this);
        }
    };
    div.className = "confirm";
    html.push("<div class=\"spacer_50\"></div>");
    html.push("<div class=\"confirm_window\">");
    html.push("<div class=\"confirm_img\"></div>");
    html.push("<p class=\"confirm_text\">" + str + "</p>");
    html.push("<button class=\"confirm_yes\">yes</button>");
    html.push("<button class=\"confirm_no\">no</button>");
    html.push("</div>");
    div.innerHTML = html.join("\n");
    document.body.appendChild(div);
};
ROODA.Usuario = function (id, usuario, nome) {
    if (typeof id === 'number') this.id = id;
    if (typeof usuario === 'string') this.usuario = usuario;
    if (typeof nome === 'string') this.nome = nome;
    if (id && !(nome || usuario)) {
        // pegar dados via ajax (futuro)
    }
}
ROODA.Usuario.prototype = {
    'id': 0,
    'usuario': '',
    'nome': '',
    'jQuery': null,
    'toString': function() {
        return (this.nome || this.usuario || '');
    },
    'toHTML': function () {
        var j = $('<b>').text(this.toString());
        if (this.jQuery) {
            this.jQuery.add(j);
        } else {
            this.jQuery = j;
        }
        return j;
    },
    'updateHtml': function () {
        this.jQuery.text(this.toString);
    }
}