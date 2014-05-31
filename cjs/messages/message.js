/**
 * Js file to work with messages in views
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */

/**
 * Function to format the message to display in the view
 *
 * @param  string $format The format of the message
 * @param  string $msg The message
  */
function msg_format(format, msg) 
{	
	$(document.createElement('div'))
		.attr('id', 'message')
		.addClass(format)
		.html(msg)
		.appendTo('#footer');
	
	message();
}


/**
 * Function to show the message success in the view
 *
 * @param  string $msg The success message
  */
function msg_ok(msg)
{
	msg_format('message-success', msg);
}

/**
 * Function to show the message of information in the view
 *
 * @param  string $msg The information message
  */
function msg_info(msg)
{
	msg_format('message', msg);
}

/**
 * Function to show the message error in the view
 *
 * @param  string $msg The error message
  */
function msg_error(msg)
{
	msg_format('message-error', msg);
}

/**
 * This function is used to define the css style and to show the message in 
 * the view
 *
  */
function message() {
	
	//jQuery("#message").css('margin-left', -(jQuery("#message").width()/2));
/*	jQuery("#message").fadeIn("slow", function () {
				jQuery("#message").show();
			});
	
	setTimeout(function(){
		jQuery("#message").fadeOut("slow", function () {
			jQuery("#message").remove();
		}); }, 8000);
     *
     *
     *
                                jQuery(".alert").css('margin-left', -(jQuery(".alert").width()/2));
     */
    
    //jQuery(".alert").alert();
    jQuery(".alert").fadeIn();
    setTimeout(function(){jQuery(".alert").fadeOut("slow", function () {
            jQuery(".alert").remove();
    }); }, 8000);
}