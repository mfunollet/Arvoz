				<div id="sub-menu">
                	<h2>Listar &Aacute;lbuns de fotos</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/fotosalbum')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/fotosalbum')?>">Adicionar &Aacute;lbum de fotos</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
				<table width="100%">
					<thead> 
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
							<td><strong>Nome</strong></td>
							<!--<td><strong>Thumb</strong></td>-->
							<td><strong>Fotos</strong></td>
							<td><strong>A&ccedil;&atilde;o</strong></td>				
						</tr>
					</thead>
					<tbody>
					<? foreach($lista->all as $album): ?>
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
							<td><?=$album->nome ?></td>
							<!--<td><?=($album->foto->arquivo) ? img('uploads/fotos/'.$album->foto->miniaturaConfig[0]['width'].'x'.$album->foto->miniaturaConfig[0]['height'].'/'.$album->foto->arquivo).'':'';?></td>-->
							<td><?=anchor('adm/listar/foto/'.$album->id,'Listar Fotos')?></td>
							<td>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$album->id,'<img src="'.base_url().'inc/img/editar.gif"  border="0" alt="Editar" />')?>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$album->id.'/apagar','<img src="'.base_url().'inc/img/excluir.gif"  border="0" alt="Apagar" />')?>
							</td>
						</tr>
					<? endforeach; ?>
					</tbody>
				</table>
                </div>