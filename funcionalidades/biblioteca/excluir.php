<?	
	function rm($fileglob){
	   if (is_string($fileglob)) {
	       if (is_file($fileglob)) {
	           return unlink($fileglob);
	       } else if (is_dir($fileglob)) {
	           $ok = rm("$fileglob/*");
	           if (! $ok) {
	               return false;
	           }
	           return rmdir($fileglob);
	       } else {
	           $matching = glob($fileglob);
	           if ($matching === false) {
	               trigger_error(sprintf('No files match supplied glob %s', $fileglob), E_USER_WARNING);
	               return false;
	           }     
	           $rcs = array_map('rm', $matching);
	           if (in_array(false, $rcs)) {
	               return false;
	           }
	       }     
	   } else if (is_array($fileglob)) {
	       $rcs = array_map('rm', $fileglob);
	       if (in_array(false, $rcs)) {
	           return false;
	       }
	   } else {
	       trigger_error('Param #1 must be filename or glob pattern, or array of filenames or glob patterns', E_USER_ERROR);
	       return false;
	   }
	
	   return true;
	}


	require_once("../sistema.inc.php");
	require_once("biblioteca.inc.php");
	
	$usuario      = sessaoUsuario();
	$codUsuario   = $usuario["codUsuario"];

	$turma        = sessaoTurma();
	$codTurma     = $turma["codTurma"];
	$associacao   = $turma["associacao"];		
	
	$excluir= $_POST['excluir'];
	

if ((($associacao=='P')or($associacao=='M'))and$excluir[0]!=''){
	foreach ($excluir as $codMaterial){		
		$select = "SELECT * FROM BibliotecaMateriais WHERE codTurma=$codTurma ORDER BY data DESC";
		$result = db_busca($select);
		$tipoMaterial=$result[0]['tipoMaterial'];
		//echo "<$codMaterial,$tipoMaterial>";
		if ($tipoMaterial=='a'){
			$pasta=$file_dir . "/biblioteca" . "/" . $codTurma . "/" . $codMaterial;			
			rm($pasta);	
			echo "oi";		
		}
		
			
		$delete = "DELETE FROM BibliotecaMateriais WHERE codMaterial=$codMaterial";
		db_faz($delete);		
				
	}	
}
echo "<script>window.location=('index.php')</script>";	
?>