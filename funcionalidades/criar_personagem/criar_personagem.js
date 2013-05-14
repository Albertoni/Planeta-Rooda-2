;(function () {
    'use strict';
    var atualiza = function () {}, // função que desenha personagem (definida apos todas as imagems terem sido carregadas)
        dom = {}, form = {}, ctx,
        menuBotoes = [ 'cabelo', 'olhos', 'pele', 'cinto', 'luvas' ],
        cabeloCores = [ 'castanho', 'preto', 'loiro', 'ruivo' ],
        imgs = { 'cabelos' : {}, 'olhos' : [] },
        getImageContext = function (img) {
	         var canvas = document.createElement("canvas");
	         var ctx = canvas.getContext('2d');
	         canvas.width = img.width;
	         canvas.height = img.height;
	         ctx.drawImage(img,0,0);
	         return ctx;//.getImageData(0, 0, canvas.width, canvas.height);
        },
        colorAllPixels = function(ctx,hex) {
            var r = parseInt(hex.slice(0,2),16),
                g = parseInt(hex.slice(2,4),16),
                b = parseInt(hex.slice(4,6),16),
                imgData = ctx.getImageData(0, 0, ctx.canvas.width, ctx.canvas.height),
                i, j, n = imgData.data.length / 4;
            for (i = 0; i < n; i+=1) {
                imgData.data[i * 4 + 0] = r;
                imgData.data[i * 4 + 1] = g;
                imgData.data[i * 4 + 2] = b;
            }
            ctx.putImageData(imgData, 0, 0);
        },
        drawContext = function (ctx, imgData, x, y){
            ctx.drawImage(imgData.canvas, x, y);
        };
    // DOM INTERFACE
    dom.canvas = document.getElementById('canvas_personagem');
    ctx = dom.canvas.getContext('2d');
    dom.menu = document.getElementById('menu_criar_personagem');
    dom.cabelo = document.getElementById('opcoes_cabelo');
    dom.cabeloEstilo = document.getElementById('cabelo_estilo');
    dom.cabeloCor = document.getElementById('cabelo_cor');
    dom.olhos = document.getElementById('opcoes_olhos');
    dom.pele = document.getElementById('opcoes_pele');
    dom.cinto = document.getElementById('opcoes_cinto');
    dom.luvas = document.getElementById('opcoes_luvas');
    // DATA FORM
    form.cabeloEstilo = document.getElementById('cabelo_js');
    form.cabeloCor = document.getElementById('cor_cabelo_js');
    form.olhos = document.getElementById('olhos_js');
    form.cor_pele = document.getElementById('cor_pele_js');
    form.cor_cinto = document.getElementById('cor_cinto_js');
    form.cor_luvas = document.getElementById('cor_luvas_js');
    form.form = document.getElementById('form_personagem');
    // Função que atualiza a visualização do personagem.
    // É atribuida a "atualiza" quando todas as imagens terminam de ser carregadas.
    function atualiza_c () {
        var corpo = imgs.corpo,
            olhos = imgs.olhos[parseInt(form.olhos.value, 10) - 1],
            cabeloCor = form.cabeloCor.value,
            cabeloEstilo = parseInt(form.cabeloEstilo.value, 10) - 1,
            cabelo = imgs.cabelos[cabeloCor][cabeloEstilo],
            peleCor = form.cor_pele.value,
            luvasCor = form.cor_luvas.value,
            cintoCor = form.cor_cinto.value;
        // limpa o canvas
        ctx.clearRect(0, 0, dom.canvas.width, dom.canvas.height);
        if (peleCor.length === 6) {
            drawContext(ctx, imgs.mapaPele,17,41);
        }
        if(luvasCor.length === 6) {
            drawContext(ctx, imgs.mapaLuvas,21,214);
        }
        if (cintoCor.length === 6) {
            drawContext(ctx, imgs.mapaCinto,30,173);
        }
        ctx.drawImage(corpo, 0, 0);
        if (olhos) {
            ctx.drawImage(olhos, 37, 84);
        }
        if (cabelo) {
            ctx.drawImage(cabelo, 5, 11);
        }
    }
    // CARREGANDO IMAGENS
    (function () {
        var i, j, 
            n_cores = cabeloCores.length, 
            n_estilos = 20, // 20 estilos de cabelo por cor
            n_olhos = 8,
            completo = 0,  // quantidade de imagens já carregadas
            total = (n_cores * n_estilos) + n_olhos + 4; // total de imagens p/ carregar (cabelos + olhos + corpo)
        // BARRA DE LOADING
        var desenhaBarra = function() {
            var margem = 40.5,
                border = 3,
                largura = dom.canvas.width-margem*2,
                altura = dom.canvas.height-margem*2,
                progresso = completo/total;
            ctx.clearRect(0, 0, dom.canvas.width, dom.canvas.height);
            ctx.fillStyle = "rgb(83, 104, 111)";
            ctx.fillRect(margem, margem, largura, altura);
            ctx.fillStyle = "rgb(238, 245, 245)";
            ctx.fillRect(margem, margem, largura, altura-altura*progresso);
            ctx.fillStyle = "rgb(5,5,5)";
            ctx.strokeRect(margem,margem,largura,altura);
        };
        desenhaBarra();
        var carregou = function () {
            // limpa o canvas
            completo += 1;
            desenhaBarra();
            if (completo === total) {
                imgs.mapaPele = getImageContext(imgs.mapaPeleImg);
                imgs.mapaLuvas = getImageContext(imgs.mapaLuvasImg);
                imgs.mapaCinto = getImageContext(imgs.mapaCintoImg);
                colorAllPixels(imgs.mapaPele, form.cor_pele.value);
                colorAllPixels(imgs.mapaCinto, form.cor_cinto.value);
                colorAllPixels(imgs.mapaLuvas, form.cor_luvas.value);
                atualiza = atualiza_c;
                atualiza();
            }
				this.onload = null;
        }
        for (i = 0; i < n_cores; i += 1) {
            // para cada cor de cabelo
            imgs.cabelos[cabeloCores[i]] = []; // new Array(m);
            for (j = 0; j < n_estilos; j += 1) {
                // carregar a imagem de cada um dos 20 estilos
                imgs.cabelos[cabeloCores[i]][j] = new Image();
                imgs.cabelos[cabeloCores[i]][j].onload = carregou;
                imgs.cabelos[cabeloCores[i]][j].src = "images/desenhos/cabelos/" + cabeloCores[i] + "/cabelo" + (j + 1).toString(10) + ".png";
            }
        }
        // carregando olhos
        imgs.olhos = []; // new Array(n);
        for (i = 0; i < n_olhos; i += 1) {
            imgs.olhos[i] = new Image();
            imgs.olhos[i].onload = carregou;
            imgs.olhos[i].src = "images/desenhos/olhos/olho" + (i + 1).toString(10) + ".png";
        }
        imgs.corpo = new Image();
        imgs.corpo.onload = carregou;
        imgs.mapaPeleImg = new Image();
        imgs.mapaPeleImg.onload = carregou;
        imgs.mapaLuvasImg = new Image();
        imgs.mapaLuvasImg.onload = carregou;
        imgs.mapaCintoImg = new Image();
        imgs.mapaCintoImg.onload = carregou;
        imgs.corpo.src = "images/desenhos/personagem_limpo.png";
        imgs.mapaPeleImg.src = "images/desenhos/mapa_pele.png";
        imgs.mapaLuvasImg.src = "images/desenhos/mapa_luvas.png";
        imgs.mapaCintoImg.src = "images/desenhos/mapa_cinto.png";
    }());
    // FIM CARREGAR IMAGENS
    // Menu Handler
    dom.menu.onclick = function (e) {
        var sibling, container, opcao;
        e = e || event;
        opcao = e.target.id.slice(3);
        if (menuBotoes.indexOf(opcao) !== -1) {
            container = dom[opcao];
            if (container) {
                container.style.display = "block";
            }
            e.target.classList.add('selected');
            sibling = e.target.parentElement.firstElementChild;
            while (sibling) {
                if (sibling !== e.target) {
                    sibling.classList.remove('selected');
                    container = dom[sibling.id.slice(3)];
                    if (container) {
                        container.style.display = "none";
                    }
                }
                sibling = sibling.nextElementSibling;
            }
        }
    };
    // HairStyle Handler
    dom.cabeloEstilo.onclick = function (e) {
        var element, id, number;
        e = e || event;
        element = e.target;
        if (element.className === 'img') {
            element = element.parentElement;
        }
        id = element.id;
        number = parseInt(id.slice(6), 10);
        if (number > 0 && number <= 20) {
            form.cabeloEstilo.value = number;
            atualiza();
        }
    };
    // HairColor Handler
    dom.cabeloCor.onclick = function (e) {
        var cor;
        e = e || event;
        cor = e.target.id.slice(7);
        if (cabeloCores.indexOf(cor) !== -1) {
            dom.cabeloEstilo.className = cor;
            form.cabeloCor.value = cor;
            atualiza();
        }
    };
    dom.olhos.onclick = function (e) {
        var element, num;
        e = e || event;
        element = e.target;
        if (element.id) {
            num = parseInt(element.id.slice(4), 10);
            if (num > 0) {
                form.olhos.value = num;
                atualiza();
            }
        }
    };
    dom.pele.onclick = function (e) {
        var element;
        e = e || event;
        element = e.target;
        if (element.tagName.toUpperCase() === "LI" && element.textContent.length === 6) {
            form.cor_pele.value = element.textContent;
            if (imgs.mapaPele) {
                colorAllPixels(imgs.mapaPele, element.textContent);
            }
            atualiza();
        }
    }
    dom.cinto.onclick = function (e) {
        var element;
        e = e || event;
        element = e.target;
        if (element.tagName.toUpperCase() === "LI" && element.textContent.length === 6) {
            form.cor_cinto.value = element.textContent;
            if (imgs.mapaCinto) {
                colorAllPixels(imgs.mapaCinto, element.textContent);
            }
            atualiza();
        }
    }
    dom.luvas.onclick = function (e) {
        var element;
        e = e || event;
        element = e.target;
        if (element.tagName.toUpperCase() === "LI" && element.textContent.length === 6) {
            form.cor_luvas.value = element.textContent;
            if (imgs.mapaLuvas) {
                colorAllPixels(imgs.mapaLuvas, element.textContent);
            }
            atualiza();
        }
    }
}());
// vim: ts=4 sts=4 sw=4 expandtab
