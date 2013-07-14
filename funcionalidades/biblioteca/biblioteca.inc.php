<?php
// !SQLINJECTION
 function arrumar_data($data) {
	$nova_data_array = explode("-",$data);
	$nova_data = $nova_data_array[2]."-".$nova_data_array[1]."-".$nova_data_array[0];
	return $nova_data;
}


function listaComentarios($codMaterial,$codUsuario){		
	$select = "SELECT  * FROM BibliotecaComentarios WHERE codMaterial='$codMaterial' ORDER BY data DESC ";
		$result = db_busca($select);
		
		if ($result!=''){
		?>
				<div style="overflow: auto; width: 100%; height:400;">
		  <table width='100%' cellspacing='0' border='0'>	<?php
		
			$bgColor[0] = "#97E6FF";
			$bgColor[1] = "#33CCFF";
			$i = 1;
			
			foreach ($result as $a){
				
				$comentario=$a['comentario'];
				$data=$a['data'];
				$codEscritor=$a['codUsuario'];
				$select = "SELECT usuario_nome FROM usuarios WHERE usuario_id='$codEscritor' ";
				$busca = db_busca($select);
				$nomeEscriotor=$busca[0]['usuario_nome'];
				
				$i = 1-$i;
				$color = $bgColor[$i];
				$codComentario=$a['codComentario'];
				
				$select = "SELECT codComentario FROM BibliotecaComentariosVistos WHERE codComentario='$codComentario' and codUsuario='$codUsuario' ";
			  	$saida = db_busca($select);
			  	$viu =$saida[0]['codComentario'];
				
			  if ($viu==''){
					$ins="INSERT INTO BibliotecaComentariosVistos (codComentario,codUsuario,codMaterial)  VALUES ('$codComentario','$codUsuario','$codMaterial')";
						$faz = db_faz($ins);
					 
					}
				
			  $data = arrumar_data($data);	
			?><TR>
						<TD bgcolor="<?=$color; ?>" colspan='2' width='100%' height="50" border='0' class='texto_base_12' ><?=$comentario?></TD>
				</TR>  
				 <TR>
			 		<TD bgcolor="<?=$color; ?>"  width='60%' height="10" border='0' align:right class='descricao_11'>Comentário de  <?=$nomeEscriotor?> em <?=$data?></TD>
			</TR>
			 		 <?php
		  } 
		  ?></table>
			</div><?php			
	}
}

function enfraqueceCor($cor){ 
	$vals['r'] = hexdec(substr($cor, 0, 2));
	$vals['g'] = hexdec(substr($cor, 2, 2));
	$vals['b'] = hexdec(substr($cor, 4, 2));
	$tip='mais';
	foreach($vals as $a){
		if($a +35 > 255){
			$tip='menos';
		}
	}	
	if ($tip=='menos'){
		foreach( $vals as $val ){
			if($val -35 < 0){
				$v = 0;
			}else{
				$v = $val -35;
			} 
			$v = dechex($v);
			$out .= str_pad($v, 2, '0', STR_PAD_LEFT);
		}
	}else{
		foreach( $vals as $val ){
			if($val +35 > 255){
				$v = 255;
			}else{
				$v = $val +35;
			} 
			$v = dechex($v);
			$out .= str_pad($v, 2, '0', STR_PAD_LEFT);
		}
	}  
	return $out;
}

function data($data){
	$date = explode("-", $data);
	$ano  = $date[0];
	$mes  = $date[1];
	$dia  = $date[2];
	return "$dia/$mes/$ano";
}

