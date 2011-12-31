
var menuLeft = {

	__construct: function()    {
	    
	    var self = menuLeft;
	    self.currentFrame = null;
	    
	    $(function(){
	    	$(".menu a").click(function(){
	    		self.currentFrame = this.href;
	    	});
	    });
	    
        $('#maxframe').click(function(){
    	    menuLeft.maxFrame();
        }
        );
	    
	},
	
	maxFrame: function(){
	    var self = menuLeft;
	    
	    if(self.currentFrame!=null)
	    	top.document.location = self.currentFrame;
	}

}

$(document).ready(menuLeft.__construct);
