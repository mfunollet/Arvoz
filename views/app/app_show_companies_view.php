<?php if ($element->exists()) : ?>
<ul class="thumbnails">
    <?php foreach ($element as $item) : ?>
        <li class="span3" itemscope itemtype="http://schema.org/Corporation">
            <div class="thumbnail">
                <?php echo img($item->get_image(180, 180)); ?>
                <h3 itemprop="name"><?php echo anchor('companies/view_company/'.$item->id, $item->name, array('itemprop'=>'url')) ?></h3>
                <p>
                    <span itemprop="email"><?php echo $item->email1 ?></span><br>
                    <span itemprop="telephone"><?php echo $item->phone1 ?></span><br>               
                </p>
            </div>
        </li>    
    <?php endforeach; ?>
</ul>
<?php endif; ?>



