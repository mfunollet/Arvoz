<?php $this->load->view('base/emails/header_email') ?>
    
    <h2>Olá admin,</h2>	

    <p>
        <?php echo $p->get_name() ?> solicitou a inclusão da incubadora <?php echo $i->get_name() ?> no Arvoz.
    </p>

    <p>
        <b>Nome:</b> <?php echo $p->get_name() ?><br>        
        <b>Email:</b> <?php echo $p->email1 ?><br>
        <b>Gênero:</b> <?php echo $p->gender ?><br>
        <b>Data de nascimento:</b> <?php echo $p->birthday ?><br>
    </p>
    
    <p>
        <b>Nome:</b> <?php echo $i->get_name() ?><br>
        <b>CNPJ:</b> <?php echo $i->cnpj ?><br>
        <b>Email:</b> <?php echo $i->email1 ?><br>
        <b>Telefone:</b> <?php echo $i->phone1 ?><br>
        <b>Data de fundação:</b> <?php echo $i->foundation ?><br>
    </p>

<?php $this->load->view('base/emails/footer_email') ?>				