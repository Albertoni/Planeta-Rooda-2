var CRIAR_PESONAGEM = (function () {
    var dom = {};
    var form = {};
    var persongagem = {};
    var menuBotoes = [ 'cabelo', 'olhos', 'pele', 'acessorios', 'botaseluvas' ];
    var cabeloCores = [ 'castanho', 'preto', 'loiro', 'ruivo' ];
    var imgs = { 'cabelos' : {}, 'olhos' : [] };
    var ctx;

    // DOM INTERFACE
    dom.canvas = document.getElementById('canvas_personagem');
    dom.menu = document.getElementById('menu_criar_personagem');
    dom.cabelo = document.getElementById('opcoes_cabelo');
    dom.cabeloEstilo = document.getElementById('cabelo_estilo');
    dom.cabeloCor = document.getElementById('cabelo_cor');
    dom.olhos = document.getElementById('opcoes_olhos');
    dom.pele = document.getElementById('opcoes_pele');
    dom.acessorios = document.getElementById('opcoes_acessorios');
    dom.botaseluvas = document.getElementById('opcoes_botaseluvas');

    // DATA FORM
    form.cabeloEstilo = document.getElementById('cabelo_js');
    form.cabeloCor = document.getElementById('corCabeloSelecionada_js');
    form.olhos = document.getElementById('olhos_js');
    form.pele = document.getElementById('pele_js');
    form.cinto = document.getElementById('cinto_js');
    form.luvas = document.getElementById('luvas_js');
    form.form = document.getElementById('form_personagem');

    ;(function () {
        var i, j, n = cabeloCores.length, m = 20;

        // carregando cabelos
        for (i=0;i<n;i+=1) {
            imgs.cabelos[cabeloCores[i]] = Array(m);
            for (j=0;j<m;j+=1) {
                imgs.cabelos[cabeloCores[i]][j] = new Image();
                imgs.cabelos[cabeloCores[i]][j].src = "images/desenhos/cabelos/"+cabeloCores[i]+"/cabelo"+(j+1).toString(10)+".png";
                console.log(j.toString()+imgs.cabelos[cabeloCores[i]][j].src);
            }
        }

        // carregando olhos
        n = 8;
        imgs.olhos = Array(n);

        for (i=0;i<n;i+=1) {
            imgs.olhos[i] = new Image();
            imgs.olhos[i].src = "images/desenhos/olhos/olho"+(i+1).toString(10)+".png";
            console.log(imgs.olhos[i].src);
        }

        imgs.corpo = new Image();
        imgs.corpo.src = "images/desenhos/personagem_limpo.png";
    }());

    ctx = dom.canvas.getContext('2d');
    
    function atualiza() {
        var corpo = imgs.corpo;
        var olhos = imgs.olhos[parseInt(form.olhos.value,10)-1];
        var cabeloCor = form.cabeloCor.value;
        var cabeloEstilo = parseInt(form.cabeloEstilo.value,10)-1;
        var cabelo = imgs.cabelos[cabeloCor][cabeloEstilo];
        ctx.clearRect(0,0,dom.canvas.width,dom.canvas.height);
        ctx.drawImage(corpo,0,0);
        if (olhos)
            ctx.drawImage(olhos,37,80);
        if (cabelo)
            ctx.drawImage(cabelo,5,11);
    }

    // Menu Handler
    dom.menu.onclick = function (e) {
        var sibling, container, opcao;
        e = e || event;
        opcao = e.target.id.substr(3);
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
                    container = dom[sibling.id.substr(3)];
                    if (container) {
                        container.style.display = "none";
                    }
                }
                sibling = sibling.nextElementSibling;
            }
        }
    }
    // HairStyle Handler
    dom.cabeloEstilo.onclick = function (e) {
        var element, id, number;
        e = e || event;
        element = e.target;
        if (element.className === 'img') {
            element = element.parentElement;
        }
        id = element.id;
        number = parseInt(id.substr(6),10);
        if (number > 0 && number <= 20) {
            form.cabeloEstilo.value = number;
            atualiza();
        }
    };
    // HairColor Handler
    dom.cabeloCor.onclick = function (e) {
        var cor;
        e = e || event;
        cor = e.target.id.substr(7);
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
            num = parseInt(element.id.substr(4),10);
            if (num > 0) {
                form.olhos.value = num;
                atualiza();
            }
        }
    };
}());
// vim: sts=4 ts=4 sw=4 expandtab
