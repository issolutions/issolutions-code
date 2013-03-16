<?php
/**
 * WallPress Theme Customizer & Apply Settings
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */

/*-----------------------------------------------------------------------------------*/
/*	Get customize value
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'wallpress_get_customize' ) ) :
function wallpress_get_customize( $value, $default = false ) {
	if ( !$value ) return false;
	$options = get_option( 'wallpress_theme_options' );
	if ( $options && isset( $options[$value] ) && $options[$value]!=='' ) {
		return $options[$value];
	}
	return $default;
}
endif;

/*-----------------------------------------------------------------------------------*/
/*	Customize Init
/*-----------------------------------------------------------------------------------*/
add_action( 'after_setup_theme', 'wallpress_init_customize' );
if ( ! function_exists( 'wallpress_init_customize' ) ) :
	function wallpress_init_customize() {
		$the_theme_status = get_option( 'wallpress_theme_options' );
		if ( !$the_theme_status || isset( $_GET['reload'] ) ) {
			update_option( 'wallpress_theme_options', wallpress_default_customize() );
		}
	}
endif;

/*-----------------------------------------------------------------------------------*/
/*	Customize Preview
/*-----------------------------------------------------------------------------------*/
function wallpress_customize_preview_js() {
	wp_enqueue_script( 'wallpress-customizer', get_template_directory_uri() . '/inc/customize/customize.js', array( 'customize-preview' ), '20120523', true );
}

/*-----------------------------------------------------------------------------------*/
/*	Customize Control
/*-----------------------------------------------------------------------------------*/
if ( class_exists( 'WP_Customize_Control' ) ) {
	class WP_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';

		public function __construct( $manager, $id, $args = array() ) {
			$this->statuses = array( '' => __( 'Default', 'wallpress' ) );
			parent::__construct( $manager, $id, $args );
		}

		public function render_content() {
			$id    = 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) );
			$class = 'customize-control customize-control-' . $this->type;

?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea id="<?php echo esc_attr( $id ); ?>" <?php $this->link() ?> class="<?php echo esc_attr( $class ); ?>">
					<?php echo esc_attr( $this->value() ); ?>
				</textarea>
			</label>
			<?php
		}
	}

	//Dropdown list google fonts
	class WP_Gfonts_Control extends WP_Customize_Control {
		public $type = 'gfonts';

		public function __construct( $manager, $id, $args = array() ) {
			$this->statuses = array( '' => __( 'Default','wallpress' ) );
			parent::__construct( $manager, $id, $args );
		}

		public function render_content() {

			// ** Uncomment if you want to update Gfonts list ***
			//copy('http://phat-reaction.com/googlefonts.php?format=php',get_stylesheet_directory().'/inc/gfonts.txt');

			$fontsSeraliazed = wp_remote_fopen( get_template_directory_uri().'/inc/gfonts.txt' );
			$fontArray = unserialize( $fontsSeraliazed );

			$id    = 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) );
			$class = 'customize-control customize-control-' . $this->type;
?>
			<label>
					<span class="customize-control-title"><?php echo  $this->label ; ?></span>
					<select <?php $this->link(); ?>>
					  <option value="">Select font family</option>
						<?php
			foreach ( $fontArray as $value )
				echo '<option value="' . esc_attr( $value['font-family'] )."|".esc_attr( $value['css-name'] ). '"' . selected( $this->value(), esc_attr( $value['font-name'] )."|".esc_attr( $value['css-name'] ), false ) . '>' . $value['font-name'] . '</option>';
?>
					</select>
				</label>
			<?php

		}
	}

	//Category Select
	class WP_Cats_Select_Control extends WP_Customize_Control {
		public $type = 'gfonts';

		public function __construct( $manager, $id, $args = array() ) {
			$this->statuses = array( '' => __( 'Default' ,'wallpress') );
			parent::__construct( $manager, $id, $args );
		}

		public function render_content() {

			$dropdown = wp_dropdown_categories(
				array(
					'name'              => '_customize-dropdown-categories-' . $this->id,
					'echo'              => 0,
					'show_option_none'  => __( '&mdash; All categories &mdash;', 'wallpress' ),
					'option_none_value' => '0'
				)
			);

			// Hackily add in the data link parameter.
			$dropdown = str_replace( '<select', '<select style="height:180px" multiple="multiple" ' . $this->get_link(), $dropdown );

			foreach ( $this->value() as  $value ) {

				$dropdown = str_replace( 'value="'.$value, 'selected="selected" value="'.$value, $dropdown );
			}

			printf(
				'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
				$this->label,
				$dropdown
			);

		}
	}
}

