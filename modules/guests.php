<?php
include '../config.php';
include '../header.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

// DELETE
if ($action === 'delete' && $id) {
    $conn->query("DELETE FROM guests WHERE GuestID = '$id'");
    header("Location: guests.php");
    exit;
}

// INSERT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $fields = ['Name', 'Contact', 'VisitingReason', 'Address', 'RelationToResident'];
    $values = [];
    foreach ($fields as $field) {
        $values[] = "'" . $conn->real_escape_string($_POST[$field]) . "'";
    }
    $sql = "INSERT INTO guests (Name, Contact, VisitingReason, Address, RelationToResident) VALUES (" . implode(",", $values) . ")";
    $conn->query($sql);
    echo "<div class='alert alert-success'>Record added.</div>";
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && $id) {
    $fields = ['Name', 'Contact', 'VisitingReason', 'Address', 'RelationToResident'];
    $updates = [];
    foreach ($fields as $field) {
        $val = $conn->real_escape_string($_POST[$field]);
        $updates[] = "$field = '$val'";
    }
    $sql = "UPDATE guests SET " . implode(",", $updates) . " WHERE GuestID = '$id'";
    $conn->query($sql);
    echo "<div class='alert alert-info'>Record updated.</div>";
}

// FORM
if ($action === 'add' || ($action === 'edit' && $id)) {
    $row = [];
    if ($action === 'edit') {
        $result = $conn->query("SELECT * FROM guests WHERE GuestID = '$id'");
        $row = $result->fetch_assoc();
    }
?>
    <h2 class="mb-4"><?php echo ucfirst($action); ?> Guests</h2>
    <form method="post">
        <div class="mb-3">
            <label for="Name" class="form-label">Name</label>
            <input type="text" class="form-control" id="Name" name="Name" value="<?php echo isset($row['Name']) ? htmlspecialchars($row['Name']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="Contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="Contact" name="Contact" value="<?php echo isset($row['Contact']) ? htmlspecialchars($row['Contact']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="VisitingReason" class="form-label">VisitingReason</label>
            <input type="text" class="form-control" id="VisitingReason" name="VisitingReason" value="<?php echo isset($row['VisitingReason']) ? htmlspecialchars($row['VisitingReason']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="Address" class="form-label">Address</label>
            <input type="text" class="form-control" id="Address" name="Address" value="<?php echo isset($row['Address']) ? htmlspecialchars($row['Address']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="RelationToResident" class="form-label">RelationToResident</label>
            <input type="text" class="form-control" id="RelationToResident" name="RelationToResident" value="<?php echo isset($row['RelationToResident']) ? htmlspecialchars($row['RelationToResident']) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="guests.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
<?php
    include '../footer.php';
    exit;
}

// DEFAULT LIST VIEW
echo "<h2 class='text-center mb-4'>Guests Management</h2>";
echo "<a href='guests.php?action=add' class='btn btn-success mb-3'>Add New</a>";
echo "<a href='../index.php' class='btn btn-secondary mb-3 ms-2'>Home</a>";

$result = $conn->query("SELECT * FROM guests");
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
            <a href='guests.php?action=edit&id=" . $row['GuestID'] . "' class='btn btn-sm btn-warning'>Edit</a>
            <a href='guests.php?action=delete&id=" . $row['GuestID'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this record?\");'>Delete</a>
        </td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No records found.</p>";
}

include '../footer.php';
?>