<?php
include '../config.php';
include '../header.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

// DELETE
if ($action === 'delete' && $id) {
    $conn->query("DELETE FROM apartments WHERE ApartmentID = '$id'");
    header("Location: apartments.php");
    exit;
}

// INSERT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $fields = ['Floor', 'BlockNo', 'BHK', 'BlockName'];
    $values = [];
    foreach ($fields as $field) {
        $values[] = "'" . $conn->real_escape_string($_POST[$field]) . "'";
    }
    $sql = "INSERT INTO apartments (Floor, BlockNo, BHK, BlockName) VALUES (" . implode(",", $values) . ")";
    $conn->query($sql);
    echo "<div class='alert alert-success'>Record added.</div>";
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && $id) {
    $fields = ['Floor', 'BlockNo', 'BHK', 'BlockName'];
    $updates = [];
    foreach ($fields as $field) {
        $val = $conn->real_escape_string($_POST[$field]);
        $updates[] = "$field = '$val'";
    }
    $sql = "UPDATE apartments SET " . implode(",", $updates) . " WHERE ApartmentID = '$id'";
    $conn->query($sql);
    echo "<div class='alert alert-info'>Record updated.</div>";
}

// FORM
if ($action === 'add' || ($action === 'edit' && $id)) {
    $row = [];
    if ($action === 'edit') {
        $result = $conn->query("SELECT * FROM apartments WHERE ApartmentID = '$id'");
        $row = $result->fetch_assoc();
    }
?>
    <h2 class="mb-4"><?php echo ucfirst($action); ?> Apartments</h2>
    <form method="post">
        <div class="mb-3">
            <label for="Floor" class="form-label">Floor</label>
            <input type="text" class="form-control" id="Floor" name="Floor" value="<?php echo isset($row['Floor']) ? htmlspecialchars($row['Floor']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="BlockNo" class="form-label">BlockNo</label>
            <input type="text" class="form-control" id="BlockNo" name="BlockNo" value="<?php echo isset($row['BlockNo']) ? htmlspecialchars($row['BlockNo']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="BHK" class="form-label">BHK</label>
            <input type="text" class="form-control" id="BHK" name="BHK" value="<?php echo isset($row['BHK']) ? htmlspecialchars($row['BHK']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="BlockName" class="form-label">BlockName</label>
            <input type="text" class="form-control" id="BlockName" name="BlockName" value="<?php echo isset($row['BlockName']) ? htmlspecialchars($row['BlockName']) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="apartments.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
<?php
    include '../footer.php';
    exit;
}

// DEFAULT LIST VIEW
echo "<h2 class='text-center mb-4'>Apartments Management</h2>";
echo "<a href='apartments.php?action=add' class='btn btn-success mb-3'>Add New</a>";
echo "<a href='../index.php' class='btn btn-secondary mb-3 ms-2'>Home</a>";

$result = $conn->query("SELECT * FROM apartments");
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
            <a href='apartments.php?action=edit&id=" . $row['ApartmentID'] . "' class='btn btn-sm btn-warning'>Edit</a>
            <a href='apartments.php?action=delete&id=" . $row['ApartmentID'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this record?\");'>Delete</a>
        </td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No records found.</p>";
}

include '../footer.php';
?>