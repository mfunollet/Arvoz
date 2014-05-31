<div class="span3">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
            <li class="nav-header">Sidebar</li>
            <?php
            echo '<h1>DashBoard</h1><br />';
            echo img($logged_user->get_image(50, 50)) . '<h3>Olá ' . $logged_user->first_name . '</h3>';
            ?>

            <?php if (isset($menu['sidebar_left'])): ?>
                <?php foreach ($menu['sidebar_left'] as $link): ?>
                    <li><?php echo anchor($link['uri'], $link['title'], ' class ="classeteste"'); ?><li />
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>