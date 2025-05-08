<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['EOInumber'] . "</td>";
        echo "<td>" . $row['Job Reference number'] . "</td>";
        echo "<td>" . $row['First name'] . " " . $row['Last name'] . "</td>";
        echo "<td>" . $row['Address'] . "</td>";
        echo "<td>" . $row['Email'] . "</td>";
        echo "<td>" . $row['Phone'] . "</td>";
        echo "<td>" . $row['Skills'] . "</td>";
        echo "<td>" . $row['Other skills'] . "</td>";
        echo "<td class='status'>";
        echo "<form method='get' action='manage.php'>";
        echo "<input type='hidden' name='eoi_number' value='" . $row['EOInumber'] . "'>";
        echo "<select name='new_status' id='new_status'>";
        $statuses = ['New', 'Current', 'Final'];
        foreach ($statuses as $status) {
            $selected = ($row['Status'] === $status) ? "selected" : "";
            echo "<option value='$status' $selected>$status</option>";
        }
        echo "</select> ";
        echo "<button type='submit' name='update' value='status'>Update</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr>";
    echo "<td colspan='9'>There are no eoi to display.</td>";
    echo "</tr>";
}
?>