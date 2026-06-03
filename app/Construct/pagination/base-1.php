<nav aria-label="Page navigation">
  <ul class="pagination">

    <?php if(!empty($first_page)): ?>
    <li class="page-item first" style="padding:0">
      <a class="page-link first" href="<?= $first_page; ?>" aria-label="First page">
        <span aria-hidden="true" style="font-size:2rem;color:#eba0a6!important">最初</span>
      </a>
    </li>
    <?php endif; ?>

    <?php if(!empty($pages_left)): ?>
      <?php foreach ($pages_left as $page_left): ?>
    <li class="page-item">
      <a class="page-link" href="<?= $page_left['link']; ?>">
        <?= $page_left['number']; ?>
      </a>
    </li>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if(!empty($current_page)): ?>
    <li class="page-item active"><a class="page-link"><?= $current_page; ?></a></li>
    <?php endif; ?> 

    <?php if(!empty($pages_right)): ?>
      <?php foreach ($pages_right as $page_right): ?>
    <li class="page-item">
      <a class="page-link" href="<?= $page_right['link']; ?>">
        <?= $page_right['number']; ?>
      </a>
    </li>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if(!empty($last_page)): ?>
    <li class="page-item last">
      <a class="page-link last" href="<?= $last_page; ?>" aria-label="Last page">
        <span aria-hidden="true" style="font-size:2rem;color:#eba0a6!important">最後</span>
      </a>
    </li>
    <?php endif; ?>

  </ul>
</nav>
