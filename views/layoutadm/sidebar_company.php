<div class="span3">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
            <li class="nav-header">Sidebar</li>
            <?php
            if ($logged_user->id == $logged_company->owner->id):
                echo 'sou owner';
            endif;
            debug($menu);
            ?>

            <?php if (isset($menu['sidebar_left'])): ?>
                <?php foreach ($menu['sidebar_left'] as $title => $links): ?>
                    <h4><?php echo $title; ?></h4>
                    <?php foreach ($links as $link): ?>
                        <li><?php echo anchor($link['uri'], $link['title'], ' class ="classeteste"'); ?><li />
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>


            <?php
            echo img($logged_company->get_image(50, 50)) . '<h3>Olá ' . $logged_company->name . '</h3>';
            ?>
        </ul>
    </div>
</div>
