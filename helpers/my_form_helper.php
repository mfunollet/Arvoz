<?php

// ------------------------------------------------------------------------

/**
 * Text Input Field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
function input_autocomplete($object, $field, $value, $options) {
    $input = $field . '_input';
    $html = form_input($input, $value, 'id="' . $input . '"');
    $html .= '
            <script type = "text/javascript">
	$(function() {
		$( "#' . $input . '" ).autocomplete({
			source: function(request, response) {
				$.ajax({ url: "' . site_url() . '/' . plural($field) . '/autocomplete",
				data: { term: $("#qtext").val()},
				dataType: "json",
				type: "POST",
				success: function(data){
					response(data);
				}
			});
		},
		minLength: 2
		}).data( "autocomplete" )._renderItem = formatP;
	});            
        </script>';
    return $html;
}
?>
