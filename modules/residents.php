<?php
include '../config.php';
include '../header.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($action === 'delete' && $id) {
    $conn->query("DELETE FROM residents WHERE ResidentID = '$id'");
    header("Location: residents.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $fields = ['ApartmentID', 'Name', 'BillStatus', 'MaritalStatus', 'Contact', 'SeniorCitizen', 'Age', 'OwnershipType'];
    $values = [];
    foreach ($fields as $field) {
        $values[] = "'" . $conn->real_escape_string($_POST[$field]) . "'";
    }
    $sql = "INSERT INTO residents (" . implode(",", $fields) . ") VALUES (" . implode(",", $values) . ")";
    $conn->query($sql);
    echo "<div class='alert alert-success'>Record added.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && $id) {
    $fields = ['ApartmentID', 'Name', 'BillStatus', 'MaritalStatus', 'Contact', 'SeniorCitizen', 'Age', 'OwnershipType'];
    $updates = [];
    foreach ($fields as $field) {
        $val = $conn->real_escape_string($_POST[$field]);
        $updates[] = "$field = '$val'";
    }
    $sql = "UPDATE residents SET " . implode(",", $updates) . " WHERE ResidentID = '$id'";
    $conn->query($sql);
    echo "<div class='alert alert-info'>Record updated.</div>";
}

if ($action === 'add' || ($action === 'edit' && $id)) {
    $row = [];
    if ($action === 'edit') {
        $result = $conn->query("SELECT * FROM residents WHERE ResidentID = '$id'");
        $row = $result->fetch_assoc();
    }
?>
    <h2 class="mb-4"><?php echo ucfirst($action); ?> Resident</h2>
    <form method="post">
<?php foreach (['ApartmentID', 'Name', 'BillStatus', 'MaritalStatus', 'Contact', 'SeniorCitizen', 'Age', 'OwnershipType'] as $col): ?>
        <div class="mb-3">
            <label for="<?php echo $col; ?>" class="form-label"><?php echo $col; ?></label>
            <input type="text" class="form-control" name="<?php echo $col; ?>" value="<?php echo isset($row[$col]) ? htmlspecialchars($row[$col]) : ''; ?>">
        </div>
<?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="residents.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
<?php
    include '../footer.php';
    exit;
}

echo "<h2 class='text-center mb-4'>Residents Management</h2>";
echo "<a href='residents.php?action=add' class='btn btn-success mb-3'>Add New</a>";
echo "<a href='../index.php' class='btn btn-secondary mb-3 ms-2'>Home</a>";

$result = $conn->query("SELECT ResidentID, ApartmentID, Name, BillStatus, MaritalStatus, Contact, SeniorCitizen, Age, OwnershipType FROM residents");

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered'><thead><tr>";
    foreach (['ResidentID', 'ApartmentID', 'Name', 'BillStatus', 'MaritalStatus', 'Contact', 'SeniorCitizen', 'Age', 'OwnershipType'] as $col) {
        echo "<th>$col</th>";
    }
    echo "<th>Actions</th></tr></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach (['ResidentID', 'ApartmentID', 'Name', 'BillStatus', 'MaritalStatus', 'Contact', 'SeniorCitizen', 'Age', 'OwnershipType'] as $col) {
            echo "<td>" . htmlspecialchars($row[$col]) . "</td>";
        }
        echo "<td>
            <a href='residents.php?action=edit&id=" . $row['ResidentID'] . "' class='btn btn-sm btn-warning'>Edit</a>
            <a href='residents.php?action=delete&id=" . $row['ResidentID'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this record?\");'>Delete</a>
        </td></tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No records found.</p>";
}

include '../footer.php';
?>