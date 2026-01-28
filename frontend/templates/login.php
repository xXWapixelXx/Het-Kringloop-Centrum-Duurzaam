<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Login template
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kringloop Centrum Duurzaam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #3754DB;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 360px;
            padding: 40px 35px;
        }
        .login-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-title h2 {
            color: #333;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .login-title p {
            color: #666;
            font-size: 0.9rem;
        }
        .form-label {
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: #3754DB;
            box-shadow: 0 0 0 3px rgba(55, 84, 219, 0.15);
        }
        .btn-login {
            background-color: #3754DB;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            font-weight: 500;
            width: 100%;
            margin-top: 10px;
            color: white;
        }
        .btn-login:hover {
            background-color: #2a41a8;
            color: white;
        }
        .alert {
            border-radius: 8px;
            font-size: 0.9rem;
            padding: 12px 15px;
        }
        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: #999;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="login-title">
        <h2>Kringloop Centrum</h2>
        <p>Log in om verder te gaan</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="gebruikersnaam" class="form-label">Gebruikersnaam</label>
            <input type="text" class="form-control" id="gebruikersnaam" name="gebruikersnaam" required autofocus>
        </div>

        <div class="mb-3">
            <label for="wachtwoord" class="form-label">Wachtwoord</label>
            <input type="password" class="form-control" id="wachtwoord" name="wachtwoord" required>
        </div>

        <button type="submit" class="btn btn-login">Inloggen</button>
    </form>

    <p class="footer-text">Neem contact op met beheerder voor een account</p>
</div>

</body>
</html>
