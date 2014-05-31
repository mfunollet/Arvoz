<?php if ($element->exists()) : ?>
    <ul class="thumbnails">
        <?php foreach ($element as $item) : ?>
            <li  class="span2" id="<?php echo $ctrlr ?>_<?php echo $item->id ?>">
                <div class="thumbnail">
                    <?php echo img($item->company->get_image(180, 180)); ?>
                    <h3 itemprop="name"><?php echo anchor('companies/view/' . $item->company->id, $item->company->get_name(), array('itemprop' => 'url')) ?></h3>
                    <p>
                        <span itemprop="email"><?php echo $item->company->email1 ?></span><br>
                        <span itemprop="phone1"><?php echo $item->company->phone1 ?></span><br>               
                    </p>
                </div>
            </li>    
        <?php endforeach; ?>
    </ul>
<?php endif; ?>



