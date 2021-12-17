<?php
/**
 * Plugin Name:     BlockAdBlock: Plugin by Utopian Corps
 * Plugin URI:      https://utopiancorps.com
 * Description:     A simple plugin to block ads using scripts generated via https://blockadblock.com
 * Author:          Antariksh Patre
 * Author URI:      https://github.com/aapatre
 * Text Domain:     utopiancorps-blockadblock
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Utopiancorps_Blockadblock
 */

// Custom Settings Page.

add_action( 'admin_menu', 'uc_blockadblock_add_admin_menu' );
add_action( 'admin_init', 'uc_blockadblock_settings_init' );


function uc_blockadblock_add_admin_menu(  ) { 

	add_menu_page( 'BlockAdBlock Settings', 'BlockAdBlock', 'manage_options', 'uc_blockadblock_settings', 'uc_blockadblock_options_page', 'dashicons-privacy' );

}


function uc_blockadblock_settings_init(  ) { 

	register_setting( 'uc_adblock_settings_page', 'uc_blockadblock_settings' );

	add_settings_section(
		'uc_blockadblock_settings_page_section', 
		__( 'Instructions', 'utopiancorps-blockadblock' ), 
		'uc_blockadblock_settings_section_callback', 
		'uc_adblock_settings_page'
	);

	add_settings_field( 
		'uc_blockadblock_script_textarea', 
		__( 'Insert Your Customized BlockAdblock Code:', 'utopiancorps-blockadblock' ), 
		'uc_blockadblock_script_textarea_render', 
		'uc_adblock_settings_page', 
		'uc_blockadblock_settings_page_section' 
	);

	add_settings_field( 
		'uc_blockadblock_js_protection_checkbox', 
		__( 'Enable JavaScript Blocking Protection?', 'utopiancorps-blockadblock' ), 
		'uc_blockadblock_js_protection_checkbox_render', 
		'uc_adblock_settings_page', 
		'uc_blockadblock_settings_page_section' 
	);


}


function uc_blockadblock_script_textarea_render(  ) { 

	$options = get_option( 'uc_blockadblock_settings' );
	?>
	<textarea cols='40' rows='5' name='uc_blockadblock_settings[uc_blockadblock_script_textarea]'><?php echo $options['uc_blockadblock_script_textarea']; ?></textarea>
	<?php

}


function uc_blockadblock_js_protection_checkbox_render(  ) { 

	$options = get_option( 'uc_blockadblock_settings' );
	?>
	<input type='checkbox' name='uc_blockadblock_settings[uc_blockadblock_js_protection_checkbox]' <?php checked( $options['uc_blockadblock_js_protection_checkbox'], 1 ); ?> value='1'>
	<?php

}


function uc_blockadblock_settings_section_callback(  ) { 

	echo __( 'Instructions area', 'utopiancorps-blockadblock' );

}


function uc_blockadblock_options_page(  ) { 

		?>
		<form action='options.php' method='post'>

			<h2>BlockAdBlock Settings</h2>

			<?php
			settings_fields( 'uc_adblock_settings_page' );
			do_settings_sections( 'uc_adblock_settings_page' );
			submit_button();
			?>

		</form>
		<?php

}

// Enqueue script on frontend.

add_action( 'wp_footer', 'uc_blockadblock_frontend_execute_script', 160 );
add_action( 'wp_body_open', 'uc_blockadblock_frontend_js_block_protection', 1 );

function uc_blockadblock_frontend_execute_script() {
    $options = get_option( 'uc_blockadblock_settings' );
    echo $options['uc_blockadblock_script_textarea'];
}

function uc_blockadblock_frontend_js_block_protection() {
    $options = get_option( 'uc_blockadblock_settings' );

    if ($options['uc_blockadblock_js_protection_checkbox'] && !is_admin()) {
        ?>
            <div id="babasbmsgx" style="visibility: visible !important;">Please disable your adblock and script blockers to view this page</div>
            <script>document.getElementsByTagName("body")[0].style = 'visibility: hidden !important;';</script>
        <?php
    }
}
