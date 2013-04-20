    <?php
    session_start();
    header('Content-type: text/html; charset=utf-8');
    
    require("../../cfg.php");
    require("../../bd.php");
    require("../../funcoes_aux.php");
    require("../../usuarios.class.php");
    require("../../login.class.php");
//  require("verifica_user.php");
    require("blog.class.php");
    require("../../file.class.php");
    require("../../link.class.php");
//  require("visualizacao_blog.php");
    require("../../reguaNavegacao.class.php");
    $usuario_id = $_SESSION['SS_usuario_id'];
    
    $blog_id = isset($_GET['blog_id']) ? $_GET['blog_id'] : die("não foi fornecido id de blog");
    $blog_id = ($blog_id == "meu_blog") ? getMeuBlog() : $_GET['blog_id'];
    
    // Eu amo a linha de código abaixo.
    true == (is_numeric($blog_id)) or die('A id do blog precisa ser num&eacute;rica!'); // Sabe SQL injection?
    
    
    global $usuario_id;
    if ($usuario_id == 0){
        die("Voc&ecirc; n&atilde;o est&aacute; logado. Por favor volte.");
    }
    
    $blog = new Blog($blog_id);
    if(!$blog->getExiste())
        if($blog_id == $usuario_id)
            $blog->save();  // Não faz muito sentido, mas não tou encostando.
        else
            die("Blog inexistente");
    $ini = isset($_GET['ini']) && $_GET['ini'] >= 0 ? floor($_GET['ini']/$blog->getPaginacao())*$blog->getPaginacao() : 0;
    $ini = $ini < 0 ? 0 : $ini;
    $ini = $ini > $blog->getSize() ? floor($blog->getSize()/$blog->getPaginacao())*$blog->getPaginacao() : $ini;
    
    $usuario = new Usuario();
    $usuario->openUsuario($_SESSION['SS_usuario_id']);
    
    $turma = isset($_GET['turma']) ? $_GET['turma'] : 0;
    $permissoes = checa_permissoes(TIPOBLOG, $turma);
    if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}
    
    // "blog_inserirPost,blog_editarPost,blog_inserirComentarios,blog_excluirPost,blog_adicionarLinks,blog_adicionarArquivos";
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="blog.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script src="../../js/compatibility.js"></script>
<script src="../../js/rooda.js"></script>
<script src="../../js/ajax.js"></script>
<script src="../../js/ajaxFileManager.js"></script>
<script type="text/javascript" src="../../postagem_wysiwyg.js"></script><!--para o mostraDescri()-->
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="blog.js"></script>
<script type="text/javascript" src="blog_ajax.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>

<script>
var linkHTML = function (link) {
    return "<li class=\"tabela_blog\"><a href=\""+link+"\" target=\"_blank\">"+link+"</a><div class=\"bts_caixa\"><img class=\"apagar\" src=\"../../images/botoes/bt_x.png\" /></div>";
}

var submitLinkForm = (function () {
    function handler() {
        var loading, link_box;
        if (this.readyState !== this.DONE) {
            // requisição em andamento, não fazer nada.
            return;
        }
        
        // Fim do request, remover tela de loading
        if (loading = document.getElementById('loading')) {
            loading.style.display = 'none';
        }

        if (this.status !== 200) {
            ROODA.ui.alert("Não foi possivel contatar o servidor.\nVerifique sua conexão com a internet.");
            return;
        }
        if(t = this.responseText) {
            try {
                res = JSON.parse(t);
            }
            catch (e) {
                ROODA.ui.alert("Erro desconhecido (0xTTYLGB");
                console.log("JSON: " + e.message + ":\n"+t);
                return;
            }
            if (res.errors) {
                ROODA.ui.alert(res.errors.join("\n"));
            } else if (res.ok) {
                link_box = document.getElementById("caixa_link");
                if (link_box) {
                    link_box.innerHTML += linkHTML(res.endereco);
                }
            }
        } else {
            console.log("Sem resposta do servidor.");
        }
    }

    var submitForm = submitFormFunction(handler);
    return (function (f) {
        var e = document.getElementById('loading');
        if (e) {
            e.style.display = 'block';
        }
        submitForm(f);
    });
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
        if (loading = document.getElementById('loading')) {
            loading.style.display = 'none';
        }
        if (this.status !== 200) {
            ROODA.ui.alert("Não foi possivel contatar o servidor.\nVerifique sua conexão com a internet.");
            return;
        }
        // OK
        file_list = document.getElementById("caixa_arq");
        if(t = this.responseText) {
            try {
                res = JSON.parse(t);
            }
            catch (e) {
                ROODA.ui.alert("Erro desconhecido (0xTTYLGB");
                console.log("JSON: " + e.message + ":\n"+t);
                return;
            }
            if (res.errors) {
                ROODA.ui.alert(res.errors.join("\n"));
            } else if (res.file_id && res.file_name) {
                newfile = document.createElement("li");
                newfile.id = "liFile" + res.file_id;
                newfile.innerHTML = "<a href=\"../../downloadFile.php?id=" + res.file_id + "\">" + res.file_name +"</a>" +
                    '<img align="right" src="../../images/botoes/bt_x.png" onclick="ROODA.ui.confirm(\'Tem certeza que deseja excluir este arquivo?\',function(){ deleteFile(' + res.file_id + ') });" />';
                file_list.appendChild(newfile);
            } else {
                ROODA.ui.alert("Não sabemos o que aconteceu, mas estamos trabalhando para descobrir");
            }
        } else {
            console.log("Sem resposta do servidor.");
        }
    }
    var submitForm = submitFormFunction(uploadFormHandler);
    return (function (f) {
        var e = document.getElementById('loading');
        if (e) {
            e.style.display = 'block';
        }
        submitForm(f);
    });
})();

