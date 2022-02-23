<?php

class Product implements \JsonSerializable {
    
    private string $name;
    private int $price;
    private int $stock;
    private int $gender;
    private int $id;
    
    //Fuera de BBDD
    private int $quantity;
    
    
    public function parametersConstruct(int $id, string $name,int $price,int $stock,int $gender) {
        $this->name = $name;
        $this->stock = $stock;
        $this->price = $price;
        $this->gender = $gender;
        $this->id = $id;
    }
    
    public function jsonConstruct($json) {
        foreach (json_decode($json, true) AS $key => $value) {
            $this->{$key} = $value;
        }
    }

    
    public function DB_insert($dbconn){
        pg_prepare($dbconn, "my_query_INSERT", 'INSERT INTO products (name,price,stock,gender) VALUES ($1,$2,$3,$4) returning id;');
        $result = pg_execute($dbconn, "my_query_INSERT", array($this->name, $this->price,$this->stock, $this->gender));
        while ($row = pg_fetch_row($result)) {
            $this->id = $row[0];
        }
        // Free resultset Liberal la pool.
        pg_free_result($result);
    }
    
    public static function DB_selectAll($dbconn){
        pg_prepare($dbconn, "my_query_SELECTALL", 'SELECT id,name,price,stock,gender FROM products;');
        $result = pg_execute($dbconn, "my_query_SELECTALL",array());
        $products = array();
        while ($row = pg_fetch_row($result)) {
            $newProduct = new Product;
            $newProduct->parametersConstruct($row[0],$row[1],$row[2],$row[3],$row[4]);
            $products[]=$newProduct;
        }
        // Free resultset
        pg_free_result($result);
        return $products;
    }
    
    
    public function DB_update($dbconn){
        $update = "UPDATE products SET ";
        $fields = array();
        $values = array();
        
        if (isset($this->name)) {
            $fields[] = "name";
            $values[] = $this->name;
        }
        if (isset($this->price)) {
            $fields[] = "price";
            $values[] = $this->price;
        }
        if (isset($this->stock)) {
            $fields[] = "stock";
            $values[] = $this->stock;
        }
        if (isset($this->gender)) {
            $fields[] = "gender";
            $values[] = $this->gender;
        }
        
        for ($i = 0; $i < count($fields); $i++) {
            $update .= $fields[$i]."=$".($i+1);
            if($i!=count($fields)-1){
                $update .= ",";
            }
        }
        $update .=  " WHERE id = $". (count($fields)+1);
        
        $values[] = $this->id;
        
        pg_prepare($dbconn, "my_query_UPDATE", $update);
        $result = pg_execute($dbconn, "my_query_UPDATE", $values);
        
        return $this;
    }
    
    public function DB_buyProduct($dbconn){
        pg_prepare($dbconn, "my_query_BUYPRODUCT", 'UPDATE products SET stock = stock - $1 WHERE id = $2 AND stock >= $1 ;');
        $result = pg_execute($dbconn, "my_query_BUYPRODUCT",array($this->quantity,$this->id));
        
        if(pg_affected_rows($result)===0){
            return ResponseCodes::$ERROR;
        }else{
            return ResponseCodes::$OK;
        }
        pg_free_result($result);
    }
    
    public static function DB_selectAllGender($dbconn,$gender){
        pg_prepare($dbconn, "my_query_SELECTALLGENDER", 'SELECT id,name,price,stock,gender FROM products WHERE gender = $1;');
        $result = pg_execute($dbconn, "my_query_SELECTALLGENDER",array($gender));
        $products = array();
        while ($row = pg_fetch_row($result)) {
            $newProduct = new Product;
            $newProduct->parametersConstruct($row[0],$row[1],$row[2],$row[3],$row[4]);
            $products[]=$newProduct;
        }
        // Free resultset
        pg_free_result($result);
        return $products;
    }
       
    
    
    //Para que las variables privadas de clase también se conviertan a json
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
    public function getName(): string {
        return $this->name;
    }

    public function getPrice(): int {
        return $this->price;
    }

    public function getStock(): int {
        return $this->stock;
    }

    public function getGender(): int {
        return $this->gender;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setPrice(int $price): void {
        $this->price = $price;
    }

    public function setStock(int $stock): void {
        $this->stock = $stock;
    }

    public function setGender(int $gender): void {
        $this->gender = $gender;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }
}
