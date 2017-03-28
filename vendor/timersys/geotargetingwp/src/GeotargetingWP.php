<?php namespace GeotWP;
use GeotWP\Exception\AddressNotFoundException;
use GeotWP\Exception\InvalidIPException;
use GeotWP\Exception\InvalidLicenseException;
use GeotWP\Exception\OutofCreditsException;
use GuzzleHttp\Client;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class GeotargetingWP{

	/**
	 * @var Client
	 */
	private $client;

	private $ip;

	private $user_data;
	/**
	 * @var Array of settings
	 */
	private $opts;

	/**
	 * Constructor
	 *
	 * @param $acces_token
	 * @param $args
	 */
	public function __construct( $acces_token, $args = array() ) {
		$this->client = new Client(
			[
				'base_uri' => 'https://geotargetingwp.com/api/v1/',
				'headers' => [
					'Content-Type' => 'application/json'
				],
				'defaults' => [
					'license' =>  $acces_token
				]
			]
		);
		$this->ip = getUserIP();
		$this->set_defaults($args);

	}

	/**
	 * Main function that return User data
	 * @param string $ip
	 *
	 * @return mixed
	 */
	public function getData( $ip = "" ){
		if( ! empty( $ip ) )
			$this->ip = $ip;

		if( ! empty ( $this->user_data ) && empty( $ip ) )
			return $this->user_data;

		$this->initUserData();

		// Start sessions if needed
		if( is_session_started() === FALSE && ! $this->opts['disable_sessions'] )
			session_start();

		// Easy debug
		if( isset( $_GET['geot_debug'] ) )
			return $this->debugData();

		// If user set cookie and not in debug mode
		if(  ! $this->opts['debug_mode']  &&  ! empty( $_COOKIE[$this->opts['cookie_name']] ) )
			return $this->setUserData('geot_cookie' , $_COOKIE[$this->opts['cookie_name']] );

		// If we already calculated on session return (if we are not calling by IP & if cache mode (sessions) is turned on)
		if( empty( $ip ) && ! $this->opts['disable_sessions'] && !empty ( $_SESSION['geot_data'] ) && ! $this->opts['debug_mode'] )
			return  unserialize( $_SESSION['geot_data'] ) ;

		// check for crawlers
		$CD = new CrawlerDetect();
		if( $CD->isCrawler() && ! empty( $this->opts['bots_country'] ) )
			return $this->setUserData('country' , $this->opts['bots_country']);

		// time to call api
		$res = $this->client->request('GET', '', [
			'ip' => $this->ip
		]);
		$this->validateResponse( $res );
		return $this->cleanReponse( $res );
	}


	/**
	 * Set some default options for the class
	 * @param $args
	 */
	private function set_defaults( $args ) {

		$this->opts = [
			'disable_sessions'  => false, // cache mode turned on by default
			'debug_mode'        => false, // similar to disable sessions but also invalidates cookies
			'bots_country'      => '', // a default country to return if a bot is detected
			'cookie_name'       => 'geot_cookie' // cookie_name to store country iso code
		];

		foreach ($args as $key => $value )
			if ( isset( $this->opts[$key] ) )
				$this->opts[$key] = $value;
	}

	/**
	 * Return debug data set in query vars
	 */
	private function debugData() {
		if( isset( $_GET['geot_state'] ) ) {
			$state = new stdClass;
			$state->name = esc_attr( $_GET['geot_state'] );
			$state->isoCode = isset( $_GET['geot_state_code'] ) ? esc_attr( $_GET['geot_state_code'] ) : '';
		}

		$this->user_data = array(
			'country' => ( $_GET['geot_debug'] ),
			'city'    => isset( $_GET['geot_city'] ) ? filter_var( $_GET['geot_city'], FILTER_SANITIZE_FULL_SPECIAL_CHARS ) : '',
			'zip'     => isset( $_GET['geot_zip'] ) ? filter_var( $_GET['geot_zip'], FILTER_SANITIZE_FULL_SPECIAL_CHARS ) : '',
			'state'   => isset( $state ) ? $state : '',
		);

		return $this->user_data;
	}


	/**
	 * Init empty array of user data
	 */
	private function initUserData() {
		$this->user_data =  [
			'continent' => '',
			'country' => '',
			'state'   => '',
			'city'    => '',
		];
	}

	/**
	 * Add new values or update in user data
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	private function setUserData( $key, $value ) {
		$this->user_data[$key] = $value;
		return $this->user_data;
	}

	/**
	 * Check returned response
	 *
	 * @param $res
	 *
	 * @throws AddressNotFoundException
	 * @throws InvalidIPException
	 * @throws InvalidLicenseException
	 * @throws OutofCreditsException
	 */
	private function validateResponse( $res ) {
		$code = $res->getStatusCode();
		switch ($code) {
			case '404':
				throw new AddressNotFoundException($res->getReasonPhrase());
			case '500':
				throw new InvalidIPException($res->getReasonPhrase());
			case '401':
				throw new InvalidLicenseException($res->getReasonPhrase());
			case '403':
				throw new OutofCreditsException($res->getReasonPhrase());
			case '200':
			default:
				break;
		}
	}

	/**
	 * For now it just convert json data to object
	 * @param $res
	 *
	 * @return mixed
	 */
	private function cleanReponse( $res ) {
		$body = $res->getBody();
		return json_decode($body);
	}


}