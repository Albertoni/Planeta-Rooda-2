<?php
require_once("cor.fatores.php");

$acor = $cor[$_GET['fer'].".".$_GET['subf'].".".$_GET['fator']];
echo	"<ul style='list-style-type:none'>".
			"<li>".
				"<img src='quadradinho_2.php?r={$acor[0]}&g={$acor[1]}&b={$acor[2]}' width='11px' height='11px'  /> ".
				mb_strtoupper($_GET['subf'])." - ".$fator." : ".$_GET['val'].
			"</li>".
		"</ul>";

