<!-- app/Views/login_view.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Data Login</title>
</head>
<body>
    <h1>Data Login</h1>
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($loginData as $row) : ?>
                <tr>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['PASSWORD']; ?></td> <!-- Menggunakan huruf besar 'PASSWORD' -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
