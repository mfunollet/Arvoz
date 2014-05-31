<?php if ($element->exists()) : ?>
    <ul class="thumbnails">
        <?php foreach ($element as $item) : ?>
            <li  class="span2" id="<?php echo $ctrlr ?>_<?php echo $item->id ?>">
                <div class="thumbnail">
                    <?php echo img($item->get_image(180, 180)); ?>
                    <h3 itemprop="name"><?php echo anchor('people/view/' . $item->id, $item->get_name(), array('itemprop' => 'url')) ?></h3>
                    <p>
                        <span itemprop="email"><?php echo $item->email1 ?></span><br>
                        <span itemprop="phone1"><?php echo $item->phone1 ?></span><br>               
                    </p>
                </div>
            </li>    
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