function parteCima($buscaTitulo,$buscaQuem,$buscaPalavras){
	echo "<table width='100%' height='100%' cellspacing='0' cellpadding='0' border='0'>";
	echo "<tr><td valign='top' align='right'>";
	echo "<font class='campos_13'>Título do Material: </font>";
	echo "</td>";
	echo "<td>";
	echo "<input class='campos_x' type='text' name='titulo' value='$buscaTitulo'>";
	echo "<font class='campos_13'> ou</font>";
	echo "</td></tr>";
	echo "<tr><td valign='top' align='right'>";
	echo "<font class='campos_13'>Quem Enviou: </font>";
	echo "</td>";
	echo "<td>";
	echo "<input  class='campos_x' type='text' name='quem' value='$buscaQuem'>";
	echo "<font class='campos_13'> ou</font>";
	echo "</td></tr>";
	echo "<tr><td valign='top' align='right'>";
	echo "<font class='campos_13'>Palavras do Material: </font>";
	echo "</td>";
	echo "<td>";
	echo "<input  class='campos_x' type='text' name='palavras' value='$buscaPalavras'>";
	echo "</td></tr>";
	echo "</table>";
}

function enviaMaterial(){
	?>
	<script language='javascript'>
		function testaEnvia(){
			var1 = document.salva.titulo.value;
			var2 = document.salva.palavras.value;
			var3 = document.salva.con.value;				
			if ((var1!="")&(var2!="")&(var3!="")){
				document.salva.submit();
			}else{
				alert('Preencha todos os campos obrigatórios');
			}
		}
		function troca(a)  {				
			if(a=="link"){	
				document.salva.tipoMaterial.value="link";			
				tipoEnv.innerHTML="<table border='0' cellspacing='0'><tr><td><font class='campos_13'>Link:&nbsp;</font></td><td><input type='text' class='campos_x' size='21' name='con'></td></tr></table>";
			}
			if(a=="arquivo"){				
				document.salva.tipoMaterial.value="arquivo";
				tipoEnv.innerHTML="<table border='0' cellspacing='0'><tr><td><font class='campos_13'>Arquivo:&nbsp;</font></td><td><input type='file' size='8' class='campos_x' name='con'></td></tr></table>";
			}			
	 	 }
	</script>
	<?php
	echo"<form method='post' action='salva.php' name='salva' enctype='multipart/form-data' onsubmit='return testaCampos()'>";
	echo "<table width='100%' height='100%' valign='top' cellspacing='2' cellpadding='0' border='0'>";
	echo "<tr><td valign='top' align='right'>";
	echo "<font class='campos_13'>Título do Material:&nbsp;</font>";
	echo "</td>";
	echo "<td>";
	echo "<input type='text' class='campos_x' name='titulo' size='21' value=''>";	
	echo "</td></tr>";	
	echo "<tr><td valign='top' align='right'>";
	echo "<font class='campos_13'>Palavras do Material:&nbsp;</font>";
	echo "</td>";
	echo "<td>";
	echo "<input type='text' class='campos_x' name='palavras' size='21' value=''>";
	echo "</td></tr>";	
	echo "<tr><td colspan='2' align='right'>";
	echo "<font class='campos_13'>Link&nbsp;</font>";?>
	<input type='radio' name='tipo' value='link' checked='checked' onClick="troca('link');">
	<?php
	echo "<font class='campos_13'>Arquivo&nbsp;</font>";;?>
	<input type='radio' name='tipo' value='arquivo' onClick="troca('arquivo');">
	<?php
	echo "</td></tr>";
	echo "<tr><td colspan='2'align='right'>";
	
	echo "<DIV ID='tipoEnv'><table border='0' cellspacing='0'><tr><td><font class='campos_13'>Link:&nbsp;</font></td><td><input type='text' size='21' class='campos_x' name='con'></td></tr></table></DIV>";
	
	echo "</td></tr>";	
	echo "</table>";	
	echo "<input type='hidden' name='tipoMaterial' value='link'>";	
}

 function RemoverAcentos($s)
 {

	$s = ereg_replace("[áàâãª]","A",$s);
	$s = ereg_replace("[ÁÀÂÃ]","A",$s);
	$s = ereg_replace("[éèê]","E",$s);
	$s = ereg_replace("[ÉÈÊ]","E",$s);
	$s = ereg_replace("[óòôõº]","O",$s);
	$s = ereg_replace("[ÓÒÔÕ]","O",$s);
	$s = ereg_replace("[úùû]","U",$s);
	$s = ereg_replace("[ÚÙÛ]","U",$s);
	$s = str_replace("ç","C",$s);
	$s = str_replace("Ç","C",$s);
	
	return $s;

 }