// DELETE FILE AJAX
var deleteFile = (function () {
    function deleteFileHandler() {
        if (this.readyState !== this.DONE) {
            // requisição em andamento, não fazer nada.
            return;
        }
        if (this.status !== 200) {
            ROODA.ui.alert("Não foi possivel contatar o servidor.\nVerifique sua conexão com a internet.");
            return;
        }
        if (t = this.responseText) {
            try {
                res = JSON.parse(t)
            }
            catch (e) {
                console.log("JSON: " + e.message);
                ROODA.ui.alert("Algo de errado aconteceu.");
                return;
            }
            if (res.ok) {
                if(elem = document.getElementById("liFile" + this.fileId)) {
                    elem.parentElement.removeChild(elem);
                }
            } else {
                if(res.error) {
                    ROODA.ui.alert(res.error);
                } else {
                    ROODA.ui.alert("Nao deu certo");
                }
            }
        }
    };
    var deleteFile = deleteFileFunction(deleteFileHandler);
    return (function (id) {
        deleteFile(id);
    });
})();

// DELETE LINK AJAX
var deleteFile = (function () {
    function deleteFileHandler() {
        if (t = this.responseText) {
            try {
                res = JSON.parse(t)
            }
            catch (e) {
                console.log("JSON: " + e.message);
                ROODA.ui.alert("Algo de errado aconteceu.");
                return;
            }
            if (res.ok) {
                if(elem = document.getElementById("liLink" + this.linkId)) {
                    elem.parentElement.removeChild(elem);
                }
            } else {
                if(res.error) {
                    ROODA.ui.alert(res.error);
                } else {
                    ROODA.ui.alert("Nao deu certo");
                }
            }
        }
    };
    var deleteFile = deleteFileFunction(deleteFileHandler);
    return (function (id) {
        deleteFile(id);
    });
})();
</script>
<script language="javascript">

function coment(){
    if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
        document.getElementById('ie_coments').style.width = 85 + '%';
        $('.bloqueia ul').css('margin-right','17px');
    }
};
</script>

<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();coment();">

<div id="descricao"></div>

<div id="fundo_lbox"></div>
<div id="light_box" class="bloco"></div>

<div id="topo">
<div id="centraliza_topo">
        <?php 
            $regua = new reguaNavegacao();
            $regua->adicionarNivel("Blog");
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
                <div id="rel"><p id="balao">Aqui, você pode encontra um espaço para escrita pessoal onde pode compartilhar 
                diversos assuntos com seus colegas e permitir que eles, além de visualizar, publiquem comentários 
                em suas postagens.</p></div>
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
        
        <div class="bts_cima">
        <a href="blog_inicio.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_voltar.png" align="left"/></a>
<?php
if ($usuario->podeAcessar($permissoes["blog_inserirPost"], $turma)){
    echo "          <a href=\"blog_postagem.php?blog_id=$blog_id&amp;turma=$turma\"><img src=\"../../images/botoes/bt_criar_postagem.png\" border=\"0\" align=\"right\"/></a>";
}
?>
        </div>
        <div class="troca_paginas">
            <center>
            <div class="paginas_padding">
                <?=$blog->mostraPaginacao($ini)?>
            </div>
            </center>
        </div>
        <div id="esq" class="margem_paginas">
            <div class="bloco" id="ident">
                <h1><?=fullUpper($blog->getTitle())?></h1>

