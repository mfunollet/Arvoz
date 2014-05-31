				<div id="sub-menu">
                	<h2>Listar Not&iacute;cias</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/noticia')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/noticia')?>">Adicionar Not&iacute;cia</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
				Legenda : os listado da cor <b style="color :#00923f">verde</b> s&atilde;o not&iacute;cias em destaque.
				<table width="100%">
					<thead> 
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
							<td><strong>Titulo</strong></td>
							<!--<td><strong>Thumb</strong></td>-->
							<td><strong>Autor</strong></td>
							<td><strong>A&ccedil;&atilde;o</strong></td>				
						</tr>
					</thead>
					<tbody>
					<? foreach($lista->all as $noticia): ?>
						<tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;<?=($noticia->destaquenoticia->id)? 'color : #00923f;' : '' ?>" >
							<td><?=$noticia->titulo ?></td>
							<!--<td><?=($noticia->arquivo) ? img('uploads/noticias/'.$noticia->miniaturas[0]['width'].'x'.$noticia->miniaturas[0]['height'].'/'.$noticia->arquivo):
												img('uploads/noticias/'.$noticia->miniaturas[0]['width'].'x'.$noticia->miniaturas[0]['height'].'/blank.gif');?></td>-->
							<td><?=$noticia->usuario->login ?></td>
							<td>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$noticia->id,'<img src="'.base_url().'inc/img/editar.gif"  border="0" alt="Editar" />')?>
								<?=anchor('adm/'.strtolower(get_class($lista)).'/'.$noticia->id.'/apagar','<img src="'.base_url().'inc/img/excluir.gif"  border="0" alt="Apagar" />')?>
							</td>
						</tr>
					<? endforeach; ?>
					</tbody>
				</table>
                </div>