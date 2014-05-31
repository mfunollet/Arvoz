				<div id="sub-menu">
                	<h2><?=($n->id)?'Editar':'Criar'?> Not&iacute;cia</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/noticia')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/noticia')?>">Adicionar Not&iacute;cia</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
				<?=($n->arquivo) ? img('uploads/noticias/'.$n->miniaturas[1]['width'].'x'.$n->miniaturas[1]['height'].'/'.$n->arquivo):'';?>
				<?=$n->render_form($campos,$url)?>
                </div>