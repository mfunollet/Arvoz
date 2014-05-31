				<div id="sub-menu">
                	<h2>Listar Grupos</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/grupo')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/grupo')?>">Adicionar grupo</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
                    <?=$g->render_form($campos,$url)?>
                </div>