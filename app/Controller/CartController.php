<?php
namespace App\Controller;
use App\Widgets\Cart\Cart;


class CartController
{

    public function add_to_cart()
    {
        $input = request()->get("id");
        $product_id = hsc($input);
        
        if (Cart::addToCart($product_id)) {
            $product_id = preg_replace('~\D+~', '', $product_id);
            response()->json([
                'id'   => $product_id,
                'data' => 'Товар (ID: '.$product_id.') успешно добавлен', // Это пойдет в нижнюю часть тоста
            ], 200);
        
        }
        response()->text('ID продукта не найден или недоступен', 400);

    }


    public function add_to_favorites()
    {

    }

}
