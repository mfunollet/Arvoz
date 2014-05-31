<?php if ($this->authentication->is_signed_in()) : ?>
<div class="dashboard_profile_image">
    <?php echo img($logged_p->get_image(50,50)) . nbs(1) . anchor($this->ctrlr_name.'/profile', $logged_p->get_name()); ?>
</div>
<?php endif; ?>


