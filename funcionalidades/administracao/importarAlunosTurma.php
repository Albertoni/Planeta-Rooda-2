
<?php
/**
 * Esta página implementa a funcionalidade "Importar Alunos de Outra Turma", delimitada na ata de 28/05 da seguinte maneira:
 * Optar pelo a turma em um menu e listar todos os alunos de uma turma, mas de forma que o professor selecione um por um ou
 * todos os alunos.
 */
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("verificaPermissoesAdministracao.php");

$user = usuario_sessao();
validaPermissaoAcesso($user->getId());
$q = new conexao();
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

        #containerPesquisa{
            margin-bottom: 20px;
            border: 1px solid gray;
            padding:3px;
        }
    </style>
</head>

<body onload="atualiza('ajusta()');inicia();ajusta_img();">
<div id="descricao"></div>
<div id="topo">
    <div id="centraliza_topo">
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
                    <div id="rel"><p id="balao">Para importar alunos de uma turma, escolha na caixa abaixo a turma da qual deseja importar e selecione os alunos</p></div>
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
            <div id="esq">
                <div id="escolheTurma" class="bloco">
                    <h1>Escolher de qual turma importar</h1>
                    <ul class="sem_estilo">
                        <div id="containerPesquisa">
                            Pesquisar por
                            <input type="radio" name="tipoPesquisaTurma" onclick="filtrarTurmas()" value="nomeTurma" checked><span style="margin-right:2em;">Nome da Turma</span>
                            <input type="radio" name="tipoPesquisaTurma" onclick="filtrarTurmas()" value="descricao"><span style="margin-right:2em;">Descrição</span>
                            <br><br>
                            <input id="filtroTurma" type="text" onkeyup="filtrarTurmas()">
                        </div>
                            <table>
                                <colgroup span="4"></colgroup>
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Nome da Turma</th>
                                    <th>Descrição</th>
                                </tr>
                                </thead>
                                <tbody id="lista_turmas">
                                <tr class="cor1"><td>Só um instante, carregando as turmas...</td></tr>
                                </tbody>
                            </table>
                    </ul>
                </div>
                <br>
                <div id="add_alunos" class="bloco">
                    <?php
                    $q = new conexao();
                    $idTurma = $_GET['turma'];
                    $q->solicitar("SELECT nomeTurma FROM Turmas WHERE codTurma = '$idTurma'");
                    $nomeTurma = $q->resultado['nomeTurma'];
                    ?>
                    <h1>Importar participantes para a turma <?=$nomeTurma?></h1>
                    <ul class="sem_estilo">
                        <div id="containerPesquisa">
                            Pesquisar por
                            <input type="radio" name="tipoPesquisa" onclick="filtrar()" value="nome" checked><span style="margin-right:2em;">Nome</span>
                            <input type="radio" name="tipoPesquisa" onclick="filtrar()" value="email"><span style="margin-right:2em;">Email</span>
                            <input type="radio" name="tipoPesquisa" onclick="filtrar()" value="login">Login
                            <br><br>
                            <input id="filtro" type="text" onkeyup="filtrar()">
                        </div>

                        <form name="fConteudo" id="postFormId" action="salvaInsereUsuario.php" method="post">
                            <input type="hidden" name="ids_alunos" id="ids_alunos" value="" />
                            <input name="turmaLista" type="hidden" value="<?=$_GET['turma']?>">
                            <table>
                                <colgroup span="4"></colgroup>
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Login</th>
                                </tr>
                                </thead>
                                <tbody id="lista_usuarios">
                                <tr class="cor1"><td>Só um instante, carregando os usuários...</td></tr>
                                </tbody>
                            </table>
                        </form>

                    </ul>
                </div>
                <div class="bts_baixo">
                    <!-- Retorna o usuário para o local de onde ele veio. Foi preciso tratar isso a partir da ata de 28/05, quando
                    ficou combinado de criar uma gerencia dentro da turma.-->
                    <?php
                    if(strnatcmp($_POST['deOndeVem'],"listaFuncionalidadesAdministracao.php")==0){
                        echo "<a href=\"listaFuncionalidadesAdministracao.php?turma=".$_GET['turma']."\" align=\"left\" >";
                    }
                    else{   echo "<a href=\"listaFuncionalidadesGerenciaTurma.php?turma=".$_GET['turma']."\" align=\"left\" >";}
                    ?>
                    <img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
                    </a>
                    <input form="postFormId" type="image" src="../../images/botoes/bt_confirm.png" align="right"/>
                </div>
            </div>

        </div><!-- Fecha Div conteudo -->
    </div><!-- Fecha Div conteudo_meio -->
    <div id="conteudo_base">
    </div><!-- para a imagem de fundo da base -->
