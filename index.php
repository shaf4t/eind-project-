<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wishlist"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT id, naam, tijd, soort FROM afspraken";
$result = $conn->query($sql);


if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle Afspraken</title>
    <link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>

<div class="navbar">
    <a href="afspraak-maken.php">Afspraak maken</a>
    <a href="index.php">afspraken</a>
    <a href="update.php">Afspraak wijzigen</a>
    <a href="delete.php">Afspraak annuleren</a>
    <a href="logout.php">Uitloggen</a>
</div>  

<div class="container">
    <h1>Alle geplande afspraken</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Gewenste tijd</th>
            <th>Soort afspraak</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Format tijd (e.g., "09:00:00") to "9:00"
                $formattedTijd = date('G:i', strtotime($row["tijd"]));

                echo "<tr>
                        <td>" . htmlspecialchars($row["id"]) . "</td>
                        <td>" . htmlspecialchars($row["naam"]) . "</td>
                        <td>" . htmlspecialchars($formattedTijd) . "</td>
                        <td>" . htmlspecialchars($row["soort"]) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Geen afspraken gevonden</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
