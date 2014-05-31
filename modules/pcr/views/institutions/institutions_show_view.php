<?php if ($element->exists()) : ?>
    <table>
        <div id="items_view">
            <tr><th>Nome</th></tr>
            <tr><th>Ações</th></tr>
            <?php foreach ($element as $item) : ?>
                <tr itemscope itemtype="http://schema.org/person">
                    <td><span itemprop="name"><?php echo anchor('companies/view_company/' . $item->id, $item->name) ?></span></td>
                </tr>
            <?php endforeach; ?>
        </div>	
    </table>
<?php endif; ?>



