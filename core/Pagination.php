<?php
namespace Master;

class Pagination
{
   protected string $uriPage;
   //protected int $totalRecords;
   protected int $countPages;
   protected int $currentPage;

   public function __construct(

      protected int $totalRecords    = 0,
      protected int $onPageRecords   = PAGINATION_SETTINGS['onPageRecords'],
      protected int $requestInterval = PAGINATION_SETTINGS['requestInterval'],
      protected int $startPaging     = PAGINATION_SETTINGS['startPaging'],
      protected string $template     = PAGINATION_SETTINGS['template'],

   )
   {
      $this->uriPage         = Application::$app->request->getRequestParams();
      $this->totalRecords    = $this->getTotalRecords(TABLE_NAME);   
      $this->countPages      = $this->getCountPages();
      $this->currentPage     = $this->getCurrentPage();
      $this->requestInterval = $this->getRequestInterval();
   }


   protected function getTotalRecords($tbl): ?int
   {
      $slug = Application::$app->request->getPath($this->uriPage);
      
      $total_records = Application::$app->cache->countCache_ByCategory(basename($slug)) ??
         Application::$app->db->countDB_ByCategory(TABLE_NAME, basename($slug));

      if ($total_records == null) {
         $total_records = Application::$app->cache->countCache_ById() ??
            Application::$app->db->getColumn(TABLE_NAME);
      }
      return $total_records;
   
   }


   protected function getCountPages()
   {
      return (int)ceil($this->totalRecords / $this->onPageRecords) ?: 1; 
         //Application::$app->abort->error(500);

   }


   protected function getCurrentPage()
   {
      $page = (int)Application::$app->request->get('page', 1);
      
      if ($page < 1 || $page > $this->countPages) {
         Application::$app->abort->error();
         
      }
      return $page;

   }


   protected function getRequestInterval(): int
   {
      return ($this->countPages <= $this->startPaging) ? $this->countPages : $this->requestInterval;
      // startPaging -> количество страниц с которых начинается пагинация

   }


   public function getOffset(): int
   {
      return  ($this->currentPage - 1) * $this->onPageRecords;   

   }


   public function getPageNumber()
   {
      $first_page   = '';
      $back         = '';
      $pages_left   = [];
      //$current_page = $this->currentPage;
      $current_page = '';
      $pages_right  = [];
      $go           = '';
      $last_page    = '';

      $current_page = ($this->countPages > 1) ? $this->currentPage : '';

      if ($this->currentPage > $this->requestInterval +1) {
         $first_page = $this->getLink(1);      
      }
      
      if ($this->currentPage > 1) {
         $back = $this->getLink($this->currentPage -1);
      }

      for ($i = $this->requestInterval; $i > 0; $i--) {
         if ($this->currentPage -$i > 0) {
            $pages_left[] = [
               'link'   => $this->getLink($this->currentPage -$i),
               'number' => $this->currentPage -$i,
            ];
         }
      }

      for ($i = 1; $i <= $this->requestInterval; $i++) {
         if ($this->currentPage +$i <= $this->countPages) {
            $pages_right[] = [
               'link'   => $this->getLink($this->currentPage +$i),
               'number' => $this->currentPage +$i,
            ];
         }
      }

      if ($this->currentPage < $this->countPages) {
         $go = $this->getLink($this->currentPage +1);
      }
         
      if ($this->currentPage < ($this->countPages - $this->requestInterval)) {
         $last_page = $this->getLink($this->countPages);
      }
      
      return Application::$app->view->partial_view($this->template,
        compact('first_page','back','pages_left','current_page','go','pages_right','last_page'));   

   }

   
   protected function getLink($page): string
   {
      /*
      if ($page === '1') { // удаляем ?page=1
         return rtrim($this->uriPage, '?&');
      }
      return str_contains($this->uriPage, '?') 
         ? "{$this->uriPage}&page={$page}" 
         : "{$this->uriPage}?page={$page}";
      */
      
      $url = $this->uriPage;
      // отделить path и query
      $parts = parse_url($url);
      $query = [];
      if (isset($parts['query'])) parse_str($parts['query'], $query);

      $page = (int)$page;
      if ($page == 1) {
         unset($query['page']);
      } else {
         $query['page'] = $page;
      }

      $new_query = http_build_query($query);
      $base = $parts['path'] ?? $url; // для относительных строк без схемы/хоста (вида "?a=1" и т.п.)
      return $new_query === '' ? $base : $base .'?'. $new_query;

   }


   public function __toString(): string
   {
      return $this->getPageNumber();

   }


}
