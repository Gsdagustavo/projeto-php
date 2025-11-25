<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
</head>
<body>
<h1>Home</h1>

<script>

    document.addEventListener('DOMContentLoaded', async () => {
            const response = await fetch('../api.php?route=users');

            if (!response.ok) {
                alert('Erro interno no servidor. Tente novamente mais tarde')
                return;
            }

            const usersJSON = await response.json();

            console.log(usersJSON);
        }
    );

    function buildCard(id, name) {
        let card = document.createElement('div');
        card.id = 'card' + id;
        card.classList.add('card');
        card.appendChild(card);
        return card;
    }

</script>


</body>
</html>