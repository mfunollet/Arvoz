<?php if (!isAjax()) : ?>    
    <?php $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $data); ?>
<?php endif; ?>

<?php $this->load->view($content, $data); ?>

<?php if (!isAjax()) : ?>    
    <?php echo $data['pagination']; ?>
    <?php $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $data); ?>
<?php endif; ?>
