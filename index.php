<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Тестовая форма</title>
<link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Оставьте свои данные</h2>
    <form>
        <input type="hidden" name="TIME" value="0">
        <input type="text" placeholder="Имя" name="NAME" required>
        <input type="email" placeholder="Email" name="EMAIL" required>
        <input type="tel" placeholder="Телефон" name="PHONE" required>
        <input type="number" placeholder="Цена" name="PRICE" required>
        <button type="submit">Отправить</button>
    </form>
    <div id="success-message" style="display: none; color: green;">Форма успешно отправлена!</div>
    <div id="error-message" style="display: none; color: red;">Произошла ошибка при отправке формы. Попробуйте еще раз!</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeField = document.querySelector('input[name="TIME"]');

        let timeoutId;
        window.addEventListener('load', function() {
            timeoutId = setTimeout(function() {
                timeField.value = '1';
            }, 30000);
        });

        window.addEventListener('unload', function() {
            clearTimeout(timeoutId);
        });

        const form = document.querySelector('form');
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('send.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                if (data.includes('error')) {
                    document.getElementById('error-message').style.display = 'block';
                    form.style.display = 'none';
                } else {
                    document.getElementById('success-message').style.display = 'block';
                    form.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Произошла ошибка:', error);
                errorMessage.style.display = 'block';
                form.style.display = 'none';
            });
        });
    });
</script>
</body>
</html>