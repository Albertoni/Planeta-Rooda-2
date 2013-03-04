<?php
	include "../controller/controllerConsultaFatorPersonalidade.php";
	//Dados recebidos do controller

?>
<html>
	<head></head>
	<body>
		<label for="assistencia">Assit&ecirc;ncia:</label>
		<input type="text" id="assistencia" name="assistencia" value="<?=$assistencia?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="intracepcao">Intracep&ccedil;&atilde;o:</label>
		<input type="text" id="intracepcao" name="intracepcao" value="<?=$intracepcao?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="afago">Afago:</label>
		<input type="text" id="afago" name="afago" value="<?=$afago?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="deferencia">Defer&ecirc;ncia:</label>
		<input type="text" id="deferencia" name="deferencia" value="<?=$deferencia?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="afiliacao">Afiliacao:</label>
		<input type="text" id="afiliacao" name="afiliacao" value="<?=$afiliacao?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="dominancia">Domin&acirc;ncia:</label>
		<input type="text" id="dominancia" name="dominancia" value="<?=$dominancia?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="denegacao">Denega&ccedil;&atilde;o:</label>
		<input type="text" id="denegacao" name="denegacao" value="<?=$denegacao?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="desempenho">Desempenho:</label>
		<input type="text" id="desempenho" name="desempenho" value="<?=$desempenho?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="exibicao">Exibi&ccedil;&atilde;o:</label>
		<input type="text" id="exibicao" name="exibicao" value="<?=$exibicao?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="agressao">Agress&atilde;o:</label>
		<input type="text" id="agressao" name="agressao" value="<?=$agressao?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="ordem">Ordem:</label>
		<input type="text" id="ordem" name="ordem" value="<?=$ordem?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="persistencia">Persist&ecirc;ncia:</label>
		<input type="text" id="persistencia" name="persistencia" value="<?=$persistencia?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="mudanca">Mudan&ccedil;a:</label>
		<input type="text" id="mudanca" name="mudanca" value="<?=$mudanca?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="autonomia">Autonomia:</label>
		<input type="text" id="autonomia" name="autonomia" value="<?=$autonomia?>" maxlength=512 size=10 readonly="readonly" />
		<br>
		<label for="heterossexualidade">Heterossexualidade:</label>
		<input type="text" id="heterossexualidade" name="heterossexualidade" value="<?=$heterossexualidade?>" maxlength=512 size=10 readonly="readonly" />
		<br>
	</body>
</html>