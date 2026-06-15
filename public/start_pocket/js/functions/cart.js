// cart.js
// show — глагол, явно обозначает действие (показать тост).
// Toast — явно тип уведомления.
export function showToast(detailMessage, type = 'success') {
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
      // на всякий случай — нейтральный вариант
      // например сервер вернул неожиданный или пустой res.type
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
      // для корректного удаления после завершения анимации
      actualToastEl.addEventListener('hidden.bs.toast', () => actualToastEl.remove());
    });
    bsToast.show();
  }
  
  // bind — указывает, что функция только «навешивает/привязывает» обработчики, 
  // а не выполняет действие (не добавляет в корзину сама по себе).
  // AddToCart — ясно показывает область ответственности (обработчики для кнопок «добавить в корзину»).
  function bindAddToCart(container = document.body) {
    // используем делегирование на ближайшем контейнере
    $(container).on('click', '.add-to-cart', function (e) {
      e.preventDefault();
      const btn = $(this);
      const icon = btn.find('i');
      const loader = btn.find('.loader');
      const productId = btn.data('id');
      const csrfToken = $('meta[name="csrf-token"]').attr('content') || null;
      if (!csrfToken) {
        showToast('Потерян ключ доступа', 'danger');
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
          if (res && res.status === 'error') {
            const t = res.type || 'warning';
            showToast(res.message || 'Ошибка', t);
          } else {
            showToast((res && res.message) || 'Успешно добавлено', 'success');
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
          showToast(errorMsg, type);
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
  }

  /*
  export function initCart(container = document.body) {
    // если нужен jQuery-ready, можно проверить состояние документа
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => bindAddToCart(container));
    } else {
      bindAddToCart(container);
    }
  }*/

  export function initCart(container = document.body) {
    bindAddToCart(container);
  }
  
  
