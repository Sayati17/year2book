<?php
// Set secure session cookie attributes
session_set_cookie_params([
    'lifetime' => 3600, // adjust as needed
    'path' => '/',
    // 'domain' => 'yourdomain.com',
    'secure' => false, // if deployed set into true for https
    'httponly' => true,
    'samesite' => 'Strict',
]);
require "connection/conn.php";
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ./login_page.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
</head>
<body>
    <form method="POST" action="">
        <input type="text" placeholder="Search" name="search" value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>"></input>
        <button type="submit">Search</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['search'])) {
            $query = $_POST['search'];
            // Sanitize user input for search
            $query = htmlspecialchars($query);

            $search = "SELECT id, username, nim, majority, yearofgraduation FROM users WHERE nim = ? OR username = ? OR majority = ? OR yearofgraduation = ?";
            $stmt = $conn->prepare($search);
            $stmt->bind_param("ssss", $query, $query, $query,$query);
            $stmt->execute();
            $result = $stmt->get_result();
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<table>";
                echo "<tr>";
                echo "<th>" . htmlspecialchars($counter) . "</th>";
                echo '<td>|</td>';
                echo "<th>" . htmlspecialchars($row['nim']) . "</th>";
                echo '<th>|</th>';
                echo "<th>" . htmlspecialchars($row['username']) . "</th>";
                echo '<th>|</th>';
                echo "<th>" . htmlspecialchars($row['majority']) . "</th>";
                echo '<th>|</th>';
                echo "<th>" . htmlspecialchars($row['yearofgraduation']) . "</th>";
                echo '<th>|</th>';
                echo "</tr>";
                echo "</table>";
                $counter++;
            }
            if($result->num_rows == 0){
                echo "Data Not Found";
            }
        }
    }
    ?>
    <br>
    <table>
        <tr>
            <th>No</th>
            <th>|</th>
            <th>NIM</th>
            <th>|</th>
            <th>Username</th>
            <th>|</th>
            <th>Majority</th>
            <th>|</th>
            <th>Year of Graduation</th>
            <th>|</th>
        </tr>

        <?php
            $query = "SELECT id, username, nim, majority, yearofgraduation FROM users ORDER BY yearofgraduation";
            $result = $conn->query($query);
            $counter = 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($counter) . "</td>";
                    echo '<td>|</td>';
                    echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
                    echo '<td>|</td>';
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo '<td>|</td>';
                    echo "<td>" . htmlspecialchars($row['majority']) . "</td>";
                    echo '<td>|</td>';
                    echo "<th>" . htmlspecialchars($row['yearofgraduation']) . "</th>";
                    echo '<th>|</th>';
                    echo "</tr>";
                    $counter++;
                }
            } else {
                echo '<tr><td colspan="7">No data</td></tr>';
            }
        ?>
    </table>
    <br>
    <form action="./Home.php">
        <button type="submit">Back to Home</button>
    </form>
</body>
</html>
