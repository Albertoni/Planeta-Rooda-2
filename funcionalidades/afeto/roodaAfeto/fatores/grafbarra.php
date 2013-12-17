<?php
class Bg_barra{
	public $itera;
	public $h;
	public $l;
	public $borda=10;
	public $canvas;
	public $max;
	public $min;
	public $cor;
	public $font;

	function bg_barra($mx){
		$this->itera = $mx;
		
		$this->l = 45;
		$this->h = 170;
		$this->font = $this->h*5/400;
		
		$this->ltotal = $this->l*$mx;
		$this->htotal = $this->h+20;
		
		
		$this->canvas = ImageCreate($this->ltotal,$this->htotal);
		
		$this->cor =
				array(	
					'black' => ImageColorAllocate($this->canvas,0,0,0),
					'white' => ImageColorAllocate($this->canvas,255,255,255),
					'c' => ImageColorAllocate($this->canvas,211,55,73),
					'e' => ImageColorAllocate($this->canvas,0,156,0),
					'i' => ImageColorAllocate($this->canvas,3,139,226),
					'lgrey' => ImageColorAllocate($this->canvas,127,127,127),
				);
	}

	function draw_limits($max,$min){
		$this->max = $max;
		$this->min = $min;
		/*-----------------	Imprime Limites	-----------------------------------------*/
		ImageString(	$this->canvas,	$this->font,
						$this->borda,$this->borda/2,
						"+".abs($max),$this->cor['white']);

		ImageString(	$this->canvas,	$this->font,
						$this->borda,($this->h-$this->borda)/2,
						" 0",$this->cor['white']);

		ImageString(	$this->canvas,	$this->font,
						$this->borda,$this->h-2*$this->borda,
						"-".abs($min),$this->cor['white']);
		/*-----------------	Imprime Linhas	-----------------------------------------*/
		ImageLine(	$this->canvas,
					2.5*$this->borda,$this->h/2,
					$this->l,$this->h/2,
					$this->cor['white']);				//horizontal

		ImageLine(	$this->canvas,
					2.5*$this->borda,0,
					2.5*$this->borda,$this->htotal,
					$this->cor['white']);				//vertical

		ImageLine(	$this->canvas,
					0,$this->h,
					$this->ltotal,$this->h,
					$this->cor['white']);				//inferior
	}

	function draw_barra($cor,$val,$itera_n,$fator){
		$meiox = ($this->l)/2;		
		$meioy = ($this->h)/2;
			ImageLine($this->canvas,
					$this->l*$itera_n,$meioy,
					$this->l*($itera_n+1),$meioy,
					$this->cor['white']);

		$dx	=	15;
		$dy	=	($val<0)?	$val/$this->min:$val/$this->max;

		if($dy != 0)
			ImageFilledRectangle(
				$this->canvas,
				$itera_n*$this->l + ($meiox)-$dx,							($meioy),
				$itera_n*$this->l + ($meiox)+$dx,							$meioy-$meioy*$dy*.95,
				$this->cor[$cor]);
		else
			ImageFilledRectangle(
				$this->canvas,
				$itera_n*$this->l + ($meiox)-$dx,							$meioy-$this->h/100,
				$itera_n*$this->l + ($meiox)+$dx,							$meioy+$this->h/100,
				$this->cor[$cor]);
		
		ImageString(	$this->canvas,	$this->font,
						$itera_n*$this->l+10,$this->h+5,
						$fator,$this->cor['white']);
	}
	
	function draw_and_close(){
		header ("Content-type: image/png");
		ImagePNG($this->canvas); 
		ImageDestroy($this->canvas);
	}
}
if(	isset($_GET['min'])	&&		isset($_GET['max'])	&&		isset($_GET['c'])	&&		isset($_GET['val']) &&		isset($_GET['tit'])	){
	$int = 1 + count($_GET['c']);
	
	$c	=	$_GET['c'];		//cor / fator
	$v	=	$_GET['val'];	//valor
	$t	=	$_GET['tit'];	//titulo
	
	$canvas = new Bg_barra( $int );
	$canvas->draw_limits(abs($_GET['max']),abs($_GET['min']));
	for($i=0;$i<count($c);$i++)
		$canvas->draw_barra($c[$i],$v[$i],$i+1,$t[$i]);
	$canvas->draw_and_close();
}
?>