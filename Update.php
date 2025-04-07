<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = "";
$messageType = "";

include 'classes/database.php';
include 'classes/Appointment.php';


$database = new Database();
$conn = $database->getConnection();

$appointment = new Appointment($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = intval($_POST['appointment_id']);
    $naam = $_POST['naam'] ?? null;
    $soort = $_POST['soort'] ?? null;

    $tijdInput = $_POST['tijd'] ?? null;
    $tijd = isset($tijdInput) ? sprintf('%02d:00:00', $tijdInput) : null;

    if (!empty($naam) || !empty($soort) || !empty($tijd)) {
        // ✅ Check if the appointment exists
        $check_sql = "SELECT * FROM afspraken WHERE id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $appointment_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            if ($appointment->updateAppointment($appointment_id, $naam, $soort, $tijd)) {
                $message = "Afspraak met ID $appointment_id is succesvol bijgewerkt.";
                $messageType = "success";
            } else {
                $message = "Er is een fout opgetreden bij het bijwerken van de afspraak.";
                $messageType = "error";
            }
        } else {
            $message = "Afspraak niet gevonden.";
            $messageType = "error";
        }
    } else {
        $message = "Geen velden ingevuld om bij te werken.";
        $messageType = "error";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afspraak Wijzigen</title>
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
    <h1>Afspraak Wijzigen</h1>

    <?php if (!empty($message)): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="update.php" method="POST">
        <label for="appointment_id">Voer Afspraak ID In:</label>
        <input type="number" name="appointment_id" id="appointment_id" required><br><br>

        <label for="naam">Naam:</label>
        <input type="text" name="naam" id="naam"><br><br>

        <div class="form-group">
            <label for="soort">Nieuwe soort afspraak:</label>
            <select name="soort" id="soort">
                <option value="">Selecteer een optie</option>
                <option value="knippen">Knippen (€25,00)</option>
                <option value="vlechten">Vlechten (€50,00)</option>
                <option value="vlechten en knippen">Vlechten en Knippen (€75,00)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tijd">Nieuwe tijd:</label>
            <select name="tijd" id="tijd" required>
                <option value="9">9:00</option>
                <option value="10">10:00</option>
                <option value="11">11:00</option>
                <option value="12">12:00</option>
                <option value="14">14:00</option>
                <option value="15">15:00</option>
                <option value="17">17:00</option>
                <option value="19">19:00</option>
                <option value="20">20:00</option>
            </select>
        </div>

        <button type="submit">Afspraak Wijzigen</button>
    </form>
</div>

</body>
</html>
