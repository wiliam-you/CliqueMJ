jQuery(document).ready(function() 
{
    /* landing Form Validation Start */
    jQuery("#admin_login").validate({
    
        errorClass: 'text-danger',
        rules: {
           
            email: 
            {
                required: true,
                email:true
                
            },
            password: 
            {
                required: true,
                minlength:6
            }
           
        },
        messages: {
            email:{
                required: "Please enter email.",
                email: "Please enter valid email."
            },
           
            password: {
                required: "Please enter password.",
                minlength: "Please enter minimum 6 characters."
            }   
            
        },
        submitHandler: function(form) {
          
            form.submit();
        }
    });
   

});

