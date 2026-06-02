<?php

function app(): \Master\Application
{
   return \Master\Application::$app;
}

function session(): \Master\Session
{
   return app()->session;
}

function cache(): \Master\Cache
{
   return app()->cache;
}

function get_route_params(): array
{
   return app()->router->route_params;
}

function get_route_param($key, $default=''): string
{
   return app()->router->route_params[$key] ?? $default;
}

function array_value_search($arr, $index, $value)
{
   foreach ($arr as $k => $v) {
      if ($v[$index] == $value) {
         return $k;   
      }
   }
   return null;
}


// сохранить порядок ключей в сессии

function shuffle_assoc(array $arr): array {
   if (session_status() !== PHP_SESSION_ACTIVE) session_start();

   $keys = array_keys($arr);
   shuffle($keys);

   $_SESSION['shuffled_keys'] = $keys; // сохраняем порядок сразу после shuffle

   $result = [];
   foreach ($keys as $k) {
       if (array_key_exists($k, $arr)) $result[$k] = $arr[$k];
   }
   return $result;
}

function restore_order(array $arr): array {
   if (session_status() !== PHP_SESSION_ACTIVE) session_start();

   if (empty($_SESSION['shuffled_keys']) || !is_array($_SESSION['shuffled_keys'])) {
       return $arr;
   }

   $keys = $_SESSION['shuffled_keys'];
   $keySet = array_flip($keys); // для O(1)-проверок

   $res = [];
   foreach ($keys as $k) {
       if (array_key_exists($k, $arr)) $res[$k] = $arr[$k];
   }

   foreach ($arr as $k => $v) {
       if (!array_key_exists($k, $keySet)) $res[$k] = $v;
   }

   return $res;
}

/* ----------- */

function db()
{
   return app()->db;
}

function request(): \Master\Request
{
   return app()->request;
}

function response(): \Master\Response
{
   return app()->response;
}

function f_view($layout='', $view='', $data=[])
{
   return app()->view->full_view($layout, $view, $data);
}

function p_view($view='', $data=[])
{ 
   return app()->view->partial_view($view, $data);
}

function user_icon()
{
   return '<a class="user_out" title="выход" href="'. base_url('/logout') .'">
            <img class="ico" src="'. base_url(POCKET_STYLE .'/favicon/user_out.png') .'"/>
           </a>';
}
function user_icon_product()
{
   return '<a class="user_out" title="выход" href="'. base_url('/category') .'">
            <img class="ico" src="'. base_url(POCKET_STYLE .'/favicon/user_out.png') .'"/>
           </a>';
}
function user_heart()
{
   return '<a href="'. base_url('/account') .'">
            <img class="ico" src="'. base_url(POCKET_STYLE .'/favicon/user_heart.png') .'"/>
           </a>';
}
function user_cart()
{
   return '<a href="'. base_url('/account') .'">
            <img class="ico" src="'. base_url(POCKET_STYLE .'/favicon/user_cart.png') .'"/>
           </a>';
}

function abort($code=404)
{
   response()->set_response_code($code);
   app()->view->resource_files("/errors/{$code}");
   exit();
}

function base_url($path = '/'): string
//function base_url($path = '/'): string
{
   //return rtrim(PATH, '/') .'/path';
   return PATH . $path;
}

function safe_put_json($relativePath, $data) {
   $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($relativePath, '/');
   $dir = dirname($fullPath);

   if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
      error_log("Ошибка: Не удалось создать директорию $dir");
      return false;
   }

   $tempFile = $fullPath . '.tmp';
   $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

   if (file_put_contents($tempFile, $jsonData) === false) {
      return false;
   }

   // Атомарная замена: если rename прошел, значит файл записан полностью
   if (!rename($tempFile, $fullPath)) {
      unlink($tempFile);
      return false;
   }

   touch($fullPath);
   return true;

}


function link_resource($rel, $href): string
{   
   return "<link rel='{$rel}' href='/public/assets{$href}'>";
}

function base_href($path=""): string
{
   if (app()->get('lang')['base'] != 1) {
      return PATH . '/' . app()->get('lang')['code'] . $path;
   }
   return PATH . $path;
}

