<nav aria-label="Page navigation example">
  <ul class="pagination">

    <?php if(!empty($pages_left)): ?>
      <?php foreach ($pages_left as $page_left): ?>
    <li class="page-item">
      <a class="page-link" href="<?= $page_left['link']; ?>">
        <?= $page_left['number']; ?>
      </a>
    </li>
      <?php endforeach; ?>
    <?php endif; ?>

    <li class="page-item active"><a class="page-link"><?= $current_page; ?></a></li>

    <?php if(!empty($pages_right)): ?>
      <?php foreach ($pages_right as $page_right): ?>
    <li class="page-item">
      <a class="page-link" href="<?= $page_right['link']; ?>">
        <?= $page_right['number']; ?>
      </a>
    </li>
      <?php endforeach; ?>
    <?php endif; ?>

  </ul>
</nav>
