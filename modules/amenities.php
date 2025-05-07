<?php
include '../config.php';
include '../header.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

// DELETE
if ($action === 'delete' && $id) {
    $conn->query("DELETE FROM amenities WHERE AmenityID = '$id'");
    header("Location: amenities.php");
    exit;
}

// INSERT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $fields = ['Name', 'Status', 'Directions', 'FeesPaid'];
    $values = [];
    foreach ($fields as $field) {
        $values[] = "'" . $conn->real_escape_string($_POST[$field]) . "'";
    }
    $sql = "INSERT INTO amenities (Name, Status, Directions, FeesPaid) VALUES (" . implode(",", $values) . ")";
    $conn->query($sql);
    echo "<div class='alert alert-success'>Record added.</div>";
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && $id) {
    $fields = ['Name', 'Status', 'Directions', 'FeesPaid'];
    $updates = [];
    foreach ($fields as $field) {
        $val = $conn->real_escape_string($_POST[$field]);
        $updates[] = "$field = '$val'";
    }
    $sql = "UPDATE amenities SET " . implode(",", $updates) . " WHERE AmenityID = '$id'";
    $conn->query($sql);
    echo "<div class='alert alert-info'>Record updated.</div>";
}

// FORM
if ($action === 'add' || ($action === 'edit' && $id)) {
    $row = [];
    if ($action === 'edit') {
        $result = $conn->query("SELECT * FROM amenities WHERE AmenityID = '$id'");
        $row = $result->fetch_assoc();
    }
?>
    <h2 class="mb-4"><?php echo ucfirst($action); ?> Amenities</h2>
    <form method="post">
        <div class="mb-3">
            <label for="Name" class="form-label">Name</label>
            <input type="text" class="form-control" id="Name" name="Name" value="<?php echo isset($row['Name']) ? htmlspecialchars($row['Name']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="Status" class="form-label">Status</label>
            <input type="text" class="form-control" id="Status" name="Status" value="<?php echo isset($row['Status']) ? htmlspecialchars($row['Status']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="Directions" class="form-label">Directions</label>
            <input type="text" class="form-control" id="Directions" name="Directions" value="<?php echo isset($row['Directions']) ? htmlspecialchars($row['Directions']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="FeesPaid" class="form-label">FeesPaid</label>
            <input type="text" class="form-control" id="FeesPaid" name="FeesPaid" value="<?php echo isset($row['FeesPaid']) ? htmlspecialchars($row['FeesPaid']) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="amenities.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
<?php
    include '../footer.php';
    exit;
}

// DEFAULT LIST VIEW
echo "<h2 class='text-center mb-4'>Amenities Management</h2>";
echo "<a href='amenities.php?action=add' class='btn btn-success mb-3'>Add New</a>";
echo "<a href='../index.php' class='btn btn-secondary mb-3 ms-2'>Home</a>";

$result = $conn->query("SELECT * FROM amenities");
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
            <a href='amenities.php?action=edit&id=" . $row['AmenityID'] . "' class='btn btn-sm btn-warning'>Edit</a>
            <a href='amenities.php?action=delete&id=" . $row['AmenityID'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this record?\");'>Delete</a>
        </td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No records found.</p>";
}

include '../footer.php';
?>