function listaMateriais($codTurma,$codUsuario,$buscaTitulo,$buscaQuem,$buscaPalavras,$check,$edit=0,$associacao='A'){

	$cor1 = "00CCFF";
	$cor2 = enfraqueceCor($cor1);
	$i = 0;
	$select = "SELECT * FROM BibliotecaMateriais WHERE codTurma=$codTurma ORDER BY data DESC";
	$result = db_busca($select);		
	echo "<div style='height:100px; width:350px; overflow:auto;'>";
	echo "<table width='100%'>";
	foreach($result as $a){		
		$select = "SELECT * FROM usuarios WHERE usuario_id=$a[codUsuario]";
		$usrInfo = db_busca($select);
		$select = "SELECT codComentario FROM BibliotecaComentarios WHERE codMaterial=$a[codMaterial]";
		$comentarios = db_busca($select);
		$novos = 0;
		$coment = count($comentarios);
		foreach ($comentarios as $b){
			$select = "SELECT codMaterial FROM BibliotecaComentariosVistos WHERE ((codComentario=$b[codComentario])and(codUsuario=$codUsuario))";
			$novo = db_busca($select);
			if ($novo[0][codMaterial]==''){$novos++;}
		}		
		
		$foto = $usrInfo[0][usuario_foto];
		$nome = $usrInfo[0][usuario_nome];	
		$codusuario_teste =	 $usrInfo[0][usuario_id];
		$data = data($a[data]);	
		
		$pasta="/biblioteca" . "/" . $codTurma . "/" . $a[codMaterial] . "/" .$a[material];
		$link="abrirArquivo.php$pasta";		
		$mostra = false;
		if (($buscaTitulo=='')and($buscaPalavras=='')and($buscaQuem=='')){
			$mostra = true;
		}
		if ($buscaTitulo!=''){
			if (substr_count(strtoupper(RemoverAcentos($a[titulo])),strtoupper(RemoverAcentos($buscaTitulo)))!=0){$mostra = true;}
		}
		if ($buscaPalavras!=''){
			if (substr_count(strtoupper(RemoverAcentos($a[palavras])),strtoupper(RemoverAcentos($buscaPalavras)))!=0){$mostra = true;}
		}	
		if ($buscaQuem!=''){
			if (substr_count(strtoupper(RemoverAcentos($nome)),strtoupper(RemoverAcentos($buscaQuem)))!=0){$mostra = true;}
		}	
		if($mostra){
			$i++;
			if ($i%2==0){
					echo "<tr bgcolor='$cor1'><td valign='top'>";
				}else{
					echo "<tr bgcolor='$cor2'><td valign='top'>";
				}	
			echo "<table width='100%' border='0'>";
			echo "<tr><td width='45%' valign='top' class='campos_13'>Enviado por: </td><td class='texto_base_12'> $nome</td>";
			if(($edit==0) and (($codUsuario == $codusuario_teste) or ($associacao=='M') or ($associacao=='P')) ){echo "<td width='1'><a href='editar.php?c=$a[codMaterial]&a=$a[tipoMaterial]'>Editar</a></td></tr>";}
			echo "<tr><td valign='top' class='campos_13'>Título do Material: </td><td colspan='2' class='texto_base_12'> $a[titulo] </td></tr>";
			if ($a[palavras]!=''){
			echo "<tr><td valign='top' class='campos_13'>Palavras do Material: </td><td colspan='2' class='texto_base_12'> $a[palavras]</td></tr>";}
			echo "<tr><td valign='top' class='campos_13'>Data: </td><td colspan='2' class='texto_base_12'> $data</td></tr>";		
			echo "</table>";
			echo "<table width='100%'>";
			echo "<tr><td>";
			$link2 = $a[material];
			$link2 = str_replace('http://','',$link2);
			if($a[tipoMaterial]=='l'){?>
			<a href='#' onClick="window.open('http://<?=$link2?>','<?=$a[titulo]?>');";>[ <?=$link2?> ]</a>
			<?php }
			if($a[tipoMaterial]=='a'){			
				echo "<a href='$link' target=_blank>[ Abrir ]</a>";
			}
			echo "</td><td align='right'>";
			?>
			<A href="#" onClick="window.open('comentarios.php?m=<?=$a[codMaterial]; ?>', '', 'width=450px, height=700px')">(<?=$coment?>) Comentarios<?php if ($novos!=0){?>, (<?=$novos?>) Novos</a><?php }
			echo "</td></tr>";
			echo "</table>";
			echo "</td>";
			if(($check==1)and($edit==0)){
				echo "<td width='1'>";			
				echo "<input type='checkbox' name='excluir[]' value='$a[codMaterial]'>";
				echo "</td>";
			}
			echo "</tr>";
		}		
	}
	echo "</table>";	
	echo "</div>";
}

