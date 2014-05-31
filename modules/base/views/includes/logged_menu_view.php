<?php
$logged_p = $this->authentication->get_logged_p();
if ( $logged_p ) :
    ?>

    <ul class="nav pull-right">        
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><div class="navbar-text user-menu"><?php echo $logged_p->get_name(); ?> <?php echo img($logged_p->get_image(50, 50)); ?></div></a>
            <ul id='logged_menu' class="dropdown-menu">
                <li><?php echo anchor($this->session->userdata('user_type') . '/profile', lang('my_profile')); ?></li>
                <li class="divider"></li>

                <?php if ( $logged_company ) : // Se estiver incorporado    ?>

                    <li class="nav-header"><?php echo lang('use_as') ?></li>
                    <li><?php echo anchor('people/company_logout', img($logged_user->get_image(50, 50)) . $logged_user->first_name . ' ' . $logged_user->last_name); ?> </li> 
                    <?php if ( $show_use_as_company->exists() ) : ?>                   

                        <?php foreach ( $show_use_as_company as $chp ) : ?>
                            <?php if ( $chp->company->id != $logged_company->id ) : ?>
                                <li><?php echo anchor('people/use_as_company/' . $chp->company->id, img($chp->company->get_image(50, 50)) . $chp->company->name) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php foreach ( $show_use_as_institution as $ihp ) : ?>
                            <?php if ( $ihp->institution->id != $logged_company->id ) : ?>
                                <li><?php echo anchor('people/use_as_institution/' . $ihp->institution->id, img($ihp->institution->get_image(50, 50)) . $ihp->institution->name) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    <?php endif; ?>

                    <li class="divider"></li>
                    <?php if ( $this->authentication->is_incorporated() ) : ?>
                        <li><?php echo anchor($this->session->userdata('user_type') . '/edit', lang('edit_company')); ?></li>
                    <?php endif; ?>
                    <li>
                        <?php echo anchor('people/logout', lang('logout')); ?>
                    </li>
                </ul>
            </li>
        </ul>

    <?php elseif ( $logged_user ) : // Se estiver logado como User ?>
        <?php if ( $show_use_as_company->exists() || $show_use_as_institution->exists() ) : ?>
            <li class="nav-header"><?php echo lang('use_as') ?></li>
            <?php if ( $show_use_as_company->exists() ) : ?>
                <?php foreach ( $show_use_as_company as $chp ) : ?>
                    <li><?php echo anchor('people/use_as_company/' . $chp->company->id, img($chp->company->get_image(50, 50)) . $chp->company->name) ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ( $show_use_as_institution->exists() ) : ?>
                <?php foreach ( $show_use_as_institution as $ihp ) : ?>
                    <li><?php echo anchor('people/use_as_institution/' . $ihp->institution->id, img($ihp->institution->get_image(50, 50)) . $ihp->institution->name) ?></li>
                <?php endforeach; ?>

            <?php endif; ?>
            <li class="divider"></li>
        <?php endif; ?>

        <li>
            <?php echo anchor('people/edit', lang('edit_profile')); ?>
        </li>
        <li>
            <?php echo anchor('people/logout', lang('logout')); ?>
        </li>
        </ul>
        </li>
        </ul>
    <?php endif; ?>
<?php endif; ?>