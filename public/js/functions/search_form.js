export function initSearchForm({
  buttonId = "submit",
  inputId = "input"
} = {}) {
  $(document).on('click', '#' + buttonId, function (e) {
    e.preventDefault();

    const $form = $(this).closest('form');
    const $input = $form.find('#' + inputId);

    if ($form.length === 0 || $input.length === 0) return;

    const isHidden = $input.hasClass('hide');
    const hasValue = ($input.val() || "").trim().length > 0;

    // 1-й клик: открыть
    if (isHidden) {
      $input.removeClass('hide').focus();
      return;
    }

    // 2-й клик: если пусто — закрыть
    if (!hasValue) {
      $input.addClass('hide');
      return;
    }

    // если заполнено — отправить
    const formEl = $form[0];
    if (formEl) {
      (formEl.requestSubmit ? formEl.requestSubmit() : formEl.submit());
    }
  });
}

