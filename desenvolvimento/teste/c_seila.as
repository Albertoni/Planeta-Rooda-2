
	
class c_seila extends MovieClip {
		
		
	public function c_seila() {
		onMouseDown = funcaoMouseDown;
	}
		
		
	public function funcaoMouseDown(){
		_x+=5;
		this['naosei']._y+=10;
	}
		
	
}
