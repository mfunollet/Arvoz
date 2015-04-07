				<div id="sub-menu">
                	<h2>Listar Usu&aacute;rios</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/usuario')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/usuario')?>">Adicionar Usu&aacute;rio</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
				<table width="100%">
					<thead> 
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
							<td><strong>Login</strong></td>
							<td><strong>Email</strong></td>
							<td><strong>Grupo</strong></td>
							<td><strong>Data Cria&ccedil;&atilde;o</strong></td>
							<td><strong>Data Modifica&ccedil;&atilde;o</strong></td>
							<td><strong>A&ccedil;&atilde;o</strong></td>				
						</tr>
					</thead>
					<tbody>
					<? foreach($lista->all as $usuario): ?>
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
							<td><?=$usuario->login ?></td>
							<td><?=$usuario->email ?></td>
							<td><?=$usuario->grupo->nome ?></td>
							<td><?=$usuario->criado ?></td>
							<td><?=$usuario->modificado ?></td>
							<td>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$usuario->id,'<img src="'.base_url().'inc/img/editar.gif"  border="0" alt="Editar" />')?>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$usuario->id.'/apagar','<img src="'.base_url().'inc/img/excluir.gif"  border="0" alt="Apagar" />')?>
							</td>
						</tr>
					<? endforeach; ?>
					</tbody>
				</table>
                </div>