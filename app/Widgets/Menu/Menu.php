<?php
namespace App\Widgets\Menu;

class Menu
{
    protected array  $data;
    protected array  $tree;
    protected string $menu_html;

    protected $table      = MENU_TABLE;
    protected $template   = MENU_TEMPLATE;
    protected $cache_time = MENU_CACHE_TIME;
    protected $cache_key  = MENU_CACHE_KEY;
    protected $container  = 'div'; //'ul';
    protected $class      = 'container dropdown-menu megamenu'; //'menu';
    protected $attrs      = []; // [' role' => 'menu', ' id' => 'menu'];
    protected $prepend    = '<div class="row g-3">'. PHP_EOL;
    protected $append     = '</div';

    public function __construct(array $options = [])
    {
        $this->get_options($options);
        $this->template = __DIR__ . "/{$this->template}.php";
        
        $this->run();
    
    }

    protected function run()
    {
        $menu_html = cache()->get($this->cache_key);
        $this->data = db()->query("select * from {$this->table}")->getAssoc("id");
        $this->tree = $this->get_tree();
        $this->menu_html = $this->get_menu_html($this->tree);
        
        $this->output();
        return $this;
        
    }

    protected function output()
    {
        $attrs = '';
        if (! empty($this->attrs)) { // если задаёи атрибуты
            foreach ($this->attrs as $k => $v) {
                $attrs .= "$k=\"$v\"";
            }
        }
        $menu_html = '';
        if ($this->container) { // если открываем контейнер
            $menu_html .= "<{$this->container} class=\"{$this->class}\"$attrs>";
        }
        $menu_html .= $this->prepend; // если есть ещё обёртка типа row
        $menu_html .= $this->menu_html;
        $menu_html .= $this->append;
        if ($this->container) { // закрываем контейнер
            $menu_html .= "</{$this->container}>";
        }
        cache()->set($this->cache_key, $menu_html, $this->cache_time);
        echo $menu_html;
    
    }


    protected function get_options($options)
    {
        foreach ($options as $key => $value) {

            if (property_exists($this, $key)) {
                $this->$key = $value;
            
            }
        }
    
    }

    
    protected function get_tree(): array
    {
        $tree = [];
        $tbl  = $this->table;
        $data = $this->data;

        foreach ($data as $id => &$node) {

            if (!$node['parent_id']) {
                $tree[$id] = &$node;

            } else {
                $data[$node['parent_id']]['children'][$id] = &$node;

            }
        }
        return $tree;
    
    }

    protected function get_menu_html($tree, $tab = '')
    {
        $str = '';
        foreach ($tree as $id => $item) {
            $str .= $this->cat_to_template($item, $tab, $id);
        
        }
        return $str;
    
    }

    protected function cat_to_template($item, $tab, $id)
    {
        ob_start();
        require $this->template;
        return ob_get_clean();
    
    }


}
