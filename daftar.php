<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pengguna</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5" style="width: 40%;">
       <div class="card shadow">
        <div class="card-body">
        <h2 class="mt-3 text-center">Daftar</h2>
        <form action="daftar-proses.php" method="post">
            <div class="form-group">
                <label for="username">Buat username baru:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Buat password baru:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Daftar sekarang</button>
            <p class="mt-3">Sudah punya akun? login <a href="login-pengguna.php">Disini</a></p>
        </form>
        </div>
       </div>
    </div>
</body>
</html>
