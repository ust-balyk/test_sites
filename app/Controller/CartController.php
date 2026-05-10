<?php
namespace App\Controller;
use App\Widgets\Cart\Cart;

class CartController
{

    public function add_to_cart()
    {
        /*
        sleep(2);
        echo "ok";*/
        $product_id = request()->get("id");
        if ($product_id) {
            response()->text('Product id = '. $product_id, 200);   

        }
        
        if(Cart::addToCart($product_id)) {
            response()->json(['data' => 'product added to cart',]);

        }

        response()->text('Product not found', 400);
        
    }

    public function add_to_favorites()
    {

    }

}
