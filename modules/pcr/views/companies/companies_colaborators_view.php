<?php if ( $element->exists() ): ?>
    <?php foreach ( $element as $item ) : ?>
        <div class="card" id="<?php echo $ctrlr ?>_<?php echo $item->id ?>">  
            <div class="image"><?php echo img($item->person->get_image(50,50)) ?></div>
            <div class="name"><?php echo anchor('people/view/' . $item->person->id, $item->person->first_name . ' ' . $item->person->last_name) ?></h3></div>
            <div class="job"><?php echo $item->job; ?></div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>





