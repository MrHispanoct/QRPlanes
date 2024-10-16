<?php
/*Cabecera CORS*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: api-key,X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header('Access-Control-Request-Headers: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, PATCH, DELETE");
header("Accept: application/json, text/plain, */*");

?>