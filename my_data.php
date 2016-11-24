<?php 
/*
Plugin Name: Meus Dados
Plugin URI: 
Description: Permite gerenciar as informações, contatos, redes sociais e localização no Google Maps de sua empresa.
Version: 1.0
Author: Elder Carvalho
Author URI: https://www.facebook.com/elder.carvalho.5
Text Domain: 
Domain Path: /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class MyData{
	private $options;

	public function __construct(){
		add_action('admin_menu', array($this,'md_create_menu'));
		add_action('admin_enqueue_scripts',array($this,'md_enqueue_scripts'));
		add_action('admin_init', array($this,'md_init'));
		add_action('admin_notices', array($this,'admin_notices'));
		$this->load_depencencies();
		$this->options = get_option('md_options');
	}

	public function load_depencencies(){
		include 'includes/settings.php';
	}

	public function md_create_menu(){
		add_menu_page( 'Meus Dados', 'Meus Dados', 'manage_options', 'my_data_page', array($this,'md_options'),'dashicons-portfolio',absint(79));
	}

	public function md_options(){
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>

		<div class="wrap">
			<h1>Meus Dados</h1>

			<form method="post" action="options.php"> 
				<?php
	                settings_fields( 'md_options_group');
	                do_settings_sections( 'my_data_page' );
	                submit_button();
	            ?>
			</form>

		</div>

		<?php
	}

	public function md_init(){
		register_setting('md_options_group', 'md_options');

		add_settings_section( 'general_info_section', 'Informações Gerais', array($this,'general_info_section_callback'), 'my_data_page' );
		add_settings_field( 'md_name', 'Nome', array($this,'md_name_callback'), 'my_data_page', 'general_info_section');
		add_settings_field( 'md_address', 'Endereço', array($this,'md_address_callback'), 'my_data_page', 'general_info_section');
		add_settings_field( 'md_city', 'Cidade', array($this,'md_city_callback'), 'my_data_page', 'general_info_section');
		add_settings_field( 'md_state', 'Estado', array($this,'md_state_callback'), 'my_data_page', 'general_info_section');
		add_settings_field( 'md_cep', 'CEP', array($this,'md_cep_callback'), 'my_data_page', 'general_info_section');
		add_settings_field( 'md_phones', 'Telefone(s)', array($this,'md_phones_callback'), 'my_data_page', 'general_info_section');
		add_settings_field( 'md_whatsapp', 'WhatsApp', array($this,'md_whatsapp_callback'), 'my_data_page', 'general_info_section');
		add_settings_field( 'md_email', 'Email', array($this,'md_email_callback'), 'my_data_page', 'general_info_section');

		add_settings_section( 'schedule_section', 'Horários de Atendimento', '', 'my_data_page' );
		add_settings_field( 'md_schedule_business', 'Normal', array($this,'md_schedule_business_callback'), 'my_data_page', 'schedule_section');
		add_settings_field( 'md_schedule_weekend', 'Fim de Semana', array($this,'md_schedule_weekend_callback'), 'my_data_page', 'schedule_section');
		add_settings_field( 'md_schedule_other', 'Outro', array($this,'md_schedule_other_callback'), 'my_data_page', 'schedule_section');

		add_settings_section( 'social_info_section', 'Redes Sociais', '', 'my_data_page' );
		add_settings_field( 'md_facebook', 'Facebook', array($this,'md_facebook_callback'), 'my_data_page', 'social_info_section');
		add_settings_field( 'md_instagram', 'Instagram', array($this,'md_instagram_callback'), 'my_data_page', 'social_info_section');
		add_settings_field( 'md_youtube', 'YouTube', array($this,'md_youtube_callback'), 'my_data_page', 'social_info_section');
		add_settings_field( 'md_twitter', 'Twitter', array($this,'md_twitter_callback'), 'my_data_page', 'social_info_section');
		add_settings_field( 'md_google_plus', 'Google Plus', array($this,'md_google_plus_callback'), 'my_data_page', 'social_info_section');
		add_settings_field( 'md_pinterest', 'Pinterest', array($this,'md_pinterest_callback'), 'my_data_page', 'social_info_section');
		
		add_settings_section( 'logos_section', 'Logos', array($this,'general_info_section_callback'), 'my_data_page' );
		add_settings_field( 'md_header_logo', 'Logo Cabeçalho', array($this,'md_header_logo_callback'), 'my_data_page', 'logos_section');
		add_settings_field( 'md_logo_footer', 'Logo Rodapé', array($this,'md_footer_logo_callback'), 'my_data_page', 'logos_section');
		
		add_settings_section( 'google_maps_section', 'Google Maps', array($this,'google_maps_section_callback'), 'my_data_page' );
		add_settings_field( 'md_google_maps', 'Localização', array($this,'md_google_maps_callback'), 'my_data_page', 'google_maps_section');
	}

	//General Informations Fields

	public function general_info_section_callback(){
		
	}

	public function md_name_callback(){
		printf(
			'<input type="text" id="md_name" class="regular-text" name="md_options[md_name]" value="%s">',
			isset($this->options['md_name']) ? $this->options['md_name'] : ''
		);
	}

	public function md_address_callback(){
		printf(
			'<input type="text" id="md_address" class="regular-text" name="md_options[md_address]" value="%s">',
			isset($this->options['md_address']) ? $this->options['md_address'] : ''
		);
	}

	public function md_city_callback(){
		printf(
			'<input type="text" id="md_city" class="regular-text" name="md_options[md_city]" value="%s">',
			isset($this->options['md_city']) ? $this->options['md_city'] : ''
		);
	}

	public function md_state_callback(){
		printf(
			'<input type="text" id="md_state" class="regular-text" name="md_options[md_state]" value="%s">',
			isset($this->options['md_state']) ? $this->options['md_state'] : ''
		);
	}

	public function md_cep_callback(){
		printf(
			'<input type="text" id="md_cep" class="regular-text" name="md_options[md_cep]" value="%s">',
			isset($this->options['md_cep']) ? $this->options['md_cep'] : ''
		);
	}

	public function md_phones_callback(){
		printf(
			'<input type="text" id="md_phones" class="regular-text" name="md_options[md_phones]" value="%s" placeholder="">',
			isset($this->options['md_phones']) ? $this->options['md_phones'] : ''
		);
	}

	public function md_whatsapp_callback(){
		printf(
			'<input type="text" id="md_whatsapp" class="regular-text" name="md_options[md_whatsapp]" value="%s">',
			isset($this->options['md_whatsapp']) ? $this->options['md_whatsapp'] : ''
		);
	}

	public function md_email_callback(){
		printf(
			'<input type="text" id="md_email" class="regular-text" name="md_options[md_email]" value="%s">',
			isset($this->options['md_email']) ? $this->options['md_email'] : ''
		);
	}

	//Schedule Fields

	public function md_schedule_business_callback(){
		printf(
			'<input type="text" id="md_schedule_business" class="regular-text" name="md_options[md_schedule_business]" value="%s">',
			isset($this->options['md_schedule_business']) ? $this->options['md_schedule_business'] : ''
		);
	}

	public function md_schedule_weekend_callback(){
		printf(
			'<input type="text" id="md_schedule_weekend" class="regular-text" name="md_options[md_schedule_weekend]" value="%s">',
			isset($this->options['md_schedule_weekend']) ? $this->options['md_schedule_weekend'] : ''
		);
	}

	public function md_schedule_other_callback(){
		printf(
			'<input type="text" id="md_schedule_other" class="regular-text" name="md_options[md_schedule_other]" value="%s">',
			isset($this->options['md_schedule_other']) ? $this->options['md_schedule_other'] : ''
		);
	}

	//	Social Networks Fields
	
	public function md_facebook_callback(){
		printf(
			'<input type="text" id="md_facebook" class="regular-text" name="md_options[md_facebook]" value="%s">',
			isset($this->options['md_facebook']) ? $this->options['md_facebook'] : ''
		);
	}

	public function md_instagram_callback(){
		printf(
			'<input type="text" id="md_instagram" class="regular-text" name="md_options[md_instagram]" value="%s">',
			isset($this->options['md_instagram']) ? $this->options['md_instagram'] : ''
		);
	}

	public function md_youtube_callback(){
		printf(
			'<input type="text" id="md_youtube" class="regular-text" name="md_options[md_youtube]" value="%s">',
			isset($this->options['md_youtube']) ? $this->options['md_youtube'] : ''
		);
	}

	public function md_twitter_callback(){
		printf(
			'<input type="text" id="md_twitter" class="regular-text" name="md_options[md_twitter]" value="%s">',
			isset($this->options['md_twitter']) ? $this->options['md_twitter'] : ''
		);
	}

	public function md_google_plus_callback(){
		printf(
			'<input type="text" id="md_google_plus" class="regular-text" name="md_options[md_google_plus]" value="%s">',
			isset($this->options['md_google_plus']) ? $this->options['md_google_plus'] : ''
		);
	}

	public function md_pinterest_callback(){
		printf(
			'<input type="text" id="md_pinterest" class="regular-text" name="md_options[md_pinterest]" value="%s">',
			isset($this->options['md_pinterest']) ? $this->options['md_pinterest'] : ''
		);
	}

	//Logos Fields

	public function md_header_logo_callback(){

		$logoUrl = isset($this->options['md_header_logo']) ? $this->options['md_header_logo'] : '';

		$header_logo = $this->checkHost($logoUrl,'header');

		?>
			<input type="text" id="md_header_logo" class="regular-text" name="md_options[md_header_logo]" value="<?php echo esc_url($header_logo); ?>">
			<input type="button" id="set-md_header_logo" class="set-logo button media-button  select-mode-toggle-button <?php if($header_logo != ''){ echo 'hidden'; } ?>" value="Upload Logo" data-logo="md_header_logo">
			<input type="button" id="remove-md_header_logo" class="remove-logo button media-button  select-mode-toggle-button <?php if($header_logo == ''){ echo 'hidden'; } ?>" value="Remover Logo" data-logo="md_header_logo">
			<div class="md_logo_img md_header_logo_img <?php if($header_logo == ''){ echo 'hidden'; } ?>">
				<img src="<?php echo $header_logo ?>">
			</div>
		<?php

	}

	public function md_footer_logo_callback(){

		$logoUrl = isset($this->options['md_footer_logo']) ? $this->options['md_footer_logo'] : '';

		$footer_logo = $this->checkHost($logoUrl,'footer');

		?>
			<input type="text" id="md_footer_logo" class="regular-text" name="md_options[md_footer_logo]" value="<?php echo esc_url($footer_logo); ?>"> 
			<input type="button" id="set-md_footer_logo" class="set-logo button media-button  select-mode-toggle-button <?php if($footer_logo != ''){ echo 'hidden'; } ?>" value="Upload Logo" data-logo="md_footer_logo">
			<input type="button" id="remove-md_footer_logo" class="remove-logo button media-button  select-mode-toggle-button <?php if($footer_logo == ''){ echo 'hidden'; } ?>" value="Remover Logo" data-logo="md_footer_logo">
			<div class="md_logo_img md_footer_logo_img <?php if($footer_logo == ''){ echo 'hidden'; } ?>">
				<img src="<?php echo $footer_logo ?>">
			</div>

	<?php

	}

	//Google Maps Localization Fields

	public function google_maps_section_callback(){
	}

	public function md_google_maps_callback(){

		// $ip = $_SERVER['REMOTE_ADDR'];
		// $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

		$settings = get_option('md_settings');
		if($this->options['md_google_maps']){ $localization = $this->options['md_google_maps']; }else{ $localization = 'Manaus';}
		?>
			<input type="text" id="md_google_maps" class="large-text" name="md_options[md_google_maps]" value="<?php echo $localization ?>">
			<?php if($settings['md_google_api_key']): ?>
			<div class="localization">
				<iframe frameborder="0" 
				style="border:0" 
				src="https://www.google.com/maps/embed/v1/place?key=<?php echo $settings['md_google_api_key'] ?> &#10;&amp;q=<?php echo $localization ?>" 
				allowfullscreen=""></iframe>
			</div>
		<?php
			else:
				echo '<p>É necessário inserir uma chave de api para visualizar a localização no mapa. Ir para <a href="' . admin_url('admin.php?page=md_settings_page') . '" >Opções</a> </p>';
			endif;

	}

	//Load Scripts

	public function md_enqueue_scripts(){
  	  	wp_enqueue_media();
        wp_enqueue_script('md-upload',plugins_url('js/md_upload.js',__FILE__), array('jquery'),'1.0', FALSE);
        wp_enqueue_style('md-style',plugins_url('css/md_style.css',__FILE__));	
	}

	//This methods are useful when you move the site to another server
	private function checkHost($logoUrl,$local){
		$host = get_option('home');

		$hasHost = strpos($logoUrl, $host);

		if($hasHost === FALSE and $logoUrl != ''){
			$pos = strpos($logoUrl, '/wp-content');
			$cutUrl = substr($logoUrl, $pos);
			$realUrl = $host . $cutUrl;
			$this->md_options['md_'.$local.'_logo'] = $realUrl;
			update_option( 'md_options', $this->md_options );

			return $realUrl;
		}

		return $logoUrl;
	}

	private function loadLogos(){
		if(!is_admin()){
			$this->options = get_option('md_options');
			$this->options['md_header_logo'] = $this->checkHost($this->options['md_header_logo'],'header');
			$this->options['md_footer_logo'] = $this->checkHost($this->options['md_footer_logo'],'footer');
			update_option( 'md_options', $this->md_options );
		}
	}

	public function admin_notices() {
	   if ( ! isset( $_POST['md_options'] ) ) {
	     return;
	   }
	   ?>
	   <div class="updated">
	      <p>Dados Salvos.</p>
	   </div>
	   <?php
	 }

	 //GETTERS

	 public function __get($prop){
	 	if(!empty($this->options['md_'.$prop]) and  $this->options['md_'.$prop] != ''){
	 		return $this->options['md_'.$prop];
	 	}
	 }

	public function get_social($html = FALSE, $class = ''){
		$info = $this->options;
        $snw = array();
        $snw['facebook'] = isset($info['md_facebook']) ? $info['md_facebook'] : '';
        $snw['instagram'] = isset($info['md_instagram']) ? $info['md_instagram'] : '';
        $snw['youtube'] = isset($info['md_youtube']) ? $info['md_youtube'] : '';
        $snw['twitter'] = isset($info['md_twitter']) ? $info['md_twitter'] : '';
        $snw['google-plus'] = isset($info['md_google_plus']) ? $info['md_google_plus'] : '';
        $snw['pinterest'] = isset($info['md_pinterest']) ? $info['md_pinterest'] : '';

        if($html){
        	$social_list = '<ul class="social-list '.$class.'">';
        	foreach($snw as $name => $url){
        		if($url != ''){
        			$social_list .= '<li>
        								<a href="' . $url . '">
        									<i class="fa fa-' . $name . '" aria-hidden="true"></i>
        								</a>
        							</li>'; 	
        		}
        	}
        	$social_list .= '</ul>';
        	echo $social_list;
        	return;
        }
        return $snw;
	}

	public function get_phones($delimiter){
		return $phones = explode($delimiter, $this->options['md_phones']);
	}
}

global $mydata;
$mydata = new MyData();


 ?>