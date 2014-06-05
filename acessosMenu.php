<?php
require_once dirname(__FILE__).'/bd.php';
require_once dirname(__FILE__).'/cfg.php';
?>
<html>
	<body>
		<form action="acessosDados.php" method="post">
			<select name="usuario">
				<option value="0">- Usuários -</option>
				<?php
				$usuarios=new conexao();
				$usuarios->solicitarSI('SELECT DISTINCT u.usuario_id,u.usuario_nome
										FROM '.$tabela_usuarios.' AS u,'.$tabela_acessos_planeta.' AS ap
										WHERE u.usuario_id=ap.id_usuario
										ORDER BY u.usuario_nome ASC');
				for($i=0;$i<$usuarios->registros;$i++){
					echo '<option value="'.$usuarios->resultado['usuario_id'].'">'.$usuarios->resultado['usuario_nome'].'</option>';
					$usuarios->proximo();
				}
				?>
			</select>
			<input type="submit" value="Ver" />
		</form>
	</body>
</html>
