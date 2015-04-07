<?php if (!isAjax() && !isset($ajax)) : ?>
    <?php $this->load->view($layout . '/' . $layout . '_header_view', $data); ?>
<?php endif; ?>

<?php $this->load->view($content, $data); ?>

<?php if (!isAjax() && !isset($ajax)) : ?>
    <?php echo $data['pagination']; ?>
    <?php $this->load->view($layout . '/' . $layout . '_footer_view', $data); ?>
<?php endif; ?>
