<div id="frame">    
</div>
    
<script type="text/javascript">
    
    $.get("<?php echo site_url('sites/show'); ?>", function(data, status, response) {
        $('#frame').html(data.content);  
        mark_deletable();
        mark_ajax_modal();
    }, 'json');
</script>