<?php if (isset($top_left_menu)):  ?>
    <ul class="nav">    
        <?php foreach ($top_left_menu as $title => $links): ?>            
            <?php
            foreach ($links as $link): 
                $active = '';
                if (($this->uri->rsegment(0) == $link['uri']) || ($this->uri->rsegment(1) == $link['uri']) || ($this->uri->rsegment(1) . '/' . $this->uri->rsegment(2) == $link['uri']))
                    $active = 'active';
                ?>
                <li class="<?php echo $active ?>"><?php echo anchor($link['uri'], $link['title']); ?><li />
            <?php endforeach; ?>
        <?php endforeach; ?>    
    </ul>
    <div class="divider-vertical left"></div>
<?php endif; ?>

<?php if (isset($top_right_menu)): ?>
    <ul class="nav pull-right">    
        <?php foreach ($top_right_menu as $title => $links): ?>            
            <?php
            foreach ($links as $link):
                $active = '';
                if (($this->uri->rsegment(0) == $link['uri']) || ($this->uri->rsegment(1) == $link['uri']) || ($this->uri->rsegment(1) . '/' . $this->uri->rsegment(2) == $link['uri']))
                    $active = 'active';?>
                <li class="<?php echo $active ?>"><?php echo anchor($link['uri'], $link['title']); ?><li />
            <?php endforeach; ?>
        <?php endforeach; ?>    
    </ul>
<?php endif; ?>