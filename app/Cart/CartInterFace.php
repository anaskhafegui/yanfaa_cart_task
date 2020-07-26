<?php

namespace App\Cart;

interface CartInterFace
{
    public function add($productid);
    public function delete($productid);
    public function empty();

}
