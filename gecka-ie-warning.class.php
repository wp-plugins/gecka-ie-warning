<?php

/**
 * Main Plugin class
 * @author lox
 *
 */
class Gecka_IeWarning {
	
	const Domain = 'gecka-ie-warning';
	
	private $settings_options;
	
	private $ie_version;
	private $ie_warning_delay;
	
	/**
	 * Constructor
	 */
	public function __construct() {

		load_plugin_textdomain(self::Domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
		
		$this->ie_version = (int)get_option('iew_version') ? (int)get_option('iew_version') : 6;
		$this->ie_warning_delay = get_option('iew_delay');
		
		// add the setting to the media page
		add_action('admin_init', array($this, 'admin_init') );
		
		$this->settings_version = array( '6'=>__('before IE7', self::Domain), '7'=>__('before IE8', self::Domain));
		$this->settings_options = array( '0'=>__('Never', self::Domain), 'no'=>__('Always', self::Domain), ''=>__('Every session', self::Domain), '1'=>__('Once a day', self::Domain), '7'=>__('Every 7 days', self::Domain), '30'=>__('Every 30 days', self::Domain), '365'=>__('Every year', self::Domain) );
		
		if( $this->ie_warning_delay !== '0') {
			wp_enqueue_scripts('jquery');
			add_action('wp_head', array($this, 'ie_warning') );
		}
	}
	
	public function admin_init () {
		
		add_settings_section('ie_warnings', __('IE Version Warnings', self::Domain), array($this, 'section'), 'reading');

		register_setting('reading','iew_version');
		register_setting('reading','iew_delay');
		add_settings_field('iew_delay', __('Warning display fequency', self::Domain), array($this, 'field_iew_delay'), 'reading', 'ie_warnings');
		add_settings_field('iew_version', __('Show the warning for versions', self::Domain), array($this, 'field_iew_version'), 'reading', 'ie_warnings');		
	
	}
	
	public function section () {}
	
	public function field_iew_version () {
		
		$ie7 = esc_attr( $this->ie_version );
		
		?>
		<select name="iew_version">
		<?php
		
		foreach($this->settings_version as $key => $val) {
		    $selected = selected($key, $ie7, false);
		    echo '<option value="'.$key.'"'.$selected.'>'.$val.'</option>';
		}
		
		?>
		</select>
		<?php
		
	}
	
	public function field_iew_delay () {
		$ie6 = esc_attr( $this->ie_warning_delay );
		
		?>
		<select name="iew_delay">
		<?php
		
		foreach($this->settings_options as $key => $val) {
		    $selected = selected($key, $ie6, false);
		    echo '<option value="'.$key.'"'.$selected.'>'.$val.'</option>';
		}
		
		?>
		</select>
		<?php
	
	}
	
	function ie_warning ($options) {
	
		echo "\n<!--[if lte IE ".$this->ie_version."]>";
		
		$iev = $this->ie_version + 1;
	    $options = array(   'msg1' => __( 'Did you know that your Internet Explorer is out of date?', self::Domain ),
		                    'msg2' => __( 'To get the best possible experience using our website we recommend that you upgrade to a newer version or other web browser. A list of the most popular web browsers can be found below.', self::Domain ),
		                    'msg3' => __( 'Just click on the icons to get to the download page', self::Domain ),
		                    'br1' => __( 'Internet Explorer ' . $iev . '+', self::Domain ),
		                    'br2' => __( 'Firefox 3+', self::Domain ),
		                    'br3' => __( 'Safari 3+', self::Domain ),
		                    'br4' => __( 'Opera 9.5+', self::Domain ),
		                    'br5' => __( 'Chrome 2.0+', self::Domain ),
		    				'msg4' => __( 'Close', self::Domain ),
	    					'cookie' => esc_js( $this->ie_warning_delay ) );
		    
		$data = "var iews = { ";
		$eol = '';
		foreach ( $options as $var => $val ) {
			$data .= "$eol $var: \"" . esc_js( $val ) . '"';
			$eol = ",";
		}
		$data .= " }; ";
		
		echo "\n<script type='text/javascript'>";
		echo "/* <![CDATA[ */";
		echo $data;
		echo "/* ]]> */";
		echo "</script>";
			
?>
<script src="<?php echo GKIEW_URL ?>/ie/warning.js"></script><script>jQuery(document).ready(function($){ iew("<?php echo GKIEW_URL ?>/ie/"); });</script>		
<?php
	    echo "<![endif]-->";
	}
}
