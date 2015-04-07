<!DOCTYPE html>
<html>
    <head>
        <title>
            <?php echo ((isset($header->title)) ? $header->title . ' - ' : '').$this->config->item('app_name'); ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta property="og:url" content="<?php echo current_url() ?>" />
        <link rel="icon" type="image/png" href="<?php echo base_url() ?>favicon.png"/>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript" ></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript" ></script>

        <?php
        a2_css($css_files);
        a2_javascript($js_files);
        echo link_tag('cjs/app.css');
        ?>

        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>
        <?php $this->load->view('base/includes/top_bar_view', $data); ?>

        <div class="container">
            <?php $this->load->view('base/includes/messages_view', $data); ?>
            <div class="row">
