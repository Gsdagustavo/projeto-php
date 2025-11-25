<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
</head>
<body>

<div class="container">
    <h1>Login</h1>
    <input id="username-input" type="text" placeholder="Username"><br>
    <input id="password-input" type="password" placeholder="Password"><br>
    <input id="submit-btn" type="submit" value="Login">
</div>

<script>

    const loginBtn = document.getElementById('submit-btn');
    loginBtn.addEventListener('click', async () => {
        const usernameInput = document.getElementById('username-input');
        const passInput = document.getElementById('password-input');

        const user = {
            username: usernameInput.value,
            password: passInput.value,
        };

        const response = await fetch('../api.php?route=login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(user)
        });

        if (!response.ok) {
            alert('Erro interno no servidor. Tente novamente mais tarde');
            return;
        }

        const data = await response.json();

        console.log(data);

        alert(data.message);

        if (data.success) {
            window.location.href = '/projeto-php/public/pages/home.php';
        }
    })

</script>

</body>
</html>