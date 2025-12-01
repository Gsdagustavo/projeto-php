<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .col-id {
            width: 20px;
            white-space: nowrap;
        }
    </style>
</head>
<body class="p-4">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Home</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        Add User
    </button>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered w-100">
        <thead class="table-dark">
        <tr>
            <th class="col-id">ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Birth Date</th>
        </tr>
        </thead>
        <tbody id="usersTableBody"></tbody>
    </table>
</div>

<!-- ADD USER MODAL -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label class="form-label mt-2">Username</label>
                <input type="text" id="newUsername" class="form-control" placeholder="Enter username">

                <label class="form-label mt-3">Email</label>
                <input type="email" id="newEmail" class="form-control" placeholder="Enter email">

                <label class="form-label mt-3">Birth Date</label>
                <input type="date" id="newBirthDate" class="form-control">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="saveUserBtn">Save</button>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    class User {
        constructor(id, username, email, birthdate) {
            this.id = id;
            this.username = username;
            this.email = email;
            this.birthdate = birthdate;
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        await loadUsers();
        document.getElementById('saveUserBtn').addEventListener('click', addUser);
    });

    async function loadUsers() {
        const response = await fetch('../api.php?route=users');

        if (!response.ok) {
            alert('Erro interno no servidor. Tente novamente mais tarde');
            return;
        }

        const usersJSON = await response.json();
        const tbody = document.querySelector('#usersTableBody');
        tbody.innerHTML = "";

        usersJSON.forEach(data => {
            const user = new User(data.id, data.username, data.email, data.birth_date);
            tbody.appendChild(buildRow(user));
        });
    }

    function buildRow(user) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="col-id">${user.id}</td>
            <td>${user.username}</td>
            <td>${user.email || "-"}</td>
            <td>${user.birthdate || "-"}</td>
        `;
        return tr;
    }

    async function addUser() {
        const username = document.getElementById('newUsername').value.trim();
        const email = document.getElementById('newEmail').value.trim();
        const birthdate = document.getElementById('newBirthDate').value;

        const response = await fetch('../api.php?route=users', {
            method: 'POST',
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({username, email, birthdate})
        });

        if (!response.ok) {
            alert("Erro interno no servidor. Tente novamente mais tarde");
            return;
        }

        const data = await response.json();

        if (!data.success) {
            alert(data.message);
            return;
        }

        const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
        modal.hide();

        document.getElementById('newUsername').value = "";
        document.getElementById('newEmail').value = "";
        document.getElementById('newBirthDate').value = "";

        await loadUsers();
    }
</script>

</body>
</html>
