<?php if (!isAjax()) : ?>    
    <?php $this->load->view('base/simple_layout/header', $data); ?>
<?php endif; ?>

<?php $this->load->view($content, $data); ?>

<?php if (!isAjax()) : ?>    
    <?php echo $data['pagination']; ?>
    <?php $this->load->view('base/simple_layout/footer', $data); ?>
<?php endif; ?>
