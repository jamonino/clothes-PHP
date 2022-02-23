<?php

 
function userService($requestMethod,$bodyRequest){
    switch ($requestMethod) {
        case 'POST':
            if(!isset($id)) {
                $myDb = new DB();
                $productToPut = new Product;
                $productToPut->jsonConstruct($bodyRequest);
                $responseCode = $productToPut->buyProduct_DB($myDb->connection);
                return_response(200, "OK", new Response($responseCode));
            }else{
                return_response(405, "Method Not Allowed", null);
            }
            
            break;

        case 'GET':
            return_response(405, "Method Not Allowed", null);
            break;

        case 'PUT':
            return_response(405, "Method Not Allowed", null);
            break;

        default:
            return_response(405, "Method Not Allowed", null);
            break;
     }
}