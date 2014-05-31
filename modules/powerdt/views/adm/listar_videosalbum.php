				<div id="sub-menu">
                	<h2>Listar &Aacute;lbuns de v&iacute;deos</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/videosalbum')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/videosalbum')?>">Adicionar &Aacute;lbum de v&iacute;deos</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
				<?=anchor('adm/destaquevideo','Escolher Video em destaque');?>
				<table width="100%">
					<thead> 
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
							<td><strong>Nome</strong></td>
							<td><strong>A&ccedil;&atilde;o</strong></td>				
						</tr>
					</thead>
					<tbody>
					<? foreach($lista->all as $videosAlbum): ?>
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
							<td><?=$videosAlbum->nome ?></td>
							<td>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$videosAlbum->id,'<img src="'.base_url().'inc/img/editar.gif"  border="0" alt="Editar" />')?>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$videosAlbum->id.'/apagar','<img src="'.base_url().'inc/img/excluir.gif"  border="0" alt="Apagar" />')?>
							</td>
						</tr>
					<? endforeach; ?>
					</tbody>
				</table>
                </div>