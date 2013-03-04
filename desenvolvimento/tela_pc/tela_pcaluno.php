<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../login.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="planeta.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="login_ie6.js"></script>
<![endif]-->

</head>

<body onload="entra();" >
	<div style="margin-left:40%">
		<div id="fundo_ilusta" style="z-index:2;position:absolute;top:50px;left:60%; margin-left:-250px;">
			<img src="fundo_ilust.png" />
		</div>
		<div id="ilusta" style="z-index:3;position:absolute;top:50px; left:60%; margin-left:-100px;">
			<img src="capa_planeta.png"  />
		</div>

		<a href="../../funcionalidades/blog/blog.php?blog_id=meu_blog">
			<img src="blog.png" style="z-index:4;margin-top:250px; margin-left:-15px; position:absolute" />
		</a>
		<a href="../../funcionalidades/arte/planeta_arte2.php">
			<img src="planeta_arte.png" style="z-index:5;margin-top:340px; position:absolute" />
		</a>
		<a href="../../funcionalidades/criar_personagem/criar_personagem.php?id_char_as=<?=$_SESSION['SS_personagem_id']?>">
			<img src="mudar_aparencia.png" style="z-index:6;margin-top:410px; position:absolute" />
		</a>
	</div>
</body>
</html>
