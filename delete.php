<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'classes/database.php';
include 'classes/Appointment.php';


$message = "";
$messageType = "";


$database = new Database();
$conn = $database->getConnection();


$appointment = new Appointment($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);

    if ($appointment->deleteAppointment($appointment_id)) {
        $message = "Afspraak #$appointment_id is succesvol geannuleerd.";
        $messageType = "success";
    } else {
        $message = "Afspraak met ID $appointment_id niet gevonden of kon niet worden verwijderd.";
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afspraak Annuleren</title>
    <link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>

<div class="navbar">
    <a href="afspraak-maken.php">Afspraak Maken</a>
    <a href="index.php">Afspraken</a>
    <a href="update.php">Afspraak Wijzigen</a>
    <a href="delete.php">Afspraak Annuleren</a>
    <a href="logout.php">Uitloggen</a>
</div>

<div class="container">
    <h1>Afspraak Annuleren</h1>

    <?php if (!empty($message)): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="delete.php" method="POST">
        <label for="appointment_id">Voer afspraak ID in om te annuleren:</label>
        <input type="number" name="appointment_id" id="appointment_id" required>
        <button type="submit">Verwijder Afspraak</button>
    </form>
</div>

</body>
</html>
