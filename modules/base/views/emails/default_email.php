
<?php $this->load->view('base/emails/header_email') ?>

	<h2>Olá, <?php echo $to->get_name() ?>, </h2>

	<p>
		<?php echo $msg ?>
	</p>


<?php $this->load->view('base/emails/footer_email') ?>				