<?php
// script para a exibição dos posts
    $id_estilo = 1;
    for($i=$ini;($i<$ini+$blog->getPaginacao()) && ($i<$blog->getSize());$i++) {
        imprimePost($blog->posts[$i], $blog->getId(), $id_estilo, $blog->owners, $usuario_id, $usuario, $permissoes, $turma);
        $id_estilo = 3 - $id_estilo; // alterna o estilo da div entre 2 e 1
    }
?>
            </div>
        </div>
        <div id="dir" class="margem_paginas">
            <div class="bloco" id="perfil">
                <h1 id="nomeblog"><a class="toggle" id="toggle_perfil">▼</a> <?php echo count($blog->owners) > 1 ? "AUTORES" : fullUpper($blog->owners[0]->getName()) ?></h1>
                <ul class="sem_estilo" id="caixa_perfil">
<?php
    foreach($blog->owners as $owner) {
        imprimeDono($owner, $usuario_id);
    }
?>
                </ul>
            </div>
            <div class="bloco" id="post">
                <h1><a class="toggle" id="toggle_post">▼</a> POSTAGENS</h1>
                <div class="bloqueia">
                    <ul class="sem_estilo" id="caixa_post">
<?
imprimeListaPosts($blog->getId(), $turma);
?>
                    </ul>
                </div>
            </div>
            <div class="bloco" id="arquivos">
                <? 
                $consulta = new conexao();
                $consulta->solicitar("SELECT Tipo FROM $tabela_blogs WHERE Id = $blog_id");
                $tipoBlog = $consulta->resultado['Tipo'];
                $funcionalidade_id = $blog->getId();
                $funcionalidade_tipo = $tipoBlog;
                ?>
                
                <h1><a class="toggle" id="toggle_arq">▼</a> ARQUIVOS </h1>
                <!-- <div class="add" id="divLinkAdicionarArquivo">adicionar</div> -->
                <div class="add" onclick="botaoAdicionar('addFileDiv')">adicionar</div>
                <div class="bloqueia">
                    <ul class="sem_estilo" id="caixa_arq">
                    <li id="addFileDiv" style="display:none">
                        <form id="file_form" method="post" enctype="multipart/form-data" action="../../uploadFile.php?funcionalidade_id=<?=$blog_id?>&amp;funcionalidade_tipo=<?=TIPOBLOG?>" onsubmit="submitFileForm(this);return false;">
                            <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                            <div class="file_input" style="display:inline-block">
                                <input name="userfile" type="file" id="procura_arquivo" class="upload_file" title="Procurar Arquivo" style="" required />
                            </div>
                            <div id="f_arquivo" style="display:inline-block;width: 80px;" class="falso_text">&nbsp;</div>
                            <br>
                            <button type="submit" class="submit" name="upload" value="Enviar" style="float:right">Enviar</button>
                        </form>
                        <script>


    // -------------
    var bt_arquivo = document.getElementById('procura_arquivo');
    var f_arquivo = document.getElementById('f_arquivo');
    

    var change_file = function (){
        f_arquivo.innerHTML = '&nbsp;';
        for (i=0;i<bt_arquivo.files.length;i++){
            f_arquivo.innerHTML = bt_arquivo.files[i].name + ' ';
        }
    };
    bt_arquivo.onchange = change_file;
    bt_arquivo.form.onreset = change_file;
                        </script>
                        </li>
<?php
                            //jquery com javascript
                            //colocar um evento onClick no adicionar
                            //evento tornarah uma div invisivel em visivel reestruturando adequadamente a pagina
                            //
                            $consulta = new conexao();
                            $id = $blog->getId();
                            $consulta->solicitar("SELECT nome,arquivo_id FROM $tabela_arquivos WHERE funcionalidade_tipo='$tipoBlog' AND funcionalidade_id='$blog_id'");

                            $downloadFile="../../downloadFile.php";
                            $funcionalidade_tipo=(string)$tipoBlog;
                            $funcionalidade_id=(string)$id;
                            for($i=0 ; $i<count($consulta->itens);$i++) {
                                $file_name= $consulta->resultado['nome'];
                                $destino =$downloadFile;
                                $destino.="?id=".$consulta->resultado['arquivo_id'];
?>
                                <li class="tabela_blog" id="liFile<?=$consulta->resultado['arquivo_id']?>">
                                    <a href="<?=$destino?>" target='_blank'><?=$file_name ?></a> 
                                    <div class="bts_caixa"><img class="apagar" src="../../images/botoes/bt_x.png" onclick="ROODA.ui.confirm('Tem certeza que deseja excluir este arquivo?',function(){deleteFile(<?=$consulta->resultado['arquivo_id']?>);});"/></div>
                                </li>
<?php
                                $consulta->proximo();
                            }
