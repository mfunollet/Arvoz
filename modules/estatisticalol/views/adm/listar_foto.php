				<div id="sub-menu">
                	<h2>Listar Fotos</h2>
                <!--	Sub-menu	-->
                	<ul>
						<li class="sub-menu-separador"><a href="<?php echo site_url('adm/listar/fotosalbum')?>">Ver &Aacute;lbuns</a></li> 
						<li><?=anchor('adm/foto/'.$fa->id,'Adicionar Foto')?><br /></li>
                    </ul>
                </div>
             
                <div id="form" style="padding: 0 30px; margin: 15px 0;">
                    <table width="100%">
                        <thead> 
                            <tr style="background: url(<?=base_url()?>inc/img/linha.gif) repeat-x; height: 20px;">
                                <td><strong>Foto</strong></td>
                                <td><strong>Nome</strong></td>
                                <td><strong>A&ccedil;&atilde;o</strong></td>				
                            </tr>
                        </thead>
                        <tbody>
							<? foreach($fa->foto->all as $foto): ?>
                                <tr>
                                    <td><?=($foto->arquivo) ? img('uploads/fotos/'.$foto->miniaturas[0]['width'].'x'.$foto->miniaturas[0]['height'].'/'.$foto->arquivo):'';?></td>
                                    <td><?=($foto->nome) ? $foto->nome:'';?></td>
                                    <td>
                                    <?=anchor('adm/foto/'.$fa->id.'/'.$foto->id.'/apagar','<img src="'.base_url().'inc/img/excluir.gif"  border="0" alt="Apagar" />')?>
                                    </td>
                                </tr>
                            <? endforeach; ?>
                        </tbody>
                    </table>
                	<?=anchor('adm/listar/fotosalbum/'.$fa->id,'Voltar')?>
                </div>