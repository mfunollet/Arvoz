<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
	<title>Arvoz</title>

     <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link href="<?php echo base_url() ?>cjs/docs/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>cjs/docs/css/arvoz.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>cjs/docs/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url() ?>cjs/docs/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>cjs/docs/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>cjs/docs/js/bootstrap-modal.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>cjs/docs/js/bootstrap-tooltip.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>cjs/docs/js/bootstrap-alert.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>cjs/docs/js/jquery-ui-1.8.17.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>cjs/docs/js/jquery.ui.datepicker-pt-BR.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>cjs/docs/js/jquery.numeric.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>cjs/docs/js/arvoz.util.js"></script>
</head>
<body>
    <div class="container" style="padding-right:80px">
        <section id="grid-system">
            <div class="row">
                <div class="span12 header">HEADER</div>
            </div>
            <div class="row">
                <div class="span3">
                    <div class="well">
                        <ul class="nav nav-list">
                            <li class="nav-header">
                                Processos
                            </li>
                            <li class="<?php echo ($active == 'process_document') ? 'active' : '' ?>">
                                <a href="<?php echo site_url('process');?>">Documentos</a>
                            </li>
                            <li class="nav-header">
                                Empreendimentos
                            </li>
                            <li class="<?php echo ($active == 'prospection_1') ? 'active' : '' ?>">
                                <a href="<?php echo site_url('prospection/index/1');?>">Sensibilização</a>
                            </li>
                            <li class="<?php echo ($active == 'prospection_2') ? 'active' : '' ?>">
                                <a href="<?php echo site_url('prospection/index/2');?>">Prospecção</a>
                            </li>
                            <li class="nav-header">
                                Empreendedores
                            </li>
                            <li class="<?php echo ($active == 'qualification') ? 'active' : '' ?>">
                                <a href="<?php echo site_url('qualification');?>">Qualificação</a>
                            </li>
                             <li class="nav-header">
                                Planejamento
                            </li>
                            <li class="<?php echo ($active == 'planning') ? 'active' : '' ?>">
                                <a href="<?php echo site_url('planning');?>">Gestão</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="span9 content">