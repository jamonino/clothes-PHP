<?php

 require "db/DB.php";
 require "model/Product.php";
 require "api/adminService.php";
 require "api/userService.php";
 require "utils/Response.php";
 require "utils/ResponseCodes.php";

 
 

 function return_response($status, $statusMessage, $data) {
    header("HTTP/1.1 $status $statusMessage");
    header("Content-Type: application/json; charset=UTF-8");
    //CORS
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");  

    echo json_encode($data);
 }
 
 
 $connectionURI = explode("/", $_SERVER['REQUEST_URI']);
 
 //Se limpian los elementos en blanco para direcciones del tipo dominio.com/product/1/ que nos daría un último elemento del array en blanco
 foreach ($connectionURI as $key => $value) {
    if(empty($value)) {
        unset($connectionURI[$key]);
    }
 }

 //Obtenemos id (si es el caso) y entidad
 if(end($connectionURI)>0) {
    $param = $connectionURI[count($connectionURI)];
    $entity = $connectionURI[count($connectionURI) - 1];
 } else {
    $entity = $connectionURI[count($connectionURI)];
 }
 
 $bodyRequest = file_get_contents("php://input");

  switch ($entity) {
    case 'admin':
        adminService($_SERVER['REQUEST_METHOD'], $bodyRequest,$param);
        break;
    case 'user':
        userService($_SERVER['REQUEST_METHOD'], $bodyRequest,$param);
        break;
    default:
        break;
  } 
    
 


 