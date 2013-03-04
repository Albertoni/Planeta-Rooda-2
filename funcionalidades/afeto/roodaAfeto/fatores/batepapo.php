<?php
/*----------------------------------------------------------------------*/
$b_b = 
	db_busca(
"SELECT count(*) num, BPM.codUsuario codus
FROM BatePapoSala as BPS, BatePapoMensagem as BPM, TurmaUsuario as TU
WHERE BPS.codTurma=$codTurma AND BPS.codSala=BPM.codSala AND
		TU.associacao='A' AND TU.codTurma=$codTurma AND TU.codUsuario=BPM.codUsuario and BPM.quando between '{$dataini}' AND '{$datafim}'
GROUP BY BPM.codUsuario"
	);
$freq_btp = cont_freq(&$b_b,&$num['A'],$codUs);
/*----------------------------------------------------------------------*/

?>