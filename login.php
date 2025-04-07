<?php
session_start();


$conn = new mysqli("localhost", "root", "", "wishlist");



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if (!$email || !$password) {
        echo "<p style='color:red; text-align:center;'>Vul alle velden in.</p>";
    } else {
    
        $sql = "SELECT * FROM account WHERE email = ?";
        $stmt = $conn->prepare($sql); 

        if (!$stmt) {
            die("SQL preparation failed: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: index.php");
                exit;
            } else {
                echo "<p style='color:red; text-align:center;'>Onjuist wachtwoord.</p>";
            }
        } else {
            echo "<p style='color:red; text-align:center;'>Geen account gevonden met dit e-mailadres.</p>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <style>
       
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        
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

    <div class="navbar">
        <a href="Login.php">Login</a>
        <a href="Register.php">Register</a>
    </div>

    
    <h2>Inloggen</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Wachtwoord:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Log in</button>
    </form>
</body>
</html>