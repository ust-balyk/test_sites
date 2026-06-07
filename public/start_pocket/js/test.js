// Подключите этот файл после загрузки DOM (например внизу страницы)
(function(){
  const toggleBtn = document.getElementById('toggle-edit-btn');
  const newBtn = document.getElementById('new-product-btn');
  const saveBtn = document.getElementById('save-all-btn');
  const formatTools = document.getElementById('format-tools');
  const fileInputId = 'admin-file-input-temp';
  let isEditMode = false;
  let isNew = false;

  function setEditable(state){
    const fields = ['edit-title','edit-description','edit-price'];
    fields.forEach(id=>{
      const el = document.getElementById(id);
      if (!el) return;
      el.contentEditable = state ? 'true' : 'false';
      if (state) el.classList.add('editable'); else el.classList.remove('editable');
    });
    // reviews edit toggle
    document.querySelectorAll('.editable-review-author, .editable-review-text').forEach(el=>{
      el.contentEditable = state ? 'true' : 'false';
    });
    const imgContainer = document.getElementById('image-container');
    if (imgContainer){
      if (state){
        imgContainer.classList.add('edit-image-hover');
        imgContainer.addEventListener('click', onImageClick);
      } else {
        imgContainer.classList.remove('edit-image-hover');
        imgContainer.removeEventListener('click', onImageClick);
      }
    }
    if (formatTools) formatTools.classList.toggle('d-none', !state);
  }

  function toggleEditMode(){
    isEditMode = !isEditMode;
    setEditable(isEditMode);
  }

  function prepareNewProduct(){
    isNew = true;
    if (!isEditMode) toggleEditMode();
    const title = document.getElementById('edit-title');
    const desc = document.getElementById('edit-description');
    const price = document.getElementById('edit-price');
    const img = document.getElementById('main-product-image');
    const idInput = document.getElementById('admin-product-id');

    if (title) title.innerText = 'Название нового товара';
    if (desc) desc.innerHTML = '<p>Введите описание...</p>';
    if (price) price.innerText = '0.00';
    if (img) img.src = '/images/onerror.webp';
    if (idInput) idInput.value = 'new';
  }

  function onImageClick(){
    let fi = document.getElementById(fileInputId);
    if (!fi){
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

  function onFileChange(e){
    const f = e.target.files && e.target.files[0];
    if (!f) return;
    const reader = new FileReader();
    reader.onload = function(ev){
      const img = document.getElementById('main-product-image');
      if (img) img.src = ev.target.result;
    };
    reader.readAsDataURL(f);
  }

  function format(cmd){
    if (!isEditMode) return;
    document.execCommand(cmd, false, null);
  }

  async function saveAllChanges(){
    const idInput = document.getElementById('admin-product-id');
    const outer = idInput ? idInput.value : 'new';
    const titleEl = document.getElementById('edit-title');
    const priceEl = document.getElementById('edit-price');
    const descEl = document.getElementById('edit-description');

    if (!titleEl || !priceEl || !descEl){
      alert('Поля не найдены.');
      return;
    }

    const fd = new FormData();
    fd.append('outer_id', outer);
    fd.append('title', titleEl.innerText.trim());
    fd.append('price', priceEl.innerText.trim());
    fd.append('description', descEl.innerHTML.trim());

    const file = document.getElementById(fileInputId);
    if (file && file.files && file.files[0]){
      fd.append('image', file.files[0]);
    }

    // Собираем отзывы в массив
    const reviews = [];
    document.querySelectorAll('#reviews-list .review').forEach(r=>{
      const author = (r.querySelector('.card-title')||{innerText:''}).innerText.trim();
      const date = (r.querySelector('.text-muted')||{innerText:''}).innerText.trim();
      const rating = (r.querySelector('.star-rating')||{innerText:''}).innerText.trim();
      const text = (r.querySelector('.card-text')||{innerText:''}).innerText.trim();
      reviews.push({author,date,rating,text});
    });
    fd.append('reviews', JSON.stringify(reviews));

    
    try {
      const resp = await fetch('/editor', {
        method: 'POST',
        body: fd,
        credentials: 'same-origin'
      });
      if (!resp.ok) {
        const txt = await resp.text();
        alert('Ошибка сохранения: ' + resp.status + ' ' + txt);
        return;
      }
      const res = await resp.json();
      if (res.success){
        alert('Сохранено');
        if (res.redirect) window.location.href = res.redirect; else location.reload();
      } else {
        alert('Сохранение не удалось: ' + (res.error || 'неизвестная ошибка'));
      }

    } catch (err){
      console.error(err);
      alert('Test.js/Ошибка : ' + err.message);
    }
  }

  // Events
  document.addEventListener('click', function(e){
    const cmd = e.target.closest('[data-cmd]');
    if (cmd){
      format(cmd.getAttribute('data-cmd'));
    }
  });

  if (toggleBtn) toggleBtn.addEventListener('click', toggleEditMode);
  if (newBtn) newBtn.addEventListener('click', prepareNewProduct);
  if (saveBtn) saveBtn.addEventListener('click', saveAllChanges);

  // expose for console/debug if needed
  window.adminProduct = { toggleEditMode, prepareNewProduct, saveAllChanges };
})();
