<?php $this->load->view('base/header_view', $data); ?>         
    <div class="content span12">            
        <?php if (isset($header->title) || isset($header->screen_title) ) : ?>
            <h1><?php echo isset($header->screen_title) ? $header->screen_title : $header->title ?></h1>
        <?php endif; ?>