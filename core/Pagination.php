<?php
namespace Master;

class Pagination
{
   protected string $uriPage;
   protected int $totalRecords;
   protected int $countPages;
   protected int $currentPage;

   public function __construct(
      
      protected int $linesOnPage     = PAGINATION_SETTINGS['linesOnPage'],
      protected int $requestInterval = PAGINATION_SETTINGS['requestInterval'],
      protected int $startPaging     = PAGINATION_SETTINGS['startPaging'],
      protected string $template     = PAGINATION_SETTINGS['template'],

   )
   {
      $this->uriPage         = Application::$app->request->getRequestParams();
      $this->totalRecords    = $this->getTotalRecords('users');   
      $this->countPages      = $this->getCountPages();
      $this->currentPage     = $this->getCurrentPage();
      $this->requestInterval = $this->getRequestInterval();
   }

   protected function getTotalRecords($tbl): int
   {
      if (PAGINATION_SETTINGS['totalRecords'] > 0) {
         return PAGINATION_SETTINGS['totalRecords'];

      }
      return Application::$app->db->getColumn($tbl);
   
   }
   
   protected function getCountPages()
   {
      return (int)ceil($this->totalRecords / $this->linesOnPage) ?:
         Application::$app->abort->error(500);

   }

   protected function getCurrentPage()
   {
      $page = (int)Application::$app->request->get('page', 1);
      
      if ($page < 1 || $page > $this->countPages) {
         Application::$app->abort->error();
         //Application::$app->response->redirect();
         
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
      return  ($this->currentPage - 1) * $this->linesOnPage;   

   }

   public function getPageNumber()
   {
      $first_page   = '';
      $back         = '';
      $pages_left   = [];
      $current_page = $this->currentPage;
      $pages_right  = [];
      $go           = '';
      $last_page    = '';
      
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
      return str_contains($this->uriPage, '?') ?
         "{$this->uriPage}&page={$page}" : "{$this->uriPage}?page={$page}";

   }

   public function __toString(): string
   {
      return $this->getPageNumber();

   }


}
