const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function showAlert(elementId, message, type) {
    const alertElement = document.getElementById(elementId);
    alertElement.textContent = message;
    alertElement.className = `alert alert-${type}`;
    alertElement.style.display = 'block';
    setTimeout(() => {
        alertElement.style.display = 'none';
    }, 5000);
}

function loadUsers() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    const tableBody = document.getElementById('usersTableBody');
    loadingIndicator.style.display = 'block';
    tableBody.innerHTML = '';
    fetch('/api/users', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Ошибка загрузки данных');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        loadingIndicator.style.display = 'none';
        if (!data.status) {
            tableBody.innerHTML = '<tr><td colspan="3" class="empty-state">Пользователи не найдены</td></tr>';
            return;
        }
        data.data.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${escapeHtml(user.name)}</td>
                <td>${escapeHtml(user.city)}</td>
                <td>${user.images_count}</td>
            `;
            tableBody.appendChild(row);
        });
    })
    .catch(error => {
        loadingIndicator.style.display = 'none';
        showAlert('tableAlert', 'Error loading users: ' + error.message, 'error');
        tableBody.innerHTML = '<tr><td colspan="3" class="empty-state">Error loading data</td></tr>';
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', function() {
    document
    .getElementById('userForm')
    .addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('submitBtn');
        const formData = new FormData(this);
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';
        fetch('/api/create-user', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'User created error!');
                });
            }
            return response.json();
        })
        .then(data => {
            showAlert('formAlert', 'User created successful!', 'success');
            document.getElementById('userForm').reset();
            loadUsers();
        })
        .catch(error => {
            showAlert('formAlert', 'Error: ' + error.message, 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add user';
        });
    });
    loadUsers();
});
