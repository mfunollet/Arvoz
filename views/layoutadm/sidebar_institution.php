<div class="span3">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
            <li class="nav-header">Sidebar</li>
            <?php if ($logged_user->id == $logged_company->owner->id): ?>
                sou owner
            <?php endif; ?>

            <?php if ($logged_company->get_image(50, 50)) : ?>
                <?php echo img($logged_company->get_image(50, 50)); ?>
            <?php endif; ?>

            <?php if (isset($menu['sidebar_left'])): ?>
                <?php foreach ($menu['sidebar_left'] as $link): ?>
                    <li><?php echo anchor($link['uri'], $link['title'], ' class ="classeteste"'); ?><li />
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>