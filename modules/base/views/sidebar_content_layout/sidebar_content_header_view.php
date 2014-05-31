<?php $this->load->view('base/header_view', $data); ?>    
    <div id="left_sidebar" class="span2 sidebar">  
        <?php $this->load->view('base/includes/sidebar_view', $data); ?>
    </div>
    <div class="content span10">            
        <?php if (isset($header->title) || isset($header->screen_title) ) : ?>
            <h1><?php echo isset($header->screen_title) ? $header->screen_title : $header->title ?></h1>
        <?php endif; ?>