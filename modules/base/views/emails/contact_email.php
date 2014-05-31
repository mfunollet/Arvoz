<?php $this->load->view('base/emails/header_email') ?>

	<h2>Olá, <?php echo $to->get_name() ?>, </h2>

	<p>
		<a href='<?php echo site_url($sender_type.'/view/'.$from->id) ?>' target='_blank'><?php echo $from->get_name() ?></a> lhe enviou uma mensagem via <?php echo $this->config->item('app_name') ?>:
	</p>
	
	<p>
		<?php echo $msg ?>
	</p>

<?php $this->load->view('base/emails/footer_email') ?>				