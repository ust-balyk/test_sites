// cart.js
export function showBSCoreToast(detailMessage, type = 'success') {
  const container = document.querySelector('.toast-container');
  const template = document.getElementById('toast-template');
  if (!container || !template) return;
  const clone = template.content.cloneNode(true);
  const toastEl = clone.querySelector('.toast');
  const iconEl = clone.querySelector('.jp-icon');
  const titleEl = clone.querySelector('.jp-status-label');
  const textEl = clone.querySelector('.jp-detail-text');
  if (!toastEl || !iconEl || !titleEl || !textEl) return;
  toastEl.classList.remove('bg-success', 'bg-warning', 'bg-danger', 'bg-secondary');
  toastEl.style.transition = '';
  toastEl.style.maxHeight = '';
  toastEl.style.opacity = '';
  toastEl.style.marginTop = '';
  toastEl.style.marginBottom = '';
  toastEl.style.paddingTop = '';
  toastEl.style.paddingBottom = '';
  toastEl.style.overflow = '';
  if (type === 'success') {
    toastEl.classList.add('bg-success'); iconEl.textContent = '済'; titleEl.textContent = 'Добавлено в корзину';
  } else if (type === 'warning') {
    toastEl.classList.add('bg-warning'); iconEl.textContent = '誤'; titleEl.textContent = 'Товар не найден';
  } else if (type === 'danger') {
    toastEl.classList.add('bg-danger'); iconEl.textContent = '危'; titleEl.textContent = 'Угроза безопасности';
  } else {
    toastEl.classList.add('bg-secondary'); iconEl.textContent = ''; titleEl.textContent = '';
  }
  textEl.textContent = detailMessage || '';
  container.appendChild(clone);
  const actualToastEl = container.querySelector('.toast:last-child');
  const bsToast = new bootstrap.Toast(actualToastEl, { autohide: true, delay: 3000 });
  actualToastEl.addEventListener('hide.bs.toast', () => { actualToastEl.style.pointerEvents = 'none'; });
  actualToastEl.addEventListener('hidden.bs.toast', () => {
    actualToastEl.style.maxHeight = actualToastEl.offsetHeight + 'px';
    void actualToastEl.offsetHeight;
    actualToastEl.style.transition = 'max-height 0.35s ease, opacity 0.35s ease, margin 0.35s ease, padding 0.35s ease';
    actualToastEl.style.maxHeight = '0';
    actualToastEl.style.opacity = '0';
    actualToastEl.style.marginTop = '0';
    actualToastEl.style.marginBottom = '0';
    actualToastEl.style.paddingTop = '0';
    actualToastEl.style.paddingBottom = '0';
    actualToastEl.style.overflow = 'hidden';
    actualToastEl.addEventListener('hidden.bs.toast', () => actualToastEl.remove());
  });
  bsToast.show();
}

// Общие helper'ы
function getCsrf() {
  return $('meta[name="csrf-token"]').attr('content') || null;
}
function handleAjaxError(xhr) {
  let errorMsg = 'Произошла ошибка'; let type = 'warning';
  try {
    const json = xhr.responseJSON || JSON.parse(xhr.responseText || '{}');
    if (json) {
      if (json.message) errorMsg = json.message;
      else if (json.errors) {
        const first = Object.values(json.errors)[0];
        errorMsg = Array.isArray(first) ? first[0] : first;
      }
      if (json.type) type = json.type;
      if (json.status && json.status === 'error' && !json.type) type = 'warning';
    }
  } catch (e) {}
  if (xhr.status === 419 || xhr.status === 401 || xhr.status === 403) {
    type = 'danger';
    if (!errorMsg || errorMsg === 'Произошла ошибка') errorMsg = 'Ошибка идентификации продукта';
  } else if (!type) type = 'warning';
  showBSCoreToast(errorMsg, type);
}

// Удаление из корзины
export function removeFromCart(itemId, { successCallback = null } = {}) {
  const csrf = getCsrf();
  if (!csrf) { showBSCoreToast('Потерян ключ доступа', 'danger'); return; }
  $.ajax({
    url: baseUrl + 'cart/remove',
    method: 'POST',
    data: { id: itemId },
    headers: { 'X-CSRF-TOKEN': csrf },
    success(res) {
      showBSCoreToast((res && res.message) || 'Удалено из корзины', 'success');
      if (res && res.cart_html) $('#cart-container').html(res.cart_html);
      if (typeof successCallback === 'function') successCallback(res);
    },
    error(xhr) { handleAjaxError(xhr); }
  });
}

// Изменение количества
export function changeQuantity(itemId, qty, { successCallback = null } = {}) {
  const csrf = getCsrf();
  if (!csrf) { showBSCoreToast('Потерян ключ доступа', 'danger'); return; }
  $.ajax({
    url: baseUrl + 'cart/change-quantity',
    method: 'POST',
    data: { id: itemId, qty: qty },
    headers: { 'X-CSRF-TOKEN': csrf },
    success(res) {
      showBSCoreToast((res && res.message) || 'Количество обновлено', 'success');
      if (res && res.cart_html) $('#cart-container').html(res.cart_html);
      if (typeof successCallback === 'function') successCallback(res);
    },
    error(xhr) { handleAjaxError(xhr); }
  });
}

