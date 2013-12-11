<?php
function ImageBoldLine($resource, $x1, $y1, $x2, $y2, $Color, $BoldNess=2, $func='imageLine')
{
    $x1 -= ($buf=ceil(($BoldNess-1) /2));
    $x2 -= $buf;
    for($i=0;$i < $BoldNess;++$i)
        $func($resource, $x1 +$i, $y1, $x2 +$i, $y2, $Color);
}

class Bg_quad{
	public $tam;
	public $canvas;
	public $cor;
	public $nraios;
	public $intensidades;
	public $c;
	public $raio;

	function gera($arg){
		$this->tam = $arg;
		$this->extra = $arg/10;
		$this->c['x'] = $this->tam/2 + $this->extra;
		$this->c['y'] = $this->tam/2 + $this->extra;
		$this->bgquad();
		$this->draw_raios();
		$this->draw_r();
		$this->draw_med();
	}

	function draw(){
		header ("Content-type: image/png"); 
		ImagePNG($this->canvas); 
		ImageDestroy($this->canvas);
	}
	
	function bgquad(){
		$this->canvas = ImageCreate($this->tam+2*$this->extra+1,$this->tam+2*$this->extra+1);
		$this->nraios = 16;
		$this->intensidades = 5;
		$this->cor = array(	
							'lgrey' => ImageColorAllocate($this->canvas,68,68,68),
							'lgrey' => ImageColorAllocate($this->canvas,127,127,127),
							'black' => ImageColorAllocate($this->canvas,0,0,0),
							'white' => ImageColorAllocate($this->canvas,255,255,255),
							'red' => ImageColorAllocate($this->canvas,255,0,0),
							'trq' => ImageColorAllocate($this->canvas,0,255,255),
							'blue' => ImageColorAllocate($this->canvas,0,0,255),
							'green' => ImageColorAllocate($this->canvas,0,255,0),
							);
			$this->cor[0][0] = ImageColorAllocate($this->canvas,0xFF,0xFF,0xFF);
			$this->cor[1][1] = ImageColorAllocate($this->canvas,0xF6,0xE0,0x11);
			$this->cor[1][2] = ImageColorAllocate($this->canvas,0xF7,0xB6,0x17);
			$this->cor[1][3] = ImageColorAllocate($this->canvas,0xCB,0x94,0x2B);
			$this->cor[1][4] = ImageColorAllocate($this->canvas,0x9E,0x75,0x2B);
			$this->cor[2][1] = ImageColorAllocate($this->canvas,0x93,0x4C,0x76);
			$this->cor[2][2] = ImageColorAllocate($this->canvas,0xC0,0x4F,0x72);
			$this->cor[2][3] = ImageColorAllocate($this->canvas,0xD3,0x54,0x55);
			$this->cor[2][4] = ImageColorAllocate($this->canvas,0xD8,0x53,0x2E);
			$this->cor[3][1] = ImageColorAllocate($this->canvas,0x79,0x75,0xB5);
			$this->cor[3][2] = ImageColorAllocate($this->canvas,0x59,0x82,0xAF);
			$this->cor[3][3] = ImageColorAllocate($this->canvas,0x65,0x98,0xB7);
			$this->cor[3][4] = ImageColorAllocate($this->canvas,0x58,0xA3,0xAE);
			$this->cor[4][1] = ImageColorAllocate($this->canvas,0xAD,0xD1,0x4E);
			$this->cor[4][2] = ImageColorAllocate($this->canvas,0x79,0xC1,0x56);
			$this->cor[4][3] = ImageColorAllocate($this->canvas,0x39,0xB4,0x5B);
			$this->cor[4][4] = ImageColorAllocate($this->canvas,0x00,0xB1,0x7B);
		$this->raio = $this->intensidades*$this->extra;
	}

