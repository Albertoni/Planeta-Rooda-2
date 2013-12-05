<?php

class projeto{
	private $id;
	private $nome;
	private $palavras;
	private $dataInicio;
	private $dataFim;
	private $alunos;
	function __construct(	$id = 0,
							$nome = "",
							$palavras = "",
							$dataInicio = 0;
							$dataFim = 0;
							$alunos = array()
						){
		if($id === 0){
			$this->id = 0;
			$this->nome = $nome;
			$this->palavras = $palavras;
			$this->dataInicio = $dataInicio;
			$this->dataFim = $dataFim;
			$this->alunos = $alunos;
		}else{
			$this->carrega($id);
		}
	}
}