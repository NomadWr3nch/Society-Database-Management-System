<?php
include '../config.php';
include '../header.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

// DELETE
if ($action === 'delete' && $id) {
    $conn->query("DELETE FROM owners WHERE OwnerID = '$id'");
    header("Location: owners.php");
    exit;
}

// INSERT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $fields = ['ApartmentID', 'BoughtOn', 'AadharID', 'Contact', 'NRI'];
    $values = [];
    foreach ($fields as $field) {
        $values[] = "'" . $conn->real_escape_string($_POST[$field]) . "'";
    }
    $sql = "INSERT INTO owners (ApartmentID, BoughtOn, AadharID, Contact, NRI) VALUES (" . implode(",", $values) . ")";
    $conn->query($sql);
    echo "<div class='alert alert-success'>Record added.</div>";
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && $id) {
    $fields = ['ApartmentID', 'BoughtOn', 'AadharID', 'Contact', 'NRI'];
    $updates = [];
    foreach ($fields as $field) {
        $val = $conn->real_escape_string($_POST[$field]);
        $updates[] = "$field = '$val'";
    }
    $sql = "UPDATE owners SET " . implode(",", $updates) . " WHERE OwnerID = '$id'";
    $conn->query($sql);
    echo "<div class='alert alert-info'>Record updated.</div>";
}

// FORM
if ($action === 'add' || ($action === 'edit' && $id)) {
    $row = [];
    if ($action === 'edit') {
        $result = $conn->query("SELECT * FROM owners WHERE OwnerID = '$id'");
        $row = $result->fetch_assoc();
    }
?>
    <h2 class="mb-4"><?php echo ucfirst($action); ?> Owners</h2>
    <form method="post">
        <div class="mb-3">
            <label for="ApartmentID" class="form-label">ApartmentID</label>
            <input type="text" class="form-control" id="ApartmentID" name="ApartmentID" value="<?php echo isset($row['ApartmentID']) ? htmlspecialchars($row['ApartmentID']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="BoughtOn" class="form-label">BoughtOn</label>
            <input type="text" class="form-control" id="BoughtOn" name="BoughtOn" value="<?php echo isset($row['BoughtOn']) ? htmlspecialchars($row['BoughtOn']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="AadharID" class="form-label">AadharID</label>
            <input type="text" class="form-control" id="AadharID" name="AadharID" value="<?php echo isset($row['AadharID']) ? htmlspecialchars($row['AadharID']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="Contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="Contact" name="Contact" value="<?php echo isset($row['Contact']) ? htmlspecialchars($row['Contact']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="NRI" class="form-label">NRI</label>
            <input type="text" class="form-control" id="NRI" name="NRI" value="<?php echo isset($row['NRI']) ? htmlspecialchars($row['NRI']) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="owners.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
<?php
    include '../footer.php';
    exit;
}

// DEFAULT LIST VIEW
echo "<h2 class='text-center mb-4'>Owners Management</h2>";
echo "<a href='owners.php?action=add' class='btn btn-success mb-3'>Add New</a>";
echo "<a href='../index.php' class='btn btn-secondary mb-3 ms-2'>Home</a>";

$result = $conn->query("SELECT * FROM owners");
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
            <a href='owners.php?action=edit&id=" . $row['OwnerID'] . "' class='btn btn-sm btn-warning'>Edit</a>
            <a href='owners.php?action=delete&id=" . $row['OwnerID'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this record?\");'>Delete</a>
        </td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No records found.</p>";
}

include '../footer.php';
?>