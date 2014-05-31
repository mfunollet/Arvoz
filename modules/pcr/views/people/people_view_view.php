<div id="items_view">
    Nome: <?php echo $element->first_name ?> <?php echo $element->last_name ?><br />
    Email: <?php echo $element->email1 ?>
    <br /><br />
    <h3>Sites:</h3>
    <?php foreach ($element->sites as $site): ?>
        <?php echo anchor($site->url, $site->name, 'target="_blank"') ?><br />
    <?php endforeach; ?>
</div>	
