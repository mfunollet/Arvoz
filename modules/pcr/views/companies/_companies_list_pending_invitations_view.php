<?php if ($element->exists()): ?>
    <?php foreach ($element as $chp): ?>
        A empresa <?php echo $chp->company->name; ?> Esta te convidando para ser encubada la. 
        <?php echo anchor('institution_has_companies/respond_invite/' . $chp->id . '/true', 'Aceitar'); ?> 
        <?php echo anchor('institution_has_companies/respond_invite/' . $chp->id, 'Recusar'); ?> <br />
    <?php endforeach; ?>
<?php else: ?>
    Você não possui notificações
<?php endif; ?>