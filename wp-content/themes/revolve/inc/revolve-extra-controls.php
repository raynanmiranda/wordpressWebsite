<?php
    if( class_exists( 'WP_Customize_Control' ) ):
    	
        class Revolve_WP_Customize_Submenu_Control extends WP_Customize_Control{
            public function render_content() {
                $ppages = $this->revolve_get_pages_with_childpages();
				?>
				<label>
                    <?php if ( ! empty( $this->label ) ) : ?>
    				    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                    <?php endif; ?>
                </label>
                <?php if(!empty($ppages)) : ?>
                    <ul class="ppar-page-containers">
                    <?php foreach($ppages as $page) : ?>
                        <?php
                            $sargs = array(
                                'parent' => $page->ID,
                            	'post_type' => 'page',
                            	'post_status' => 'publish'
                            );
                        
                            $spages = get_pages($sargs);
                        ?>
                        <li class="par-page-titler">
                            <span class="par-page-title"><?php echo $page->post_title; ?></span>
                            <?php if(!empty($spages)) : ?>
                                <ul class="ssub-page-container">
                                    <?php foreach($spages as $spage) : ?>
                                        <li class="sub-page-titler">
                                            <table>
                                                <tr>
                                                    <th>Page</th>
                                                    <td><?php echo $spage->post_title; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>ID</th>
                                                    <td><input type="text" /></td>
                                                </tr>
                                            </table>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>                    
				<?php
            }
            
            public function revolve_get_pages_with_childpages() {
                $fargs = array(
                	'parent' => 0,
                	'post_type' => 'page',
                	'post_status' => 'publish'
                );
                
                $ppages = get_pages($fargs);
                return $ppages;
            }
        }
        
    endif;