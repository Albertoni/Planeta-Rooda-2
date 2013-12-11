<?php




	$ARQUIVO_PHP_CONTROLLER = "../controller/controllerInsercaoFatorPersonalidade.php";



?>
<html>
	<head></head>
	<body>
		Registrar Fatores de Personalidade
		<br><br><br>
		
		<form method="post" action="<?=$ARQUIVO_PHP_CONTROLLER?>">
			<label for="nomeUsuario">Nome do Usu&aacute;rio:</label>
			<input type="text" id="nomeUsuario" name="nomeUsuario" maxlength=512 size=80 />
			<br>
			<label for="assistencia">Assit&ecirc;ncia:</label>
			<input type="text" id="assistencia" name="assistencia" maxlength=512 size=10 />
			<br>
			<label for="intracepcao">Intracep&ccedil;&atilde;o:</label>
			<input type="text" id="intracepcao" name="intracepcao" maxlength=512 size=10 />
			<br>
			<label for="afago">Afago:</label>
			<input type="text" id="afago" name="afago" maxlength=512 size=10 />
			<br>
			<label for="deferencia">Defer&ecirc;ncia:</label>
			<input type="text" id="deferencia" name="deferencia" maxlength=512 size=10 />
			<br>
			<label for="afiliacao">Afiliacao:</label>
			<input type="text" id="afiliacao" name="afiliacao" maxlength=512 size=10 />
			<br>
			<label for="dominancia">Domin&acirc;ncia:</label>
			<input type="text" id="dominancia" name="dominancia" maxlength=512 size=10 />
			<br>
			<label for="denegacao">Denega&ccedil;&atilde;o:</label>
			<input type="text" id="denegacao" name="denegacao" maxlength=512 size=10 />
			<br>
			<label for="desempenho">Desempenho:</label>
			<input type="text" id="desempenho" name="desempenho" maxlength=512 size=10 />
			<br>
			<label for="exibicao">Exibi&ccedil;&atilde;o:</label>
			<input type="text" id="exibicao" name="exibicao" maxlength=512 size=10 />
			<br>
			<label for="agressao">Agress&atilde;o:</label>
			<input type="text" id="agressao" name="agressao" maxlength=512 size=10 />
			<br>
			<label for="ordem">Ordem:</label>
			<input type="text" id="ordem" name="ordem" maxlength=512 size=10 />
			<br>
			<label for="persistencia">Persist&ecirc;ncia:</label>
			<input type="text" id="persistencia" name="persistencia" maxlength=512 size=10 />
			<br>
			<label for="mudanca">Mudan&ccedil;a:</label>
			<input type="text" id="mudanca" name="mudanca" maxlength=512 size=10 />
			<br>
			<label for="autonomia">Autonomia:</label>
			<input type="text" id="autonomia" name="autonomia" maxlength=512 size=10 />
			<br>
			<label for="heterossexualidade">Heterossexualidade:</label>
			<input type="text" id="heterossexualidade" name="heterossexualidade" maxlength=512 size=10 />
			<br>
			<input type="submit" value="Registrar">
		</form>
	</body>
</html>