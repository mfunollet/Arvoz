				<div id="sub-menu">
                	<h2>Criar Usu&aacute;rio</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/usuario')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/usuario')?>">Adicionar Usu&aacute;rio</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
				<?=$u->render_form($campos,$url)?>
                </div>