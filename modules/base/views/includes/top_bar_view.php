<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="container">
            <a href="<?php echo site_url() ?>" class="navbar-brand">
                <?php 
                    echo img('images/app_tag.png', FALSE, array(
                            'alt' => $this->config->item('app_name'),
                            'title' => $this->config->item('app_name')
                    )); 
                ?>
                <?php echo $this->config->item('app_name'); ?>
            </a>
            <div class="divider-vertical left"></div>
            <div class="nav-collapse">
                <?php $this->load->view('base/includes/top_menu_view', $data); ?>
                <?php $this->load->view('base/includes/logged_menu_view', $data); ?>
            </div>
        </div>
    </div>
</nav>