function editar($codMaterial){
	$select = "SELECT * FROM BibliotecaMateriais WHERE codMaterial=$codMaterial";
	$result = db_busca($select);	
	$titulo = $result[0][titulo];
	$palavras = $result[0][palavras];
	$tipo = $result[0][tipoMaterial];
	$material = $result[0][material];
	echo "<table width='100%' height='100%' valign='top' cellspacing='2' cellpadding='0' border='0'>";
	echo "<tr><td valign='top' align='right'>";
	echo "<font class='campos_13'>Título do Material:&nbsp;</font>";
	echo "</td>";
	echo "<td>";
	echo "<input type='text' name='titulo' size='21' value='$titulo'>";	
	echo "</td></tr>";	
	echo "<tr><td valign='top' align='right'>";
	echo "<font class='campos_13'>Palavras do Material:&nbsp;</font>";
	echo "</td>";
	echo "<td>";
	echo "<input type='text' name='palavras' size='21' value='$palavras'>";
	echo "</td></tr>";		
	echo "<tr><td colspan='2'align='right'>";
	
	echo "<DIV ID='tipoEnv'><table border='0' cellspacing='0'><tr><td>";
	if($tipo=='l'){
		echo "<font class='campos_13'>Link:</font></td>";
	}else{
		echo "<font class='campos_13'>Arquivo:</font></td>";
	}	
	if($tipo=='l'){
		echo "<td><input type='text' size='21' name='link' value='$material'></td>";
	}else{
		echo "<td><font class='descricao_11'>$material</font></td>";
	}
	echo "</tr></table></DIV>";
	
	echo "</td></tr>";	
	echo "</table>";	
}


	 function revisar_arquivos ($tipo_arquivo,$erro,$nome) {
	  //função escrita por Daniel Corrêa com o objetivo de identificar e restringir determinados tipos de arquivos
	 

	  $nomeExtensao	=explode(".",$nome); //desmancha o nome do arquivo em nome no indice [0] e a extenção no indice [n](último)
	  $numeroDeCampos = count($nomeExtensao); // descobre qual o último elemento do array, aquele q terá a extenção
	  $extensao		=$nomeExtensao[$numeroDeCampos -1]; //pega soh a extenção
	  
	  
	  
		  if($erro == NULL) {
		  if(	$tipo_arquivo == ""  ||
				$extensao	 == "exe"
//			  $tipo_arquivo == "application/x-msdownload" || 
//			  $tipo_arquivo == "application/octet-stream" ||
//			  $tipo_arquivo == "application/x-msdos-program" 
			 //É mais fácil classificar arquivos permitidos, porém o objetivo aqui é o oposto
		  
		  )  
		  {		//Caso contenha alguma extensão acima
				  $valido = 0;
				  echo "<script language=javascript>alert('Arquivos executáveis não são permitidos!')</script>"; 
		  } else {$valido = 1;}
	 
				  
	  } else { 
	  //Caso contenha algum erro no arquivo --> Ex: tamanho
	  $valido = 0;	 
	  }
		  	
		return $valido;
	  }



?>
