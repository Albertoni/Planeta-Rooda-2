var CRIAR_PESONAGEM = function () {
    var dom = {};
    var form = {};
    var menuBotoes = [ 'cabelo', 'olhos', 'pele', 'acessorios', 'botaseluvas' ];
    var cabeloCores = [ 'castanho', 'preto', 'loiro', 'ruivo' ];
    dom.canvas = document.getElementById('canvas_personagem');
    
    dom.menu = document.getElementById('menu_criar_personagem');
    
    dom.cabelo = document.getElementById('opcoes_cabelo');
    dom.cabeloEstilo = document.getElementById('cabelo_estilo');
    dom.cabeloCor = document.getElementById('cabelo_cor');
    dom.olhos = document.getElementById('opcoes_olhos');
    dom.pele = document.getElementById('opcoes_pele');
    dom.acessorios = document.getElementById('opcoes_acessorios');
    dom.botaseluvas = document.getElementById('opcoes_botaseluvas');
    
    form.cabeloEstilo = document.getElementById('cabelo_js');
    form.cabeloCor = document.getElementById('corCabeloSelecionada_js');
    form.olhos = document.getElementById('olhos_js');
    form.pele = document.getElementById('pele_js');
    form.cinto = document.getElementById('cinto_js');
    form.luvas = document.getElementById('luvas_js');
    // Menu Handler
    dom.menu.onclick = function (e) {
        var sibling, container, opcao;
        e = e || event;
        opcao = e.target.id.substr(3);
        if (menuBotoes.indexOf(opcao) !== -1)
        {
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
        if (element.className === 'img')
        {
            element = element.parentElement;
        }
        id = element.id;
        number = parseInt(id.substr(6),10);
        if (number > 0 && number <= 20) {
            form.cabeloEstilo.value = number;
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
        }
    };
}();
