<?php

 
function adminService($requestMethod,$bodyRequest,$param){
    switch ($requestMethod) {
        case 'POST':
            $myDb = new DB();
            $newProduct = new Product;
            $newProduct->jsonConstruct($bodyRequest);
            $newProduct->DB_insert($myDb->connection);
            return_response(200, "OK", new Response(ResponseCodes::$OK));
            break;

        case 'GET':
            $myDb = new DB();
            if(isset($param)) {
                return_response(405, "Method Not Allowed", null);
            }else{
                return_response(200, "OK", new Response(ResponseCodes::$OK,Product::DB_selectAll($myDb->connection)));
            }
            break;

        case 'PUT':
            if(isset($param)) {
                $myDb = new DB();
                $productToPut = new Product;
                $productToPut->jsonConstruct($bodyRequest);
                $productToPut->setId($param);
                $productToPut->DB_update($myDb->connection);
                return_response(200, "OK", new Response(ResponseCodes::$OK));
            }else{
                return_response(405, "Method Not Allowed", null);
            }
            break;

        default:
            return_response(405, "Method Not Allowed", null);
            break;
     }
}