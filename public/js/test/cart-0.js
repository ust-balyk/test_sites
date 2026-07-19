function showBSCoreToast(detailMessage, type = 'success') {
  const container = document.querySelector('.toast-container');
  const template = document.getElementById('toast-template');
  if (!container || !template) return;
  
  const clone = template.content.cloneNode(true);
  const toastEl = clone.querySelector('.toast');
  const iconEl = clone.querySelector('.jp-icon');
  const titleEl = clone.querySelector('.jp-status-label');
  const textEl = clone.querySelector('.jp-detail-text');
  
  if (!toastEl || !iconEl || !titleEl || !textEl) return;
  
  // Сбрасываем классы/стили от предыдущих вызовов (на случай повторного использования)
  toastEl.classList.remove('bg-success', 'bg-warning', 'bg-danger');
  toastEl.style.transition = '';
  toastEl.style.maxHeight = '';
  toastEl.style.opacity = '';
  toastEl.style.marginTop = '';
  toastEl.style.marginBottom = '';
  toastEl.style.paddingTop = '';
  toastEl.style.paddingBottom = '';
  toastEl.style.overflow = '';
  
  // Настройка темы
  if (type === 'success') {
    toastEl.classList.add('bg-success');
    iconEl.textContent = '済'; // завершено
    titleEl.textContent = 'Добавлено в корзину';
  } else if (type === 'warning') {
    toastEl.classList.add('bg-warning');
    iconEl.textContent = '誤'; // ошибка
    titleEl.textContent = 'Товар не найден';
  } else if (type === 'danger') {
    toastEl.classList.add('bg-danger');
    iconEl.textContent = '危'; // опасно
    titleEl.textContent = 'Угроза безопасности';
  } else {
    // на всякий случай — нейтральный вариант
    toastEl.classList.add('bg-secondary');
    iconEl.textContent = '';
    titleEl.textContent = '';
  }
  
  textEl.textContent = detailMessage || '';
  
  container.appendChild(clone);
  
  // После вставки в DOM получаем реальный элемент (в clone он уже в документе)
  const actualToastEl = container.querySelector('.toast:last-child');
  
  const bsToast = new bootstrap.Toast(actualToastEl, {
    autohide: true,
    delay: 3000
  });
  
  actualToastEl.addEventListener('hide.bs.toast', () => {
    actualToastEl.style.pointerEvents = 'none';
  });
  
  actualToastEl.addEventListener('hidden.bs.toast', () => {
    // плавное схлопывание перед удалением
    actualToastEl.style.maxHeight = actualToastEl.offsetHeight + 'px';
    // reflow
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
    // для корректного удаления после завершения анимации
    actualToastEl.addEventListener('hidden.bs.toast', () => actualToastEl.remove());
    //setTimeout(() => actualToastEl.remove(), 400);
  });
  
  bsToast.show();

}
  
$(document).ready(function() {
  //$(document).on('click', '.add-to-cart', function (e) {
  $(document.body).on('click', '.add-to-cart', function (e) {
    e.preventDefault();

    const btn = $(this);
    const icon = btn.find('i');
    const loader = btn.find('.loader'); // предполагаемый селектор для лоадера
    const productId = btn.data('id');

    const csrfToken = $('meta[name="csrf-token"]').attr('content') || null;

    if (!csrfToken) {
      showBSCoreToast('Потерян ключ доступа', 'danger');
      return;
    }

    $.ajax({

      url: baseUrl + 'add-to-cart',
      method: 'POST',
      data: { 'id': productId },
      headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {},
      beforeSend: function () {
        btn.prop('disabled', true);
        if (icon.length) icon.addClass('d-none');
        if (loader.length) loader.removeClass('d-none');
      },
        
      success: function (res) {
        // ожидаем JSON: { status: 'ok'|'error', type?: 'success'|'warning'|'danger', 
        // message?: '...' , mini_cart?, cart_qty? }
        if (res && res.status === 'error') {
          const t = res.type || 'warning';
          showBSCoreToast(res.message || 'Ошибка', t);
        } else {
          showBSCoreToast((res && res.message) || 'Успешно добавлено', 'success');
          if (res && res.mini_cart) $('#offcanvasCart .offcanvas-body').html(res.mini_cart);
          if (res && typeof res.cart_qty !== 'undefined') $('.offcanvas-cart-qty').text(res.cart_qty);
          icon.css('color', 'blue').addClass('in_cart');
        }
      },
  
      error: function (xhr) {
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
            if (json.type) type = json.type; // сервер явно указал тип
            if (json.status && json.status === 'error' && !json.type) type = 'warning';
          }
        } catch (e) {
            // игнорируем парсинг-ошибки  
        }
        
        // HTTP-статусы с CSRF/auth проблемой считаем 'danger'
        if (xhr.status === 419 || xhr.status === 401 || xhr.status === 403) {
          type = 'danger';
          if (!errorMsg || errorMsg === 'Произошла ошибка') {
            errorMsg = 'Ошибка идентификации продукта';
          }
        } else if (!type) {
          type = 'warning';
        }
        
        showBSCoreToast(errorMsg, type);
        
      },
        
      complete: function () {
        setTimeout(function () {
          btn.prop('disabled', false);
          if (icon.length) icon.removeClass('d-none');
          if (loader.length) loader.addClass('d-none');
        }, 300);
      }
        
  });
});
