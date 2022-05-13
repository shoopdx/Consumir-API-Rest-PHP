<?php

/**
 * Clase para consumir API Rest
 * Las operaciones soportadas son:
 * 	
 * 	- POST		: Agregar
 * 	- GET		: Consultar
 * 	- DELETE	: Eliminar
 * 	- PUT		: Actualizar
 * 	- PATCH		: Actualizar por parte
 * 
 * Extras
 * 	- autenticación de acceso básica (Basic Auth)
 *  	- Conversor JSON
 *
 * @author     	Diego Valladares <dvdeveloper.com>
 * 
 * Extras 2
 * 	- OAuthParams parametros para la autenticación OAuth 2
 *  - OAuth2 atenticación de acceso OAuth 2
 *
 * @author     	acetorod
 * 
 * @version 	2.0
 */
class API
{
	private $statusCode;
	private $time;
	private $curl_errno;
	private $curl_error;

	/**
	 * autenticación de acceso básica (Basic Auth)
	 * Ejemplo Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==
	 *
	 * @param string $URL url para acceder y obtener un token
	 * @param string $usuario usuario
	 * @param string $password clave
	 * @return JSON
	 */
	public function Authentication($URL, $usuario, $password)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$usuario:$password");
		$result = curl_exec($ch);
		$this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
		$this->time = round($total_time * 1000);
		$this->curl_errno = curl_errno($ch);
		$this->curl_error = curl_error($ch);
		curl_close($ch);
		return $result;
	}

	/**
	 * Parámetros necesarios según el tipo de autenticación OAuth 2
	 * @param string $grant_type especifica el tipo de autenticación: 1 - Password Credentials, 2 - Client Credentials, 3 - Authorization Credentials
	 * @return ARRAY 
	 */
	static function OAuthParams($grant_type = '1')
	{
		$options = array();
		switch ($grant_type) {
			case '1': //Password Credentials
				$options['grant_type'] = 'password';
				$options['client_id'] = '';
				$options['client_secret'] = '';
				$options['username'] = '';
				$options['password'] = '';
				$options['scope'] = '';
				break;

			case '2': //Client Credentials
				$options['grant_type'] = 'client_credentials';
				$options['client_id'] = '';
				$options['client_secret'] = '';
				$options['scope'] = '';
				break;

			case '3': //Authorization Credentials
				$options['grant_type'] = 'authorization_code';
				$options['client_id'] = '';
				$options['client_secret'] = '';
				$options['redirect_uri'] = '';
				$options['authorize_uri'] = '';
				$options['scope'] = '';
				break;

			default: //Password Credentials por defecto
				$options['grant_type'] = 'password';
				$options['client_id'] = '';
				$options['client_secret'] = '';
				$options['username'] = '';
				$options['password'] = '';
				$options['scope'] = '';
				break;
		}
		return $options;
	}

	/**
	 * Autenticación OAuth 2 Grant types Password Credentials, Client Credentials y Authorization Credentials
	 * Ejemplo Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==
	 *
	 * @param string $TokenURL url para acceder y obtener un token
	 * @param array $options parámetros para autenticar según el grant type (ver función OAuthParams)	 
	 * @return JSON
	 */
	function OAuth2($TokenURL, $options)
	{
		$datapost = '';
		foreach ($options as $key => $value) {
			$datapost .= $key . "=" . $value . "&";
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $TokenURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datapost);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$result = curl_exec($ch);
		$this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
		$this->time = round($total_time * 1000);
		$this->curl_errno = curl_errno($ch);
		$this->curl_error = curl_error($ch);
		curl_close($ch);
		return $result;
	}

	/**
	 * Enviar parámetros a un servidor a través del protocolo HTTP (POST).
	 * Se utiliza para agregar datos en una API REST
	 *
	 * @param string $URL URL recurso, ejemplo: http://website.com/recurso
	 * @param string $TOKEN token de autenticación
	 * @param array|string $PARAMS parámetros a envíar
	 * @return JSON
	 */
	public function POST($URL, $TOKEN, $PARAMS)
	{
		$datapost = '';
		if (is_array($PARAMS)) {
			foreach ($PARAMS as $key => $value) {
				$datapost .= $key . "=" . $value . "&";
			}
		} else {
			$datapost = $PARAMS;
		}

		$headers 	= array('Authorization: ' . $TOKEN, 'Content-Type: application/json');
		$ch 		= curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datapost);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		$this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
		$this->time = round($total_time * 1000);
		$this->curl_errno = curl_errno($ch);
		$this->curl_error = curl_error($ch);
		curl_close($ch);
		return $response;
	}

	/**
	 * Consultar a un servidor a través del protocolo HTTP (GET).
	 * Se utiliza para consultar recursos en una API REST
	 *
	 * @param string $URL URL recurso, ejemplo: http://website.com/recurso/(id) no obligatorio
	 * @param string $TOKEN token de autenticación
	 * @return JSON
	 */
	public function GET($URL, $TOKEN)
	{
		$headers 	= array('Authorization: ' . $TOKEN, 'Content-Type: application/json');
		$ch 		= curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		$this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
		$this->time = round($total_time * 1000);
		$this->curl_errno = curl_errno($ch);
		$this->curl_error = curl_error($ch);
		curl_close($ch);
		return $response;
	}

	/**
	 * Consultar a un servidor a través del protocolo HTTP (DELETE).
	 * Se utiliza para eliminar recursos en una API REST
	 *
	 * @param string $URL URL recurso, ejemplo: http://website.com/recurso/id
	 * @param string $TOKEN token de autenticación
	 * @return JSON
	 */
	public function DELETE($URL, $TOKEN)
	{
		$headers 	= array('Authorization: ' . $TOKEN, 'Content-Type: application/json');
		$ch 		= curl_init();

		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		$this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
		$this->time = round($total_time * 1000);
		$this->curl_errno = curl_errno($ch);
		$this->curl_error = curl_error($ch);
		curl_close($ch);
		return $response;
	}

	/**
	 * Enviar parámetros a un servidor a través del protocolo HTTP (PUT).
	 * Se utiliza para editar un recurso en una API REST
	 *
	 * @param string $URL URL recurso, ejemplo: http://website.com/recurso/id
	 * @param string $TOKEN token de autenticación
	 * @param array|string $PARAMS parámetros a envíar
	 * @return JSON
	 */
	public function PUT($URL, $TOKEN, $PARAMS)
	{
		$datapost = '';
		if (is_array($PARAMS)) {
			foreach ($PARAMS as $key => $value) {
				$datapost .= $key . "=" . $value . "&";
			}
		} else {
			$datapost = $PARAMS;
		}

		$headers 	= array('Authorization: ' . $TOKEN, 'Content-Type: application/json');
		$ch 		= curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datapost);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		$this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
		$this->time = round($total_time * 1000);
		$this->curl_errno = curl_errno($ch);
		$this->curl_error = curl_error($ch);
		curl_close($ch);
		return $response;
	}

	/**
	 * Enviar parámetros a un servidor a través del protocolo HTTP (PATCH).
	 * Se utiliza para editar un atributo específico (recurso) en una API REST
	 *
	 * @param string $URL URL recurso, ejemplo: http://website.com/recurso/id
	 * @param string $TOKEN token de autenticación
	 * @param array|string $PARAMS parametros parámetros a envíar
	 * @return JSON
	 */
	public function PATCH($URL, $TOKEN, $PARAMS)
	{
		$datapost = '';
		if (is_array($PARAMS)) {
			foreach ($PARAMS as $key => $value) {
				$datapost .= $key . "=" . $value . "&";
			}
		} else {
			$datapost = $PARAMS;
		}

		$headers 	= array('Authorization: ' . $TOKEN, 'Content-Type: application/json');
		$ch 		= curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datapost);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		$this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
		$this->time = round($total_time * 1000);
		$this->curl_errno = curl_errno($ch);
		$this->curl_error = curl_error($ch);
		curl_close($ch);
		return $response;
	}

	/**
	 * Convertir JSON a un ARRAY
	 *
	 * @param json $json Formato JSON
	 * @return ARRAY
	 */
	static function JSON_TO_ARRAY($json)
	{
		return json_decode($json, true);
	}

	/**
	 * Convertir JSON a un OBJECT
	 *
	 * @param json $json Formato OBJECT
	 * @return OBJECT
	 */
	static function JSON_TO_OBJECT($json)
	{
		return json_decode($json);
	}

	/**
	 * Retorna el código de estatus de la última conexión
	 * @return int statusCode
	 */
	public function getStatuscode()
	{
		return $this->statusCode;
	}

	/**
	 * Retorna el tiempo en segundos de la última conexión
	 * @return int time
	 */
	public function getTime()
	{
		return $this->time;
	}

	/**
	 * Retorna un array con la información del error y su código
	 * @return ARRAY
	 */
	public function getError()
	{
		return array('error' => $this->curl_error, 'errorno' => $this->curl_errno);
	}

	/**
	 * Retorna un booleano para saber si hay o no un error en la última conexión
	 * @return boolean
	 */
	public function isError()
	{
		return trim($this->curl_error) == '' ? false : true;
	}
}