?>
                    </ul>
                </div>
            </div>
            <div class="bloco" id="link">
<?php
                    $novo_link = "novo_link.php";
                    $novo_link.= "?funcionalidade_tipo=".TIPOBLOG;
                    $novo_link.= "&funcionalidade_id=".$blog->getId();
                    $novo_link = "javascript:window.open('$novo_link');";
                ?>
                <h1><a class="toggle" id="toggle_link">▼</a> LINKS</h1>
                <div class="add" onclick="botaoAdicionar('addLinkLi');">adicionar</div>
                <div class="bloqueia">
                    <ul class="sem_estilo" id="caixa_link">
                    <li id="addLinkLi" class="tabela_port" style="display:none;">
                        <form name="addLinkForm" action="../../novo_link.php?funcionalidade_tipo=<?=$funcionalidade_tipo?>&amp;funcionalidade_id=<?=$funcionalidade_id?>" onsubmit="submitLinkForm(this);return false;" method="post">
                            Novo Link: <input name="novoLink" id="novoLink" type="text"/>
                            <input name="submit" type="submit" id="submit" value="Submit" />
                        </form>
                    </li>
<?php
                            $funcionalidade_tipo= $tipoBlog;
                            $funcionalidade_id= $id;
                            
                            $consulta = new xonexao();
                            $consulta->solicitar("SELECT Id,titulo,endereco FROM $tabela_links WHERE funcionalidade_tipo = '$funcionalidade_tipo' AND funcionalidade_id = '$funcionalidade_id'");
                            
                            while($consulta->resultado) {
                                $id = $consulta->resultado['Id'];
                                $endereco = $consulta->resultado['endereco'];
                                $titulo = trim($consulta->resultado['titulo']);
                                if ($titulo === "") { 
                                    $titulo = $endereco;
                                }
                                echo "
                        <li class=\"tabela_blog\">
                            <a href=\"$endereco\" target=\"_blank\">$titulo</a>
                            <div class=\"bts_caixa\"><img class=\"apagar\" src=\"../../images/botoes/bt_x.png\" onclick=\"ROODA.ui.confirm('Tem certeza que deseja excluir este arquivo?',function(){deleteLink($id);};);\" /></div>
                        </li>";
                                $consulta->proximo();
                            }
?>
                    </ul>
                </div>
            </div>
            <div class="bloco" id="tag">
                <h1><a class="toggle" id="toggle_tag">▼</a> TAGS</h1>
                <div class="bloqueia">
                    <ul class="sem_estilo" id="caixa_tag">
<?php
foreach ($blog->tags as $tag){
    imprimeTags($tag, $blog_id);
}
?>
                    </ul>
                </div>
            </div>
        </div>
            <div class="troca_paginas">
                <center>
                <div class="paginas_padding">
                    <?=$blog->mostraPaginacao($ini)?>
                </div>
                </center>
            </div>

        <div class="bts_baixo">
            <a href="blog_inicio.php"><img src="../../images/botoes/bt_voltar.png" align="left"/></a>
<?php
if ($usuario->podeAcessar($permissoes["blog_inserirPost"], $turma)){
    echo "          <a href=\"blog_postagem.php?blog_id=$blog_id&turma=$turma\"><img src=\"../../images/botoes/bt_criar_postagem.png\" border=\"0\" align=\"right\"/></a>";
}
?>
        </div>
    
    </div><!-- Fecha Div conteudo -->
    
    </div><!-- Fecha Div conteudo_meio -->   
    <div id="conteudo_base">
    </div><!-- para a imagem de fundo da base -->
        
</div><!-- fim da geral -->
    <!-- loading -->
    <div id="loading" style="display:none;">
        <div class="spacer_50"><!-- empty --> </div>
        <div class="loading_anim">
            <h2>Processando</h2>
        </div>
    </div>

</body>
</html>

<?php
    function getMeuBlog() {
        global $tabela_blogs;
        global $usuario_id;
        $consulta = new conexao();
        $consulta->solicitar("SELECT * FROM $tabela_blogs WHERE OwnersIds = '$usuario_id'");
        if(!$consulta->itens) {
            $blog = new Blog(0);
            $aux_id = $blog->getId();
        } else {
            $aux_id = $consulta->itens[0]['Id'];
        }
        return $aux_id;
    }
?>
