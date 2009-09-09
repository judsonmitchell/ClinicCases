// JavaScript Document

var Accordion = Class.create();

Accordion.prototype = {
	initialize: function(id, tag, name) {
		this.id = id;
		this.headerTag = tag.toUpperCase();
		this.instance = name;
		this.headingClassName = (arguments[3] || "panel");
		this.contentClassName = (arguments[4] || "panelBody");
		this.panels = new Array();

		var tags = $(id).getElementsByTagName('*');		
		for ( var i = 0; i < tags.length; i++) {
			switch(tags.item(i).tagName) {
				case this.headerTag:
					tags.item(i).style.cursor = "pointer";
					tags.item(i).onclick = this._returnEvalCode(this.instance);
					break;

				default:
					if (tags.item(i).className == this.headingClassName) {
						tags[i]._index = this._returnIndex(this.panels.length);
						this.panels[this.panels.length] = tags.item(i);
						//the line above is same meaning as "this.panels.push(tags.item(i));"
						
						if (this.panels.length == 1) {
							tags.item(i).id = "visible";
						}
					}

					if (tags.item(i).className == this.contentClassName) {
						tags.item(i).style.display = "none";
					}
					break;

			}
		}
		this.length = this.panels.length;
		this.show(0, true);
	},

	show: function(index, force) {
		if ( (index >= this.length) || (index < 0) ) {
			//alert("index out of range");
			return;
		}

		if ( $('visible') == this.panels[index] ){
			if (force) {
				//alert("force to show the visible element.");
				for(var i = 0; i < this.length; i++) {
					if(this._body(this.panels[i]).style.display != "none") {
						new Effect.SlideUp(this._body(this.panels[i]));
					}
				}
				new Effect.SlideDown(this._body(this.panels[index]));
				return;
			}
			
			//alert("it's already shown now.");
			return;
		}

		//alert("show another element.");
		new Effect.Parallel(
			[
				new Effect.SlideUp( this._body($('visible')) ),
				new Effect.SlideDown( this._body(this.panels[index]) )
			], {
				duration: 0.2
			}
		);
	
		$('visible').id = "";
		this.panels[index].id = "visible";
		return;
	},

	_body: function(e) {
		var tags = e.getElementsByTagName('*');
		for( var i=0; i<tags.length; i++) {
			if (tags.item(i).className == this.contentClassName) {
				return tags.item(i);
			}
		}
	},

	_returnIndex: function(i) {
		return function() {
			return i;
		}
	},

	_returnEvalCode: function(s) {
		return function(){
			eval(s + ".show(" + this.parentNode._index() + ");");
		}
	}
};