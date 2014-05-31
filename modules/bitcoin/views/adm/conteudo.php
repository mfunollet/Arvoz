				<div id="sub-menu">
                	<h2>Criar Conteudo</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/conteudo')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/conteudo')?>">Adicionar Partido</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
                    <?=$c->render_form($campos,$url)?>
                </div>