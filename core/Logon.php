<?php
namespace Master;
use Valitron\Validator;

class Logon
{
   # выбранная таблица
   protected string $table;
   
   # время создания/изменения записи
   public bool $timestamps = true;
   
   # требуемые для валидации поля
   protected array $loaded = [];
   
   # массив для указания необходимых данных
   protected array $fillable = [];   
   
   # временный массив
   public array $attributes = [];
   
   # правила проверки валидности полей
   protected array $rules = [];
   
   # массив правок для меток
   protected array $labels = [];
   
   # для полученых ошибок
   protected array $errors = [];

   
   public function loadData(): void
   {
      # получаем все поля формы
      $data = Application::$app->request->getData();
      # сканируем $data на совпадения с ключами loaded
      foreach ($this->loaded as $field) {
         # если в полученых полях есть совпадения по имени ключа
         if (isset($data[$field])) {
            $this->attributes[$field] = $data[$field];
          
         } else {
            $this->attributes[$field] = '';

         }
      }
   }

   public function validate($data = [], $rules = [], $labels = []): bool
   {
      if (!$data) {
         $data = $this->attributes;

      }         
      if (!$rules) {
         $rules = $this->rules;

      }
      if (!$labels) {
         $labels = $this->labels;
      
      }
      # новый пусть к языковому пакету
      Validator::langDir(WWW . '/lang');
      Validator::lang('ru');
      //$validator = new Valitron\Validator($data);
      $validator = new Validator($data);
      $validator->rules($rules);
      $validator->labels($labels);

      if ($validator->validate()) {
         return true;

      } else {
         $this->errors = $validator->errors();
         return false;

      }
   
   }

   public function save()
   {
      $attributes = $this->attributes;
      
      # оставляем поля необходимые базе данных
      foreach ($attributes as $k => $v) {
         if (!in_array($k, $this->fillable)) {
            unset($attributes[$k]);
      
         }
      }
         
      $fields = array_keys($attributes);
      $field_names = array_map(fn($field_names) => "`{$field_names}`", $fields);
      $field_names = implode(',', $field_names);
      
      $placeholders = array_map(fn($field_values) => ":{$field_values}", $fields);
      $placeholders = implode(',', $placeholders);
      
      if ($this->timestamps) {
         $attributes['created_at'] = date("Y-m-d"); // H:i:s");
         $attributes['updated_at'] = date("Y-m-d"); // H:i:s");
         $field_names .= ', `created_at`, `updated_at`';
         $placeholders .= ', :created_at, :updated_at';
      
      }
      
      $query = "insert into {$this->table} ($field_names) values ($placeholders)";
         
      Application::$app->db->query($query, $attributes);
      
      return true; //return db()->getInsertId();


   }

   public function getErrors(): array
   {
      return $this->errors;

   }

}
