<?php
  
//  require_once("../../funcoes_aux.php");
  require_once("../../file.class.php");
  //require_once("../../bd.php");

  
  $funcionalidade_tipo = $_GET['funcionalidade_tipo'];
  $funcionalidade_id = $_GET['funcionalidade_id'];
  $file_name = $_GET['file_name'];
  
  $file = new File($funcionalidade_tipo, $funcionalidade_id, $file_name);
  $file->download();
  

?>
<script type='text/javascript'>
	document.self.close();
</script>
