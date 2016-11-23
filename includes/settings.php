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
			<h2>Ajuda</h2>
			<h4>Variáveis</h4>
			<p>
				<code>global mydata;</code><br>
				<code>echo $mydata->name</code><br>
				<code>echo $mydata->address</code><br>
				<code>echo $mydata->city</code><br>
				<code>echo $mydata->state</code><br>
				<code>echo $mydata->cep</code><br>
				<code>echo $mydata->phones</code><br>
				<code>echo $mydata->whatsapp</code><br>
				<code>echo $mydata->email</code><br>
				<code>echo $mydata->schedule_business</code><br>
				<code>echo $mydata->schedule_weekend</code><br>
				<code>echo $mydata->schedule_other</code><br>
				<code>echo $mydata->facebook</code><br>
				<code>echo $mydata->instagram</code><br>
				<code>echo $mydata->youtube</code><br>
				<code>echo $mydata->twitter</code><br>
				<code>echo $mydata->google_plus</code><br>
				<code>echo $mydata->pinterest</code><br>
				<code>echo $mydata->header_logo</code><br>
				<code>echo $mydata->google_maps</code>
			</p>
			<h4>Funções</h4>
			<p>
				<code>mydata->get_social($html,$class);</code><br>
				Retorna um array de url's indexados pelo nome da rede social.
			</p>
			<p><strong>$html</strong> - Padrão: FALSE<br>
			Imprime uma lista de redes sociais com ícones Font Awesome.</p>
			<p><strong>$class</strong> - Padrão: ''<br>
			Classe css para a lista.</p>
			<p>
				<code>mydata->get_phones($html,$class);</code><br>
				Retorna um array de telefones.
			</p>
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