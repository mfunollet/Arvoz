				<div id="sub-menu">
                	<h2>Criar Album de videos</h2>
                <!--	Sub-menu	-->
					<ul> 
                        <li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/videosalbum')?>">Ver Todos</a></li> 
                        <li><a href="<?php echo site_url('adm/videosalbum')?>">Adicionar &Aacute;lbum de v&iacute;deos</a></li> 
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
				<?=$va->render_form($campos,$url)?>
                </div>