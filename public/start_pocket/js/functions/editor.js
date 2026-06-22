/* * * editor.js * * */
export default function initEditorProduct() {
  const toggleBtn = document.getElementById('toggle-edit-btn');
  const newBtn = document.getElementById('new-product-btn');
  const saveBtn = document.getElementById('save-all-btn');
  const formatTools = document.getElementById('format-tools');
  const fileInputId = 'admin-file-input-temp';

  const productRoot = document.querySelector('.product[data-slug]');

  const isEditableNow = () => document.querySelector('[contenteditable="true"]') !== null;

  function getSlug() {
    return productRoot?.dataset.slug?.trim() || '';
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

  function toggleEditMode() {
    setEditable(!isEditableNow());
  }

  function prepareNewProduct() {
    setEditable(true);

    const title = document.getElementById('edit-title');
    const desc = document.getElementById('edit-description');
    const price = document.getElementById('edit-price');
    const img = document.getElementById('main-product-image');
    const idInput = document.getElementById('admin-product-id');

    if (title) title.innerText = 'Название нового товара';
    if (desc) desc.innerHTML = '<p>Введите описание...</p>';
    if (price) price.innerText = '0';
    if (img) img.src = '/images/onerror.webp';
    if (idInput) idInput.value = 'new';
    if (productRoot) productRoot.dataset.slug = 'new';
  }

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
    reader.onload = ev => {
      const img = document.getElementById('main-product-image');
      if (img) img.src = ev.target.result;
    };
    reader.readAsDataURL(f);
  }

  function format(cmd) {
    if (!isEditableNow()) return;
    document.execCommand(cmd, false, null);
  }

  async function saveAllChanges() {
    const idInput = document.getElementById('admin-product-id');
    const outer = idInput?.value || 'new';
    const slug = getSlug();

    const titleEl = document.getElementById('edit-title');
    const priceEl = document.getElementById('edit-price');
    const descEl = document.getElementById('edit-description');

    if (!titleEl || !priceEl || !descEl) {
      alert('Поля не найдены.');
      return;
    }

    const fd = new FormData();
    fd.append('slug', slug);
    fd.append('outer_id', outer);
    fd.append('title', titleEl.innerText.trim());
    fd.append('price', priceEl.innerText.trim());
    fd.append('description', descEl.innerHTML.trim());

    const file = document.getElementById(fileInputId);
    if (file?.files?.[0]) fd.append('image', file.files[0]);

    const reviews = [...document.querySelectorAll('#reviews-list .review')].map(r => ({
      author: (r.querySelector('.card-title')?.innerText || '').trim(),
      date: (r.querySelector('.text-muted')?.innerText || '').trim(),
      rating: (r.querySelector('.star-rating')?.innerText || '').trim(),
      text: (r.querySelector('.card-text')?.innerText || '').trim(),
    }));
    fd.append('reviews', JSON.stringify(reviews));

    try {
      const resp = await fetch('/editor', { method: 'POST', body: fd, credentials: 'same-origin' });
      const txt = await resp.text();

      if (!resp.ok) {
        alert('Ошибка сохранения: ' + resp.status + ' ' + txt);
        return;
      }

      let res;
      try { res = JSON.parse(txt); } catch { alert('Неверный ответ от сервера.'); return; }

      if (res?.success) {
        alert('Сохранено');
        if (res.redirect) location.href = res.redirect;
        else location.reload();
      } else {
        alert('Сохранение не удалось: ' + (res?.error || 'неизвестная ошибка'));
      }
    } catch (err) {
      alert('Ошибка: ' + (err?.message || err));
    }
  }

  toggleBtn?.addEventListener('click', toggleEditMode);
  newBtn?.addEventListener('click', prepareNewProduct);
  saveBtn?.addEventListener('click', saveAllChanges);

  document.addEventListener('click', e => {
    const cmd = e.target.closest('[data-cmd]');
    if (cmd) format(cmd.getAttribute('data-cmd'));
  });
}