	function draw_med(){
		$_size = 3;
		$size = ($_size-1)/2;
		ImageFilledRectangle($this->canvas,	
					$this->c['x']-$size,$this->extra,
					$this->c['x']+$size,2*$this->c['y']-$this->extra,
					$this->cor['trq']);
		ImageFilledRectangle($this->canvas,
					$this->extra,$this->c['y']-$size,
					2*$this->c['x']-$this->extra,$this->c['y']+$size,
					$this->cor['trq']);
	}

	function draw_r(){
		$n_circ = $this->intensidades;
		for($i=1;$i<=$n_circ;$i++){
			ImageEllipse(	$this->canvas,
							$this->c['x'],$this->c['y'],
							$i*$this->tam/$n_circ,$i*$this->tam/$n_circ,
							$this->cor['lgrey']);
		}
	}

	function draw_raios(){
		$tot = 360;
		$ini = 0;//$tot/(2*$this->nraios);
		for($i=1;$i<=4;$i++){
			for($j=1;$j<=4;$j++){
				$c = $this->sqq($i,$j);
				ImageFilledArc(	$this->canvas,	
							$this->c['x'],$this->c['y'],
							$this->raio*2,$this->raio*2,
							$ini, $ini + 360/16,
							$this->cor[ 1+($c-$c%4)/4 ][ 4-$c%4 ],
							IMG_ARC_PIE
							);				
				$ini += 360/16;
			}
			}
	}

	function draw_raios2(){
		$tot = 360;
		$ini = 0;//$tot/(2*$this->nraios);
		for($i=0;$i<$this->nraios;$i++){
			$g = $ini + $i*$tot/$this->nraios;
			ImageLine(	$this->canvas,	
						$this->c['x'],$this->c['y'],
						$this->c['x']+$this->raio*cos($g*M_PI/180),$this->c['y']+$this->raio*sin($g*M_PI/180),
						$this->cor['blue']
						);
		}
	}

	function pts($arg){
		$x = $this->c['x'];
		$y = $this->c['y'];
		$w = $this->tam/100;
		$rr = $this->tam/10;
		$ini = (360/$this->nraios)/2;

		$ad = array();			$desenhado = array();

		for($i=0;$i<(count($arg)/3);$i++){
			$r = $arg[3*$i]*$rr;
				$st = $this->sqq($arg[3*$i+1],$arg[3*$i+2]);
			$xx = $x + $r*cos(($ini+360*$st/$this->nraios)*M_PI/180);
			$yy = $y + $r*sin(($ini+360*$st/$this->nraios)*M_PI/180);
				
			$ad[] = array($xx,$yy,$arg[3*$i+1],$arg[3*$i+2],!($arg[3*$i+1]==0 || $arg[3*$i+2]==0 || $arg[3*$i]==0));
		}
			$ad[] = $ad[0];		//"fechar" o ciclo. primeiro vertice eh tambem o ultimo

		if(count($ad)>1)
			for($i=0;$i<count($ad)-1;$i++){
				if(($ad[$i][0] != $ad[$i+1][0]) || ($ad[$i][1] != $ad[$i+1][1])){
					ImageBoldLine($this->canvas,
									$ad[$i][0],$ad[$i][1],
									$ad[$i+1][0],$ad[$i+1][1],
									$this->cor['black'],	2);
				}
			}
		
		for($i=0;$i<(count($ad)-1);$i++){
			if($ad[$i][4])
				ImageFilledRectangle($this->canvas,
									$ad[$i][0]-$w,$ad[$i][1]-$w,
									$ad[$i][0]+$w,$ad[$i][1]+$w,
									$this->cor['black']);
			else
				ImageFilledRectangle($this->canvas,
									$ad[$i][0]-$w,$ad[$i][1]-$w,
									$ad[$i][0]+$w,$ad[$i][1]+$w,
									$this->cor['white']);
		}
	}

	public function sqq($i,$j){
		if($i%2==0)
			return 20-4*$i-(5-$j);
		else
			return 20-4*$i-$j;
	}
}
?>