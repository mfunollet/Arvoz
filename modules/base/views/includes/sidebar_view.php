<?php if (isset($sidebars)) : ?>
    <?php foreach ($sidebars as $widget) : ?>
        <?php
        if (is_array($widget))
            $this->load->view($widget['view'], $widget['data']);
        else
            $this->load->view($widget);
        ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($left_menu)): ?>
    <div class="well">
        <ul class="nav nav-list">
            <?php foreach ($left_menu as $title => $links): ?>
                <?php echo (!empty($title)) ? '<li class="nav-header">' . $title . '</li>' : '' ?>
                <?php foreach ($links as $link): ?>
                    <li class="<?php echo ($link['active']) ? 'active' : '' ?>"><?php echo anchor($link['uri'], $link['title'], ' class ="classeteste"'); ?><li />
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
