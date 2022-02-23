<?php

class Response implements \JsonSerializable {
    private int $responseCode;
    private array $products;
    
    public function __construct(int $responseCode, array $products = null) {
        $this->responseCode = $responseCode;
        if ($products != null){$this->products = $products;}
    }
    //Para que las variables privadas de clase también se conviertan a json
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

}
