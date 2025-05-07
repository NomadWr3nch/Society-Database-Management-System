<?php
include '../config.php';
include '../header.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

// DELETE
if ($action === 'delete' && $id) {
    $conn->query("DELETE FROM maintenance WHERE MaintenanceID = '$id'");
    header("Location: maintenance.php");
    exit;
}

// INSERT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $fields = ['ResidentID', 'Amount', 'PaidStatus', 'DateTime'];
    $values = [];
    foreach ($fields as $field) {
        $values[] = "'" . $conn->real_escape_string($_POST[$field]) . "'";
    }
    $sql = "INSERT INTO maintenance (ResidentID, Amount, PaidStatus, DateTime) VALUES (" . implode(",", $values) . ")";
    $conn->query($sql);
    echo "<div class='alert alert-success'>Record added.</div>";
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && $id) {
    $fields = ['ResidentID', 'Amount', 'PaidStatus', 'DateTime'];
    $updates = [];
    foreach ($fields as $field) {
        $val = $conn->real_escape_string($_POST[$field]);
        $updates[] = "$field = '$val'";
    }
    $sql = "UPDATE maintenance SET " . implode(",", $updates) . " WHERE MaintenanceID = '$id'";
    $conn->query($sql);
    echo "<div class='alert alert-info'>Record updated.</div>";
}

// FORM
if ($action === 'add' || ($action === 'edit' && $id)) {
    $row = [];
    if ($action === 'edit') {
        $result = $conn->query("SELECT * FROM maintenance WHERE MaintenanceID = '$id'");
        $row = $result->fetch_assoc();
    }
?>
    <h2 class="mb-4"><?php echo ucfirst($action); ?> Maintenance</h2>
    <form method="post">
        <div class="mb-3">
            <label for="ResidentID" class="form-label">ResidentID</label>
            <input type="text" class="form-control" id="ResidentID" name="ResidentID" value="<?php echo isset($row['ResidentID']) ? htmlspecialchars($row['ResidentID']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="Amount" class="form-label">Amount</label>
            <input type="text" class="form-control" id="Amount" name="Amount" value="<?php echo isset($row['Amount']) ? htmlspecialchars($row['Amount']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="PaidStatus" class="form-label">PaidStatus</label>
            <input type="text" class="form-control" id="PaidStatus" name="PaidStatus" value="<?php echo isset($row['PaidStatus']) ? htmlspecialchars($row['PaidStatus']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="DateTime" class="form-label">DateTime</label>
            <input type="text" class="form-control" id="DateTime" name="DateTime" value="<?php echo isset($row['DateTime']) ? htmlspecialchars($row['DateTime']) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="maintenance.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
<?php
    include '../footer.php';
    exit;
}

// DEFAULT LIST VIEW
echo "<h2 class='text-center mb-4'>Maintenance Management</h2>";
echo "<a href='maintenance.php?action=add' class='btn btn-success mb-3'>Add New</a>";
echo "<a href='../index.php' class='btn btn-secondary mb-3 ms-2'>Home</a>";

$result = $conn->query("SELECT * FROM maintenance");
if ($result->num_rows > 0) {
    echo "<table class='table table-bordered'><thead><tr>";
    while ($fieldinfo = $result->fetch_field()) {
        echo "<th>{ $fieldinfo->name }</th>";
    }
    echo "<th>Actions</th></tr></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "<td>
            <a href='maintenance.php?action=edit&id=" . $row['MaintenanceID'] . "' class='btn btn-sm btn-warning'>Edit</a>
            <a href='maintenance.php?action=delete&id=" . $row['MaintenanceID'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this record?\");'>Delete</a>
        </td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No records found.</p>";
}

include '../footer.php';
?>