</div><!-- fim da geral -->
</body>
<script type="text/javascript">
    function ajusta_img() {
        if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
            $('#cont_img3').css('width','436px');
            $('#cont_img3').css('padding-right','20px');
            $('#cont_img').css('height','170px');
        }
    }

    // setando o formulário
    document.getElementById('postFormId').onsubmit = function(){
        var selected = [];
        for (var i = 0; i < listaUsuariosSelecionados.length; i++) {
            if(listaUsuariosSelecionados[i] == true){
                selected.push(i); // o indice é o id
            };
        };

        document.getElementById('ids_alunos').value = selected.join(';');

        return true;
    };

    var listaTurmas = [
        <?php
        $consulta = new conexao();
        $consulta->solicitar("SELECT codTurma,nomeTurma,descricao FROM Turmas");

        for($i=0; $i < ($consulta->registros - 1); $i++){ // for precisa do -1 porque o ultimo não pode ter virgula
            echo "{idTurma:\"".$consulta->resultado['codTurma']."\", nomeTurma:\"".$consulta->resultado['nomeTurma']."\", descricao:\"".$consulta->resultado['descricao']."\"},\n";
            $consulta->proximo();
        }
        echo "{idTurma:\"".$consulta->resultado['codTurma']."\", nomeTurma:\"".$consulta->resultado['nomeTurma']."\", descricao:\"".$consulta->resultado['descricao']."\"}\n";
        ?>
    ];

    var listaUsuariosSelecionados = [];

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

    function filtrarTurmas(){
        var modoFiltragem = document.querySelector('input[name="tipoPesquisaTurma"]:checked');
        modoFiltragem = (modoFiltragem == null) ? "nomeTurma" : modoFiltragem.value;
        // Caso não tenha nenhum marcado retorna null e a linha acima conserta.

        var input = document.getElementById("filtroTurma");

        var listaFiltrada = listaTurmas.filter(function(turma){
            /*
             Vamos por partes:
             Primeiro, a função filter loopa por todos os elementos, chamando essa função anonima que declarei agora uma vez sobre cada elemento do array. Se e somente se retornar true, o elemento vai pro array filtrado.

             usuario[modoFiltragem] quer dizer acessar a propriedade do objeto usuario contida em modoFiltragem.
             Exemplo:modoFiltragem = "nome";
             usuario[modoFiltragem] == usuario["nome"] == usuario.nome;

             Passa-se tudo para minúscula para facilitar a vida do usuário.

             E por fim, um indexOf para ver se a string sendo buscada é contida na propriedade.
             */
            return ((turma[modoFiltragem].toLowerCase().indexOf(input.value.toLowerCase())) != -1);
        });

        setaListaDeTurmas(listaFiltrada);
    }

    function setaListaDeUsuarios(lista){
        console.log(lista);
        var lista = JSON.parse(lista);
        console.log(lista);
        var tamanhoLista = lista.length;
        var elementoLista = document.getElementById('lista_usuarios');

        elementoLista.innerHTML = ""; // Precisa limpar ela pra inserir os dados atualizados

        function imprime(estruturaDados){
            function geraTd(textoLink, id){
                var td = document.createElement('td');
                td.innerHTML = textoLink;

                return td;
            };
            var tr = document.createElement('tr');
            tr.className = 'trTabelaAlunos';

            var tdCheckbox = document.createElement('td');
            var checkbox = document.createElement('input');
            checkbox.type = "checkbox";
            checkbox.value = estruturaDados['idUsuario'];
            checkbox.addEventListener("click", function(){
                listaUsuariosSelecionados[this.value] = this.checked;
            }, false);
            checkbox.checked = (((listaUsuariosSelecionados[estruturaDados['idUsuario']] == false) ||
                ((listaUsuariosSelecionados[estruturaDados['idUsuario']] == undefined))) // undefined para caso nunca tenha sido clicado, e não, undefined não é igual a false
                ? false : true);
            tdCheckbox.appendChild(checkbox);

            var tdNome = geraTd(estruturaDados['nome'], estruturaDados['idUsuario']);
            var tdEmail = geraTd(estruturaDados['email'], estruturaDados['idUsuario']);
            var tdLogin = geraTd(estruturaDados['login'], estruturaDados['idUsuario']);


            tr.appendChild(tdCheckbox);
            tr.appendChild(tdNome);
            tr.appendChild(tdEmail);
            tr.appendChild(tdLogin);
            elementoLista.appendChild(tr);
        };

        for(var i=0; i < tamanhoLista; i++){
            imprime(lista[i]);
        };
    }
    var turmaSelecionada=[];

    function setaListaDeTurmas(lista){
        var tamanhoLista = lista.length;
        var elementoLista = document.getElementById('lista_turmas');

        elementoLista.innerHTML = ""; // Precisa limpar ela pra inserir os dados atualizados

        function imprime(estruturaDados){
            function geraTd(textoLink, id){
                var td = document.createElement('td');
                td.innerHTML = textoLink;

                return td;
            };
            var tr = document.createElement('tr');
            tr.className = 'trTabelaTurmas';

            var tdCheckbox = document.createElement('td');
            var checkbox = document.createElement('input');
            checkbox.type = "radio";
            checkbox.name = "radial";
            checkbox.value = estruturaDados['idTurma'];
            checkbox.addEventListener("click", function(){
                turmaSelecionada[this.value] = this.checked;
                $.ajax("retornaAlunosTurma.php?idTurma="+estruturaDados['idTurma']).done(setaListaDeUsuarios);
            }, false);
            checkbox.checked = (((turmaSelecionada[estruturaDados['idTurma']] == false) ||
                ((turmaSelecionada[estruturaDados['idTurma']] == undefined))) // undefined para caso nunca tenha sido clicado, e não, undefined não é igual a false
                ? false : true);
            tdCheckbox.appendChild(checkbox);

            var tdNomeTurma = geraTd(estruturaDados['nomeTurma'], estruturaDados['idTurma']);
            var tdDescricao = geraTd(estruturaDados['descricao'], estruturaDados['idTurma']);


            tr.appendChild(tdCheckbox);
            tr.appendChild(tdNomeTurma);
            tr.appendChild(tdDescricao);
            elementoLista.appendChild(tr);
        };

        for(var i=0; i < tamanhoLista; i++){
            imprime(lista[i]);
        };
    }
    setaListaDeTurmas(listaTurmas);
</script>
</html>
