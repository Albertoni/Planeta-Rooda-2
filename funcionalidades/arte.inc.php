<?php
	require_once("../sistema.inc.php");	
	
	function montaBuscaArte($palavras,$data_ini,$data_fim,$codTurma) {
		$query = "";
		if(($palavras != "") && ($data_ini != "") && ($data_fim != "")) {
			$query = "SELECT * FROM ArtesDesenhos WHERE (Palavras LIKE '%$palavras%' OR Titulo LIKE '%$palavras%') AND (Data BETWEEN '$data_ini' AND '$data_fim') AND (Status = 'a') AND (CodTurma='$codTurma')";		
		} else {
			if($palavras != "") {
				$query = "SELECT * FROM ArtesDesenhos WHERE Palavras LIKE '%$palavras%' OR Titulo LIKE '%$palavras%' AND (Status = 'a') AND (CodTurma='$codTurma')";
			} elseif(($data_ini != "") && ($data_fim != "")) {
				$query = "SELECT * FROM ArtesDesenhos WHERE (Data BETWEEN '$data_ini' AND '$data_fim') AND (Status = 'a') AND (CodTurma='$codTurma')";
			}
		}
		return $query;	
	}
	
	function getCodigoAutor($codDesenho) {
		$q = "SELECT CodUsuario FROM ArtesDesenhos WHERE CodDesenho='$codDesenho'";
		$codAutor = db_busca($q);
		return $codAutor[0]['CodUsuario'];
	}
	
	function getArquivoDesenho($codDesenho) {
		$q = "SELECT Arquivo FROM ArtesDesenhos WHERE CodDesenho='$codDesenho'";
		$codAutor = db_busca($q);
		return $codAutor[0]['Arquivo'];
	}
	
	function getDesenhoInfo($codDesenho) {
		$q = "SELECT * FROM ArtesDesenhos WHERE CodDesenho='$codDesenho'";
		$resultado = db_busca($q);
		return $resultado[0];
	}
	
	function getCarimboInfo($codCarimbo) {
		$q = "SELECT * FROM ArtesCarimbos WHERE CodCarimbo='$codCarimbo'";
		$resultado = db_busca($q);
		return $resultado[0];
	}
	
	function getCarimbos($categoria,$codUsuario) {
		$usr_aux = sessaoUsuario();
		$codUsuario_aux   = $usr_aux["codUsuario"];
		if($categoria!="Personalizados") 
			$q = "SELECT * FROM ArtesCarimbos WHERE Categoria='$categoria'";
		else 
			if($codUsuario == 0)
				$q = "SELECT * FROM ArtesCarimbos WHERE (Categoria='$categoria' AND CodUsuario!='$codUsuario_aux')";
			else
				$q = "SELECT * FROM ArtesCarimbos WHERE (Categoria='$categoria' AND 								CodUsuario='$codUsuario')";
		$resultado = db_busca($q);
		return $resultado;
	}
	
	function getCarimbosUsuario($codUsuario) {
		return getCarimbos("Personalizados",$codUsuario);
	}		
	
	function getDesenhosUsuario($codUsuario,$codTurma) {
		if($codUsuario == 0)  {
			$usr_aux = sessaoUsuario();
			$codUsuario_aux   = $usr_aux["codUsuario"];
			$q = "SELECT * FROM ArtesDesenhos WHERE (Status='a' AND CodTurma='$codTurma' AND codUsuario != '$codUsuario_aux') ORDER BY Data DESC";
		} else
			$q = "SELECT * FROM ArtesDesenhos WHERE (codUsuario='$codUsuario' AND Status='a' AND CodTurma='$codTurma') ORDER BY Data DESC";
		return db_busca($q);
	}
	
	function getLixeiraUsuario($codUsuario,$codTurma) {
		$q = "SELECT * FROM ArtesDesenhos WHERE (codUsuario='$codUsuario' AND Status='l' AND CodTurma='$codTurma') ORDER BY Data DESC";
		return db_busca($q);
	}
	
	function formataData($data) {
		return date("d/m/Y H:i",strtotime($data));	
	}	
	
	function zeraNovosComentarios($codDesenho) {
		db_faz("UPDATE ArtesComentarios SET Visualizado='1' WHERE codDesenho='$codDesenho'");
	}
	
	function contaComentarios($codDesenho) {
		return sizeof(db_busca("SELECT CodDesenho FROM ArtesComentarios WHERE CodDesenho='$codDesenho'"));
	}

	function contaNovosComentarios($codDesenho) {
		return sizeof(db_busca("SELECT CodDesenho FROM ArtesComentarios WHERE (CodDesenho='$codDesenho' AND Visualizado='0')"));
	}
	
	function limparLixeira($codUsuario,$codTurma) {
		echo "Chegou aqui!";
		$lixeira = getLixeiraUsuario($codUsuario,$codTurma);
		$apagou = true;
		foreach($lixeira as $desenho) {
			if(unlink("../arte/desenhos/".$desenho['Arquivo'])) { // apaga o arquivo contendo o desenho
				$apagou = $apagou & true;
				$codDesenho = $desenho['CodDesenho']; 
				db_faz("DELETE FROM ArtesDesenhos WHERE CodDesenho='$codDesenho' LIMIT 1"); // apaga o registro do desenho no banco de dados
				db_faz("DELETE FROM ArtesComentarios WHERE CodDesenho='$codDesenho'"); // apaga todos os comentários do desenho
			}
		}

		if($apagou) {
		?>
			<script>
			alert('Todos os desenhos selecionados foram enviados para a lixeira!');
			history.back();
			</script>
		<?php
		} else {
		?>
			<script>
			alert('Um ou mais desenhos podem não ter sido excluídos!');
			history.back();
			</script>
		<?php
		}
	}
	
	function enlixeirarDesenho($codDesenho) {
	/* Sim, a palavra "enlixeirar" foi deliberadamente inventada com a finalidade de suprir a carência de um verbo em português com o mesmo
	significado da expressão "mandar para a lixeira". Em alemão, certamente esse problema não ocorre... E se começássemos a por o nome
	das funções em alemão? :P */
		return mysql_query("UPDATE ArtesDesenhos SET Status='l' WHERE codDesenho='$codDesenho'");
	}
	
	function restaurarDesenho($codDesenho) {
		return mysql_query("UPDATE ArtesDesenhos SET Status='a' WHERE codDesenho='$codDesenho'");
	}
	
	function showTodos($ref,$inicial,$codTurma) {
		global $draws_path;
		$desenhos = getDesenhosUsuario($ref,$codTurma);
		$i = ($inicial%6!=0) ? 0 : $inicial;
		if($ref != 0) 
			$marginleft = 125;
		else
			$marginleft = 125;
	
?>
		<table style=" margin-left:125; margin-top:7">
		<form action="actionDesenho.php" method="post" name="formDesenhos">
		<input type="hidden" name="acao" id="acao" value=""/>
		<input type="hidden" name="cod_desenhos" id="cod_desenhos" value=""/>
<?php
		if(sizeof($desenhos)==0) {
		?>
			<tr><td><font class="titulo_menor_15">Nenhum desenho disponível.</font><br></td></tr>
		<?php
		}

		if($i==sizeof($desenhos)-1) {
		?>
			<input type="checkbox" name="id_desenhos" id="checksave" value="none" style="visibility:hidden"/>
		<?php
		}
		if(sizeof($desenhos)>$inicial) {
			while($i-$inicial<6 && $i<sizeof($desenhos)) {
				if(($i%2) == 0) {
?>
					<tr>
<?php
				}
?>
						<td><input type="checkbox" name="id_desenhos" id="check<?=$i?>" value="<?=$desenhos[$i]['CodDesenho']?>" style="visibility:hidden"/></td>
						<td valign="top">
						<?php if($ref == 0) { ?>
							<a href="visualizarDesenho.php?cod_desenho=<?=$desenhos[$i]['CodDesenho']?>">
						<?php } ?>						
						<img src="<?=$draws_path . $desenhos[$i]['Arquivo']?>" style="border:transparent solid 2px;width:75;height:75" id="desenho<?=$i?>" 
							<?php if($ref != 0) { ?>
								onclick="selecionaDesenho('<?=$i?>')";
							<?php } ?>
						>
						<?php if($ref == 0) { ?>
							</a>
						<?php } ?>						
						</td>
						<td valign="top">
							<font class="titulo_menor_15"><?=$desenhos[$i]['Titulo']?></font><br>
							<font class="texto_base_12"><?=getNomeUsuario($desenhos[$i]['CodUsuario'])?></font><br></font>
						</td>
<?php
				if(($i%2) != 0) {
?>	
					</tr>
<?php
				}
				$i++;
			}
		}
?>		
		</form>
		</table>
<?php
	}
	


	function showTodosCarimbos($ref,$inicial) {
		global $draws_path;
		$carimbos = getCarimbosUsuario($ref);
		$i = ($inicial%6!=0) ? 0 : $inicial;
		if($ref != 0) 
			$marginleft = 125;
		else
			$marginleft = 125;
	
?>
		<table style=" margin-left:125; margin-top:7">
		<form action="actionCarimbos.php" method="post" name="formCarimbos">
		<input type="hidden" name="acao" id="acao" value=""/>
		<input type="hidden" name="cod_carimbos" id="cod_carimbos" value=""/>
<?php
		if(sizeof($carimbos)==0) {
		?>
			<tr><td><font class="titulo_menor_15">Nenhum carimbo disponível.</font><br></td></tr>
		<?php
		}

		if($i==sizeof($carimbos)-1) {
		?>
			<input type="checkbox" name="id_carimbos" id="checksave" value="none" style="visibility:hidden"/>
		<?php
		}
		if(sizeof($carimbos)>$inicial) {
			while($i-$inicial<6 && $i<sizeof($carimbos)) {
				if(($i%2) == 0) {
?>
					<tr>
<?php
				}
?>
						<td><input type="checkbox" name="id_carimbos" id="check<?=$i?>" value="<?=$carimbos[$i]['CodCarimbo']?>" style="visibility:hidden"/></td>
						<td valign="top">
						<?php if($ref == 0) { ?>
							<a href="javascript:confirma_exportar('<?=$carimbos[$i]['CodCarimbo']?>');"exportarCarimbo.php?codCarimbo=<?=$carimbos[$i]['CodCarimbo']?>">
						<?php } ?>						
						<img src="<?=$draws_path . $carimbos[$i]['Arquivo']?>" style="border:transparent solid 2px;width:75;height:75" id="carimbo<?=$i?>" 
							<?php if($ref != 0) { ?>
								onclick="selecionaCarimbo('<?=$i?>')";
							<?php } ?>
						>
						<?php if($ref == 0) { ?>
							</a>
						<?php } ?>								
						</td>
						<td valign="top">
							<font class="titulo_menor_15"><?=$carimbos[$i]['Titulo']?></font><br>
							<font class="texto_base_12"><?=getNomeUsuario($carimbos[$i]['CodUsuario'])?></font><br></font>
						</td>
<?php
				if(($i%2) != 0) {
?>	
					</tr>
<?php
				}
				$i++;
			}
		}
?>		
		</form>
		</table>
<?php
	}




	function showTodosLixeira($ref,$inicial,$codTurma) {
		global $draws_path;
		$desenhos = getLixeiraUsuario($ref,$codTurma);
		$i = ($inicial%6!=0) ? 0 : $inicial;
		if($ref != 0) 
			$marginleft = 125;
		else
			$marginleft = 125;
?>
		<table style=" margin-left:125; margin-top:7">
		<form action="actionDesenho.php" method="post" name="formDesenhos">
		<input type="hidden" name="acao" id="acao" value=""/>
		<input type="hidden" name="cod_desenhos" id="cod_desenhos" value=""/>

<?php
		if(sizeof($desenhos)==0) {
		?>
			<tr><td><font class="titulo_menor_15">A lixeira está vazia.</font><br></td></tr>
		<?php
		}
		if($i==sizeof($desenhos)-1) {
		?>
			<input type="checkbox" name="id_desenhos" id="checksave" value="none" style="visibility:hidden"/>
		<?php
		}

		if(sizeof($desenhos)>$inicial) {
			while($i-$inicial<6 && $i<sizeof($desenhos)) {
				if(($i%2) == 0) {
?>
					<tr>
<?php
				}
?>
						<td><input type="checkbox" name="id_desenhos" id="check<?=$i?>" value="<?=$desenhos[$i]['CodDesenho']?>" style="visibility:hidden"/></td>
						<td valign="top">
						<?php if($ref == 0) { ?>
							<a href="visualizarDesenho.php?cod_desenho=<?=$desenhos[$i]['CodDesenho']?>">
						<?php } ?>						
						<img src="<?=$draws_path . $desenhos[$i]['Arquivo']?>" style="border:transparent solid 2px;width:75;height:75" id="desenho<?=$i?>" 
							<?php if($ref != 0) { ?>
								onclick="selecionaDesenho('<?=$i?>')";
							<?php } ?>
						>
						<?php if($ref == 0) { ?>
							</a>
						<?php } ?>						
						</td>
						<td valign="top">
							<font class="titulo_menor_15"><?=$desenhos[$i]['Titulo']?></font><br>
							<font class="texto_base_12"><?=getNomeUsuario($desenhos[$i]['CodUsuario'])?></font><br></font>
						</td>
<?php
				if(($i%2) != 0) {
?>	
					</tr>
<?php
				}
				$i++;
			}
		}
?>		
		</form>
		</table>
<?php
	}


	function showBusca($inicial,$desenhos) {
		global $draws_path;
		$i = ($inicial%6!=0) ? 0 : $inicial;
		$marginleft = 125;
?>
		<table style=" margin-left:125; margin-top:7">
		<form action="actionDesenho.php" method="post" name="formDesenhos">
		<input type="hidden" name="acao" id="acao" value=""/>
		<input type="hidden" name="cod_desenhos" id="cod_desenhos" value=""/>

<?php
		if(sizeof($desenhos)==0) {
		?>
			<tr><td><font class="titulo_menor_15">Nenhum resultado foi encontrado.</font><br></td></tr>
		<?php
		}
		if($i==sizeof($desenhos)-1) {
		?>
			<input type="checkbox" name="id_desenhos" id="checksave" value="none" style="visibility:hidden"/>
		<?php
		}

		if(sizeof($desenhos)>$inicial) {
			while($i-$inicial<6 && $i<sizeof($desenhos)) {
				if(($i%2) == 0) {
?>
					<tr>
<?php
				}
?>
						<td><input type="checkbox" name="id_desenhos" id="check<?=$i?>" value="<?=$desenhos[$i]['CodDesenho']?>" style="visibility:hidden"/></td>
						<td valign="top">
						<a href="visualizarDesenho.php?cod_desenho=<?=$desenhos[$i]['CodDesenho']?>">
						<img src="<?=$draws_path . $desenhos[$i]['Arquivo']?>" style="border:transparent solid 2px;width:75;height:75" id="desenho<?=$i?>" 
							</a>
						</td>
						<td valign="top">
							<font class="titulo_menor_15"><?=$desenhos[$i]['Titulo']?></font><br>
							<font class="texto_base_12"><?=getNomeUsuario($desenhos[$i]['CodUsuario'])?></font><br></font>
						</td>
<?php
				if(($i%2) != 0) {
?>	
					</tr>
<?php
				}
				$i++;
			}
		}
?>		
		</form>
		</table>
<?php
	}


	function showTresPrimeiros($codUsuario,$codTurma) {
		global $draws_path;
		$desenhos = getDesenhosUsuario($codUsuario,$codTurma);
		$i=0;
		?>
			<table style=" margin-left:25; margin-top:10"><tr>
		<?php
		if(sizeof($desenhos)==0) {
		?>
			<td>
				<font class="titulo_menor_15">Nenhum desenho disponível</font><br>
			</td>
		<?php				
		} else {
			while($i<3 && $i<sizeof($desenhos)) {
			?>		
					<td valign="top">
						<a href="visualizarDesenho.php?cod_desenho=<?=$desenhos[$i]['CodDesenho']?>"><img src="<?=$draws_path . $desenhos[$i]['Arquivo']?>" style="border:#000000 solid 1px;width:75;height:75"></a>
					</td>
					<td valign="top">
						<font class="titulo_menor_15"><?=$desenhos[$i]['Titulo']?></font><br>
						<font class="texto_base_12"><?=getNomeUsuario($desenhos[$i]['CodUsuario'])?></font><br></font>
					</td>
			<?php
				$i++;
			}
		}
		?>
			</tr></table>
		<?php
	}
	
	function navDesenhos($ref,$codTurma) {
		$total_desenhos = sizeof(getDesenhosUsuario($ref,$codTurma));
		if($total_desenhos != 0) {
			$total_galerias = ceil($total_desenhos / 6);
	
			echo "<a class='link_15' href=verTodos.php?ref=" . $ref . "&inicial=0> << </a>";
			for($i=0;$i<$total_galerias;$i++) {
				$inicial = $i*6;
					echo "<a class='link_15' href=verTodos.php?ref=" . $ref . "&inicial=" . ($inicial) . ">";
				echo " " . ($i+1) . " "; 
					echo "</a>";		
			}
			echo "<a class='link_15' href=verTodos.php?ref=" . $ref . "&inicial=" . lessEqualThanNDivisibleByD($total_desenhos-1,6) . "> >> </a>";
		}
	}


	function navCarimbos($ref) {
		$total_carimbos = sizeof(getCarimbosUsuario($ref));
		if($total_carimbos != 0) {
			$total_galerias = ceil($total_carimbos / 6);
	
			echo "<a class='link_15' href=verTodosCarimbos.php?ref=" . $ref . "&inicial=0> << </a>";
			for($i=0;$i<$total_galerias;$i++) {
				$inicial = $i*6;
					echo "<a class='link_15' href=verTodosCarimbos.php?ref=" . $ref . "&inicial=" . ($inicial) . ">";
				echo " " . ($i+1) . " "; 
					echo "</a>";		
			}
			echo "<a class='link_15' href=verTodosCarimbos.php?ref=" . $ref . "&inicial=" . lessEqualThanNDivisibleByD($total_carimbos-1,6) . "> >> </a>";
		}
	}

	function navDesenhosLixeira($ref,$codTurma) {
		$total_desenhos = sizeof(getLixeiraUsuario($ref,$codTurma));
		if($total_desenhos != 0) {
			$total_galerias = ceil($total_desenhos / 6);
			echo "<a class='link_15' href=verTodos.php?ref=" . $ref . "&inicial=0> << </a>";
			for($i=0;$i<$total_galerias;$i++) {
				$inicial = $i*6;
					echo "<a class='link_15' href=verTodos.php?ref=" . $ref . "&inicial=" . ($inicial) . ">";
				echo " " . ($i+1) . " "; 
					echo "</a>";		
			}
			echo "<a class='link_15' href=verTodos.php?ref=" . $ref . "&inicial=" . lessEqualThanNDivisibleByD($total_desenhos-1,6) . "> >> </a>";
		}
	}

	function navBusca($busca) {
		$total_desenhos = sizeof($busca);
		if($total_desenhos != 0) {
			$total_galerias = ceil($total_desenhos / 6);
			echo "<a class='link_15' href=verBusca.php?inicial=0> << </a>";
			for($i=0;$i<$total_galerias;$i++) {
				$inicial = $i*6;
					echo "<a class='link_15' href=verBusca.php?inicial=" . ($inicial) . ">";
				echo " " . ($i+1) . " "; 
					echo "</a>";		
			}
			echo "<a class='link_15' href=verBusca.php?inicial=" . lessEqualThanNDivisibleByD($total_desenhos-1,6) . "> >> </a>";
		}
	}
	
	function lessEqualThanNDivisibleByD($n,$d) {
	// Retorna o maior número inteiro menor ou igual a n que seja divisível por d.
		if ($n<0)
			$n=0;
		else
			while(($n >= 0) and ($n%$d!=0))
				$n--;
		return $n;
	}
?>