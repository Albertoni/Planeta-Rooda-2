<?php
class Bg_barra{
	public $size;
	public $borda;
	public $canvas;
	public $cor;
	
	function draw_and_close(){
		header ("Content-type: image/png");
		ImagePNG($this->canvas); 
		ImageDestroy($this->canvas);
	}

	function gera($sz){
		$this->size = $sz;
		$this->borda = $sz/20;
		$this->canvas = ImageCreate($this->size,$this->size);
		$this->cor =
				array(	
					'black' => ImageColorAllocate($this->canvas,0,0,0),
					'white' => ImageColorAllocate($this->canvas,255,255,255),
					'red' => ImageColorAllocate($this->canvas,211,55,73),
					'green' => ImageColorAllocate($this->canvas,0,156,0),
					'blue' => ImageColorAllocate($this->canvas,3,139,226),
					'lgrey' => ImageColorAllocate($this->canvas,127,127,127),
				);
		$this->draw_bg();
	}

	function draw_bg(){
		$font = $this->size*5/400;
		/*-----------------	Imprime Limites	-----------------------------------------*/
		ImageString(	$this->canvas,	$font,
						0,0,
						"100%",$this->cor['white']);

		ImageString(	$this->canvas,	$font,
						0,($this->size-$this->borda)/2,
						" 50%",$this->cor['white']);

		ImageString(	$this->canvas,	$font,
						0,$this->size-$this->borda,
						" 0% ",$this->cor['white']);
		/*-----------------	Imprime Linhas	-----------------------------------------*/
		ImageLine(	$this->canvas,
					2*$this->borda,$this->size-0.5*$this->borda,
					$this->size-$this->borda,$this->size-0.5*$this->borda,
					$this->cor['white']);				//horizontal

		ImageLine(	$this->canvas,
					2*$this->borda,0,
					2*$this->borda,$this->size,
					$this->cor['white']);				//vertical

		ImageLine(	$this->canvas,
					1.8*$this->borda,0.5*$this->borda,
					2.4*$this->borda,0.5*$this->borda,
					$this->cor['white']);				//limite superior

		ImageLine(	$this->canvas,
					1.8*$this->borda,0.5*$this->size,
					2.4*$this->borda,0.5*$this->size,
					$this->cor['white']);				//limite superior

		ImageLine(	$this->canvas,
					1.8*$this->borda,$this->size-0.5*$this->borda,
					2.4*$this->borda,$this->size-0.5*$this->borda,
					$this->cor['white']);				//limite inferior
	}

	function draw_barras($conf,$esf,$indep){
		$ini = 3;
		$lado = 2;
		$cima = $this->borda/5;
		$i = 0;
$i+=1.5;/*-----------------	Imprime Barras	=>	confiança	--------------------------------*/
		if($conf!=0)
			ImageFilledRectangle(	$this->canvas,
									$ini*$i*$this->borda,					($this->size-$this->borda/2),
									($ini*$i+$lado)*$this->borda,			($this->size)*(1-$conf)+($this->borda)*($conf-1/2),
									$this->cor['red']);
		else
			ImageFilledRectangle(	$this->canvas,
									$ini*$i*$this->borda,					($this->size-$this->borda/2)-$cima,
									($ini*$i+$lado)*$this->borda,			($this->size-$this->borda/2)+$cima,
									$this->cor['red']);
$i+=1.5;/*-----------------	Imprime Barras	=>	esforço	------------------------------------*/
		if($esf!=0)
			ImageFilledRectangle(	$this->canvas,
									$ini*$i*$this->borda,					($this->size-$this->borda/2),
									($ini*$i+$lado)*$this->borda,			($this->size)*(1-$esf)+($this->borda)*($esf-1/2),
									$this->cor['green']);
		else
			ImageFilledRectangle(	$this->canvas,
									$ini*$i*$this->borda,					($this->size-$this->borda/2)-$cima,
									($ini*$i+$lado)*$this->borda,			($this->size-$this->borda/2)+$cima,
									$this->cor['green']);
$i+=1.5;/*-----------------	Imprime Barras	=>	independência	-----------------------------*/
		if($indep!=0)
			ImageFilledRectangle(	$this->canvas,
									$ini*$i*$this->borda,					($this->size-$this->borda/2),
									($ini*$i+$lado)*$this->borda,			($this->size)*(1-$indep)+($this->borda)*($indep-1/2),
									$this->cor['blue']);
		else
			ImageFilledRectangle(	$this->canvas,
									$ini*$i*$this->borda,					($this->size-$this->borda/2)-$cima,
									($ini*$i+$lado)*$this->borda,			($this->size-$this->borda/2)+$cima,
									$this->cor['blue']);
	}
}

$size = isset($_GET['size'])?	$_GET['size']	:	422;

$canvas = new Bg_barra;
$canvas->gera($size);
$canvas->draw_barras($_GET['confianca'],$_GET['esforco'],$_GET['independencia']);
$canvas->draw_and_close();



?>