<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Planeta Rooda 3.0</title>
		<link type="text/css" rel="stylesheet" href="../planeta.css" />
		<link type="text/css" rel="stylesheet" href="criar_personagem.css" />
	</head>
	<body>
		<div id="topo">
			<div id="centraliza_topo">
				<!-- regua -->
				<p id="bt_ajuda">
					<span class="troca">OCULTAR AJUDANTE</span><span style="display:none" class="troca">CHAMAR AJUDANTE</span>
				</p>
			</div>
		</div>
		<div id="geral">
			<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
			<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->
				<!-- **************************
							conteudo
					***************************** -->
				<div id="conteudo" style="position:relative;top:0;left:0;margin:0 auto;"> <!-- tem que estar dentro da div 'conteudo_meio' -->
					<div id="criar_personagem" class="bloco">
						<canvas id="canvas_personagem" width="167" height="355"></canvas>
					</div>
				</div><!-- fim do conteudo -->
			</div> <!-- fim do conteudo_meio -->  
			<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->
		</div><!-- fim da geral -->
		<script src="engine.js"></script>
		<script>

		var engine = new Engine();
		window.onkeydown = engine.keysPressed;
		window.onkeyup = engine.keysPressed;

		</script>
		<script src="graphics.js"></script>
		<script src="player.js"></script>
	</body>
</html>
