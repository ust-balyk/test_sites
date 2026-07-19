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
    toastEl.classList.add('bg-success');
    iconEl.textContent = '済';
    titleEl.textContent = 'Добавлено в корзину';
  } else if (type === 'warning') {
    toastEl.classList.add('bg-warning');
    iconEl.textContent = '誤';
    titleEl.textContent = 'Товар не найден';
  } else if (type === 'danger') {
    toastEl.classList.add('bg-danger');
    iconEl.textContent = '危';
    titleEl.textContent = 'Угроза безопасности';
  } else {
    toastEl.classList.add('bg-secondary');
    iconEl.textContent = '';
    titleEl.textContent = '';
  }
  textEl.textContent = detailMessage || '';
  container.appendChild(clone);
  const actualToastEl = container.querySelector('.toast:last-child');
  const bsToast = new bootstrap.Toast(actualToastEl, { autohide: true, delay: 3000 });
  actualToastEl.addEventListener('hide.bs.toast', () => { actualToastEl.style.pointerEvents = 'none'; });
  actualToastEl.addEventListener('hidden.bs.toast', () => {
    actualToastEl.style.maxHeight = actualToastEl.offsetHeight + 'px';
    void actualToastEl.offsetHeight;
    actualToastEl.style.transition =
      'max-height 0.35s ease, opacity 0.35s ease, margin 0.35s ease, padding 0.35s ease';
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

/* Общие хелперы для AJAX-цикла */
function commonBeforeSend(btn, icon, loader) {
  btn.prop('disabled', true);
  if (icon.length) icon.addClass('d-none');
  if (loader.length) loader.removeClass('d-none');
}
function commonComplete(btn, icon, loader) {
  setTimeout(function () {
    btn.prop('disabled', false);
    if (icon.length) icon.removeClass('d-none');
    if (loader.length) loader.addClass('d-none');
  }, 300);
}
function commonErrorHandler(xhr) {
  let errorMsg = 'Произошла ошибка';
  let type = 'warning';
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
    if (!errorMsg || errorMsg === 'Произошла ошибка') {
      errorMsg = 'Ошибка идентификации продукта';
    }
  } else if (!type) {
    type = 'warning';
  }
  showBSCoreToast(errorMsg, type);
}

/* Универсальный бинд-обработчик */
export function bindAction({
  container = document.body,
  selector = '.add-to-cart',
  urlPath = 'add-to-cart',
  beforeSendHook = null,
  successHandler = null,
  dataMapper = (btn) => ({ id: btn.data('id') })
} = {}) {
  $(container).on('click', selector, function (e) {
    e.preventDefault();
    const btn = $(this);
    const icon = btn.find('i');
    const loader = btn.find('.loader');
    const payload = dataMapper(btn);
    const csrfToken = $('meta[name="csrf-token"]').attr('content') || null;
    if (!csrfToken) {
      showBSCoreToast('Потерян ключ доступа', 'danger');
      return;
    }
    $.ajax({
      url: baseUrl + urlPath,
      method: 'POST',
      data: payload,
      headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {},
      beforeSend: function () {
        commonBeforeSend(btn, icon, loader);
        if (typeof beforeSendHook === 'function') beforeSendHook(btn);
      },
      //timeout: 5000,
      success: function (res) {
        if (res && res.status === 'error') {
          const t = res.type || 'warning';
          showBSCoreToast(res.message || 'Ошибка', t);
        } else {
          if (typeof successHandler === 'function') {
            successHandler(res, btn);
          } else {
            // дефолтное поведение (корзина)
            showBSCoreToast((res && res.message) || 'Успешно добавлено', 'success');
            if (res && res.mini_cart) $('#offcanvasCart .offcanvas-body').html(res.mini_cart);
            if (res && typeof res.cart_qty !== 'undefined') $('.offcanvas-cart-qty').text(res.cart_qty);
            const iconEl = btn.find('i');
            if (iconEl.length) iconEl.css('color', 'blue').addClass('in_cart');
          }
        }
      },
      error: function (xhr) {
        commonErrorHandler(xhr);
      },
      complete: function () {
        commonComplete(btn, icon, loader);
      }
    });
  });
}

/* Простая обёртка инициализации */
export function initActions() {
  // корзина
  bindAction({
    selector: '.add-to-cart',
    urlPath: 'add-to-cart'
    // можно передать successHandler при необходимости
  });

  // избранное
  bindAction({
    selector: '.add-to-fav',
    urlPath: 'add-to-favorites',
    successHandler: function (res, btn) {
      showBSCoreToast((res && res.message) || 'Добавлено в избранное', 'success');
      const icon = btn.find('i');
      if (icon.length) icon.toggleClass('fav active');
      if (res && typeof res.fav_count !== 'undefined') $('.fav-count').text(res.fav_count);
    },
    dataMapper: (btn) => ({ id: btn.data('id') }) // адаптируемые данные
  });
}