/*-----------------------------------------------------------------------------------*/
/*	Customize Register
/*-----------------------------------------------------------------------------------*/
add_action( 'customize_register', 'wallpress_customize_register' );
if ( ! function_exists( 'wallpress_customize_register' ) ) :
function wallpress_customize_register( $wp_customize ) {
	$options = get_option( 'wallpress_theme_options' );
	$prior = 1;

	foreach ( $options['sections'] as $key => $value ) {
		// Heading for Navigation
		$wp_customize->add_section( 'wallpress_'.$key, array(
				'title' => $value,
				'priority'=>$prior
			) );
		$prior++;
	}
	$sec = $wp_customize->get_section( 'title_tagline' );

	foreach ( $options['settings'] as $value ) {

		$wp_customize->add_setting( "wallpress_theme_options[".$value['key']."]", array(
				'default'        => $value['default'],
				'type'           => 'option',
				'capability'     => 'edit_theme_options',
				'transport'         => $value['transport']
			) );

		switch ( $value['type'] ) {
		case 'image':
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					$value['key'], array(
						'label'      => $value['label'],
						'section'    => 'wallpress_'.$value['section'],
						'priority' => $prior,
						'settings'   => 'wallpress_theme_options['.$value['key'].']'
					)

				)
			);
			break;
		case 'textarea':
			$wp_customize->add_control(
				new WP_Textarea_Control(
					$wp_customize,
					$value['key'], array(
						'label'  => $value['label'],
						'section' => 'wallpress_'.$value['section'],
						'priority' => $prior,
						'settings' => 'wallpress_theme_options['.$value['key'].']'
					)
				)
			);
			break;
		case 'gfont':
			$wp_customize->add_control(
				new WP_Gfonts_Control(
					$wp_customize,
					$value['key'], array(
						'label'  => $value['label'],
						'section' => 'wallpress_'.$value['section'],
						'priority' => $prior,
						'settings' => 'wallpress_theme_options['.$value['key'].']'

					)
				)
			);
			break;
		case 'cats_select':
			$wp_customize->add_control(
				new WP_Cats_Select_Control(
					$wp_customize,
					$value['key'], array(
						'label'  => $value['label'],
						'section' => $value['section'],
						'priority' => 99,
						'settings' => 'wallpress_theme_options['.$value['key'].']'

					)
				)
			);
			break;
		case 'select':
			$wp_customize->add_control( 'wallpress_theme_options['.$value['key'].']', array(
					'section'    => 'wallpress_'.$value['section'],
					'type'       => 'select',
					'label'   => $value['label'],
					'priority' => $prior,
					'choices'    => $value['choices'],
				) );
			break;

		case 'text':
			$wp_customize->add_control( 'wallpress_theme_options['.$value['key'].']', array(
					'section'    => 'wallpress_'.$value['section'],
					'type'       => 'text',
					'priority' => $prior,
					'label'   => $value['label']
				) );
			break;


		}
		$prior++;

	}

	if ( $wp_customize->is_preview() && ! is_admin() )
		add_action( 'customize_preview_init', 'wallpress_customize_preview_js' );
	return $wp_customize;
}
endif;

