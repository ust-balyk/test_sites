<div class="container">

    <div id="alert" class="alert alert-warning alert-dismissible fade show" role="alert">
        <?= $flash_error ?? ''; ?>
    </div>
    <script>
        setTimeout(()=>{
            var alertId = document.getElementById('alert')
            alertId.classList.add('modal')
        }, 2000);
    </script>

</div><!-- container -->

<!--Добавление ключевого слова "modal" в список классов alert div
удаляет его из DOM -->
