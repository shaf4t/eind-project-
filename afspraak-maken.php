<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afspraak maken</title>
    <link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>
 
<div class="navbar">
    <a href="afspraak-maken.php">afspraak maken</a>
    <a href="index.php">afspraken</a>
    <a href="update.php">afspraak wijzigen</a>
    <a href="delete.php">afspraak annuleren</a>
    <a href="logout.php">uitloggen</a>
 
</div>
 
<div class="container">
    <h1>afspraak maken</h1>
 
    <form action="afspraak-maken.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="naam">naam:</label>
        <input type="text" name="naam" id="naam" required>
    </div>

    <div class="form-group">
        <label for="tijd">gewenste tijd:</label>
        <select name="tijd" id="tijd" required>
        <option value="9">9:00</option>
        <option value="10">10:00</option>
        <option value="11">11:00</option>
        <option value="12">12:00</option>
        <option value="13">14:00</option>
        <option value="14">15:00</option>
        <option value="15">17:00</option>
        <option value="16">19:00</option>
        <option value="17">20:00</option>
        </select>
    </div>

    <div class="form-group">
    <label for="soort">Soort afspraak:</label>
    <select name="soort" id="soort" required>
    <option value="niks"></option>
    <option value="vlechten">knippen (25,00)</option>
        <option value="vlechten">vlechten (50,00)</option>
        <option value="vlechten en knippen">vlechten en knippen (75,00)</option>
    </select>
</div>


    <button type="submit">afspraak maken</button>
</form>

</div>

<?php
include 'classes/database.php';


$database = new Database();
$conn = $database->getConnection(); // Get the connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'];  
    $tijd = $_POST['tijd'];  
    $soort = $_POST['soort'];  

    
    if (!$conn) {
        die("Database connection failed.");
    }

    
    $tijdFormatted = sprintf("%02d:00", $tijd);

    $stmt = $conn->prepare("INSERT INTO afspraken (naam, tijd, soort) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $naam, $tijdFormatted, $soort);

    if ($stmt->execute()) {
    
        $formattedDisplayTime = date('G:i', strtotime($tijdFormatted));
        echo "<div class='alert' style='display:block;'>Afspraak ingepland voor $formattedDisplayTime!</div>";
    } else {
        echo "<div class='alert error' style='display:block;'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>
