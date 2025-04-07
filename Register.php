<?php
require_once 'classes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Hash het wachtwoord

    $sql = "INSERT INTO account (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "Registratie succesvol! <a href='Login.php'>Log in</a>";
    } else {
        echo "Er is een fout opgetreden: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
    <style>
        /* Algemene styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Navigatiebalk styling */
        .navbar {
            display: flex;
            justify-content: center;
            background-color: #333;
            padding: 10px 0;
        }

        .navbar a {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #555;
        }

        /* Container voor het formulier */
        h2 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        form {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #555;
        }

        /* Responsiviteit */
        @media (max-width: 600px) {
            .navbar {
                flex-direction: column;
                align-items: center;
            }

            .navbar a {
                margin: 5px 0;
            }

            form {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigatiebalk -->
    <div class="navbar">
        <a href="Login.php">Login</a>
        <a href="Register.php">Register</a>
    </div>

    <!-- Registrerenformulier -->
    <h2>Registreren</h2>
    <form method="POST">
        <label>Gebruikersnaam:</label>
        <input type="text" name="username" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Wachtwoord:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Registreer</button>
    </form>
</body>
</html>
