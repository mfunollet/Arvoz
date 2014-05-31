<div class="widget">
    <div class="profile_image">
        <?php echo img($element->get_image(180,180)); ?>
    </div>

    <?php foreach ($element->sites as $site): ?>
        <?php echo anchor($site->url, img($site->get_favicon()), array('target'=>'_blank', 'alt'=>$site->name, 'class'=>'site', 'title'=>$site->name)) ?>
    <?php endforeach; ?>

    <script>
        $('.site').tooltip({placement: 'top'});
    </script>
</div>