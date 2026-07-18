export function initAchievement() {

  let achievementItems = $('.achievement-item');

  if (achievementItems.length) {
    const baseDelay = 400;
    const step = 200;

    // Порядок: последний (2), средний (1), первый (0)
    let appearanceOrder = [achievementItems.length - 1, 1, 0];

    appearanceOrder.forEach(function(itemIndex, orderIndex) {
      let $item = $(achievementItems[itemIndex]);
      if (!$item.length) return;

      setTimeout(function() {
      // Просто делаем блок видимым.
      // Финальное число уже есть в HTML, оно просто отобразится вместе с блоком.
        $item.addClass('visible');
      }, baseDelay + (orderIndex * step));
    });
  }

}