/*-----------------------------------------------------------------------------------*/
/*	Add default options
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'wallpress_default_customize' ) ) :
function wallpress_default_customize() {
	$options = array();

	//Options Sections
	$options['sections'] = array(
		'wsetup' => __( 'Website Setup', 'wallpress' ),
		'layout' => __( 'Layout', 'wallpress' ),
		'typo' => __( 'Typography', 'wallpress' ),
		'seo'=> __( 'SEO - Search Engine Optimization', 'wallpress' ),
	);

	$options['settings'] = array();

	//Section Website setup
	$options['settings'][]= array(
		'key'  => 'logo_custom_type',
		'section' => 'wsetup',
		'default' => 'logo_title',
		'label' => __( 'Select logo type', 'wallpress' ),
		'type' => 'select',
		'transport' => 'refresh',
		'choices' => array (
			'logo_title' => __( 'Site title', 'wallpress' ),
			'logo_image' => __( 'Custom image below', 'wallpress' )
		),
	);
	$options['settings'][]= array(
		'key' => 'logo_custom_image',
		'section' => 'wsetup',
		'default' => get_template_directory_uri().'/assets/images/logo.png',
		'label'  => __( 'Custom site logo', 'wallpress' ),
		'type' =>  'image',
		'transport' => 'refresh'
	);
	
	$options['settings'][]= array(
		'key' => 'logo_admin_image',
		'section' => 'wsetup',
		'default' => get_template_directory_uri().'/assets/images/logo-large.png',
		'label'  => __( 'Custom login logo', 'wallpress' ),
		'type' =>  'image',
		'transport' => 'refresh'
	);
	$options['settings'][]= array(
		'key' => 'favicon',
		'section' => 'wsetup',
		'default' => get_template_directory_uri().'/assets/images/favicon.ico',
		'label'  => __( 'Favicon', 'wallpress' ),
		'type' =>  'image',
		'transport' => 'refresh'
	);
	$options['settings'][]= array(
		'key' => 'custom_code',
		'section' => 'wsetup',
		'default' => '',
		'label'  => __( 'Custom CSS', 'wallpress' ),
		'type' =>  'textarea',
		'transport' => 'postMessage'
	);
	$options['settings'][]= array(
		'key' => 'custom_js',
		'section' => 'wsetup',
		'default' => '',
		'label'  => __( 'Custom Javascript', 'wallpress' ),
		'type' =>  'textarea',
		'transport' => 'postMessage'
	);
	//Section Typography
	$options['settings'][]= array(
		'key' => 'heading_font',
		'section' => 'typo',
		'default' => '',
		'label'  => __( 'Heading font', 'wallpress' ),
		'type' =>  'gfont',
		'transport' => 'postMessage'
	);
	$options['settings'][]= array(
		'key' => 'primary_font',
		'section' => 'typo',
		'default' => '',
		'label'  => __( 'Body ', 'wallpress' ),
		'type' =>  'gfont',
		'transport' => 'postMessage'
	);
	//Section SEO
	$options['settings'][]= array(
		'key' => 'seo_desc',
		'section' => 'seo',
		'default' => '',
		'label'  => __( 'Global meta description', 'wallpress' ),
		'type' =>  'textarea',
		'transport' => 'refresh'
	);
	$options['settings'][]= array(
		'key' => 'seo_key',
		'section' => 'seo',
		'default' => '',
		'label'  => __( 'Global meta keywords', 'wallpress' ),
		'type' =>  'textarea',
		'transport' => 'refresh'
	);
	$options['settings'][]= array(
		'key' => 'static_cat',
		'section' => 'static_front_page',
		'default' =>  array(),
		'label'  => __('Static categories','wallpress'),
		'type' =>  'cats_select',
		'transport' => 'refresh'
	);
	return $options;
}
endif;

/*-----------------------------------------------------------------------------------*/
/*	Apply Website Setup
/*-----------------------------------------------------------------------------------*/
//Apply logo style
add_action( 'wp_head', 'logo_image_style' );
function logo_image_style() {
$logo_type = wallpress_get_customize( 'logo_custom_type', '' );
if ( $logo_type == 'logo_image' ) :	
	$logo_custom_image = wallpress_get_customize('logo_custom_image','');
 ?>
<style type="text/css">
	#branding a {
		display: block;
		background: url(<?php echo $logo_custom_image; ?>) no-repeat center;
		text-indent: -9999px;
	}
</style>

<?php 
endif;
}

//Apply favicon
add_action( 'wp_head', 'wallpress_favicon' );
function wallpress_favicon() {
	echo '<link type="image/x-icon" href="'.wallpress_get_customize('favicon','').'" rel="shortcut icon">';
}

//Apply Custom CSS, JS & GoogleFonts 
add_action( 'wp_enqueue_scripts', 'wallpress_customize_code' );
function wallpress_customize_code() {

	$cstyle = wallpress_get_customize( 'custom_code', '' );
	$cjavascript = wallpress_get_customize( 'custom_js', '' );

	if ( wallpress_get_customize( 'heading_font' ) || wallpress_get_customize( 'primary_font' ) ) {
		$headingfamily = ( wallpress_get_customize( 'heading_font' ) )?explode( '|', wallpress_get_customize( 'heading_font' ) ):array();
		$prifamily = ( wallpress_get_customize( 'primary_font' ) )?explode( '|', wallpress_get_customize( 'primary_font' ) ):array();
	?>
	<!-- Google Fonts -->
	<?php if ( count( $headingfamily )>0 ):
			$cstyle .="h1,h2,h3,h4,h5 { $headingfamily[0]; } \n ";
	?>
		<link href='http://fonts.googleapis.com/css?family=<?php echo $headingfamily[1]; ?>' rel='stylesheet' type='text/css' />
	<?php endif; ?>
	<?php if ( count( $prifamily )>0 ):
			$cstyle .= "body { $prifamily[0]; } \n";
	?>
		<link href='http://fonts.googleapis.com/css?family=<?php echo $prifamily[1]; ?>' rel='stylesheet' type='text/css' />
	<?php endif; ?>
	<?php
	}
	if ( $cstyle !=='' ):
	?>
		<style type="text/css" media="screen">
				<?php echo $cstyle; ?>
		</style>

	<?php
endif;
echo $cjavascript;
}

// Apply Keywords & Description
add_action( 'wp_head', 'wallpress_seo_meta', 1 );
function wallpress_seo_meta() {
	if ( wallpress_get_customize( 'seo_desc' ) ): ?>
	<meta name="description" content="<?php echo wallpress_get_customize( 'seo_desc' ); ?>" />
	<?php endif; ?>
	<?php if ( wallpress_get_customize( 'seo_key' ) ):?>
	<meta name="keywords" content="<?php echo wallpress_get_customize( 'seo_key' ); ?>" />
	<?php endif;
}

?>
