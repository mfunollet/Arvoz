				<div id="sub-menu">
                	<h2>Artigo</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/artigo')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/artigo')?>">Adicionar Artigo</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
                    <?=$a->render_form($campos,$url)?>
                </div>