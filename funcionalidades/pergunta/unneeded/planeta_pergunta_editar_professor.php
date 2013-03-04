<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="../forum/forum.css" />
<link type="text/css" rel="stylesheet" href="planeta_pergunta.css" />

<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="planeta_pergunta.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="../../forum.js"></script>
<script type="text/javascript" src="../../blog.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="../../planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();">
<img src="../../images/fundos/fundo4.png" width="100%" height="100%" style="position:fixed" />
<div id="topo">
	<div id="centraliza_topo">
        <p id="hist"><a href="#">Planeta ROODA</a> > <a href="#">Professor Fulaninho de Tal</a> > <a href="#">Planeta Pergunta</a></p>
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
                <div id="rel"><p id="balao"><b>Instruções:</b>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. 
				Etiam eget ligula eu lectus lobortis condimentum. Aliquam nonummy auctor massa. Pellentesque 
				habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p></div>

            </div>
        </div>
        <div id="ajuda_base"></div>
    </div>
</div><!-- fim do cabecalho -->
    
<a name="topo"></a>
    
<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->

<!-- **************************
			conteudo
***************************** -->
    <div id="conteudo"><!-- tem que estar dentro da div 'conteudo_meio' -->

    <form>
    
    <div class="bts_cima">
    <div class="bts_msg" align="right"> 
    <input type="image" id="resultado" src="../../images/botoes/bt_resultados.png" />
                  	
    
 
                    
   	
    
   
                    
    </div>
  
    
    </div>
    
    <div id="nova_mensagem" class="bloco">
    <h1></h1>
        <ul class="sem_estilo">

            <li><img id="cancela_msg" src="../../images/botoes/bt_cancelar_pq.png" /></li>
            <li><textarea class="msg_dimensao" rows="10"></textarea></li>
            <li><input type="image" id="confirm_msg" src="../../images/botoes/bt_confir_pq.png" /></li>
        </ul>
    </div>
    
	<div id="bloco_mensagens" class="bloco">
        <h1>QUESTIONÁRIOS</h1>
        <div class="cor1">

        	<ul>
            	<li class="tabela">
                  <div class="info">
                <p class="nome"><b>Título Questionário</b></p>
               	<p class="data">14/02/2011</p>
                <br /><div id="autor" class="criado_por">Criado por:Fulano</div>
                    
                  
                  </div>

                  
                  	 
                   
                    
             
                </li>
                <li>
                  
                  <p class="texto_resposta">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam eget ligula eu lectus lobortis condimentum. Aliquam nonummy auctor massa. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. </p>
                  	
                </li>
                
                <li><div class="enviar" align="right" style="margin-left:442px">
                <a href="planeta_pergunta_editar_p.html"><input  type="image" src="../../images/botoes/bt_editar.png" /></a>
                 <a class="encerrar" href="#"><input  type="image" src="../../images/botoes/bt_excluir.png" /></a>
                </div></li>
            </ul>
        </div>
       
	</div><!-- fim da div topicos -->
       <div id="criar_topico" class="bloco">
         <div class="troca_paginas">
            <center>
            <div class="paginas_padding">
                        <a href="#" class="primeira"><< Primeira</a><a href="#" class="seguinte">< Anterior</a><a href="#" class="numero">1</a><a href="#" class="numero">2</a><a href="#" class="numero_atual">3</a><a href="#" class="numero">4</a><a href="#" class="numero">5</a><a href="#" class="seguinte">Próxima ></a><a href="#" class="primeira">Última >></a>
            </div>
            </center>
        
        </div>
        <h1>PERGUNTAS </h1> 
        
        <ul class="sem_estilo">
            
                <li class="tabela">
                <br/>
                <b>Pergunta:</b>
                <br />
                Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam eget ligula eu lectus lobortis condimentum. Aliquam nonummy auctor massa. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.       
                </li> 
                <br />         
                    <li>
                        <b>Opção 1:</b>
                        <div id="opcção" style="margin-top:10px">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
                        <input type="radio" /></div>
                        
                    </li>
            <li class="espaco_linhas">
               		<li class="tabela">
               		 <b>Opção 2:</b>
                        <div id="opcção" style="margin-top:10px">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
                        <input type="radio" /></div>
                     
                     <li class="espaco_linhas">
               		<li class="tabela">
               		 <b>Opção 3:</b>
                        <div id="opcção" style="margin-top:10px">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
                        <input type="radio" /></div>
                     
                     <li class="espaco_linhas">
               		<li class="tabela">
               		 <b>Opção 4:</b>
                        <div id="opcção" style="margin-top:10px">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
                        <input type="radio" /></div>
                     
                       
                     
                      
                
                
        
    </ul>
    </div>
   
     
    


    
    
    
    
    
    
    
     <div class="bts_cima">
    
    <a href="planeta_pergunta.html"><input type="image" src="../../images/botoes/bt_voltar.png" align="left" style="margin-top:20px"/></a>
        <a href=""><input type="image" src="../../images/botoes/bt_exportar.png" align="right" style="margin-top:20px"/></a>
         
    
                    
   	
    
   
                    
  
  
    
    </div>
    
  
  
    
    


    
    
     
    
    </div>
    <!-- fim do conteudo -->

</div>   
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->

</body>
</html>
    
    
    
    
    
    
    
    
    
    
    
    