function uri_without_lang(): string
{
   $request_uri = request()->uri;
   $request_uri = explode('/', $request_uri, 2);
   
   if (array_key_exists($request_uri[0], LANGS)) {
      unset($request_uri[0]);
   
   }
   $request_uri = implode('/', $request_uri);
   return $request_uri ? '/' . $request_uri : '';
}

function get_alerts(): void
{
   if (!empty($_SESSION['flash'])) {
      foreach($_SESSION['flash'] as $k => $v) {
         echo app()->view->partial_view("/alert/alert_{$k}",
            ["flash_{$k}" => session()->getFlash($k)]);
      }
   }
}

function get_errors($fieldname): string // имя поля
{
   $output = "";
   $errors = session()->get('form_errors');
   if (isset($errors[$fieldname])) {
      $output .= '<div class="invalid-feedback d-block">
                  <ul class="list-unstyled">';
      foreach ($errors[$fieldname] as $error) {
         $output .= "<li>$error</li>";
      }
      $output .= '</ul></div>';
      
   }
   return $output;
}

function get_validation_class($fieldname): string
{
   $errors = session()->get('form_errors');
   if (empty($errors)) {
      return "";
   
   }
   return isset($errors[$fieldname]) ? 'is-invalid' : 'is-valid';
}

function hsc($str): string
{
   return htmlspecialchars($str, ENT_QUOTES);
}

function old($fieldname): string
{
   if (isset(session()->get('form_data')[$fieldname])) {
      return hsc(session()->get('form_data')[$fieldname]);
   
   }
   return '';
}

function get_auth_token(): string
{
   return '<input type="hidden" name="auth_token" value="'. db()->set_token_23() .'">';
}

function get_csrf_field(): string
{
   return '<input type="hidden" name="csrf_token" value="'. session()->get('csrf_token') .'">';
}

function get_csrf_meta(): string
{
   return '<meta name="csrf-token" content="'. session()->get('csrf_token') .'">';
}

function check_csrf_token(): bool
{
   if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {

      return false;

   }
   return true;
}

function editor()
{
   return \Master\Administrator::edit();
}

function check_auth(): bool
{
   return \Master\Auth::isAuth();
}

function get_user()
{
   return \Master\Auth::user();
}

function logout()
{
    \Master\Auth::logout();
}

function _e($key): void
{
   echo \Master\Language::get($key);
}

function __($key): string
{
   return \Master\Language::get($key);
}

function send_mail(array $to, string $subject, string $tpl, array $data = [], array $attachments = []): bool
{
   $mail = new \PHPMailer(true);

   try {
      $mail->SMTPDebug = MAIL_SETTINGS['debug'];
      $mail->isSMTP();
      $mail->Host = MAIL_SETTINGS['host'];
      $mail->SMTPAuth = MAIL_SETTINGS['auth'];
      $mail->Username = MAIL_SETTINGS['username'];
      $mail->Password = MAIL_SETTINGS['password'];
      $mail->SMTPSecure = MAIL_SETTINGS['secure'];
      $mail->Port = MAIL_SETTINGS['port'];

      $mail->setFrom(MAIL_SETTINGS['from_email'], MAIL_SETTINGS['from_name']);
      foreach ($to as $email) {
         mail->addAddress($email);
      
      }
      if ($attachments) {
         foreach ($attachments as $attachment) {
            $mail->addAttachment($attachment);
         }
      }
      
      $mail->isHTML(MAIL_SETTINGS['is_html']);
      $mail->CharSet = MAIL_SETTINGS['charset'];
      $mail->Subject = $subject;
      $mail->Body = view($tpl, $data, false);

      return $mail->send();
   
   } catch (Exception $e) {
      error_log("[" . date('Y-m-d H:i:s') . "] Error: {$e->getMessage()}" . PHP_EOL .
                "File: {$e->getFile()}" . PHP_EOL . "Line: {$e->getLine()}" . PHP_EOL .
                '================' . PHP_EOL, 3, ERROR_LOGS);
      return false;
   }
}

function echo_($array) {
   echo '<pre>';
      print_r($array);
   echo '</pre>';
}
