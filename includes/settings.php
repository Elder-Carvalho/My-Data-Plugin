<?php 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class MyDataSettings{
	private $settings;

	public function __construct(){
		add_action('admin_menu',array($this,'md_settings_menu'));
		add_action('admin_init',array($this,'md_settings_init'));
	}

	public function md_settings_menu(){
		add_submenu_page('my_data_page', 'Opções', 'Opções', 'manage_options', 'md_settings_page', array($this,'init_settings_page') );
	}

	public function init_settings_page(){
		
		$this->settings = get_option('md_settings');
		
		?>

		<div class="wrap">
			<h1>Opções</h1>
			<form method="POST" action="options.php"> 
				<?php 
					settings_fields('md_settings_group');
					do_settings_sections('md_settings_page');
					submit_button();
				 ?>
			</form>
		</div>


		<?php
	}

	public function md_settings_init(){
		register_setting('md_settings_group', 'md_settings');

		add_settings_section('md_settings_section', '', '', 'md_settings_page');
		add_settings_field( 'md_google_api_key', 'Chave de API', array($this,'md_google_api_key'), 'md_settings_page','md_settings_section');
	}

	public function md_google_api_key(){
		printf(
			'<input type="text" id="md_google_api_key" class="regular-text" name="md_settings[md_google_api_key]" value="%s">',
			isset($this->settings['md_google_api_key']) ? $this->settings['md_google_api_key'] : ''
		);
	}
} 

new MyDataSettings();

 ?>