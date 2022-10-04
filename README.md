# API REST - PHP
Clase para consumir API Rest en PHP

Métodos HTTP soportados
	- GET
	- POST
	- DELETE
	- PUT
	- PATCH

Extras
	- Conversor JSON a ARRAY
	- Metodo de autentificación básica
	- OAuthParams parametros para la autenticación OAuth 2
	- OAuth2 atenticación de acceso OAuth 2
	
Ejemplos
===================

Authentication
===================
```php
$URL	= 'http://ejemploapi.com/';
$api	= new API();
$rs 	= $api->Authentication($URL.'authentication','usuario','clave');
$array  = API::JSON_TO_ARRAY($rs);
$token 	= 'Bearer '.$array['data']['APIKEY'];
```

Estructura JSON Authenticacion
```
{
  "success": true,
  "code": 200,
  "data": {
    "APIKEY": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0NDAyMjA3OTgsImp0aSI6ImU2ZGMyMmEwNWQxNzE3YmNjMjYyNjk0ZDgzMGMyMmNiMjI5YmU1OTYiLCJkYXRhIjp7IklEVXN1YXJpbyI6MSwiSURFbXByZXNhIjoxLCJJRFBlcmZpbCI6MX19.UwKfoHNM3YOhrWfXVTkj8MgC5qxIjpkGQdsRoby8irg"
  }
}
```

OAuth2 Grant Type Password Credentials
===================
```php
$URL	= 'http://ejemploapi.com/token';

$params = API::OAuthParams('1'); // 1 - Grant Type Password Credentials
$params['client_id'] = 'CLIENT ID AQUI!!!';
$params['client_secret'] = 'CLIENT SECRET AQUI!!!';
$params['username'] = 'USERNAME AQUI!!!';
$params['password'] = 'PASSWORD AQUI!!!';
$params['scope'] = '';

$api = new API();
$rs = $api->OAuth2($URL, $params); 
$array = API::JSON_TO_ARRAY($rs);
$token 	= $array['token_type'].' '.$array['access_token'];
```

OAuth2 Grant Type Client Credentials
===================
```php
$URL	= 'http://ejemploapi.com/token';

$params = API::OAuthParams('2'); // 2 - Grant Type Client Credentials
$params['client_id'] = 'CLIENT ID AQUI!!!';
$params['client_secret'] = 'CLIENT SECRET AQUI!!!';
$params['scope'] = 'SCOPE AQUI!!!';

$api = new API();
$rs = $api->OAuth2($URL, $params); 
$array = API::JSON_TO_ARRAY($rs);
$token 	= $array['token_type'].' '.$array['access_token'];
```



GET
===================
```php
$URL	= 'http://ejemploapi.com/';

$api	= new API();
$rs 	= $api->GET($URL.'proyectos/1',$token);
$array  = API::JSON_TO_ARRAY($rs);
```

POST
===================
```php
$parametros = array(
	'proyecto' 	=> 'prueba1',
	'codigo'	=> 'codigo1',
	'activo'	=> 1,
	'idempresa' 	=> 1
);
$api = new API();
$rs = $api->POST($URL.'proyectos',$token,$parametros);
$rs = API::JSON_TO_ARRAY($rs);
```

DELETE
===================
```php
$api	= new API();
$rs 	= $api->DELETE($URL.'proyectos/1',$token);
$rs 	= API::JSON_TO_ARRAY($rs);
```

PUT
===================
```php
$parametros = array(
	'proyecto' 	=> 'Proyecto Ejemplo',
	'codigo'	=> 'Código 1',
	'activo'	=> 1
);
$api = new API();
$rs = $api->PUT($URL.'proyectos/1',$token,$parametros);
$rs = API::JSON_TO_ARRAY($rs);
```

PATCH
===================
```php
$parametros = array(
	'codigo'	=> 'Código 3',
	'activo'	=> 0
);
$api = new API();
$rs = $api->PATCH($URL.'proyectos/1',$token,$parametros);
$rs = API::JSON_TO_ARRAY($rs);
```
