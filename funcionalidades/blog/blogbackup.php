<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
    require("../../login.class.php");	
//	require("verifica_user.php");
	require("blog.class.php");
	require("../../file.class.php");
	require("../../link.class.php");
//	require("visualizacao_blog.php");
	$usuario_id = $_SESSION['SS_usuario_id'];	
	
	//------------------------------------------------------------------------------------------------------------
	//*********************************************** TESTES *****************************************************
	//------------------------------------------------------------------------------------------------------------
	/*
	$login = new Login("roger2","123456");
	//$login = new Login("rngouveia","fdgshaF1243");	
	
	echo($login->respostaJS());
	
	echo("usuario_id: ".$_SESSION['SS_usuario_id']."<BR />"  ) ;
	echo("usuario_nome: ".$_SESSION['SS_usuario_nome']."<BR />" );
	echo("nivel: ".$_SESSION['SS_usuario_nivel_sistema']."<BR />" );
	echo("login: ".$_SESSION['SS_usuario_login']."<BR />" );
	echo("mail: ".$_SESSION['SS_usuario_email']."<BR />" );
	echo("personagem_id:".$_SESSION['SS_personagem_id']."<BR />" );
	$data = '{ "valor":"1", "texto":"Erro no servidor"}';
	//echo($data);
	$file = new File(3,5,"roger.JPG");
	$file->download();
	echo("yey");
	
	die("MOTHERFUCKER");
	 */
	//-----------------------------------------------------------------------------------------------------------
    //********************************************** FIM TESTES *************************************************
    //-----------------------------------------------------------------------------------------------------------	
	 
	$blog_id = isset($_GET['blog_id']) ? $_GET['blog_id'] : die("não foi fornecido id de blog");
	$blog = new Blog($blog_id);
	
	
	//$login = new Login("rngouveia","3232323");
	
	
	
	$ini = isset($_GET['ini']) && $_GET['ini'] >= 0 ? floor($_GET['ini']/$blog->getPaginacao())*$blog->getPaginacao() : 0;
	$ini = $ini < 0 ? 0 : $ini;
	$ini = $ini > $blog->getSize() ? floor($blog->getSize()/$blog->getPaginacao())*$blog->getPaginacao() : $ini;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="planeta.css" />
<link type="text/css" rel="stylesheet" href="blog.css" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="planeta.js"></script>
<script type="text/javascript" src="blog.js"></script>	
<script type="text/javascript" src="blog_ajax.js"></script>	

<script language="javascript">

function coment(){
	if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
		document.getElementById('ie_coments').style.width = 85 + '%';
		$('.bloqueia ul').css('margin-right','17px');
	}
}
</script>

<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');Init();inicia();coment();">

	<div id="descricao"></div>

<div id="fundo_lbox"></div>
    <div id="light_box" class="bloco">
	</div>

<div id="topo">
<div id="centraliza_topo">
        <p id="hist"><a href="#">Planeta ROODA</a> > <a href="#">Professor Fulaninho de Tal</a> > <a href="#">Blog</a></p>
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
            	<div id="personagem"></div>
                <div id="rel"><p id="balao">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. 
				Etiam eget ligula eu lectus lobortis condimentum. Aliquam nonummy auctor massa. Pellentesque 
				habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p></div>
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
            <input type="image" src="images/botoes/bt_voltar.png" align="left"/>
            <a href="blog_postagem.php?blog_id=<?=$blog_id?>"><img src="images/botoes/bt_criar_postagem.png" border="0" align="right"/></a>
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
            	<h1><?=mb_strtoupper($blog->getTitle())?></h1>

<?php
// script para a exibição dos posts
	$cor_i = 0;
	for($i=$ini;($i<$ini+$blog->getPaginacao()) && ($i<$blog->getSize());$i++) {
		$p = $blog->posts[$i];
?>
                <div class="cor<?=$cor_i%2+1?>">
            	<ul class="sem_estilo">
                	<li class="tabela_blog">
                    	<span class="titulo">
                        	<?=$p->getTitle()?>
						</span>
                        <span class="data">
                        	<?=$p->getDate()?>
						</span>
					</li>
                    <li class="tabela_blog">
                        	<?=$p->getText()?>
                    </li>
                    <li class="tabela_blog">
                    	Por <?=$p->getAuthor()->getName()?><br />
						<a onmousedown="carregaHTML('light_box','ver_comentarios','post_id=<?=$p->getId()?>');abreFechaLB()">Ver comentários</a>
                    </li>                    
            	</ul>              
                </div>
<?php 
		$cor_i++; // alterna o estilo da div
	}
?>
                
            </div>
		</div>
    	<div id="dir" class="margem_paginas">
        	<div class="bloco" id="perfil">
            	<h1 ><a class="toggle" id="toggle_perfil">▼</a> PERFIL</h1>
                <ul class="sem_estilo" id="caixa_perfil">
<?php
	foreach($blog->owners as $owner) {
?>
                    <li class="tabela_blog">
                		<center>                    
						<?php if($owner->getId()==$usuario_id) { ?>				
							<i><?=$owner->getName()?></i>
                        <?php } else { ?>    
	                        <?=$owner->getName()?>
                        <?php } ?>    
                        </center>	
					</li>
                    <li>
                    	<center><img src="images/desenhos/img_perfil.png" alt="avatar" /></center>
                        <br />
                    </li>
<?php
	}	
