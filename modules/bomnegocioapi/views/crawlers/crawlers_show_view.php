<?php echo anchor('crawlers/create', 'Criar Crawler', array('data-toggle'=>"modal", 'title'=>lang('add_site'), 'class'=>' auto-close btn btn-mini btn-success')); ?>   

<?php if ($element->exists()): ?>
    <table id="<?php echo $this->ctrlr_name ?>_list" class="table-striped table-bordered table-condensed">
        <tr>
            <td>Keyword</td>
            <td>Filters</td>
            <td>Options</td>
            <td>Ações</td>
        </tr>
            <?php foreach ($element as $item) : ?>
            <tr id="<?php echo $ctrlr ?>_<?php echo $item->id ?>">
                    <td><?php echo $item->keyword;?></td>
                    <td><?php echo $item->filters;?></td>
                    <td><?php echo $item->options;?></td>
                    <td>
                        <a class='edit btn btn-mini btn-info auto-close' data-toggle='modal' title="Editar Crawler" href="<?php echo site_url('crawlers/edit/' . $item->id) ?>">Editar Crawler</a>
                        <a class='delete btn btn-mini btn-danger auto-close' data-id="<?php echo $item->id ?>" data-model="<?php echo $ctrlr ?>" href="<?php echo site_url('crawlers/delete/' . $item->id) ?>"><?php echo lang('delete') ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
    </table>
<?php endif; ?>