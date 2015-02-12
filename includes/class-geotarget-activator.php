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

		$current_version = get_option( 'geot_version' );

		// Only adds table if version 1.1 is being installed
		if( empty($current_version) || version_compare( '1.1', $current_version ) > 0 ) {

			$country_table = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}geot_countries` (
			`id`	 		INT(1) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, -- the id just for numeric
			`iso_code` 		VARCHAR(2) COLLATE UTF8_GENERAL_CI NOT NULL, -- the ip start from maxmind data
			`country` 		VARCHAR(150) COLLATE UTF8_GENERAL_CI NOT NULL, -- the ip end of maxmind data	
			PRIMARY KEY( `id`),
 			INDEX (iso_code, country)
			) DEFAULT CHARSET=UTF8 COLLATE=UTF8_GENERAL_CI AUTO_INCREMENT=1 ;";

			$city_table = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}geot_cities` (
			`id`	 		INT(1) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, -- the id just for numeric
			`country_code` 		VARCHAR(2) COLLATE UTF8_GENERAL_CI NOT NULL, -- the ip start from maxmind data
			`city` 		VARCHAR(150) COLLATE UTF8_GENERAL_CI NOT NULL, -- the ip end of maxmind data	
			PRIMARY KEY( `id`),
			INDEX (country_code, city)
			) DEFAULT CHARSET=UTF8 COLLATE=UTF8_GENERAL_CI AUTO_INCREMENT=1 ;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $country_table );
			self::add_countries_to_db();
			dbDelta( $city_table );

			for ( $i = 1; $i <= 6; $i ++ ) {
				$csv_file  = dirname( __FILE__ ) . '/data/geot_cities' . $i . '.csv';
				$load_data = "LOAD DATA LOCAL INFILE '{$csv_file}' INTO TABLE `{$wpdb->prefix}geot_cities` CHARACTER SET UTF8 FIELDS TERMINATED BY ',' ENCLOSED BY '\"' ESCAPED BY '\\\' LINES TERMINATED BY '\\n' ( `country_code` , `city`);";
				$wpdb->query( $load_data );
			}

		}
		// update version number to current one
		update_option( 'geot_version', GEOT_VERSION);
	}

	protected static function add_countries_to_db() {
		global $wpdb;

		$query = " INSERT INTO `{$wpdb->prefix}geot_countries` ( `iso_code`, `country`)
VALUES
	('AD','Andorra'),
	('AE','United Arab Emirates'),
	('AF','Afghanistan'),
	('AG','Antigua and Barbuda'),
	('AI','Anguilla'),
	('AL','Albania'),
	('AM','Armenia'),
	('AO','Angola'),
	('AP','Asia/Pacific Region'),
	('AQ','Antarctica'),
	('AR','Argentina'),
	('AS','American Samoa'),
	('AT','Austria'),
	('AU','Australia'),
	('AW','Aruba'),
	('AX','Aland Islands'),
	('AZ','Azerbaijan'),
	('BA','Bosnia and Herzegovina'),
	('BB','Barbados'),
	('BD','Bangladesh'),
	('BE','Belgium'),
	('BF','Burkina Faso'),
	('BG','Bulgaria'),
	('BH','Bahrain'),
	('BI','Burundi'),
	('BJ','Benin'),
	('BL','Saint Barthelemy'),
	('BM','Bermuda'),
	('BN','Brunei Darussalam'),
	('BO','Bolivia'),
	('BQ','Bonaire, Saint Eustatius and Saba'),
	('BR','Brazil'),
	('BS','Bahamas'),
	('BT','Bhutan'),
	('BW','Botswana'),
	('BY','Belarus'),
	('BZ','Belize'),
	('CA','Canada'),
	('CC','Cocos (Keeling) Islands'),
	('CD','Congo, The Democratic Republic of the'),
	('CF','Central African Republic'),
	('CG','Congo'),
	('CH','Switzerland'),
	('CI','Cote D\'Ivoire'),
	('CK','Cook Islands'),
	('CL','Chile'),
	('CM','Cameroon'),
	('CN','China'),
	('CO','Colombia'),
	('CR','Costa Rica'),
	('CU','Cuba'),
	('CV','Cape Verde'),
	('CW','Curacao'),
	('CX','Christmas Island'),
	('CY','Cyprus'),
	('CZ','Czech Republic'),
	('DE','Germany'),
	('DJ','Djibouti'),
	('DK','Denmark'),
	('DM','Dominica'),
	('DO','Dominican Republic'),
	('DZ','Algeria'),
	('EC','Ecuador'),
	('EE','Estonia'),
	('EG','Egypt'),
	('ER','Eritrea'),
	('ES','Spain'),
	('ET','Ethiopia'),
	('EU','Europe'),
	('FI','Finland'),
	('FJ','Fiji'),
	('FK','Falkland Islands (Malvinas)'),
	('FM','Micronesia, Federated States of'),
	('FO','Faroe Islands'),
	('FR','France'),
	('GA','Gabon'),
	('GB','United Kingdom'),
	('GD','Grenada'),
	('GE','Georgia'),
	('GF','French Guiana'),
	('GG','Guernsey'),
	('GH','Ghana'),
	('GI','Gibraltar'),
	('GL','Greenland'),
	('GM','Gambia'),
	('GN','Guinea'),
	('GP','Guadeloupe'),
	('GQ','Equatorial Guinea'),
	('GR','Greece'),
	('GS','South Georgia and the South Sandwich Islands'),
	('GT','Guatemala'),
	('GU','Guam'),
	('GW','Guinea-Bissau'),
	('GY','Guyana'),
	('HK','Hong Kong'),
	('HN','Honduras'),
	('HR','Croatia'),
	('HT','Haiti'),
	('HU','Hungary'),
	('ID','Indonesia'),
	('IE','Ireland'),
	('IL','Israel'),
	('IM','Isle of Man'),
	('IN','India'),
	('IO','British Indian Ocean Territory'),
	('IQ','Iraq'),
	('IR','Iran, Islamic Republic of'),
	('IS','Iceland'),
	('IT','Italy'),
	('JE','Jersey'),
	('JM','Jamaica'),
	('JO','Jordan'),
	('JP','Japan'),
	('KE','Kenya'),
	('KG','Kyrgyzstan'),
	('KH','Cambodia'),
	('KI','Kiribati'),
	('KM','Comoros'),
	('KN','Saint Kitts and Nevis'),
	('KP','Korea, Democratic People\'s Republic of'),
	('KR','Korea, Republic of'),
	('KW','Kuwait'),
	('KY','Cayman Islands'),
	('KZ','Kazakhstan'),
	('LA','Lao People\'s Democratic Republic'),
	('LB','Lebanon'),
	('LC','Saint Lucia'),
	('LI','Liechtenstein'),
	('LK','Sri Lanka'),
	('LR','Liberia'),
	('LS','Lesotho'),
	('LT','Lithuania'),
	('LU','Luxembourg'),
	('LV','Latvia'),
	('LY','Libya'),
	('MA','Morocco'),
	('MC','Monaco'),
	('MD','Moldova, Republic of'),
	('ME','Montenegro'),
	('MF','Saint Martin'),
	('MG','Madagascar'),
	('MH','Marshall Islands'),
	('MK','Macedonia'),
	('ML','Mali'),
	('MM','Myanmar'),
	('MN','Mongolia'),
	('MO','Macau'),
	('MP','Northern Mariana Islands'),
	('MQ','Martinique'),
	('MR','Mauritania'),
	('MS','Montserrat'),
	('MT','Malta'),
	('MU','Mauritius'),
	('MV','Maldives'),
	('MW','Malawi'),
	('MX','Mexico'),
	('MY','Malaysia'),
	('MZ','Mozambique'),
	('NA','Namibia'),
	('NC','New Caledonia'),
	('NE','Niger'),
	('NF','Norfolk Island'),
	('NG','Nigeria'),
	('NI','Nicaragua'),
	('NL','Netherlands'),
	('NO','Norway'),
	('NP','Nepal'),
	('NR','Nauru'),
	('NU','Niue'),
	('NZ','New Zealand'),
	('OM','Oman'),
	('PA','Panama'),
	('PE','Peru'),
	('PF','French Polynesia'),
	('PG','Papua New Guinea'),
	('PH','Philippines'),
	('PK','Pakistan'),
	('PL','Poland'),
	('PM','Saint Pierre and Miquelon'),
	('PN','Pitcairn Islands'),
	('PR','Puerto Rico'),
	('PS','Palestinian Territory'),
	('PT','Portugal'),
	('PW','Palau'),
	('PY','Paraguay'),
	('QA','Qatar'),
	('RE','Reunion'),
	('RO','Romania'),
	('RS','Serbia'),
	('RU','Russian Federation'),
	('RW','Rwanda'),
	('SA','Saudi Arabia'),
	('SB','Solomon Islands'),
	('SC','Seychelles'),
	('SD','Sudan'),
	('SE','Sweden'),
	('SG','Singapore'),
	('SH','Saint Helena'),
	('SI','Slovenia'),
	('SJ','Svalbard and Jan Mayen'),
	('SK','Slovakia'),
	('SL','Sierra Leone'),
	('SM','San Marino'),
	('SN','Senegal'),
	('SO','Somalia'),
	('SR','Suriname'),
	('SS','South Sudan'),
	('ST','Sao Tome and Principe'),
	('SV','El Salvador'),
	('SX','Sint Maarten (Dutch part)'),
	('SY','Syrian Arab Republic'),
	('SZ','Swaziland'),
	('TC','Turks and Caicos Islands'),
	('TD','Chad'),
	('TF','French Southern Territories'),
	('TG','Togo'),
	('TH','Thailand'),
	('TJ','Tajikistan'),
	('TK','Tokelau'),
	('TL','Timor-Leste'),
	('TM','Turkmenistan'),
	('TN','Tunisia'),
	('TO','Tonga'),
	('TR','Turkey'),
	('TT','Trinidad and Tobago'),
	('TV','Tuvalu'),
	('TW','Taiwan'),
	('TZ','Tanzania, United Republic of'),
	('UA','Ukraine'),
	('UG','Uganda'),
	('UM','United States Minor Outlying Islands'),
	('US','United States'),
	('UY','Uruguay'),
	('UZ','Uzbekistan'),
	('VA','Holy See (Vatican City State)'),
	('VC','Saint Vincent and the Grenadines'),
	('VE','Venezuela'),
	('VG','Virgin Islands, British'),
	('VI','Virgin Islands, U.S.'),
	('VN','Vietnam'),
	('VU','Vanuatu'),
	('WF','Wallis and Futuna'),
	('WS','Samoa'),
	('YE','Yemen'),
	('YT','Mayotte'),
	('ZA','South Africa'),
	('ZM','Zambia'),
	('ZW','Zimbabwe')";
		$wpdb->query( $query );
	}
}
