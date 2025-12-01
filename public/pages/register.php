<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f0f2f5;
        }

        #feedback {
            color: red;
        }

    </style>
</head>
<body>

<div class="card shadow p-4"
     style="width: 400px; height: 400px; display: flex; flex-direction: column;">

    <h3 class="text-center mb-3">Registrar</h3>

    <div class="fields" style="flex: 1; display: flex; flex-direction: column;">
        <div class="mb-3">
            <input id="username-input" type="text" class="form-control" placeholder="Usuário">
        </div>

        <div class="mb-3">
            <input id="email-input" type="email" class="form-control" placeholder="Email">
        </div>

        <div class="mb-3">
            <input id="birth-input" type="date" class="form-control" placeholder="Data de nascimento">
        </div>

        <div class="mb-3">
            <input id="password-input" type="password" class="form-control" placeholder="Senha">
        </div>

        <div id="feedback"></div>

        <button id="submit-btn" class="btn btn-primary w-100" style="margin-top: auto;">
            Entrar
        </button>

        <div style="display: flex; justify-content: center; margin-top: 16px">Já tem uma conta?<a href="login.php">
                Entre aqui</a></div>
    </div>

</div>

<script>
    const registerBtn = document.getElementById('submit-btn');

    registerBtn.addEventListener('click', async () => {
        const usernameInput = document.getElementById('username-input');
        const emailInput = document.getElementById('email-input');
        const birthDateInput = document.getElementById('birth-input');
        const passInput = document.getElementById('password-input');

        const user = {
            username: usernameInput.value,
            email: emailInput.value,
            birthDateInput: birthDateInput.value,
            password: passInput.value,
        };

        const response = await fetch('../api.php?route=register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(user)
        });

        const feedback = document.getElementById("feedback");

        if (!response.ok) {
            feedback.visible = true;
            feedback.innerHTML = 'Erro interno no servidor.';
            return;
        }

        const data = await response.json();

        if (!data.success) {
            feedback.visible = true;
            feedback.innerHTML = data.message;
            return;
        }

        feedback.visible = false
        feedback.innerHTML = ''

        window.location.href = '/projeto-php/public/pages/home.php';
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
