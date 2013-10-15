function Character(data){
	// appearance
	this.hair = data.hair;
	this.eyes = data.eyes;
	this.skinColor = data.skinColor;
	this.gloveColor = data.gloveColor;
	this.bodyColor = data.bodyColor;

	// position
	this.posx = data.posx;
	this.posy = data.posy;
	this.orientation = data.orientation;
}

Character.prototype.updatecharacter = function(data){
	this.posx = data.posx;
	this.posy = data.posy;
	this.orientation = data.orientation; // the side he's facing, not his preferred gender
}