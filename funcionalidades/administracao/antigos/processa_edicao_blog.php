<?php
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	
	$id = $HTTP_POST_VARS['Id'];
	$tipo = $HTTP_POST_VARS['Tipo'];
	$owners = $HTTP_POST_VARS['OwnersIds'];
	$title = $HTTP_POST_VARS['Title'];
	$apagar = $HTTP_POST_VARS['Apagar'];
	if ($apagar != null){
		$consulta = new conexao();
		$consulta->solicitar("DELETE FROM $tabela_blogs WHERE Id = $id");
		
		
		$back = -1;
	}
	else if ($id != null && $tipo != null && $owners != null && $title != null){
		$consulta = new conexao();
		$consulta->solicitar("UPDATE $tabela_blogs SET Title = '$title', OwnersIds = '$owners', Tipo = $tipo WHERE Id = $id");
		
		
		$back = -3;
	}
echo "
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<script language=\"javascript\">
		history.go($back);
	</script>
</head>";
?>
<!--            Se você está vendo isso, parabéns.
      .----------------------------------------------------.
      |                                                    |
      |       _  .-"-.  .-"-. .--.    _   _      _   ____  |
      |    ,'` | | ._ `.| ._ \|  /  ,' '\| | _ .' ) |   _|_|__
     _|_  / /| | | | \ '| | \ | ;  / ., || || || ;  |  |_(]___`\
   /___[)' | | | | '-`/ | '-`/| | / /_| || || | \ `\|  '(]____ '
  | ____[) '-' | | |-'  | .-' | |/      || `' |  ;  |  |"(]___ |
  ; ___[)| .-. | | |    | |   | '-./`|  ||    | /  /|  |__(]_ /
   \ _[) |_| \_' '_|    ._|   '---'  '--'`.__.'(_,' |_____||-`
    `-|                                                    |
      '----------------------------------------------------'
       \      '   /|//  /|/\,>7/|\>/\ \         /      ,'
        \     '; |/|;  |  .--.  .--. \ |       /     ,'
         '     |  |\| /  '__________' ||   ,-'     ,'
         |     | .-' [    \"" /:  "/  ]'.,'       /
         '.    '|     |    `-'  \-'  |  |       ,'
          |     .'.__.'     |    |   '._'      /
          |      `\||       \    /    |      .`
          |        ||        `--'     |     /
          |        \\__  __           |     '
          '.        `===(__)`.__.'    ;     |
           |         \ \             /|     |
          .|          \ `._        ,' |     |
          |            .   `-.__,-'   ;     .
          |             \    ,\_/\   /      '
          |              `..' |\\ `-'   _.-\ \
         ,'._                 | :`.  .-`    \ ;
         |   `                | '  \ \ __,-' \|
         /                    |  \  \ \      ||
         `-._                 |   '-'  \   _,'|
             `--.             :   |     `'`  ,/
                 `--._        '.__;         _/
                     ``-.___..___....----'"` 
-->
</html>