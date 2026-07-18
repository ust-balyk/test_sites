// product_card.js
export function setProductCardWidth() {
    /* одиночные карточки */
    // Находим контейнер и первый элемент (эталон ширины)
    const container = document.querySelector('#category_content');
    const firstItem = container.firstElementChild;

    // Получаем ширину первого элемента (включая границы и отступы, если нужно)
    const width = (firstItem.offsetWidth+20);

    // Создаем два новых блока
    for (let i = 0; i < 3; i++) {
        const invisibleBlock = document.createElement('div');
    // Устанавливаем стили: ширина как у первого, невидимость и отсутствие влияния на поток
        invisibleBlock.style.width = `${width}px`;
        //invisibleBlock.style.visibility = 'hidden'; // Делает невидимым, но сохраняет место
        invisibleBlock.style.opacity = '0'; // Альтернативный вариант невидимости
    }
       
}


