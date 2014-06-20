<?php

/*\
 *
 * nova_turma.php
 *
\*/

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../reguaNavegacao.class.php");

$user = usuario_sessao();

/*if($user === false){
	die("Voce nao esta logado em sua conta. Por favor volte e logue.");
}*/

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Planeta ROODA 2.0</title>

    <link type="text/css" rel="stylesheet" href="../../planeta.css" />

    <script type="text/javascript" src="../../js/compatibility.js"></script>
    <script type="text/javascript" src="../../jquery.js"></script>
    <script type="text/javascript" src="../../planeta.js"></script>
    <script type="text/javascript" src="../lightbox.js"></script>

    <!--[if IE 6]>
    <script type="text/javascript" src="planeta_ie6.js"></script>
    <![endif]-->
    <style type="text/css">
        tr{
            background-color: #EEF5F5;
        }
        tr:nth-child(odd){
            background-color: #CCECF4;
        }
        table{
            width: 100%;
        }

        input[type=checkbox]{
            display:none;
        }

        #containerPesquisa{
            margin-bottom: 20px;
            border: 1px solid gray;
            padding:3px;
        }
    </style>
</head>

<body onload="atualiza('ajusta()');inicia();Init(); checar(); ajusta_img();">
<div id="descricao"></div>
<div id="fundo_lbox"></div>
<div id="light_box" class="bloco">
    <img src="../../images/botoes/bt_fechar.png" class="fechar_coments" onmousedown="abreFechaLB()" />
</div>

<div id="topo">
    <div id="centraliza_topo">
        <?php
        $regua = new reguaNavegacao();
        $regua->adicionarNivel("Criar turma", "portfolio_inicio.php", false);
        $regua->adicionarNivel("Novo Projeto");
        $regua->imprimir();
        ?>
        <p id="bt_ajuda"><span class="troca">OCULTAR AJUDANTE</span><span style="display:none" class="troca">CHAMAR AJUDANTE</span></p>
    </div>
</div>

<div id="geral">
    <!-- **************************
                cabecalho
    ***************************** -->
    <div id="cabecalho">
        <div id="ajuda">
            <div id="ajuda_meio">
                <div id="ajudante">
                    <div id="personagem"><img src="../../images/desenhos/ajudante.png" height=145 align="left" alt="Ajudante" /></div>
                    <div id="rel"><p id="balao">Veja na lista abaixo quem está na turma!</p></div>
                </div>
            </div>
            <div id="ajuda_base"></div>
        </div>
    </div><!-- fim do cabecalho -->
    <div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
    <div id="conteudo_meio"><!-- para a imagem de fundo do meio -->

        <!-- **************************
                    conteudo
        ***************************** -->
        <div id="conteudo"><!-- tem que estar dentro da div 'conteudo_meio' -->
            <div class="bts_cima" style="float:none">
                <a href="../../listaFuncionalidades.php?turma=<?=$_GET['turma']?>" align="left" >
                    <img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
                </a>
            </div>

            <div id="exibe_usuarios" class="bloco">
                <?php
                    $q = new conexao();
                    $numTurma = $_GET['turma'];
                    $q->solicitar("SELECT nomeTurma FROM Turmas WHERE codTurma=$numTurma");
                    $nomeTurma = $q->resultado['nomeTurma'];
                ?>
                <h1>Participantes da turma <?=$nomeTurma?></h1>

                <ul class="sem_estilo">
                    <div id="containerPesquisa">
                        Pesquisar por nome
                        <input id="filtro" type="text" onkeyup="filtrar()">
                    </div>
                    <table>
                        <colgroup span="4"></colgroup>
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Nível</th>
                           <!-- Caso queiram correr o risco de ciberbulling, descomente! <th>Email</th>-->
                        </tr>
                        </thead>
                        <tbody id="lista_usuarios">
                        <tr class="cor1"><td>Só um instante, carregando ...</td></tr>
                        </tbody>
                    </table>
                </ul>
                <div style="clear:both;"></div>
            </div><!-- Fecha Div conteudo -->
            <div class="bts_baixo">
                <a href="../../listaFuncionalidades.php?turma=<?=$_GET['turma']?>" align="left" >
                    <img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
                </a>
            </div>
        </div><!-- Fecha Div conteudo_meio -->
    </div><!-- fim da geral -->
    <div id="conteudo_base">
    </div><!-- para a imagem de fundo da base -->