?>
				</ul>
            </div>
            <div class="bloco" id="post">
            	<h1><a class="toggle" id="toggle_post">▼</a> POSTAGENS</h1>
            	<div class="bloqueia">
                    <ul class="sem_estilo" id="caixa_post">
                        <li class="post_ano">
                            <a id="abre_mes">2010</a>	
                        </li>
                        <li class="tabela_oculta" id="mes_oculto">
                            <ul>
                                <li class="post_mes">
                                    <a href="#">Abril</a>	
                                </li>
                                <li>
                                    <ul>
                                         <li class="post_topico">
                                            <a href="#">Tópico 1</a>	
                                        </li>
                                         <li class="post_topico">
                                            <a href="#">Tópico 2</a>	
                                        </li>
                                        <li class="post_topico">
                                            <a href="#">Tópico 3</a>	
                                        </li>

                                    </ul>
                                </li>
                            </ul> 
                        </li>
                        <li class="post_ano">
                            <a href="#">2009</a>	
                        </li>
                    </ul>
                </div>
            </div>
            <div class="bloco" id="arquivos">
                <h1><a class="toggle" id="toggle_arq">▼</a> ARQUIVOS </h1><div class="add">adicionar</div>
                <div class="bloqueia">	
                    <ul class="sem_estilo" id="caixa_arq">
                        <li class="tabela_blog">
                            <a href="#">donec_dignissim01.jpg</a> 
                            <div class="bts_caixa"><img class="ver" src="images/botoes/bt_olho.png" /><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                        <li class="tabela_blog">
                            <a href="#">donec_dignissim02.jpg</a> 
                            <div class="bts_caixa"><img class="ver" src="images/botoes/bt_olho.png" /><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                        <li class="tabela_blog">
                            <a href="#">donec_dignissim03.jpg</a>
                            <div class="bts_caixa"><img class="ver" src="images/botoes/bt_olho.png" /><img class="apagar" src="images/botoes/bt_x.png" /></div>

                        </li>
                        <li class="tabela_blog">
                            <a href="#">augue.ppt</a>
                            <div class="bts_caixa"><img class="ver" src="images/botoes/bt_olho.png" /><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                        <li class="tabela_blog">
                            <a href="#">lacinia.ppt</a>
                            <div class="bts_caixa"><img class="ver" src="images/botoes/bt_olho.png" /><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                        <li class="tabela_blog">
                            <a href="#">dignissim.ppt</a>
                            <div class="bts_caixa"><img class="ver" src="images/botoes/bt_olho.png" /><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                        <li class="tabela_blog">
                            <a href="#">tellus.ppt</a>
                            <div class="bts_caixa"><img class="ver" src="images/botoes/bt_olho.png" /><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="bloco" id="link">
            	<h1><a class="toggle" id="toggle_link">▼</a> LINKS</h1><div class="add">adicionar</div>
                <div class="bloqueia">
                    <ul class="sem_estilo" id="caixa_link">
                        <li class="tabela_blog">
                            <a href="#">Link 1</a>
                            <div class="bts_caixa"><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                        <li class="tabela_blog">
                            <a href="#">Link 2</a>
                            <div class="bts_caixa"><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                        <li class="tabela_blog">
                                <a href="#">Link 3</a>
                            <div class="bts_caixa"><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                        <li class="tabela_blog">
                                <a href="#">Link 4</a>
                            <div class="bts_caixa"><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                         <li class="tabela_blog">
                                <a href="#">Link 5</a>
                            <div class="bts_caixa"><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                         <li class="tabela_blog">
                                <a href="#">Link 6</a>
                            <div class="bts_caixa"><img class="apagar" src="images/botoes/bt_x.png" /></div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="bloco" id="tag">
            	<h1><a class="toggle" id="toggle_tag">▼</a> TAGS</h1>
                <div class="bloqueia">
                    <ul class="sem_estilo" id="caixa_tag">
                        <li class="tabela_blog">
                                <a href="#">Tag 1</a>
                        </li>
                        <li class="tabela_blog">
                                <a href="#">Tag 2</a>
                        </li>
                        <li class="tabela_blog">
                                <a href="#">Tag 3</a>
                        </li>
                        <li class="tabela_blog">
                                <a href="#">Tag 4</a>
                        </li>
                         <li class="tabela_blog">
                                <a href="#">Tag 5</a>
                        </li>
                        <li class="tabela_blog">
                                <a href="#">Tag 6</a>
                        </li>
                        <li class="tabela_blog">
                                <a href="#">Tag 7</a>
                        </li>
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
            <input type="image" src="images/botoes/bt_voltar.png" align="left"/>
            <input type="image" src="images/botoes/bt_criar_postagem.png" align="right"/>
    	</div>
    
    </div><!-- Fecha Div conteudo -->
    
    </div><!-- Fecha Div conteudo_meio -->   
    <div id="conteudo_base">
    </div><!-- para a imagem de fundo da base -->
    	
</div><!-- fim da geral -->

</body>
</html>