// Очистка корзины
export function clearCart({ successCallback = null } = {}) {
  const csrf = getCsrf();
  if (!csrf) { showBSCoreToast('Потерян ключ доступа', 'danger'); return; }
  $.ajax({
    url: baseUrl + 'cart/clear',
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrf },
    success(res) {
      showBSCoreToast((res && res.message) || 'Корзина очищена', 'success');
      if (res && res.cart_html) $('#cart-container').html(res.cart_html);
      if (typeof successCallback === 'function') successCallback(res);
    },
    error(xhr) { handleAjaxError(xhr); }
  });
}

// Пересчёт сумм в DOM (локально, если есть данные в DOM)
export function recalcCartTotals(container = document.querySelector('#cart-container')) {
  if (!container) return;
  const rows = container.querySelectorAll('.cart-row');
  let total = 0;
  let qty = 0;
  rows.forEach(row => {
    const priceEl = row.querySelector('.cart-price');
    const qtyEl = row.querySelector('.cart-qty-input');
    const price = priceEl ? parseFloat(priceEl.dataset.price || priceEl.textContent.replace(/[^\d.,-]/g,'').replace(',','.') ) : 0;
    const q = qtyEl ? parseInt(qtyEl.value || qtyEl.dataset.qty || '0', 10) : 1;
    if (!isNaN(price) && !isNaN(q)) { total += price * q; qty += q; }
  });
  const totalEl = container.querySelector('.cart-total');
  const qtyEl = container.querySelector('.cart-total-qty');
  if (totalEl) totalEl.textContent = total.toFixed(2);
  if (qtyEl) qtyEl.textContent = qty;
}

// bindAddToCart — навешивает обработчики для add/remove/qty/clear
export function bindAddToCart(container = document.body) {
  // добавление (делегирование)
  $(container).on('click', '.add-to-cart', function (e) {
    e.preventDefault();
    const btn = $(this);
    const icon = btn.find('i');
    const loader = btn.find('.loader');
    const productId = btn.data('id');
    const csrf = getCsrf();
    if (!csrf) { showBSCoreToast('Потерян ключ доступа', 'danger'); return; }
    $.ajax({
      url: baseUrl + 'add-to-cart',
      method: 'POST',
      data: { id: productId },
      headers: { 'X-CSRF-TOKEN': csrf },
      beforeSend() { btn.prop('disabled', true); 
        if (icon.length) icon.addClass('d-none'); 
        if (loader.length) loader.removeClass('d-none'); 
      },
      timeout: 3000,
      success(res) {
        if (res && res.status === 'error') { showBSCoreToast(res.message || 'Ошибка', res.type || 'warning'); }
        else {
          showBSCoreToast((res && res.message) || 'Успешно добавлено', 'success');
          if (res && res.mini_cart) $('#offcanvasCart .offcanvas-body').html(res.mini_cart);
          if (res && typeof res.cart_qty !== 'undefined') $('.offcanvas-cart-qty').text(res.cart_qty);
          if (icon.length) icon.css('color','blue').addClass('in_cart');
        }
      },
      error(xhr) { handleAjaxError(xhr); },
      complete() { setTimeout(()=>{ btn.prop('disabled', false); if (icon.length) icon.removeClass('d-none'); if (loader.length) loader.addClass('d-none'); },300); }
    });
  });

  // удаление из корзины
  $(container).on('click', '.remove-from-cart', function (e) {
    e.preventDefault();
    const btn = $(this);
    const itemId = btn.data('id');
    removeFromCart(itemId, { successCallback() { /* опционально: локальные изменения */ } });
  });

  // изменение количества (инпут/кнопки + / -)
  $(container).on('change', '.cart-qty-input', function (e) {
    const input = $(this);
    const itemId = input.data('id');
    const qty = parseInt(input.val(), 10) || 0;
    changeQuantity(itemId, qty);
  });
  $(container).on('click', '.qty-increment', function (e) {
    e.preventDefault();
    const btn = $(this);
    const input = btn.closest('.qty-wrap').find('.cart-qty-input');
    input.val((i, v) => (parseInt(v || '0',10) + 1));
    input.trigger('change');
  });
  $(container).on('click', '.qty-decrement', function (e) {
    e.preventDefault();
    const btn = $(this);
    const input = btn.closest('.qty-wrap').find('.cart-qty-input');
    input.val((i, v) => Math.max(0, parseInt(v || '0',10) - 1));
    input.trigger('change');
  });

  // очистка корзины
  $(container).on('click', '.clear-cart', function (e) {
    e.preventDefault();
    clearCart();
  });

  // при локальном изменении — пересчёт
  $(container).on('input', '.cart-qty-input', function () { recalcCartTotals(document.querySelector('#cart-container')); });
}

