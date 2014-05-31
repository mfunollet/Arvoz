<?php if ( $this->session->flashdata('message') || isset($message) ): ?>
    <?php 
    if ( isset($message) ) 
    {
        echo $message; 
        $this->session->unset_userdata('message');
    }
    else
    {
        echo $this->session->flashdata('message');
    }
    ?>
	
	<script>
	(function($) {
		message();
	})(jQuery);	
	</script>
<?php endif; ?>
