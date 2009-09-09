var FormProtector = Class.create({

    form    : null,     // the form being protected
    alert   : false,    // whether or not to show the confirm box

    // the message to display in confirm box
    message : 'Please remember to submit your form',

    initialize : function(form)
    {
        this.form = $(form);
        this.form.observe('submit', this._onFormSubmit.bindAsEventListener(this));

        this.form.getElements().each(function(elt) {
            elt.observe('focus', function() {
                this.alert = true;
            }.bindAsEventListener(this));
        }.bind(this));

        Event.observe(window, 'beforeunload', this._onBeforeUnload.bindAsEventListener(this));
    },

    setMessage : function(str)
    {
        this.message = str;
    },

    _onFormSubmit : function(e)
    {
        this.alert = false;
    },

    _onBeforeUnload : function(e)
    {
        if (this.alert)
            e.returnValue = this.message;
    },
    
    resetAlrt : function ()
    {
    	this.alert = false;
	}
    
    
    
 
    	
  
});
