<?php
	session_start();
	
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");	
//	require_once("verifica_user.php");
	require_once("blog.class.php");
	require_once("../../reguaNavegacao.class.php");
//	require_once("visualizacao_blog.php");
	
	
	$blog_id = isset($_GET['blog_id']) ? $_GET['blog_id'] : die("não foi fornecido id de blog");
	$blog = new Blog($blog_id);
	
	
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
<script type="text/javascript" src="../lightbox.js"></script>

<script language="javascript">
function coment(){
	if (navigator.appVersion == "4.0 (compatible; MSIE 7.0; Windows NT 5.1; FDM; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; AskTbATU2/5.8.0.12304)"){ //versao do ie 7
		document.getElementById('ie_coments').style.width = 85 + '%';
	}
}
</script>

<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();coment();">
<?php echo $_SERVER["HTTP_USER_AGENT"]; ?>

	<div id="light_box"></div>
    <div id="comentarios" class="bloco">
        <h1>TÍTULO DA POSTAGEM</h1>
        <img src="images/botoes/bt_fechar.png" id="abre_coment" class="fechar_coments" onmousedown="abreComents()" />
        <div class="recebe_coments">
        <ul class="sem_estilo" id="ie_coments">  
            <ul>
            <li class="tabela_blog">
                FULANO DE TAL - Donec dignissim purus sit amet ligula lobortis quis congue sem pulvinar. Ut velit diam, pretium non varius vitae, placerat sit amet libero. Nam ornare condimentum est ac tincidunt. Mauris vitae ligula tellus. Sed orci diam, tempus nec accumsan non, facilisis in nisl. Curabitur dictum magna non mi interdum nec auctor massa feugiat. Sed sed lacus ac nisl sagittis tincidunt vitae nec mi.
            </li>
            <li class="tabela_blog">
                FULANO DE TAL - Donec dignissim purus sit amet ligula lobortis quis congue sem pulvinar. Ut velit diam, pretium non varius vitae, placerat sit amet libero. Nam ornare condimentum est ac tincidunt. Mauris vitae ligula tellus. Sed orci diam, tempus nec accumsan non, facilisis in nisl. Curabitur dictum magna non mi interdum nec auctor massa feugiat. Sed sed lacus ac nisl sagittis tincidunt vitae nec mi.
            </li>
            <li class="tabela_blog">
                FULANO DE TAL - Donec dignissim purus sit amet ligula lobortis quis congue sem pulvinar. Ut velit diam, pretium non varius vitae, placerat sit amet libero. Nam ornare condimentum est ac tincidunt. Mauris vitae ligula tellus. Sed orci diam, tempus nec accumsan non, facilisis in nisl. Curabitur dictum magna non mi interdum nec auctor massa feugiat. Sed sed lacus ac nisl sagittis tincidunt vitae nec mi.
            </li>
        </ul>
            <li id="novo_coment">
                POSTAR NOVO COMENTÁRIO
            </li>
            <li>
                <textarea class="msg_dimensao" rows="10"></textarea>
            </li>
            <li>
                <div class="enviar" align="right">
                    <input type="image" src="images/botoes/bt_confir_pq.png" />
                </div>
            </li>                
        </ul>
        </div>
	</div>

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
            <input type="image" src="images/botoes/bt_criar_postagem.png" align="right"/>
    	</div>
        <div class="troca_paginas">
            <center>
            <div class="paginas_padding">
                        <a href="#" class="primeira"><< Primeira</a><a href="#" class="seguinte">< Anterior</a><a href="#" class="numero">1</a><a href="#" class="numero">2</a><a href="#" class="numero_atual">3</a><a href="#" class="numero">4</a><a href="#" class="numero">5</a><a href="#" class="seguinte">Próxima ></a><a href="#" class="primeira">Última >></a>
            </div>
            </center>
        </div>

    	<div id="esq" class="margem_paginas">
        	<div class="bloco" id="ident">
            	<h1><?=mb_strtoupper($blog->getTitle())?></h1>
