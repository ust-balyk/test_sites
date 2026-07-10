/**
 * editor.js
 */
export default function initEditorProduct() {
  // ============================================
  // 1. ПОЛУЧЕНИЕ DOM-ЭЛЕМЕНТОВ
  // ============================================

  const toggleBtn = document.getElementById('toggle-edit-btn');
  const newBtn = document.getElementById('new-product-btn');
  const saveBtn = document.getElementById('save-all-btn');
  const formatTools = document.getElementById('format-tools');
  const categorySelect = document.getElementById('category-select');
  const categorySlugInput = document.getElementById('admin-slug-category');
  const productRoot = document.querySelector('.product[data-slug]');

  // ============================================
  // 2. ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
  // ============================================

  const isEditableNow = () => document.querySelector('[contenteditable="true"]') !== null;

  function getSlug() {
    return productRoot?.dataset.slug?.trim() ?? '';
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
      setEditable(false);
      newBtn?.classList.remove('d-none');
      toggleBtn?.classList.remove('d-none');
      if (categorySelect) categorySelect.classList.add('d-none');
      if (formatTools) formatTools.classList.add('d-none');
    } else if (state === 'add') {
      setEditable(true);
      newBtn?.classList.remove('d-none');
      toggleBtn?.classList.add('d-none');
      if (categorySelect) categorySelect.classList.remove('d-none');
      if (formatTools) formatTools.classList.remove('d-none');
      syncCategorySlugInput();
    } else if (state === 'edit') {
      setEditable(true);
      newBtn?.classList.add('d-none');
      toggleBtn?.classList.remove('d-none');
      if (categorySelect) categorySelect.classList.add('d-none');
      if (formatTools) formatTools.classList.remove('d-none');
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
    }
  }

  // ============================================
  // 5. КАТЕГОРИИ
  // ============================================

  function syncCategorySlugInput() {
    if (!categorySelect || !categorySlugInput) return;

    const modeOrSlug = categorySelect.value;
    if (modeOrSlug === 'current') {
      const currentSlug = productRoot?.dataset.slug?.trim() ?? '';
      categorySlugInput.value = currentSlug;
      console.log('✓ Категория установлена:', currentSlug);
    } else {
      categorySlugInput.value = modeOrSlug;
      console.log('✓ Выбрана категория:', modeOrSlug);
    }
  }

  function clearNewProductForm() {
    const title = document.getElementById('edit-title');
    const desc = document.getElementById('edit-description');
    const price = document.getElementById('edit-price');
    const img = document.getElementById('main-product-image');

    if (title) title.innerText = 'название..';
    if (desc) desc.innerHTML = '<p>описание..</p>';
    if (price) price.innerText = 'цена по запросу';
    if (img) img.src = '/images/onerror.webp';

    if (categorySelect) {
      categorySelect.value = 'current';
      syncCategorySlugInput();
    }
  }

  if (categorySelect) {
    categorySelect.addEventListener('change', syncCategorySlugInput);
  }

  // ============================================
  // 6. УПРАВЛЕНИЕ ИЗОБРАЖЕНИЕМ
  // ============================================

  function onImageClick() {
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

  function format(cmd, options = {}) {
    if (!isEditableNow()) return;

    const selection = window.getSelection();
    if (!selection.rangeCount) return;

    const range = selection.getRangeAt(0);
    const selectedText = range.toString();

    // Если ничего не выделено, выходим
    if (!selectedText.trim()) return;

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
      case 'unorderedList':
        createList(range, options.marker || 'disc', options.color || '#000000');
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

  function applyFormatting(range, tagName, styles = {}) {
    const element = document.createElement(tagName);
    Object.assign(element.style, styles);
    range.surroundContents(element);
  }

  function wrapInHeading(range, headingTag) {
    const heading = document.createElement(headingTag);
    heading.textContent = range.toString();
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
  
  function clearFormatting(range) {
    const startEl = range.startContainer.nodeType === Node.ELEMENT_NODE
      ? range.startContainer
      : range.startContainer.parentElement;
  
    const root = startEl?.closest('#edit-description');
    if (!root) return; // выделение не в описании
  
    const selector = [
      'strong, em, u, s, b, code, h4, h5, h6, ul, li, a, blockquote',
      'span[style*="background-color"]',
      'span[style*="color"]',
    ].join(',');
  
    const elements = Array.from(root.querySelectorAll(selector))
      .filter(el => range.intersectsNode(el));
  
    // unwrap снизу вверх (чтобы DOM не ломался)
    elements.sort((a, b) => (b.compareDocumentPosition(a) & Node.DOCUMENT_POSITION_FOLLOWING ? 1 : -1));
  
    elements.forEach(el => {
      const parent = el.parentNode;
      if (!parent) return;
  
      while (el.firstChild) parent.insertBefore(el.firstChild, el);
      parent.removeChild(el);
    });
  }
  
  // ============================================
  // 8. СОХРАНЕНИЕ ДАННЫХ
  // ============================================

  async function saveAllChanges() {
    console.log('🔍 Начинаем сохранение...');
    const idInput = document.getElementById('admin-product-id');
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
    fd.append('description', descEl.innerHTML.trim());

    if (currentMode === 'add' && categorySlugInput) {
      syncCategorySlugInput();
      fd.append('slug', categorySlugInput.value);
    }

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

      if (!resp.ok) {
        throw new Error(`Ошибка сохранения: ${resp.status}`);
      }

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
  // 9. ПРИСОЕДИНЕНИЕ ОБРАБОТЧИКОВ
  // ============================================
  
  toggleBtn?.addEventListener('click', toggleEditMode);
  newBtn?.addEventListener('click', prepareNewProduct);
  saveBtn?.addEventListener('click', saveAllChanges);

  // Обработчики для форматирования
  document.addEventListener('click', e => {
    const cmdBtn = e.target.closest('[data-cmd]');
    if (cmdBtn) {
      const cmd = cmdBtn.getAttribute('data-cmd');
      const options = {};

      if (cmd === 'textColor' || cmd === 'highlight') {
        options.color = cmdBtn.dataset.color;
      } else if (cmd === 'unorderedList') {
        options.marker = cmdBtn.dataset.marker;
      }

      format(cmd, options);
    }
  });

  // Обработчики для цветовых пикеров
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

  // Инициализация
  syncCategorySlugInput();
}

