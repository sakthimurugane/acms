$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("#addProfileForm").validate({
	  
	  onkeyup: false,
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
    	pfname: {
            required: true,
            c: 5
    	},
    	owname: {
            required: true,
            minlength: 5
    	},
    	fathname: {
            required: true,
            minlength: 5
    	},
    	
    	selectreferer: "required",
      email: {
        required: true,
        // Specify that email should be validated
        // by the built-in "email" rule
        email: true
      },
      password: {
        required: true,
        minlength: 5
      }
    },
    // Specify validation error messages
    messages: {
    	pfname: {required: "Please enter the Profile name", minlength: "Profile name should be minimum 5 characters" },
    	owname: {required: "Please enter your Owner name", minlength: "Owner name should be minimum 5 characters" },
    	fathname: {required: "Please enter your Owner's father name", minlength: "Father name should be minimum 5 characters" },
    	selectreferer: {required: "Please select the Referrer"},
    	
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      email: "Please enter a valid email address"	
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
});