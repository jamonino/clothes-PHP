<?php

 
function userService($requestMethod,$bodyRequest,$param){
    switch ($requestMethod) {
        case 'POST':
            if(!isset($param)) {
                $myDb = new DB();
                $productToPut = new Product;
                $productToPut->jsonConstruct($bodyRequest);
                $responseCode = $productToPut->DB_buyProduct($myDb->connection);
                return_response(200, "OK", new Response($responseCode));
            }else{
                return_response(405, "Method Not Allowed", null);
            }
            
            break;

        case 'GET':
            if(!isset($param)) {
                return_response(405, "Method Not Allowed", null,);
            }else{
                $myDb = new DB();
                return_response(200, "OK", new Response(ResponseCodes::$OK,Product::DB_selectAllGender($myDb->connection, $param)));
            }
            break;

        default:
            return_response(405, "Method Not Allowed", null);
            break;
     }
}