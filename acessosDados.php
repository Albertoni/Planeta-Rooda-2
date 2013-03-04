<?php
require_once dirname(__FILE__).'/bd.php';
require_once dirname(__FILE__).'/cfg.php';
?>
<html>
	<body>
		<?php
		if(isset($_POST['usuario'])&&($_POST['usuario']!='')){
			$usuario=new conexao();
			$usuario->solicitarSI('SELECT usuario_nome
								   FROM '.$tabela_usuarios.'
								   WHERE usuario_id='.intval($_POST['usuario']));
			echo 'Estatísticas de acesso: '.$usuario->resultado['usuario_nome'].'<br/></br>';
			$usuario->solicitar('SELECT id_terreno,funcionalidade,data_hora,duracao
								 FROM '.$tabela_acessos_planeta.'
								 WHERE id_usuario='.intval($_POST['usuario']).'
								 ORDER BY id_acesso DESC');
			$tempoTotal=0;
			$tempoFuncionalidade=array();
			$tempoFuncionalidade['biblioteca']=0;
			$tempoFuncionalidade['blog']=0;
			$tempoFuncionalidade['forum']=0;
			$tempoFuncionalidade['portfolio']=0;
			$tempoFuncionalidade['aparencia']=0;
			$tempoFuncionalidade['arte']=0;
			$tempoFuncionalidade['pergunta']=0;
			$tempoFuncionalidade['aulas']=0;
			$tempoFuncionalidade['player']=0;
			$tempoFuncionalidade['gerenc_funcio_turmas']=0;
			$tempoFuncionalidade['outro']=0;
			for($i=0;$i<$usuario->registros;$i++){
				$tempoTotal+=$usuario->itens[$i]['duracao'];
				if($usuario->itens[$i]['funcionalidade']==='')
					$tempoFuncionalidade['outro']+=$usuario->itens[$i]['duracao'];
				else
					$tempoFuncionalidade[$usuario->itens[$i]['funcionalidade']]+=$usuario->itens[$i]['duracao'];
			}
			$horasTotal=floor($tempoTotal/3600);
			$minutosTotal=floor(($tempoTotal%3600)/60);
			$segundosTotal=$tempoTotal%60;
			echo 'Tempo total de acesso ao planeta: '.$horasTotal.' h '.$minutosTotal.' min '.$segundosTotal.' s<br/>';
			echo 'Acesso à funcionalidade "Biblioteca": '.((floor(($tempoFuncionalidade['biblioteca']*10000)/$tempoTotal))/100).'%<br/>';
			echo 'Acesso à funcionalidade "Blog": '.((floor(($tempoFuncionalidade['blog']*10000)/$tempoTotal))/100).'%<br/>';
			echo 'Acesso à funcionalidade "Fórum": '.((floor(($tempoFuncionalidade['forum']*10000)/$tempoTotal))/100).'%<br/>';
			echo 'Acesso à funcionalidade "Portfolio": '.((floor(($tempoFuncionalidade['portfolio']*10000)/$tempoTotal))/100).'%<br/>';
			echo 'Acesso à funcionalidade "Aparência": '.((floor(($tempoFuncionalidade['aparencia']*10000)/$tempoTotal))/100).'%<br/>';
			echo 'Acesso à funcionalidade "Arte": '.((floor(($tempoFuncionalidade['arte']*10000)/$tempoTotal))/100).'%<br/>';
			echo 'Acesso à funcionalidade "Pergunta": '.((floor(($tempoFuncionalidade['pergunta']*10000)/$tempoTotal))/100).'%<br/>';
			echo 'Acesso à funcionalidade "Aulas": '.((floor(($tempoFuncionalidade['aulas']*10000)/$tempoTotal))/100).'%<br/>';
			echo 'Acesso à funcionalidade "Player": '.((floor(($tempoFuncionalidade['player']*10000)/$tempoTotal))/100).'%<br/>';
			echo 'Acesso à funcionalidade "Gerência de funcionalidades": '.((floor(($tempoFuncionalidade['gerenc_funcio_turmas']*10000)/$tempoTotal))/100).'%<br/>';
			echo 'Acesso à navegação geral e outras funcionalidades: '.((floor(($tempoFuncionalidade['outro']*10000)/$tempoTotal))/100).'%<br/>';
		}
		?>
	</body>
</html>
