let isEditMode = false;
let isNewProduct = false;

// 1. Включение режима правки
function toggleEditMode() {
    isEditMode = !isEditMode;
    const tools = document.getElementById('format-tools');
    const fields = ['edit-title', 'edit-description', 'edit-price'];
    
    tools.classList.toggle('d-none');
    
    fields.forEach(id => {
        const el = document.getElementById(id);
        el.contentEditable = isEditMode;
    });

    // Работа с картинкой
    const imgContainer = document.getElementById('image-container');
    if (isEditMode) {
        imgContainer.classList.add('edit-image-hover');
        imgContainer.onclick = () => document.getElementById('admin-file-input').click();
    } else {
        imgContainer.classList.remove('edit-image-hover');
        imgContainer.onclick = null;
    }
}

// 2. Режим "Новый товар"
function prepareNewProduct() {
    isNewProduct = true;
    if (!isEditMode) toggleEditMode();
    
    document.getElementById('edit-title').innerText = "Название нового товара";
    document.getElementById('edit-description').innerHTML = "<p>Введите описание...</p>";
    document.getElementById('edit-price').innerText = "0.00";
    document.getElementById('main-product-image').src = "/images/onerror.webp";
    document.getElementById('admin-product-id').value = "new";
}

// 3. Форматирование (Янцев style)
function format(cmd) {
    document.execCommand(cmd, false, null);
}

// 4. Предпросмотр картинки
document.getElementById('admin-file-input').onchange = function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = (event) => {
            document.getElementById('main-product-image').src = event.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    }
};

// 5. Глобальное сохранение
async function saveAllChanges() {
    const formData = new FormData();
    
    formData.append('outer_id', document.getElementById('admin-product-id').value);
    formData.append('title', document.getElementById('edit-title').innerText);
    formData.append('price', document.getElementById('edit-price').innerText);
    formData.append('description', document.getElementById('edit-description').innerHTML);
    
    const fileField = document.getElementById('admin-file-input');
    if (fileField.files[0]) {
        formData.append('image', fileField.files[0]);
    }

    const response = await fetch('../../entry/save_product.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();
    if (result.success) {
        alert("Данные успешно очищены и сохранены!");
        location.reload();
    }
}

