<table class="table table-striped">
<!--  <thead>
    <tr>
      <th>…</th>
      <th>…</th>
    </tr>
  </thead>-->
    <tbody>
        <tr>
            <td>Nome</td>
            <td><?php echo $logged_dt->name; ?></td>
        </tr>
        <tr>
            <td>Level</td>
            <td><?php echo $logged_dt->lvl; ?></td>
        </tr>
        <tr>
            <td>Citizens</td>
            <td><?php echo $logged_dt->citizens; ?></td>
        </tr>
        <tr>
            <td>Army Size</td>
            <td><?php echo $logged_dt->armySize; ?></td>
        </tr>
        <tr>
            <td>Offense Real</td>
            <td><?php echo $logged_dt->offense; ?></td>
        </tr>
        <tr>
            <td>Gold</td>
            <td><?php echo round($logged_dt->gold / 1000000, 2); ?> Milhões</td>
        </tr>
        <tr>
            <td>Banco</td>
            <td><?php echo round($logged_dt->bankgold / 1000000000, 2); ?> Bilhões</td>
        </tr>
        <tr>
            <td>Next Turn in</td>
            <td><?php echo $logged_dt->turnTime['minutes'];?>:<?php echo $logged_dt->turnTime['seconds'];?></td>
        </tr>
        <tr>
            <td>Day Time</td>
            <td><?php echo $logged_dt->dtTime['hours'];?>:<?php echo $logged_dt->dtTime['minutes'];?></td>
        </tr>
    </tbody>
</table>
<div class="progress progress-<?php echo get_color($logged_dt->fort);?>">
    <div class="bar" style="width: <?php echo $logged_dt->fort; ?>%">
        Fort: <?php echo $logged_dt->fort; ?>%
    </div>
</div>
<?php 
echo floor(($logged_dt->exp * 100);
echo ' >> '.($logged_dt->nextleveexp + $logged_dt->exp));?>
<?php $exp_porcento = floor(($logged_dt->exp * 100) / ($logged_dt->nextleveexp + $logged_dt->exp)); ?>
<div class="progress progress-<?php echo get_color($exp_porcento, TRUE);?>">
    <div class="bar" style="width: <?php echo $exp_porcento; ?>%">
        Exp: <?php echo $exp_porcento ?>%
    </div>
</div>