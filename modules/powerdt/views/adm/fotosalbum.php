				<div id="sub-menu">
                	<h2>Criar &Aacute;lbum de fotos</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/fotosalbum')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/fotosalbum')?>">Adicionar &Aacute;lbum de fotos</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
                    <?=$fa->render_form($campos,$url)?>
                </div>