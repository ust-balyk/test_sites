<?php
namespace App\Controller;
use App\Widgets\Cart\Cart;


class CartController
{
    private static function checkCsrf(): bool
    {
        // Надёжный способ получить заголовки (фермы PHP могут отличаться)
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $token = null;

        if (!empty($headers['X-CSRF-TOKEN'])) {
            $token = $headers['X-CSRF-TOKEN'];
        } elseif (!empty($headers['x-csrf-token'])) {
            $token = $headers['x-csrf-token'];
        } elseif (!empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
        }

        if (!$token) {
            return false;
        }

        $expected = $_SESSION['csrf_token'] ?? null;
        if (!is_string($expected) || !is_string($token)) {
            return false;
        }

        // hash_equals защищает от тайминг-атак
        return hash_equals($expected, $token);
    }

    public function add_to_cart()
    {
        // Проверяем CSRF — если не прошёл, возвращаем JSON с типом 'danger'
        if (!self::checkCsrf()) {
            response()->json([
                'status'  => 'error',
                'type'    => 'danger',
                'message' => 'Неверный CSRF токен или сессия истекла'
            ], 419); // 419/401/403 — по желанию; 419 распространён для expired CSRF
            return;
        }

        $input = request()->post('id');
        $product_id_raw = is_scalar($input) ? (string)$input : '';
        $product_id = hsc($product_id_raw);
        $product_id = preg_replace('~\D+~', '', $product_id_raw); // оставляем только цифры

        // Попробуйте добавить товар; метод должен вернуть true/false
        if (Cart::addToCart($product_id)) {
            response()->json([
                'status'   => 'ok',
                'type'     => 'success',
                'message'  => 'Товар (ID: '.$product_id.') успешно добавлен',
                //'id'       => $product_id,
                //'mini_cart'=> Cart::renderMiniCartHtml() ?? null, // опционально
                //'cart_qty' => Cart::getQuantity() ?? null      // опционально
            ], 200);
            return;
        }

        // Если добавление не удалось
        response()->json([
            'status'  => 'error',
            'type'    => 'warning',
            'message' => 'Ошибка идентификации продукта'
        ], 400);
    }


    public function add_to_favorites()
    {

    }

}