</div>
<script type="text/javascript">
    function ajusta_img() {
        if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
            $('#cont_img3').css('width','436px');
            $('#cont_img3').css('padding-right','20px');
            $('#cont_img').css('height','170px');
        }
    }


    var listaUsuarios = [
        <?php
        $estaTurma=$_GET['turma'];
        $nivelPorExtenso;

        $consulta = new conexao();
        $consulta->solicitar("SELECT * FROM $tabela_usuarios JOIN $tabela_turmasUsuario ON usuario_id=codUsuario
                                        WHERE codTurma=$estaTurma ORDER BY associacao DESC, usuario_nome DESC");


        for($i=0; $i < ($consulta->registros - 1); $i++){ // for precisa do -1 porque o ultimo não pode ter virgula
        //Transforma a informacao sobre a assossiacao que vem do BD em algo que o usuário entenda.
            switch($consulta->resultado['associacao']){
                case 4: $nivelPorExtenso = "Professor";
                        break;
                case 8: $nivelPorExtenso = "Monitor";
                        break;
                case 16: $nivelPorExtenso = "Aluno";
                        break;
                default: $nivelPorExtenso = "Não identificado";
            }
            echo "{id:\"".$consulta->resultado['usuario_id']."\", nome:\"".$consulta->resultado['usuario_nome']."\", assossiacao:\"".$nivelPorExtenso."\"},\n";
            $consulta->proximo();
        }
            //Necessário repetir senão viria com nivel do elemento n-1
            switch($consulta->resultado['associacao']){
                case 4: $nivelPorExtenso = "Professor";
                        break;
                case 8: $nivelPorExtenso = "Monitor";
                        break;
                case 16: $nivelPorExtenso = "Aluno";
                        break;
                default: $nivelPorExtenso = "Não identificado";
            }
        echo "{ id:\"".$consulta->resultado['usuario_id']."\", nome:\"".$consulta->resultado['usuario_nome']."\", assossiacao:\"".$nivelPorExtenso."\"}\n";
        ?>
    ];

    function filtrar(){
        var modoFiltragem = document.querySelector('input[name="tipoPesquisa"]:checked');
        modoFiltragem = (modoFiltragem == null) ? "nome" : modoFiltragem.value;
        // Caso não tenha nenhum marcado retorna null e a linha acima conserta.

        var input = document.getElementById("filtro");

        var listaFiltrada = listaUsuarios.filter(function(usuario){
            /*
             Vamos por partes:
             Primeiro, a função filter loopa por todos os elementos, chamando essa função anonima que declarei agora uma vez sobre cada elemento do array. Se e somente se retornar true, o elemento vai pro array filtrado.

             usuario[modoFiltragem] quer dizer acessar a propriedade do objeto usuario contida em modoFiltragem.
             Exemplo:modoFiltragem = "nome";
             usuario[modoFiltragem] == usuario["nome"] == usuario.nome;

             Passa-se tudo para minúscula para facilitar a vida do usuário.

             E por fim, um indexOf para ver se a string sendo buscada é contida na propriedade.
             */
            return ((usuario[modoFiltragem].toLowerCase().indexOf(input.value.toLowerCase())) != -1);
        });

        setaListaDeUsuarios(listaFiltrada);
    }

    function setaListaDeUsuarios(lista){
        var tamanhoLista = lista.length;
        var elementoLista = document.getElementById("lista_usuarios");

        elementoLista.innerHTML = ""; // Precisa limpar ela pra inserir os dados atualizados

        function imprime(estruturaDados){
            function geraTd(textoLink){
                var link = document.createElement('a');
                //TODO: fiz essa tentativa a partir do conteudo deste site: https://linkpeek.com/blog/display-image-on-hover-using-html-javascript-and-css.html
                link.href = "";//TODO para onde mandar ao clicar no nome da criatura?
                link.onmouseover = "";//TODO como fazer para a carteira aparecer ao deixar o mouse em cima??
                link.onmouseout = "";//TODO como fazer para voltar ao normal quando o mouse sair de cima?
                link.innerHTML = textoLink;
                var carteira = document.createElement('img');
                carteira.src = "";
                carteira.id = "placeHolder";
                carteira.style = "zindex:100; position:absolute;";
                link.appendChild(carteira);
                var td = document.createElement('td');
                td.appendChild(link);

                return td;
            };
            var tr = document.createElement('tr');
            tr.className = 'trTabelaAlunos';

            var tdNome = geraTd(estruturaDados['nome'],estruturaDados['id']);
            var tdNivel = geraTd(estruturaDados['assossiacao'],estruturaDados['id']);
            //var tdEmail = geraTd(estruturaDados['email'], estruturaDados['idUsuario']);

            tr.appendChild(tdNome);
            tr.appendChild(tdNivel);
            // Caso deva aparecer o email, desocmentar tr.appendChild(tdEmail);
            elementoLista.appendChild(tr);
        };

        for(var i=0; i < tamanhoLista; i++){
            imprime(lista[i]);
        };
    }

    setaListaDeUsuarios(listaUsuarios);
</script>
</body>
</html>
