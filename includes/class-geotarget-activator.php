<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		if( version_compare(PHP_VERSION, '5.6' ) === -1 )
			die(
				'<p>'. __('Hey, we\'ve noticed that you\'re running an outdated version of PHP. PHP is the programming language that WordPress and this plugin are built on. The version that is currently used for your site is no longer supported. Newer versions of PHP are both faster and more secure. In fact, your version of PHP no longer receives security updates.') .'</p>'.
			    '<p>'. __('Geotargeting PRO requires at least PHP 5.6.').'</p>'
			);

		$current_version = get_option( 'geot_version' );
		$db_version 	 = get_option( 'geot_db_version' );

		$country_table = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}geot_countries` (
		`id`	 		INT(1) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, -- the id just for numeric
		`iso_code` 		VARCHAR(2) COLLATE UTF8_GENERAL_CI NOT NULL, -- the ip start from maxmind data
		`country` 		VARCHAR(150) COLLATE UTF8_GENERAL_CI NOT NULL, -- the ip end of maxmind data
		PRIMARY KEY( `id`),
        INDEX (iso_code, country)
		) DEFAULT CHARSET=UTF8 COLLATE=UTF8_GENERAL_CI AUTO_INCREMENT=1 ;";


		$table_name = "{$wpdb->base_prefix}geot_countries";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name || version_compare( '1.8', $current_version ) == 1 ) {

			if( version_compare( '1.8', $current_version ) == 1 )
				$wpdb->query( "DROP TABLE $table_name");
			dbDelta( $country_table );
			self::add_countries_to_db();
		}

		// Upgrade post database
		if( $current_version && ! get_option( 'geot_posts_upgrade' ) ){
			self::posts_upgrade();
		}

		// update version number to current one
		update_option( 'geot_version', GEOT_VERSION);
	}

	protected static function add_countries_to_db() {
		global $wpdb;

		$query = " INSERT INTO `{$wpdb->base_prefix}geot_countries` ( `iso_code`, `country`)
VALUES
	('AD', 'Andorra' ),
('AF', 'Afghanistan' ),
('AX', 'Åland Islands' ),
('AL', 'Albania' ),
('DZ', 'Algeria' ),
('AS', 'American Samoa' ),
('AO', 'Angola' ),
('AI', 'Anguilla' ),
('AQ', 'Antarctica' ),
('AG', 'Antigua and Barbuda' ),
('AR', 'Argentina' ),
('AM', 'Armenia' ),
('AW', 'Aruba' ),
('AU', 'Australia' ),
('AT', 'Austria' ),
('AZ', 'Azerbaijan' ),
('BS', 'Bahamas' ),
('BH', 'Bahrain' ),
('BD', 'Bangladesh' ),
('BB', 'Barbados' ),
('BY', 'Belarus' ),
('BE', 'Belgium' ),
('BZ', 'Belize' ),
('BJ', 'Benin' ),
('BM', 'Bermuda' ),
('BT', 'Bhutan' ),
('BO', 'Bolivia (Plurinational State of)' ),
('BQ', 'Bonaire, Sint Eustatius and Saba' ),
('BA', 'Bosnia and Herzegovina' ),
('BW', 'Botswana' ),
('BV', 'Bouvet Island' ),
('BR', 'Brazil' ),
('IO', 'British Indian Ocean Territory' ),
('BN', 'Brunei Darussalam' ),
('BG', 'Bulgaria' ),
('BF', 'Burkina Faso' ),
('BI', 'Burundi' ),
('CV', 'Cabo Verde' ),
('KH', 'Cambodia' ),
('CM', 'Cameroon' ),
('CA', 'Canada' ),
('KY', 'Cayman Islands' ),
('CF', 'Central African Republic' ),
('TD', 'Chad' ),
('CL', 'Chile' ),
('CN', 'China' ),
('CX', 'Christmas Island' ),
('CC', 'Cocos (Keeling) Islands' ),
('CO', 'Colombia' ),
('KM', 'Comoros' ),
('CG', 'Congo' ),
('CD', 'Congo (Democratic Republic of the)' ),
('CK', 'Cook Islands' ),
('CR', 'Costa Rica' ),
('CI', 'Côte d''Ivoire' ),
('HR', 'Croatia' ),
('CU', 'Cuba' ),
('CW', 'Curaçao' ),
('CY', 'Cyprus' ),
('CZ', 'Czech Republic' ),
('DK', 'Denmark' ),
('DJ', 'Djibouti' ),
('DM', 'Dominica' ),
('DO', 'Dominican Republic' ),
('EC', 'Ecuador' ),
('EG', 'Egypt' ),
('SV', 'El Salvador' ),
('GQ', 'Equatorial Guinea' ),
('ER', 'Eritrea' ),
('EE', 'Estonia' ),
('ET', 'Ethiopia' ),
('FK', 'Falkland Islands (Malvinas)' ),
('FO', 'Faroe Islands' ),
('FJ', 'Fiji' ),
('FI', 'Finland' ),
('FR', 'France' ),
('GF', 'French Guiana' ),
('PF', 'French Polynesia' ),
('TF', 'French Southern Territories' ),
('GA', 'Gabon' ),
('GM', 'Gambia' ),
('GE', 'Georgia' ),
('DE', 'Germany' ),
('GH', 'Ghana' ),
('GI', 'Gibraltar' ),
('GR', 'Greece' ),
('GL', 'Greenland' ),
('GD', 'Grenada' ),
('GP', 'Guadeloupe' ),
('GU', 'Guam' ),
('GT', 'Guatemala' ),
('GG', 'Guernsey' ),
('GN', 'Guinea' ),
('GW', 'Guinea-Bissau' ),
('GY', 'Guyana' ),
('HT', 'Haiti' ),
('HM', 'Heard Island and McDonald Islands' ),
('VA', 'Holy See' ),
('HN', 'Honduras' ),
('HK', 'Hong Kong' ),
('HU', 'Hungary' ),
('IS', 'Iceland' ),
('IN', 'India' ),
('ID', 'Indonesia' ),
('IR', 'Iran (Islamic Republic of)' ),
('IQ', 'Iraq' ),
('IE', 'Ireland' ),
('IM', 'Isle of Man' ),
('IL', 'Israel' ),
('IT', 'Italy' ),
('JM', 'Jamaica' ),
('JP', 'Japan' ),
('JE', 'Jersey' ),
('JO', 'Jordan' ),
('KZ', 'Kazakhstan' ),
('KE', 'Kenya' ),
('KI', 'Kiribati' ),
('KP', 'Korea (Democratic People''s Republic of)' ),
('KR', 'Korea (Republic of)' ),
('KW', 'Kuwait' ),
('KG', 'Kyrgyzstan' ),
('LA', 'Lao People''s Democratic Republic' ),
('LV', 'Latvia' ),
('LB', 'Lebanon' ),
('LS', 'Lesotho' ),
('LR', 'Liberia' ),
('LY', 'Libya' ),
('LI', 'Liechtenstein' ),
('LT', 'Lithuania' ),
('LU', 'Luxembourg' ),
('MO', 'Macao' ),
('MK', 'Macedonia (the former Yugoslav Republic of)' ),
('MG', 'Madagascar' ),
('MW', 'Malawi' ),
('MY', 'Malaysia' ),
('MV', 'Maldives' ),
('ML', 'Mali' ),
('MT', 'Malta' ),
('MH', 'Marshall Islands' ),
('MQ', 'Martinique' ),
('MR', 'Mauritania' ),
('MU', 'Mauritius' ),
('YT', 'Mayotte' ),
('MX', 'Mexico' ),
('FM', 'Micronesia (Federated States of)' ),
('MD', 'Moldova (Republic of)' ),
('MC', 'Monaco' ),
('MN', 'Mongolia' ),
('ME', 'Montenegro' ),
('MS', 'Montserrat' ),
('MA', 'Morocco' ),
('MZ', 'Mozambique' ),
('MM', 'Myanmar' ),
('NA', 'Namibia' ),
('NR', 'Nauru' ),
('NP', 'Nepal' ),
('NL', 'Netherlands' ),
('NC', 'New Caledonia' ),
('NZ', 'New Zealand' ),
('NI', 'Nicaragua' ),
('NE', 'Niger' ),
('NG', 'Nigeria' ),
('NU', 'Niue' ),
('NF', 'Norfolk Island' ),
('MP', 'Northern Mariana Islands' ),
('NO', 'Norway' ),
('OM', 'Oman' ),
('PK', 'Pakistan' ),
('PW', 'Palau' ),
('PS', 'Palestine, State of' ),
('PA', 'Panama' ),
('PG', 'Papua New Guinea' ),
('PY', 'Paraguay' ),
('PE', 'Peru' ),
('PH', 'Philippines' ),
('PN', 'Pitcairn' ),
('PL', 'Poland' ),
('PT', 'Portugal' ),
('PR', 'Puerto Rico' ),
('QA', 'Qatar' ),
('RE', 'Réunion' ),
('RO', 'Romania' ),
('RU', 'Russian Federation' ),
('RW', 'Rwanda' ),
('BL', 'Saint Barthélemy' ),
('SH', 'Saint Helena, Ascension and Tristan da Cunha' ),
('KN', 'Saint Kitts and Nevis' ),
('LC', 'Saint Lucia' ),
('MF', 'Saint Martin (French part)' ),
('PM', 'Saint Pierre and Miquelon' ),
('VC', 'Saint Vincent and the Grenadines' ),
('WS', 'Samoa' ),
('SM', 'San Marino' ),
('ST', 'Sao Tome and Principe' ),
('SA', 'Saudi Arabia' ),
('SN', 'Senegal' ),
('RS', 'Serbia' ),
('SC', 'Seychelles' ),
('SL', 'Sierra Leone' ),
('SG', 'Singapore' ),
('SX', 'Sint Maarten (Dutch part)' ),
('SK', 'Slovakia' ),
('SI', 'Slovenia' ),
('SB', 'Solomon Islands' ),
('SO', 'Somalia' ),
('ZA', 'South Africa' ),
('GS', 'South Georgia and the South Sandwich Islands' ),
('SS', 'South Sudan' ),
('ES', 'Spain' ),
('LK', 'Sri Lanka' ),
('SD', 'Sudan' ),
('SR', 'Suriname' ),
('SJ', 'Svalbard and Jan Mayen' ),
('SZ', 'Swaziland' ),
('SE', 'Sweden' ),
('CH', 'Switzerland' ),
('SY', 'Syrian Arab Republic' ),
('TW', 'Taiwan, Province of China' ),
('TJ', 'Tajikistan' ),
('TZ', 'Tanzania, United Republic of' ),
('TH', 'Thailand' ),
('TL', 'Timor-Leste' ),
('TG', 'Togo' ),
('TK', 'Tokelau' ),
('TO', 'Tonga' ),
('TT', 'Trinidad and Tobago' ),
('TN', 'Tunisia' ),
('TR', 'Turkey' ),
('TM', 'Turkmenistan' ),
('TC', 'Turks and Caicos Islands' ),
('TV', 'Tuvalu' ),
('UG', 'Uganda' ),
('UA', 'Ukraine' ),
('AE', 'United Arab Emirates' ),
('GB', 'United Kingdom of Great Britain and Northern Ireland' ),
('UM', 'United States Minor Outlying Islands' ),
('US', 'United States of America' ),
('UY', 'Uruguay' ),
('UZ', 'Uzbekistan' ),
('VU', 'Vanuatu' ),
('VE', 'Venezuela (Bolivarian Republic of)' ),
('VN', 'Viet Nam' ),
('VG', 'Virgin Islands (British)' ),
('VI', 'Virgin Islands (U.S.)' ),
('WF', 'Wallis and Futuna' ),
('EH', 'Western Sahara' ),
('YE', 'Yemen' ),
('ZM', 'Zambia' ),
('ZW', 'Zimbabwe')";
		$wpdb->query( $query );
	}

	/**
	 * Add mising _geot_post introduced in 1.8 to old posts
	 * @return [type] [description]
	 */
	protected static function posts_upgrade() {
		global $wpdb;

		// grab all publish posts without _geot_post postmeta
		$posts = $wpdb->get_results("SELECT p.ID, pm.meta_value as geot_options FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm ON pm.post_id = p.ID  WHERE p.post_status = 'publish' AND pm.meta_key = 'geot_options'  AND p.ID NOT IN (  SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_geot_post' GROUP BY post_id ) ");
		// Loop all posts and check if( !empty( $opts['country_code'] ) || !empty( $opts['region'] ) || !empty( $opts['cities'] ) || !empty( $opts['state'] ) )
		$to_migrate = array();
		if( $posts ) {
			foreach( $posts as $p ){
				$opts = unserialize( $p->geot_options );
				if( !empty( $opts['country_code'] ) || !empty( $opts['region'] ) || !empty( $opts['cities'] ) || !empty( $opts['state'] ) )
					$to_migrate[] = $p->ID;
			}
		}
		// Save post meta to those posts
		if( !empty( $to_migrate ) ) {
			$sql_string = array();
			foreach ($to_migrate as $id) {
				$sql_string[] = "('$id', '_geot_post', '1' )";
			}
			$sql = "INSERT INTO $wpdb->postmeta (post_id,meta_key,meta_value) VALUES ".implode(',',$sql_string).";";

			$wpdb->query($sql);
		}
		update_option( 'geot_posts_upgrade', 1 );

	}
}
