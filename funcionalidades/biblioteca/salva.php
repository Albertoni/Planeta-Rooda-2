<?php
// !SQLINJECTION
	session_start();

	require_once("biblioteca.inc.php");
	require_once("../cfg.php");		
	require_once("../db.inc.php");	

	$codUsuario   = $_SESSION['SS_usuario_id'];
	$codTurma     = $_SESSION['SS_terreno_id'];
	$associacao   = "A";
	$autoriza = 1;
	$fundo    = "../imagens/figuras_fundo/naves.png";
	$corFundo = "#3366FF";
	$voltar = "../../planeta2_edicao/planeta2_guto/desenvolvimento/";	
	
	

$data=date("Y-m-d");
$hora=date("H:i:s");
$tipoMaterial = $_POST['tipoMaterial'];
$titulo = $_POST['titulo'];
$palavras = $_POST['palavras'];

if($titulo) {
	$select 		= "SELECT  titulo, codTurma FROM BibliotecaMateriais WHERE titulo='$titulo' AND codTurma='$codTurma'";
	$saida 				= db_busca($select);
	
	$verif = $saida[0]['titulo'];
	if($verif == "") {
			

		if ($tipoMaterial=='link'){
			$link = $_POST['con'];	
			$insert  = "INSERT INTO BibliotecaMateriais (codTurma,titulo, palavras,material,tipoMaterial,data,hora,codUsuario)
						VALUES ('$codTurma','$titulo','$palavras','$link','l','$data','$hora',$codUsuario)";
			db_faz($insert);
		}
		if ($tipoMaterial=='arquivo'){
			//echo "<script>alert('Não funciona');</script>";
			$insert = "INSERT INTO BibliotecaMateriais (codTurma,titulo,palavras,tipoMaterial,material,tamanhoMaterial,codUsuario, data,Hora) 
					   VALUES ('$codTurma','$titulo','$palavras','a','$material','$tamanho','$codUsuario','$data','$hora')";
			$codMaterial=db_faz($insert);
			
			$novaPasta=$file_dir . "/" . "biblioteca";
			
			if (!is_dir($novaPasta))
				mkdir($novaPasta);
			$novaPasta=$novaPasta . "/" . $codTurma; 
			if (!is_dir($novaPasta))
				mkdir($novaPasta);	
			$novaPasta=$novaPasta . "/" . $codMaterial;
			if (!is_dir($novaPasta))
				mkdir($novaPasta);
				
			$uploadDir=$novaPasta;
		
		//	echo "$x<br>";
			$nomeOriginal=$_FILES['con']['name']; //O nome original do arquivo no computador do usuário. 
			$material=$nomeOriginal;
			
			$tipoMime=$_FILES['con']['type']; //O tipo mime do arquivo, se o browser deu esta informação. Um exemplo pode ser "image/gif". 
		//	echo "O tipo mime é: $tipoMime<br>";
			
			$tamanho=$_FILES['con']['size']; //O tamanho, em bytes, do arquivo. 
		//	echo "O tamanho é: $tamanho<br>";
			
			$nomeTemp=$_FILES['con']['tmp_name']; //O nome temporário do arquivo, como foi guardado no servidor.
		//	echo "O nome temporário é: $nomeTemp<br>";
			
			$erros[]=$_FILES['con']['error'];
		
			$uploadFile=$uploadDir ."/". $nomeOriginal;
			
			$arquivo_valido=0; //Zerando variavel
		  $arquivo_valido=revisar_arquivos($_FILES["con"]["type"],$_FILES["con"]["error"],$nomeOriginal);
								//echo $arquivo_valido;
								
			if($arquivo_valido==1) {
								//Se arquivo OK transporta para o servidor e grava na tabela										// faz upload do arquivo
		
			
			$msg=move_uploaded_file ($nomeTemp,$uploadFile);
				
			$update="UPDATE BibliotecaMateriais SET  material=\"$material\" ,tamanhoMaterial=\"$tamanho\"
					 WHERE codMaterial=$codMaterial"; 
			db_faz($update);
		
		}
		else{
				$delete = "DELETE FROM BibliotecaMateriais WHERE codMaterial='$codMaterial'";
				db_faz($delete);		
		  
		}
		
		}// if tipo==arquivo
		echo "<script>window.location=('index.php')</script>";
	} else {
		echo"<script>alert('Um Material com o mesmo nome foi achado!')</script>";  
		echo "<script>window.location=('index.php')</script>";
	} //if($veridicação == "")
}//if($palavras)

?>