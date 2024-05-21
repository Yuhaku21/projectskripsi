<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
               <div class="card">
                <div class="card-body">
                <h2 class="text-center">Login</h2>
                <form action="proses-login-admin.php" method="POST" class="mt-4">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                    <div class="catatan mt-3">
                    <p><b>Catatan:</b></p>
                    <p>username: admin</p>
                    <p>password: admin123</p>
                    </div>
                </form>
                </div>
               </div>
            </div>
        </div>
    </div>
</body>
</html>
