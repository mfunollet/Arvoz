<?php if ($element->exists()) : ?>
    <table>
        <div id="items_view">
            <tr><th>Nome</th></tr>
            <tr><th>Ações</th></tr>
            <?php foreach ($element as $item) : ?>
                <tr id="<?php echo $ctrlr ?>_<?php echo $item->id ?>">
                    <td><span itemprop="name"><?php echo anchor('companies/view_company/' . $item->id, $item->name) ?></span></td>
                </tr>
            <?php endforeach; ?>
        </div>	
    </table>
<?php endif; ?>