<?php
namespace App\Widgets\Cart;

class Cart
{

    public function __construct()
    {
        //Выводит начальную обертку: иконку и пустой контейнер для списка
        include "cart.html";
    
    }

    public static function getCart(): array
    {
        return session()->get('cart') ?: [];
    
    }

    /*
    // Тот самый метод для AJAX, который вернет только "внутрянку"
    public static function renderItems(): string
    {
        $items = self::getCart();
        ob_start();
        // Подключаем файл, где только верстка списка товаров (li, img, price...)
        include __DIR__ . "cart_list.php";
        return ob_get_clean();
    
    }*/

    public static function addToCart($product_id =0, $quantity =1)
    {
        $added = false;

        $product_id = preg_replace('~\D+~', '', $product_id);

        $quantity = $quantity > 0 ? $quantity : 1;

        if (self::hasProductInCart($product_id)) {
            $product_id = (string)$product_id;
            // отдаём строкой id продукта и количество
            session()->set("cart.$product_id.quantity", 
                session()->get("cart.$product_id.quantity") + $quantity);
            $added = true;

        } else {
            $product = db()->query("select * from ". TABLE_NAME ." where outer_id = ? and in_stock = 1", 
                [$product_id])->getOne();

            if ($product) {
                $product_data = [
                    'id'        => $product['outer_id'],
                    'title'     => $product['title'],
                    'slug'      => $product['slug'],
                    'image'     => $product['image'],
                    'new_price' => $product['new_price'],
                    'old_price' => $product['old_price'],
                    'price'     => $product['price'],
                    'quantity'  => $quantity
                ];
                session()->set("cart.$product_id", $product_data);
                $added = true;
            }
        }
        return $added;

    }

    public static function hasProductInCart($product_id): bool
    {
        $product_id = (string)$product_id;
        return session()->has("cart.$product_id");

    }

    public static function removeProductFromCart($product_id): bool
    {
        $product_id = (string)$product_id;
        // передать только id строкой
        if (self::hasProductInCart($product_id)) {
            session()->remove("cart.$product_id");
            return true;
        }
        return false;

    }

    public static function clearCart(): bool
    {
        return session()->remove('cart');

    }

    public static function updateProductQuantity($product_id, int $quantity): bool
    {
        $quantity = $quantity > 0 ? $quantity : 1;
        $product_id = (string)$product_id;

        if (self::hasProductInCart($product_id)) {
            session()->set("cart.$product_id.quantity", $quantity);
            return true;
        }
        return false;

    }

    public static function getCartQuantityItems(): int
    {
        return count(self::getCart());

    }

    public static function getCartQuantityTotal(): int
    {
        $cart = self::getCart();
        return array_sum(array_column($cart, 'quantity'));

    }

    public static function getCartSum()
    {
        $sum = 0;
        $cart = self::getCart();

        if ($cart) {

            foreach ($cart as $item) {
                $price = $item['price'] ?: $item['new_price'];
                $sum += (int)$price * (int)$item['quantity'];
                $sum = (string)$sum;

            }
            return $sum;

        }
        return false;

    }


}
