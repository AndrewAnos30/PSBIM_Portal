<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PSBIM Portal</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <main class="content-container">
        <!-- Optional PCP Logo -->
        <div class="logo-container">
            <img src="img/psbim-logo.png" alt="PSBIM Logo" style="width:150px; margin-bottom:1rem;">
        </div>

        <div class="title">
            <h2>Member Login</h2>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error-msg">
                <?= htmlspecialchars($_SESSION['error']); ?>
            </p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="form-container">
            <form action="process_login.php" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="login">Login</button>
                </div>
            </form>
        </div>
    </main>

    <footer>
        &copy; Philippine Specialty Board in Internal Medicine
    </footer>
</body>
</html>