<?php
// script para a exibição dos posts
$i = 0;
	foreach($blog->posts as $p) {
?>
                <div class="cor<?=$i%2+1?>">
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
                        <a id="abre_coment" onmousedown="abreComents()">Ver comentários</a>
                    </li>                    
            	</ul>              
                </div>
<?php 
		$i++; // alterna o estilo da div
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
                		<center><?=$owner->getName()?></center>	
					</li>
                    <li>
                    	<center><img src="images/desenhos/img_perfil.png" alt="avatar" /></center>
                    </li>
<?php
	}	
?>
				</ul>
            </div>
            <div class="bloco" id="post">
            	<h1><a class="toggle" id="toggle_post">▼</a> POSTAGENS</h1>
            	 <ul class="sem_estilo" id="caixa_post">
                    <li class="post_ano">
                		<a id="abre_mes" onmousedown="abreMes()">2010</a>	
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
                                </ul>
                                </li>
							</ul> 
						</li>
                    <li class="post_ano">
                		<a href="#">2009</a>	
					</li>

				</ul>
            </div>
             <div class="bloco" id="arquivos">
            	<h1><a class="toggle" id="toggle_arq">▼</a> ARQUIVOS</h1>
            	 <ul class="sem_estilo" id="caixa_arq">
                    <li class="tabela_blog">
                        	<a href="#">donec_dignissim01.jpg</a>
					</li>
                    <li class="tabela_blog">
                		<a href="#">donec_dignissim02.jpg</a>
					</li>
                    <li class="tabela_blog">
                        <a href="#">donec_dignissim03.jpg</a>
					</li>
                    <li class="tabela_blog">
                        	<a href="#">augue.ppt</a>
					</li>
                    <li class="tabela_blog">
                        	<a href="#">lacinia.ppt</a>
					</li>
                    <li class="tabela_oculta" id="arquivos_ocultos">
                    	<ul>
                            <li class="tabela_blog">
                                    <a href="#">dignissim.ppt</a>
                            </li>
                            <li class="tabela_blog">
                                    <a href="#">tellus.ppt</a>
                            </li>
						</ul> 
					</li>         
					<li class="mais_blog">
                    	<p class="mais_link">
                        <a id="abre_arquivos" onmousedown="abreArquivos()">Ver mais</a>
                        </p>
					</li>
				</ul>
            </div>
            <div class="bloco" id="link">
            	<h1><a class="toggle" id="toggle_link">▼</a> LINKS</h1>
            	 <ul class="sem_estilo" id="caixa_link">
                    <li class="tabela_blog">
                        	<a href="#">Link 1</a>
					</li>
                    <li class="tabela_blog">
                        	<a href="#">Link 2</a>
					</li>
                    <li class="tabela_blog">
                        	<a href="#">Link 3</a>
					</li>
                    <li class="tabela_blog">
                        	<a href="#">Link 4</a>
					</li>
                     <li class="tabela_blog">
                        	<a href="#">Link 5</a>
					</li>
                    <li class="tabela_oculta" id="links_ocultos">
                    	<ul>
                            <li class="tabela_blog">
                                    <a href="#">Link 6</a>
                            </li>
                            <li class="tabela_blog">
                                    <a href="#">Link 7</a>
                            </li>
						</ul> 
					</li> 
                    <li class="mais_blog">
                    	<p class="mais_link">
                        	<a id="abre_links" onmousedown="abreLinks()">Ver mais</a>
						</p>
					</li>
				</ul>
            </div>
		</div>
                    <div class="troca_paginas">
            	<center>
            	<div class="paginas_padding">
                            <a href="#" class="primeira"><< Primeira</a><a href="#" class="seguinte">< Anterior</a><a href="#" class="numero">1</a><a href="#" class="numero">2</a><a href="#" class="numero_atual">3</a><a href="#" class="numero">4</a><a href="#" class="numero">5</a><a href="#" class="seguinte">Próxima ></a><a href="#" class="primeira">Última >></a>
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
