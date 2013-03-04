<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("verifica_user_biblio.php");
	require("sistema_biblioteca.php");
	//require("visualizacao_forum.php");
	
	
	$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
	if ($VERIFICA_USER_ERRO_ID == 0) {
		$BIBLIO = new forum($BIBLIO_ID);
		$BIBLIO->configBD($BD_host1,$BD_base1,$BD_user1,$BD_pass1,$tabela_biblio,$tabela_usuarios);
		/*$FORUM->topicos($pagina);
		
		$paginas = array();
		$paginas = $FORUM->paginas($pagina,10);*/
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="biblioteca.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="biblioteca.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();">

<div id="topo">
	<div id="centraliza_topo">
        <p id="hist"><a href="#">Planeta ROODA</a> > <a href="#">Professor Fulaninho de Tal</a> > <a href="#">Biblioteca</a></p>
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
    
    <div id="esq"><!-- coluna da esquerda -->
        <div class="bloco" id="procurar_material">
        	<h1>PROCURAR MATERIAL</h1>
                <form>
                <ul class="sem_estilo">
                <li><input type="text" /></li>
                <li><input type="radio" name="p_material" />Título</li>
                <li><input type="radio" name="p_material" />Autor</li>
                <li><input type="radio" name="p_material" />Palavras do Material</li>
                <li><div class="enviar" align="right"><input type="image" src="../../images/botoes/bt_procurar.png"/></div>
                </li>
                </ul>
                </form>
        </div><!-- fim da procurar_material -->
        <div class="bloco" id="enviar_material">
        	<h1>ENVIAR MATERIAL</h1>
            	<form>
                <ul class="sem_estilo">
                <li><input type="text" id="envia_titulo" /></li>
                <li class="espaco_base">Título</li>
                <li><input type="text" id="envia_autor" /></li>
                <li class="espaco_base">Autor</li>
                <li><input type="text" id="envia_tags" /></li>
                <li class="espaco_base">Palavras do Material</li>
                <li><input  type="text" id="tipo_link" /></li>
                <li id="tipo_arquivo"><div id="browse"><input type="file" id="file_real" size="1" onclick="animacao('procurar()');" /></div>
                	<input type="text" readonly="readonly" id="falso_path" /></li>
                <li><input type="radio" name="e_material" onclick="tipoMaterial('tipoLink');" />
                	Link
                	<input type="radio" name="e_material" onclick="tipoMaterial('tipoArquivo');" />
                    Arquivo	</li>          
                <li><div class="enviar" align="right"><input type="image" src="../../images/botoes/bt_enviar.png"/></div></li>
                </ul>
                </form>
        </div>
    </div><!-- fim da enviar_material -->
    
    <div id="dir"><!-- coluna da direita -->
        <div class="bloco" id="arquivos_enviados">
        	<h1>ARQUIVOS ENVIADOS</h1>
            <ul  id="" class="bloco_arquivos_enviados1">
				<li><span class="dados">Enviado&nbsp;Por:</span><span class="valor">Fulaninho de Tal</span></li>
				<li><span class="dados">Autor:</span><span class="valor"><input type="text" /></span></li>
				<li><span class="dados">Título&nbsp;do&nbsp;Material:</span><span class="valor"><input type="text" /></span></li>
				<li><span class="dados">Palavras&nbsp;do&nbsp;Material:</span><span class="valor"><input type="text" /></span></li>
				<li><span class="dados">Data:</span><span class="valor">25/02/2010</span></li>
				<li><span class="valor"><a href="#">www.nuted.edu.ufrgs.br/planetarooda/planeta.php</a></span></li>
				<li><span class="valor"><a href="#">0 Comentários</a></span></li>
				<li><div class="enviar" align="right">
				<input type="image" class="confirmar_edicao" src="../../images/botoes/bt_confir_pq.png" /></div></li>
            </ul>
			<?php
			
			for
			
			?>
			
        </div>
    </div><!-- fim da arquivos_enviados -->
    
    </div>
    <!-- fim do conteudo -->

</div>   
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->

</body>
</html>