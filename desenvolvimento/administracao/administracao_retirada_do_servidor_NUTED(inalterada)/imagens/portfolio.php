<?php
session_start();
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");

$id_usuario = $_SESSION['SS_usuario_id'];
$nome_usuario = $_SESSION['SS_usuario_nome'];

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="planeta.css" />
<link type="text/css" rel="stylesheet" href="portfolio.css" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="portfolio.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();">

<div id="topo">
	<div id="centraliza_topo">
		<p id="hist"><a href="#">Planeta ROODA</a> > <a href="#">Professor Fulaninho de Tal</a> > <a href="#">Portfólio</a></p>        
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
                <div id="rel"><p id="balao"><?="Bem vindo $nome_usuario ."?>
				</p></div>
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
            <input align="left" type="image" src=<?="../../images/botoes/bt_voltar.png"?> />            
			<a href="portfolio_novo_projeto.php" align="right" >
			    <img src=<?="../../images/botoes/bt_novo_projeto.png"?> border="0" align="right"/>
			</a>
        </div>
        <div id="esq">
        	<div id="procurar_proj" class="bloco">
            	<h1>PROCURAR PROJETO</h1>
                	<form name='procurar_projetos' method='post' >
                    	<ul class="sem_estilo">
                        	<li><input type="text" name="projeto_procurado" /></li>
                            <li><input type="radio" name="p_proj" value="titulo" />Título</li>
                            <li><input type="radio" name="p_proj" value="conteudos" />Conteúdos Abordados</li>
                            <li><input type="radio" name="p_proj" value="tags" />Palavras do Projeto</li>
                            <li><div class="enviar" align="right"><input type="submit" name="bt_procurar_projetos" src=<?="../../images/botoes/bt_procurar.png"?> /></div>
                            </li>
						</ul> 
					</form>
            </div>
			<div id="projetos" class="bloco">
            	<h1>
                	<div class="abas_port aberto" id="aba_andamento"> PROJETOS EM ANDAMENTO</div>
                    <div class="abas_port fechado" id="aba_encerrado"> PROJETOS ENCERRADOS</div>
                </h1>
            <? 
			$condicao = "owner_id = $id_usuario";
			
			if (isset($_POST['bt_procurar_projetos'])){			
				$procurar = $_POST['projeto_procurado'];
			    switch($_POST['p_proj']){
				    case "titulo":
					    $condicao .= " AND titulo LIKE '%$procurar%'";
					break;
					case "conteudos":
						$condicao .= " AND descricao LIKE '%$procurar%'";
					break;
					case "tags":
						$condicao .= " AND tags LIKE '%$procurar%'";
					break;
				}
			}
			
			global $tabela_portfolioProjetos;
			$consulta = new conexao();
			$consulta->solicitar("SELECT * FROM $tabela_portfolioProjetos WHERE $condicao");
			$consulta->fechar();
			
			// $projOpcao = "proj_encerrados";
			$projOpcao = "proj_andamento";
			for($opcao=1 ; $opcao<=2 ; $opcao++){				
				$cor = "cor1";
				?>  <div id=<?=$projOpcao ?> > <?
				for ($i=0 ; $i < count($consulta->itens) ; $i++){
				    if ((($opcao==1) and ($consulta->resultado['emAndamento']==true)) or (($opcao==2) and ($consulta->resultado['emAndamento']==false)) ){
					?>					
						<div class=<?=$cor ?> >
							<ul class="sem_estilo">							
								<li class="texto_port">
									<span class="valor">
										<a class="port_titulo" href="portfolio_projeto.php?projeto_id=<?=$consulta->resultado['id']?>">
											<?=$consulta->resultado['titulo'] ?>
										</a>
									</span>								
								</li>
								<li class="texto_port"><span class="dados">Autor:</span><span class="valor"><?=$consulta->resultado['autor'] ?></span></li>
								<li>
								    <span class="dados">Descrição:</span>
									<span class="valor"><?=$consulta->resultado['descricao'] ?></span>									
								</li>
								<a class="encerrar" href="#">[Encerrar projeto]</a>								
							</ul>
						</div>
					
					<?	
					}
					if ($cor === "cor1")
					    {$cor = "cor2";} 
					else {$cor = "cor1";};	
					$consulta->proximo();
				} //fim for de dentro (consulta de itens)
				?>  </div> <?    
				$consulta->primeiro();
				$projOpcao = "proj_encerrados";
			}//fim for de fora (troca das opcoes)
			
			?>    	
                  
            </div> <!-- fim da div de id="projetos" -->
            
			
        </div>
        <div class="bts_baixo">
            <input align="left" type="image" src=<?="../../images/botoes/bt_voltar.png"?> />
            <a href="portfolio_novo_projeto.php" align="right" >
			    <img src=<?="../../images/botoes/bt_novo_projeto.png"?> border="0" align="right"/>
			</a>
        </div>
    </div><!-- Fecha Div conteudo -->
    
    </div><!-- Fecha Div conteudo_meio -->   
    <div id="conteudo_base">
    </div><!-- para a imagem de fundo da base -->
    </div><!-- fim da geral -->
    
</body>
</html>
