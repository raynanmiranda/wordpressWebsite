<?php
	/** Revolve Woo Tweaks **/
	//woocommerce_result_count
	function revolve_woocommerce_result_count() {
		?>
		<div class="woo-entry-content">
		<?php
	}
	add_action( 'woocommerce_before_shop_loop', 'revolve_woocommerce_result_count', 19 );

	function revolve_woocommerce_pagination() {
		?>
		</div> <!-- entry-content -->
		<?php
	}
	add_action( 'woocommerce_after_shop_loop', 'revolve_woocommerce_pagination', 11 );

	function revolve_woocommerce_before_single_product() {
		?>
		<div class="woo-entry-content">
		<?php
	}
	add_action( 'woocommerce_before_single_product', 'revolve_woocommerce_before_single_product', 11 );

	function revolve_woocommerce_after_single_product() {
		?>
		</div>
		<?php
	}
	add_action( 'woocommerce_after_single_product', 'revolve_woocommerce_after_single_product', 11 );

	function revolve_woocommerce_show_page_title() {
		return false;
	}
	add_filter('woocommerce_show_page_title', 'revolve_woocommerce_show_page_title');

	function revolve_woocommerce_add_archive_titlex() {
		wp_reset_postdata();
		?>
		<header class="entry-header">
			<?php if(is_product()) : ?>
				<h1 class="page-title"><?php the_title(); ?></h1>
			<?php else : ?>
				<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
			<?php endif; ?>
		</header>
		<?php
	}

	add_action('revolve_woocommerce_archive_title', 'revolve_woocommerce_add_archive_titlex');

	function revolve_woocommerce_archive_title() {
		do_action('revolve_woocommerce_archive_title');
	}

	add_action( 'woocommerce_before_main_content', 'revolve_woocommerce_archive_title', 19 );