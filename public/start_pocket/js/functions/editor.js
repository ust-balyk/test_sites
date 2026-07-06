/**
 * editor.js
 * Модуль для управления редактированием товаров
 */
export default function initEditorProduct() {

  // ============================================
  // 1. ПОЛУЧЕНИЕ DOM-ЭЛЕМЕНТОВ
  // ============================================

  const toggleBtn = document.getElementById('toggle-edit-btn');
  const newBtn = document.getElementById('new-product-btn');
  const saveBtn = document.getElementById('save-all-btn');
  const formatTools = document.getElementById('format-tools');

  const categorySelect = document.getElementById('category-select');        // видимый селект
  const categorySlugInput = document.getElementById('admin-slug-category'); // hidden: slug категории (или текущей)

  const listBtn = document.querySelector('[data-cmd="insertUnorderedList"]');

  const fileInputId = 'admin-file-input-temp';

  const productRoot = document.querySelector('.product[data-slug]'); 

  // ============================================
  // 2. ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
  // ============================================

  const isEditableNow = () => document.querySelector('[contenteditable="true"]') !== null;

  function getSlug() {
    return productRoot?.dataset.slug?.trim() || syncCategorySlugInput();
  }

  function setEditable(state) {
    document.querySelectorAll('#edit-title, #edit-description, #edit-price').forEach(el => {
      el.contentEditable = state ? 'true' : 'false';
      el.classList.toggle('editable', state);
    });

    document.querySelectorAll('.editable-review-author, .editable-review-text').forEach(el => {
      el.contentEditable = state ? 'true' : 'false';
    });

    const imgContainer = document.getElementById('image-container');
    if (imgContainer) {
      imgContainer.classList.toggle('edit-image-hover', state);
      imgContainer.removeEventListener('click', onImageClick);
      if (state) imgContainer.addEventListener('click', onImageClick);
    }

    if (formatTools) formatTools.classList.toggle('d-none', !state);
  }
  
  // ============================================
  // 3. УПРАВЛЕНИЕ СОСТОЯНИЕМ TOOLBAR
  // ============================================

  let currentMode = 'neutral';

  function setToolbarState(state) {
    currentMode = state;
    console.log('Переход в режим:', state);

    if (state === 'neutral') {
      // Нейтральное состояние
      setEditable(false);
      newBtn?.classList.remove('d-none');
      toggleBtn?.classList.remove('d-none');

      if (categorySelect) categorySelect.classList.add('d-none');
      if (formatTools) formatTools.classList.add('d-none');
      if (listBtn) listBtn.classList.remove('d-none');

    } else if (state === 'add') {
      // Режим добавления товара
      setEditable(true);
      newBtn?.classList.remove('d-none');
      toggleBtn?.classList.add('d-none');

      if (categorySelect) categorySelect.classList.remove('d-none');
      if (formatTools) formatTools.classList.remove('d-none');
      if (listBtn) listBtn.classList.remove('d-none');

      // выставляем hidden согласно текущему выбору селекта
      syncCategorySlugInput();

    } else if (state === 'edit') {
      // Режим редактирования товара
      setEditable(true);
      newBtn?.classList.add('d-none');
      toggleBtn?.classList.remove('d-none');

      if (categorySelect) categorySelect.classList.add('d-none');
      if (formatTools) formatTools.classList.remove('d-none');
      if (listBtn) listBtn.classList.add('d-none');
    }
  }

  // ============================================
  // 4. ОБРАБОТЧИКИ КНОПОК
  // ============================================

  function toggleEditMode() {
    if (currentMode === 'neutral') {
      setToolbarState('edit');
    } else if (currentMode === 'edit') {
      setToolbarState('neutral');
    }
  }

  function toggleAddMode() {
    if (currentMode === 'neutral') {
      setToolbarState('add');
    } else if (currentMode === 'add') {
      setToolbarState('neutral');
    }
  }

  function prepareNewProduct() {
    if (currentMode === 'neutral') {
      toggleAddMode();
      clearNewProductForm();
    } else if (currentMode === 'add') {
      toggleAddMode();
      return;
    }
  }

  // ============================================
  // 5. КАТЕГОРИИ: режим "текущая" / "выбрать из списка"
  // ============================================

  /**
   * Синхронизирует значение slug категории между видимым селектом и скрытым полем ввода.
   *
   * Функция выполняет следующие действия:
   * 1. Проверяет наличие необходимых DOM-элементов (селект категории и скрытое поле для slug)
   * 2. Получает текущее значение из селекта (либо 'current', либо конкретный slug категории)
   * 3. В зависимости от выбранного режима:
   *    - Если выбран режим 'current':
   *      * Берет slug текущего продукта из data-атрибута элемента productRoot
   *      * Устанавливает это значение в скрытое поле categorySlugInput
   *      * Выводит сообщение в консоль о текущей категории
   *    - Если выбран конкретный slug категории:
   *      * Устанавливает выбранный slug напрямую в скрытое поле categorySlugInput
   *      * Выводит сообщение в консоль о выбранной категории
   *
   * Эта функция вызывается:
   * - При инициализации страницы (в конце модуля)
   * - При изменении выбора в селекте (через обработчик события 'change')
   * - При переключении в режим добавления товара
   *
   * Важно: функция обеспечивает синхронизацию между UI (видимый селект) и внутренним представлением (скрытое поле),
   * что необходимо для корректной работы механизма сохранения данных.
   */
  function syncCategorySlugInput() {
    // Проверка наличия необходимых элементов в DOM
    if (!categorySelect || !categorySlugInput) return;

    // Получение текущего значения из селекта:
    // - 'current' - использовать текущую категорию продукта
    // - конкретный slug - использовать выбранную категорию
    const modeOrSlug = categorySelect.value;

    if (modeOrSlug === 'current') {
        // Режим 'current' - берем slug из data-атрибута текущего продукта
        const currentSlug = productRoot?.dataset.slug?.trim() || '';
        // Устанавливаем slug текущего продукта в скрытое поле
        categorySlugInput.value = currentSlug;
        console.log('✓ Категория установлена:', currentSlug);
    } else {
        // Режим конкретной категории - используем выбранный slug напрямую
        categorySlugInput.value = modeOrSlug;
        console.log('✓ Выбрана категория:', modeOrSlug);
    }
  }
  
  // Очистка формы для нового товара
  function clearNewProductForm() {
    const title = document.getElementById('edit-title');
    const desc = document.getElementById('edit-description');
    const price = document.getElementById('edit-price');
    const img = document.getElementById('main-product-image');

    if (title) title.innerText = 'название..';
    if (desc) desc.innerHTML = '<p>описание..</p>';
    if (price) price.innerText = 'цена по запросу';
    if (img) img.src = '/images/onerror.webp';

    // Устанавливаем селект в режим "Текущая категория"
    if (categorySelect) {
      categorySelect.value = 'current';
      //categorySelect.value = categorySelect.querySelector('option[value="current"]')?.value || 'current';
      syncCategorySlugInput(); // Синхронизируем скрытое поле
    }
  }


  // обработчик выбора категории
  if (categorySelect) {
    categorySelect.addEventListener('change', () => {
      syncCategorySlugInput();
      console.log('categorySelect.value =', categorySelect.value, 'hidden slug =', categorySlugInput?.value);
    });
  }

  // ============================================
  // 6. УПРАВЛЕНИЕ ИЗОБРАЖЕНИЕМ
  // ============================================

  function onImageClick() {
    let fi = document.getElementById(fileInputId);
    if (!fi) {
      fi = document.createElement('input');
      fi.type = 'file';
      fi.accept = 'image/*';
      fi.id = fileInputId;
      fi.style.display = 'none';
      document.body.appendChild(fi);
      fi.addEventListener('change', onFileChange);
    }
    fi.click();
  }

  function onFileChange(e) {
    const f = e.target.files?.[0];
    if (!f) return;

    const reader = new FileReader();

    reader.addEventListener('load', ev => {
      const img = document.getElementById('main-product-image');
      if (img) img.src = ev.target.result;
    });

    reader.readAsDataURL(f);
  }

  // ============================================
  // 7. ФОРМАТИРОВАНИЕ ТЕКСТА
  // ============================================

  function format(cmd) {
    if (!isEditableNow()) return;
    document.execCommand(cmd, false, null);
  }

  // ============================================
  // 8. СОХРАНЕНИЕ ДАННЫХ
  // ============================================

  async function saveAllChanges() {
    console.log('🔍 Начинаем сохранение...');
    console.log('currentMode:', currentMode);
    console.log('window.location.origin:', window.location.origin);

    const idInput = document.getElementById('admin-product-id');
    // обнуляем 'id' если режим 'add'
    const id = currentMode === 'edit' ? idInput?.value : '';

    const titleEl = document.getElementById('edit-title');
    const priceEl = document.getElementById('edit-price');
    const descEl = document.getElementById('edit-description');
    if (!titleEl || !priceEl || !descEl) {
        alert('Поля не найдены.');
        return;
    }

    console.log('✓ Elements найдены');
    console.log('  title:', titleEl.innerText.trim());
    console.log('  price:', priceEl.innerText.trim());
    console.log('  description length:', descEl.innerHTML.trim().length);

    const fd = new FormData();
    fd.append('mode', currentMode); // 'edit' или 'add'
    fd.append('outer_id', id);
    fd.append('title', titleEl.innerText.trim());
    fd.append('price', priceEl.innerText.trim());
    fd.append('description', descEl.innerHTML.trim());

    console.log('✓ FormData базовые данные добавлены');

    if (currentMode === 'add' && categorySlugInput) {
        syncCategorySlugInput();
        fd.append('slug', categorySlugInput.value);
        console.log('✓ Категория добавлена:', categorySlugInput.value);
    }

    const file = document.getElementById(fileInputId);
    if (file?.files?.[0]) {
        fd.append('image', file.files[0]);
        console.log('✓ Файл изображения добавлен');
    }

    console.log('🔍 Начинаем собирать reviews...');
    try {
        const reviewElements = document.querySelectorAll('#reviews-list .review');
        console.log('  Found review elements:', reviewElements.length);

        const reviews = [];
        reviewElements.forEach((r, idx) => {
            try {
                const author = (r.querySelector('.card-title')?.innerText || '').trim();
                const date = (r.querySelector('.text-muted')?.innerText || '').trim();
                const rating = (r.querySelector('.star-rating')?.innerText || '').trim();
                const text = (r.querySelector('.card-text')?.innerText || '').trim();

                console.log(`  Review ${idx}:`, { author, date, rating, text });

                reviews.push({ author, date, rating, text });
            } catch (e) {
                console.warn(`  ⚠️ Ошибка при обработке review ${idx}:`, e);
            }
        });

        console.log('✓ Reviews собраны:', reviews.length);
        const reviewsJson = JSON.stringify(reviews);
        console.log('✓ Reviews сериализованы, длина:', reviewsJson.length);
        
        fd.append('reviews', reviewsJson);
        console.log('✓ Reviews добавлены в FormData');
    } catch (err) {
        console.error('❌ Ошибка при обработке reviews:', err);
    }

    console.log('🚀 Отправляем запрос...');

    try {
        const resp = await fetch('/editor', { 
            method: 'POST', 
            body: fd, 
            credentials: 'same-origin' 
        });
        
        console.log('✓ Ответ получен, статус:', resp.status);

        const txt = await resp.text();
        console.log('✓ Текст ответа получен, длина:', txt.length);

        if (!resp.ok) {
            alert('Ошибка сохранения: ' + resp.status + ' ' + txt);
            return;
        }

        let res;
        try { 
            res = JSON.parse(txt);
            console.log('✓ JSON распарсен:', res);
        } catch { 
            alert('Неверный ответ от сервера.'); 
            return; 
        }

        if (res?.success) {
            alert('Сохранено');
            if (res.redirect) location.href = res.redirect;
            else location.reload();
        } else {
            alert('Сохранение не удалось: ' + (res?.error || 'неизвестная ошибка'));
        }
    } catch (err) {
        console.error('❌ Ошибка fetch:', err);
        alert('Ошибка: ' + (err?.message || err));
    }
  }


  // ============================================
  // 9. ПРИСОЕДИНЕНИЕ ОБРАБОТЧИКОВ СОБЫТИЙ
  // ============================================

  toggleBtn?.addEventListener('click', toggleEditMode);
  newBtn?.addEventListener('click', prepareNewProduct);
  saveBtn?.addEventListener('click', saveAllChanges);

  document.addEventListener('click', e => {
    const cmd = e.target.closest('[data-cmd]');
    if (cmd) format(cmd.getAttribute('data-cmd'));
  });

  // начальная синхронизация hidden, если страница стартует сразу в add/или селект уже заполнен
  syncCategorySlugInput();
}

