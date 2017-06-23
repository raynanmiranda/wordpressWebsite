<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2015.08.11.
 * Time: 14:37
 */
?>
	<tr valign="top">
		<th scope="row">
			<label class="control-label">Redirect to: </label>
		</th>
		<td>
			<label class="radio inline">
				<input type="radio" name="form_redirect_to_ck" id="form_redirect_to_page_or_post_ck" value="page_or_post" <?php echo ( $editForm->redirectOnSuccess == '0' ) ? 'disabled' : '' ?> <?php echo ( $editForm->redirectToPageOrPost == 1 ) ? 'checked' : '' ?>>
				Page or Post
			</label>
			<label class="radio inline">
				<input type="radio" name="form_redirect_to_ck" id="form_redirect_to_url_ck" value="url" <?php echo ( $editForm->redirectOnSuccess == '0' ) ? 'disabled' : '' ?> <?php echo ( $editForm->redirectToPageOrPost == 0 ) ? 'checked' : '' ?>>
				URL entered manually
			</label>
			<div id="redirect_to_page_or_post_ck_section" <?php echo( $editForm->redirectToPageOrPost == 0 ? 'style="display: none;"' : '' ) ?>>
				<?php
				$query = new WP_Query( array( 'post_type' => array( 'page', 'post' ) ) );
				?>
				<div class="ui-widget">
					<select name="form_redirect_page_or_post_id_ck" id="form_redirect_page_or_post_id_ck" <?php echo ( $editForm->redirectOnSuccess == '0' ) ? 'disabled' : '' ?>>
						<option value=""><?php esc_html_e( 'Select from the list or start typing', 'wp-full-stripe' ); ?></option>
						<?php
						foreach ( $query->posts as $page_or_post ) {
							$option = '<option value="' . esc_attr( $page_or_post->ID ) . '"';
							if ( $page_or_post->ID == $editForm->redirectPostID ) {
								$option .= ' selected';
							}
							$option .= '>';
							$option .= esc_html( $page_or_post->post_title );
							$option .= '</option>';
							echo $option;
						}
						?>
					</select>
				</div>
			</div>
			<div id="redirect_to_url_ck_section" <?php echo( $editForm->redirectToPageOrPost == 1 ? 'style="display: none;"' : '' ) ?>>
				<input type="text" class="regular-text" name="form_redirect_url_ck" id="form_redirect_url_ck" <?php echo ( $editForm->redirectOnSuccess == '0' ) ? 'disabled' : '' ?> placeholder="Enter URL" value="<?php echo $editForm->redirectUrl; ?>" maxlength="<?php echo $form::REDIRECT_URL_LENGTH; ?>">
			</div>
		</td>
	</tr>
<?php if ( $editForm->redirectOnSuccess == '1' && $editForm->redirectToPageOrPost == 1 ): ?>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			jQuery('.page_or_post-combobox-input').prop('disabled', false);
			jQuery('.page_or_post-combobox-toggle').button("option", "disabled", false);
		});
	</script>
<?php endif; ?>