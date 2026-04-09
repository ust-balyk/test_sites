<div class="container">
<style>
    .alert_white {
        background: white;
        font-size: 16px;
        font-weight: 600;
        color: red;
    }
</style>
    <div id="alert" class="alert alert_white alert-dismissible fade show" role="alert">
        <?= $flash_error ?? ''; ?>
    </div>
    <script>
        setTimeout(()=>{
            var alertId = document.getElementById('alert')
            alertId.classList.add('modal')
        }, 3500);
    </script>

</div><!-- container -->

<!--Добавление ключевого слова "modal" в список классов alert div
удаляет его из DOM -->
