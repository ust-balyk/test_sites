/**
 * editor.js
 * Редактор товара для modes: neutral / add / edit.
 */
export default function initEditorProduct() {
  // ============================================
  // 1) Получаем нужные элементы DOM
  // ============================================

  const toggleBtn = document.getElementById('toggle-edit-btn');     // Кнопка “Вкл/выкл редактирование”
  const newBtn = document.getElementById('new-product-btn');         // Кнопка “Новый”
  const saveBtn = document.getElementById('save-all-btn');           // Кнопка “Сохранить всё”
  const formatTools = document.getElementById('format-tools');       // Панель форматирования

  const categorySelect = document.getElementById('category-select'); // Выпадающий список категорий (в режиме add)
  const categorySlugInput = document.getElementById('admin-slug-category'); // Input slug для формы add

  const productRoot = document.querySelector('.product[data-slug]'); // корневой элемент товара (нужен текущий slug)
  const idInput = document.getElementById('admin-product-id');       // outer_id (существует только для edit)

  const deleteBtn = document.getElementById('delete-product-btn');   // Кнопка “Удалить продукт” (видимость по условиям)

  // ============================================
  // 2) Вспомогательные функции управления
  // ============================================

  // Проверяем: включён ли сейчас контент-editable где-то на странице
  const isEditableNow = () => document.querySelector('[contenteditable="true"]') !== null;

  // Текущий slug товара (берём из dataset)
  function getSlug() {
    return productRoot?.dataset.slug?.trim() ?? '';
  }

  // Включает/выключает редактирование контента
  function setEditable(state) {
    // Title / Description / Price
    document.querySelectorAll('#edit-title, #edit-description, #edit-price').forEach(el => {
      el.contentEditable = state ? 'true' : 'false';        // contenteditable принимает строку
      el.classList.toggle('editable', state);              // добавляем/убираем класс для стилизации
    });

    // Авторы и текст отзывов
    document.querySelectorAll('.editable-review-author, .editable-review-text').forEach(el => {
      el.contentEditable = state ? 'true' : 'false';
    });

    // Блок изображения: включаем “перетаскивание/клик” только в edit/add
    const imgContainer = document.getElementById('image-container');
    if (imgContainer) {
      imgContainer.classList.toggle('edit-image-hover', state);

      // Снимаем обработчик, потом при необходимости ставим
      imgContainer.removeEventListener('click', onImageClick);
      if (state) imgContainer.addEventListener('click', onImageClick);
    }

    // Панель форматирования скрываем/показываем вместе с редактированием
    if (formatTools) formatTools.classList.toggle('d-none', !state);
  }

  // ============================================
  // delete
  // ============================================

  let deleteHandlerBound = false;

  function setupDeleteHandler() {
    if (deleteHandlerBound) return;
    deleteHandlerBound = true;

    if (!deleteBtn) return;

    deleteBtn.addEventListener('click', async () => {
      if (currentMode !== 'edit') return;

      const outerId = idInput?.value?.trim();
      if (!outerId) {
        alert('Не найден outer_id для удаления.');
        return;
      }

      const ok = confirm('ПОДТВЕРДИТЬ УДАЛЕНИЕ ПРОДУКТА?');
      if (!ok) return;

      const fd = new FormData();
      fd.append('mode', 'delete');
      fd.append('outer_id', outerId);
      fd.append('slug', getSlug());

      try {
        const resp = await fetch('/editor', {
          method: 'POST',
          body: fd,
          credentials: 'same-origin'
        });

        if (!resp.ok) throw new Error(`Ошибка удаления: ${resp.status}`);

        const res = await resp.json();
        if (res?.success) location.reload();
        else alert(res?.error || 'Удаление не удалось');
      } catch (err) {
        console.error('❌ Ошибка:', err);
        alert('Ошибка: ' + err.message);
      }
    });
  }
  // вызови один раз после того как заполнены idInput/deleteBtn
  setupDeleteHandler();  

  // ============================================
  // 3) Управление состоянием toolbar (modes)
  // ============================================

  // currentMode определяет режим интерфейса:
  // - neutral: всё выключено
  // - add: создание нового товара (slug меняется)
  // - edit: редактирование существующего товара
  let currentMode = 'neutral';

  function setToolbarState(state) {
    currentMode = state;
    console.log('Переход в режим:', state);

    // outer_id (id) доступен только в режиме edit (обычно)
    const hasOuterId = !!idInput?.value?.trim();

    // Ключевое требование: показываем кнопку удаления ТОЛЬКО в edit и ТОЛЬКО если есть outer_id
    if (deleteBtn) {
      deleteBtn.classList.toggle('d-none', !(state === 'edit' && hasOuterId));
    }

    // Дальше — твоя логика видимости/редактирования
    if (state === 'neutral') {
      // Никакого редактирования
      setEditable(false);

      // Показываем кнопки “new” и “toggle edit”
      newBtn?.classList.remove('d-none');
      toggleBtn?.classList.remove('d-none');

      // В add нам нужен categorySelect и formatTools — в neutral скрываем
      if (categorySelect) categorySelect.classList.add('d-none');
      if (formatTools) formatTools.classList.add('d-none');

    } else if (state === 'add') {
      // В add включаем редактирование
      setEditable(true);

      // “New” кнопка показывается
      newBtn?.classList.remove('d-none');

      // “Toggle edit” скрываем/меняем
      toggleBtn?.classList.add('d-none');

      // Категория должна быть выбрана — показываем
      if (categorySelect) categorySelect.classList.remove('d-none');

      // Панель форматирования показываем
      if (formatTools) formatTools.classList.remove('d-none');

      // Подставляем slug в input (если выбрана current)
      syncCategorySlugInput();

    } else if (state === 'edit') {
      // В edit включаем редактирование
      setEditable(true);

      // newBtn скрываем, toggleBtn показываем
      newBtn?.classList.add('d-none');
      toggleBtn?.classList.remove('d-none');

      // В edit текущую категорию выбирать не нужно
      if (categorySelect) categorySelect.classList.add('d-none');

      // Форматирование показываем
      if (formatTools) formatTools.classList.remove('d-none');
    }
  }

  // ============================================
  // 4) Кнопки режима
  // ============================================

  function toggleEditMode() {
    if (currentMode === 'neutral') setToolbarState('edit');
    else if (currentMode === 'edit') setToolbarState('neutral');
  }

  function toggleAddMode() {
    if (currentMode === 'neutral') setToolbarState('add');
    else if (currentMode === 'add') setToolbarState('neutral');
  }

  function prepareNewProduct() {
    // Если мы в neutral — включаем add и очищаем форму
    if (currentMode === 'neutral') {
      toggleAddMode();
      clearNewProductForm();

    // Если мы уже в add — просто переключать смысла нет
    } else if (currentMode === 'add') {
      toggleAddMode();
    }
  }

  // ============================================
  // 5) Категории (slug для формы add)
  // ============================================

  function syncCategorySlugInput() {
    if (!categorySelect || !categorySlugInput) return;

    const modeOrSlug = categorySelect.value;

    // “current” — подставляем slug текущего товара
    if (modeOrSlug === 'current') {
      const currentSlug = productRoot?.dataset.slug?.trim() ?? '';
      categorySlugInput.value = currentSlug;
      console.log('✓ Категория установлена:', currentSlug);

    } else {
      // иначе берём прямо из выбора
      categorySlugInput.value = modeOrSlug;
      console.log('✓ Выбрана категория:', modeOrSlug);
    }
  }

  function clearNewProductForm() {
    // В нейтрал->add очищаем поля для создания нового товара

    const title = document.getElementById('edit-title');
    const desc = document.getElementById('edit-description');
    const price = document.getElementById('edit-price');
    const img = document.getElementById('main-product-image');

    if (title) title.innerText = 'название..';
    if (desc) desc.innerHTML = '<p>описание..</p>';
    if (price) price.innerText = 'цена по запросу';
    if (img) img.src = '/images/onerror.webp';

    // Ставим “current” для категории
    if (categorySelect) {
      categorySelect.value = 'current';
      syncCategorySlugInput();
    }
  }

  if (categorySelect) {
    categorySelect.addEventListener('change', syncCategorySlugInput);
  }

  // ============================================
  // 6) Управление изображением (клик по контейнеру -> file input)
  // ============================================

  function onImageClick() {
    // Создаём инпут файла один раз (lazy init)
    let fi = document.getElementById('admin-file-input-temp');

    if (!fi) {
      fi = document.createElement('input');
      fi.type = 'file';
      fi.accept = 'image/*';
      fi.id = 'admin-file-input-temp';
      fi.style.display = 'none';

      document.body.appendChild(fi);

      fi.addEventListener('change', onFileChange);
    }

    // Триггерим диалог выбора файла
    fi.click();
  }

  function onFileChange(e) {
    // Берём первую выбранную картинку
    const f = e.target.files?.[0];
    if (!f) return;

    // Загружаем в preview через FileReader
    const reader = new FileReader();
    reader.addEventListener('load', ev => {
      const img = document.getElementById('main-product-image');
      if (img) img.src = ev.target.result;
    });

    reader.readAsDataURL(f);
  }

  // ============================================
  // 7) Форматирование текста в contenteditable
  // ============================================

  function format(cmd, options = {}) {
    // Не работаем, если контент сейчас не редактируется
    if (!isEditableNow()) return;

    const selection = window.getSelection();
    if (!selection.rangeCount) return;

    const range = selection.getRangeAt(0);

    // Для цвет/фон ты используешь span-обёртки, которые требуют выделения
    const isColorCmd = cmd === 'textColor' || cmd === 'highlight';
    const selectedText = range.toString();

    // Логика “если выделения нет — выходим”:
    // - br можно вставлять и без выделения
    // - для цветов/фона — сейчас тоже требуется выделение
    if (cmd !== 'br' && !isColorCmd && !selectedText.trim()) return;

    // Применяем команду
    switch (cmd) {
      case 'strong':
        applyFormatting(range, 'strong');
        break;
      case 'italic':
        applyFormatting(range, 'em');
        break;
      case 'underline':
        applyFormatting(range, 'u');
        break;
      case 'strikethrough':
        applyFormatting(range, 's');
        break;

      case 'highlight':
        applyFormatting(range, 'span', { backgroundColor: options.color || '#ffff00' });
        break;

      case 'textColor':
        applyFormatting(range, 'span', { color: options.color || '#000000' });
        break;

      case 'h3':
        wrapInHeading(range, 'h3');
        break;
      case 'h4':
        wrapInHeading(range, 'h4');
        break;
      case 'h5':
        wrapInHeading(range, 'h5');
        break;

      case 'br':
        insertBr(range);
        break;

      case 'unorderedList':
        createList(range, options.marker || 'square', options.color || '#000000');
        break;

      case 'link':
        createLink(range, options.url || 'https://satomi-japan.com');
        break;

      case 'clear':
        clearFormatting(range);
        break;

      default:
        return;
    }
  }

  // Оборачиваем выделенный диапазон в тег wrapper и применяем inline style
  function applyFormatting(range, tagName, styles = {}) {
    const wrapper = document.createElement(tagName);
    Object.assign(wrapper.style, styles);

    // Вырезаем содержимое выделения из DOM
    const contents = range.extractContents();

    // Кладём это внутрь wrapper
    wrapper.appendChild(contents);

    // Вставляем wrapper в DOM на место range
    range.insertNode(wrapper);

    // После вставки “схлопываем” range внутрь wrapper,
    // чтобы курсор/выделение не “разъехались”
    range.setStart(wrapper, 0);
    range.setEnd(wrapper, wrapper.childNodes.length);
  }

  function wrapInHeading(range, headingTag) {
    const heading = document.createElement(headingTag);

    // Тут ты вставляешь именно текст, без вложенной разметки
    heading.textContent = range.toString();

    // Удаляем выделение и вставляем заголовок
    range.deleteContents();
    range.insertNode(heading);
  }

  function createList(range, listStyleType, markerColor) {
    const text = range.toString();
    if (!text.trim()) return;

    const ul = document.createElement('ul');
    ul.style.listStyleType = listStyleType;
    if (markerColor) ul.style.color = markerColor;

    const li = document.createElement('li');
    li.textContent = text;

    ul.appendChild(li);

    range.deleteContents();
    range.insertNode(ul);
  }

  function createLink(range, url) {
    const link = document.createElement('a');
    link.href = url;
    link.textContent = range.toString();
    link.target = '_blank';
    link.rel = 'noopener noreferrer';

    range.deleteContents();
    range.insertNode(link);
  }

  // Вставка <br> перед/в позицию курсора
  function insertBr(range) {
    const br = document.createElement('br');

    // collapse(true) переносит range в начало (для cursor это просто “точка вставки”)
    range.collapse(true);
    range.insertNode(br);

    // Ставим курсор после br
    range.setStartAfter(br);
    range.setEndAfter(br);

    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);
  }

  // Удаление форматирования только внутри #edit-description
  function clearFormatting(range) {
    const startEl =
      range.startContainer.nodeType === Node.ELEMENT_NODE
        ? range.startContainer
        : range.startContainer.parentElement;

    const root = startEl?.closest('#edit-description');
    if (!root) return;

    // Список элементов, которые считаем “форматированием”
    const selector = [
      'strong, em, u, s, b, code, h3, h4, h5, ul, li, a, blockquote',
      'span[style*="background-color"]',
      'span[style*="color"]',
      'br'
    ].join(',');

    // Берём только те элементы, которые пересекаются с range
    const elements = Array.from(root.querySelectorAll(selector))
      .filter(el => range.intersectsNode(el));

    // Удаляем/раскрываем (unwrap) снизу вверх (чтобы DOM не ломался)
    elements.sort((a, b) =>
      (b.compareDocumentPosition(a) & Node.DOCUMENT_POSITION_FOLLOWING ? 1 : -1)
    );

    elements.forEach(el => {
      const parent = el.parentNode;
      if (!parent) return;

      // Переносим детей el внутрь parent перед удалением el
      while (el.firstChild) parent.insertBefore(el.firstChild, el);
      parent.removeChild(el);
    });
  }

  // Санация HTML перед отправкой на сервер
  function sanitizeDescription(html) {
    const doc = new DOMParser().parseFromString(`<div>${html}</div>`, 'text/html');
    const root = doc.body.firstElementChild;

    // Удаляем пустые span
    root.querySelectorAll('span').forEach(span => {
      const isEmpty = span.textContent.trim() === '' && span.children.length === 0;

      // hasStyle — “на всякий случай”: если span имеет атрибут style, мы его не трогаем
      const hasStyle = span.hasAttribute('style') || span.attributes.length > 0;

      if (isEmpty && !hasStyle) span.remove();
    });

    // Удаляем пустые текстовые узлы (включая лишние nbps)
    const walker = doc.createTreeWalker(root, NodeFilter.SHOW_TEXT);
    const toRemove = [];

    while (walker.nextNode()) {
      const t = walker.currentNode;
      if (t.textContent.replace(/\u00A0/g, ' ').trim() === '') toRemove.push(t);
    }
    toRemove.forEach(n => n.remove());

    function mergeNestedSpans(root) {
      // Убираем вложенные span-обёртки вида:
      // <span style="..."><span style="...">...</span></span>
      // удалить пустые <p></p> и <h></h> (включая пустые с пробелами/переносами)
      root.querySelectorAll('p, h1, h2, h3, h4, h5, h6').forEach(el => {
        if (el.textContent.trim() === '' && el.children.length === 0) el.remove();
      });
    
      const spans = Array.from(root.querySelectorAll('span'));
    
      for (const outer of spans) {
        // Ищем единственный элемент-span внутри outer (остальное — только пробелы/текст)
        const elementChildren = Array.from(outer.childNodes).filter(n => n.nodeType === Node.ELEMENT_NODE);
        if (elementChildren.length !== 1) continue;
    
        const inner = elementChildren[0];
        if (!inner || inner.tagName !== 'SPAN') continue;

        // Проверяем, что кроме внутреннего span нет других “смысловых” узлов
        // (допускаем только текстовые узлы из пробельных символов)
        const hasOtherMeaningfulNodes = Array.from(outer.childNodes).some(n => {
          if (n === inner) return false;
          if (n.nodeType === Node.TEXT_NODE) return n.textContent.trim() !== '';
          return true;
        });
        if (hasOtherMeaningfulNodes) continue;
    
        // Сливаем style: внешний + внутренний (внутренний обычно побеждает при конфликте)
        const outerStyle = outer.getAttribute('style');
        const innerStyle = inner.getAttribute('style');
    
        if (outerStyle && innerStyle) {
          inner.setAttribute('style', `${outerStyle}; ${innerStyle}`);
        } else if (outerStyle && !innerStyle) {
          inner.setAttribute('style', outerStyle);
        }
    
        outer.replaceWith(inner);
      }
    }
    

    // Нормализуем style у span: убираем дубликаты свойств (например color) и лишние ;;
    function cleanSpanStyles(root) {
      root.querySelectorAll('span').forEach(span => {
        const style = span.getAttribute('style');
        if (!style) return;

        const parts = style
          .split(';')
          .map(s => s.trim())
          .filter(Boolean);

        // property -> last value
        const map = new Map();
        for (const p of parts) {
          const idx = p.indexOf(':');
          if (idx === -1) continue;
          const prop = p.slice(0, idx).trim().toLowerCase();
          const value = p.slice(idx + 1).trim();
          if (!prop || !value) continue;
          map.set(prop, value);
        }

        const rebuilt = Array.from(map.entries())
          .map(([prop, value]) => `${prop}: ${value}`)
          .join('; ');

        if (rebuilt) span.setAttribute('style', rebuilt);
        else span.removeAttribute('style');
      });
    }

    mergeNestedSpans(root);
    cleanSpanStyles(root);

    // Нормализуем text nodes
    root.normalize();

    return root.innerHTML;
  }

  // ============================================
  // 8) Сохранение
  // ============================================

  async function saveAllChanges() {
    console.log('🔍 Начинаем сохранение...');

    // outer_id только для edit, для add — пусто
    const id = currentMode === 'edit' ? idInput?.value : '';

    const titleEl = document.getElementById('edit-title');
    const priceEl = document.getElementById('edit-price');
    const descEl = document.getElementById('edit-description');

    if (!titleEl || !priceEl || !descEl) {
      alert('Поля не найдены.');
      return;
    }

    const fd = new FormData();
    fd.append('mode', currentMode);
    fd.append('slug', getSlug());
    fd.append('outer_id', id);

    fd.append('title', titleEl.innerText.trim());
    fd.append('price', priceEl.innerText.trim());
    fd.append('description', sanitizeDescription(descEl.innerHTML).trim());

    fd.append('delete', deleteBtn);

    // Для add берем slug из выпадающего списка (если он используется)
    if (currentMode === 'add' && categorySlugInput) {
      syncCategorySlugInput();
      fd.append('slug', categorySlugInput.value);
    }

    // Если картинка выбрана — добавляем file
    const file = document.getElementById('admin-file-input-temp');
    if (file?.files?.[0]) {
      fd.append('image', file.files[0]);
    }

    try {
      const resp = await fetch('/editor', {
        method: 'POST',
        body: fd,
        credentials: 'same-origin'
      });

      if (!resp.ok) throw new Error(`Ошибка сохранения: ${resp.status}`);

      const res = await resp.json();
      if (res?.success) {
        alert('Сохранено');
        if (res.redirect) location.href = res.redirect;
        else location.reload();
      } else {
        alert('Сохранение не удалось: ' + (res?.error || 'неизвестная ошибка'));
      }
    } catch (err) {
      console.error('❌ Ошибка:', err);
      alert('Ошибка: ' + err.message);
    }
  }

  // ============================================
  // 9) Подвешиваем обработчики событий
  // ============================================

  toggleBtn?.addEventListener('click', toggleEditMode);
  newBtn?.addEventListener('click', prepareNewProduct);
  saveBtn?.addEventListener('click', saveAllChanges);

  // Общий клик по кнопкам форматирования (по data-cmd)
  document.addEventListener('click', e => {
    const cmdBtn = e.target.closest('[data-cmd]');
    if (!cmdBtn) return;

    const cmd = cmdBtn.getAttribute('data-cmd');

    // Собираем options в зависимости от cmd
    const options = {};
    if (cmd === 'textColor' || cmd === 'highlight') {
      options.color = cmdBtn.dataset.color;
    } else if (cmd === 'unorderedList') {
      options.marker = cmdBtn.dataset.marker;
    }

    format(cmd, options);
  });

  /*
  // Пикеры цвета: обновляют dataset color у кнопок в dropdown
  document.addEventListener('input', e => {
    if (e.target.id === 'text-color-picker') {
      const color = e.target.value;
      document.querySelectorAll('[data-cmd="textColor"]').forEach(btn => {
        btn.dataset.color = color;
      });
    } else if (e.target.id === 'bg-color-picker') {
      const color = e.target.value;
      document.querySelectorAll('[data-cmd="highlight"]').forEach(btn => {
        btn.dataset.color = color;
      });
    }
  });
  */

  // ============================================
  // 10) Инициализация стартового режима
  // ============================================

  // Приводим интерфейс в состояние “neutral”
  setToolbarState('neutral');

  // Инициализируем slug input, если он есть
  syncCategorySlugInput();
}

