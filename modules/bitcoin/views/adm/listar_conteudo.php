				<div id="sub-menu">
                	<h2>Listar Partido</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/conteudo')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/conteudo')?>">Adicionar Partido</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
				<table width="100%">
					<thead> 
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
							<td><strong>T&iacute;tulo</strong></td>
							<td><strong>Autor</strong></td>
							<td><strong>Resumo</strong></td>
							<td><strong>A&ccedil;&atilde;o</strong></td>				
						</tr>
					</thead>
					<tbody>
					<? foreach($lista->all as $conteudo): ?>
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
							<td><?=$conteudo->titulo ?></td>
							<td><?=$conteudo->usuario->login ?></td>
							<td><?=resumir($conteudo->html,50) ?></td>
							<td>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$conteudo->id,'<img src="'.base_url().'inc/img/editar.gif"  border="0" alt="Editar" />')?>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$conteudo->id.'/apagar','<img src="'.base_url().'inc/img/excluir.gif"  border="0" alt="Apagar" />')?>
							</td>
						</tr>
					<? endforeach; ?>
					</tbody>
				</table>
                </div>