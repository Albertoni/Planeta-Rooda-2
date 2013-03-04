<?php
  
//  require("../../funcoes_aux.php");
  require("../../file.class.php");
  //require("../../bd.php");

  
  $funcionalidade_tipo = $_GET['funcionalidade_tipo'];
  $funcionalidade_id = $_GET['funcionalidade_id'];
  $file_name = $_GET['file_name'];
  
  $file = new File($funcionalidade_tipo, $funcionalidade_id, $file_name);
  $file->download();
  

?>
<script type='text/javascript'>
	document.self.close();
</script>
