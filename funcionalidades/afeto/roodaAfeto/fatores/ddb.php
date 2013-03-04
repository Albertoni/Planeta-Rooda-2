<?php
/*----------------------------------------------------------------------*/
$b_d = 
	db_busca(
"select count(*) num, DM.codUsuario codus
from DDBMensagem as DM, TurmaUsuario as TU
where	TU.codTurma=$codTurma and DM.codTurma=$codTurma and TU.associacao='A'
		and TU.codUsuario=DM.codUsuario and DM.quando between '{$dataini}' AND '{$datafim}'
group by DM.codUsuario"
	);
$freq_ddb = cont_freq(&$b_d,&$num['A'],$codUs);
/*----------------------------------------------------------------------*/
?>