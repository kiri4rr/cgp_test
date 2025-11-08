<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Управление пользователями</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <script rel="preload" as="script" src="{{ asset('js/script.js') }}"></script>
</head>
<body>
    <div class="container">
        <!-- Add user from -->
        <div class="card">
            <h2>Add user</h2>

            <div id="formAlert" class="alert"></div>

            <form id="userForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" required>
                </div>

                <div class="form-group">
                    <label for="image">Image(JPG, PNG):</label>
                    <input type="file" id="image" name="image" accept="image/jpg,image/png" required>
                </div>

                <button type="submit" id="submitBtn">Add user</button>
            </form>
        </div>

        <!-- User's table -->
        <div class="card">
            <h1>User's table</h1>

            <div id="tableAlert" class="alert"></div>

            <div id="loadingIndicator" class="loading" style="display: none;">
                <div class="spinner"></div>
                <p>Loading...</p>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>City</th>
                            <th>Number of image</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
