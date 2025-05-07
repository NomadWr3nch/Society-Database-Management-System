<?php
include '../config.php';
include '../header.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

// DELETE
if ($action === 'delete' && $id) {
    $conn->query("DELETE FROM entryexit WHERE EntryExitID = '$id'");
    header("Location: entryexit.php");
    exit;
}

// INSERT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $fields = ['PersonID', 'EntryDateTime', 'ExitDateTime'];
    $values = [];
    foreach ($fields as $field) {
        $values[] = "'" . $conn->real_escape_string($_POST[$field]) . "'";
    }
    $sql = "INSERT INTO entryexit (PersonID, EntryDateTime, ExitDateTime) VALUES (" . implode(",", $values) . ")";
    $conn->query($sql);
    echo "<div class='alert alert-success'>Record added.</div>";
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && $id) {
    $fields = ['PersonID', 'EntryDateTime', 'ExitDateTime'];
    $updates = [];
    foreach ($fields as $field) {
        $val = $conn->real_escape_string($_POST[$field]);
        $updates[] = "$field = '$val'";
    }
    $sql = "UPDATE entryexit SET " . implode(",", $updates) . " WHERE EntryExitID = '$id'";
    $conn->query($sql);
    echo "<div class='alert alert-info'>Record updated.</div>";
}

// FORM
if ($action === 'add' || ($action === 'edit' && $id)) {
    $row = [];
    if ($action === 'edit') {
        $result = $conn->query("SELECT * FROM entryexit WHERE EntryExitID = '$id'");
        $row = $result->fetch_assoc();
    }
?>
    <h2 class="mb-4"><?php echo ucfirst($action); ?> Entryexit</h2>
    <form method="post">
        <div class="mb-3">
            <label for="PersonID" class="form-label">PersonID</label>
            <input type="text" class="form-control" id="PersonID" name="PersonID" value="<?php echo isset($row['PersonID']) ? htmlspecialchars($row['PersonID']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="EntryDateTime" class="form-label">EntryDateTime</label>
            <input type="text" class="form-control" id="EntryDateTime" name="EntryDateTime" value="<?php echo isset($row['EntryDateTime']) ? htmlspecialchars($row['EntryDateTime']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="ExitDateTime" class="form-label">ExitDateTime</label>
            <input type="text" class="form-control" id="ExitDateTime" name="ExitDateTime" value="<?php echo isset($row['ExitDateTime']) ? htmlspecialchars($row['ExitDateTime']) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="entryexit.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
<?php
    include '../footer.php';
    exit;
}

// DEFAULT LIST VIEW
echo "<h2 class='text-center mb-4'>Entryexit Management</h2>";
echo "<a href='entryexit.php?action=add' class='btn btn-success mb-3'>Add New</a>";
echo "<a href='../index.php' class='btn btn-secondary mb-3 ms-2'>Home</a>";

$result = $conn->query("SELECT * FROM entryexit");
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
            <a href='entryexit.php?action=edit&id=" . $row['EntryExitID'] . "' class='btn btn-sm btn-warning'>Edit</a>
            <a href='entryexit.php?action=delete&id=" . $row['EntryExitID'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this record?\");'>Delete</a>
        </td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No records found.</p>";
}

include '../footer.php';
?>