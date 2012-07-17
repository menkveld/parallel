/*
 * ViewModel: user
 */
function userViewModel() {
	var self = this;
	
    self.profileData = ko.observable(null);
    self.settingsData = ko.observable(null);

    // Error messages
    self.errorData = ko.observable();

	// Option Menu
	self.options = ['Profile','Settings','Change Password'];
	self.currentOptionId = ko.observable();
	
	// Select Option Function
	self.selectOption = function(option) {
		self.profileData(null);
		$('#spinner').show();
		
        // Load initial data
        $.ajax({
        	type: 'GET',
        	contentType: 'json',
        	url: restUrl+'/'+option,
        	success: function(data) {        		
        		// Stop the spinner
        		$('#spinner').fadeOut('fast', function() {
            		self.profileData(data);        			
        		});
        	},
        	error: function(error) {
        		
        	}
        });            
        self.currentOptionId(option);
    };
    
    // Save Profile
    self.saveProfile = function() {
    	// Turn on the spinner
    	$('#saving_spinner').spin('small');
        $.ajax({
        	type: 'POST',
        	contentType: 'json',
        	url: restUrl+'/Profile',
        	data: ko.toJSON(self.profileData),
        	
        	success: function(data) {
        		self.errorData(null);
        	},
        	
        	error: function(error) {
        		self.errorData($.parseJSON(error.responseText));	// The returned error is a jqXHR object, so we have to get the responseTExt from it
        	},
        	
        	complete: function(data) {
        		// Turn off the spinner
        		$('#saving_spinner').spin(false);
        	}
        });    
    }
    
    // Returns the requested error if present
    self.hasError = function(error) {
    	if(self.errorData() != undefined) {
        	if(self.errorData()[error]!=undefined) {
        		return self.errorData()[error];
        	} 		
    	}
    	return false;
    } 
    
    self.selectOption('Profile');
}
// Activate the wait spinner
$("#spinner").spin("large");
// Activates knockout.js
ko.applyBindings(new userViewModel());