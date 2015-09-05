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
	
Ejemplos
===================

Authentication
===================
```php
$URL	= 'http://ejemploapi.com/';
$rs 	= API::Authentication($URL.'authentication','usuario','clave');
$array  = API::JSON_TO_ARRAY($rs);
$token 	= $array['data']['APIKEY'];
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

GET
===================
```php
$rs 	= API::GET($URL.'proyectos/1',$token);
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
$rs = API::POST($URL.'proyectos',$token,$parametros);
$rs = API::JSON_TO_ARRAY($rs);
```

DELETE
===================
```php
$rs 	= API::DELETE($URL.'proyectos/1',$token);
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
$rs = API::PUT($URL.'proyectos/1',$token,$parametros);
$rs = API::JSON_TO_ARRAY($rs);
```

PATCH
===================
```php
$parametros = array(
	'codigo'	=> 'Código 3',
	'activo'	=> 0
);
$rs = API::PATCH($URL.'proyectos/1',$token,$parametros);
$rs = API::JSON_TO_ARRAY($rs);
```
