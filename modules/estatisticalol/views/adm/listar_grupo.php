				<div id="sub-menu">
                	<h2>Listar Grupos</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/grupo')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/grupo')?>">Adicionar Grupo</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
				<table width="100%">
					<thead> 
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
							<td><strong>Nome</strong></td>
							<td><strong>A&ccedil;&atilde;o</strong></td>				
						</tr>
					</thead>
					<tbody>
					<? foreach($lista->all as $grupo): ?>
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
							<td><?=$grupo->nome ?></td>
							<td>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$grupo->id,'<img src="'.base_url().'inc/img/editar.gif"  border="0" alt="Editar" />')?>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$grupo->id.'/apagar','<img src="'.base_url().'inc/img/excluir.gif"  border="0" alt="Apagar" />')?>
							</td>
						</tr>
					<? endforeach; ?>
					</tbody>
				</table>
                </div>