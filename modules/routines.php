<?php
include '../config.php';
include '../header.php';

$action = $_GET['action'] ?? '';
$id     = $_GET['id']     ?? '';

// DELETE
if ($action === 'delete' && $id) {
    $conn->query("DELETE FROM routines WHERE RoutineID = $id");
    header("Location: routines.php");
    exit;
}

// INSERT (ADD)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $resID    = $conn->real_escape_string($_POST['ResidentID']);
    $day      = $conn->real_escape_string($_POST['DayOfWeek']);
    $activity = $conn->real_escape_string($_POST['Activity']);
    $time     = $conn->real_escape_string($_POST['Time']);

    $conn->query("
      INSERT INTO routines (ResidentID, DayOfWeek, Activity, Time)
      VALUES ($resID, '$day', '$activity', '$time')
    ");
    echo "<div class='alert alert-success'>Routine added.</div>";
}

// EDIT (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && $id) {
    $resID    = $conn->real_escape_string($_POST['ResidentID']);
    $day      = $conn->real_escape_string($_POST['DayOfWeek']);
    $activity = $conn->real_escape_string($_POST['Activity']);
    $time     = $conn->real_escape_string($_POST['Time']);

    $conn->query("
      UPDATE routines
      SET ResidentID = $resID,
          DayOfWeek  = '$day',
          Activity   = '$activity',
          Time       = '$time'
      WHERE RoutineID = $id
    ");
    echo "<div class='alert alert-info'>Routine updated.</div>";
}

// SHOW ADD / EDIT FORM
if ($action === 'add' || ($action === 'edit' && $id)) {
    $row = ['ResidentID'=>'','DayOfWeek'=>'','Activity'=>'','Time'=>''];
    if ($action === 'edit') {
        $r = $conn->query("SELECT * FROM routines WHERE RoutineID = $id");
        $row = $r->fetch_assoc();
    }
    ?>
    <h2 class="text-center mb-4"><?= ucfirst($action) ?> Routine</h2>
    <form method="post" style="width:90%;max-width:600px;margin:0 auto;">
      <div class="mb-3">
        <label class="form-label">Resident ID</label>
        <input type="number" name="ResidentID" class="form-control"
               value="<?= htmlspecialchars($row['ResidentID']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Day of Week</label>
        <input type="text" name="DayOfWeek" class="form-control"
               value="<?= htmlspecialchars($row['DayOfWeek']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Activity</label>
        <input type="text" name="Activity" class="form-control"
               value="<?= htmlspecialchars($row['Activity']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Time</label>
        <input type="time" name="Time" class="form-control"
               value="<?= htmlspecialchars($row['Time']) ?>" required>
      </div>
      <button type="submit" class="btn btn-primary"><?= ucfirst($action) ?></button>
      <a href="routines.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
    <?php
    include '../footer.php';
    exit;
}

// DEFAULT LIST VIEW
echo "<h2 class='text-center mb-4'>Routines Management</h2>";
echo "<div class='d-flex justify-content-start mb-3' style='width:90%;margin:0 auto;'>
        <a href='routines.php?action=add' class='btn btn-success me-2'>Add New</a>
        <a href='../index.php' class='btn btn-secondary'>Home</a>
      </div>";

$result = $conn->query("SELECT * FROM routines");
if ($result && $result->num_rows > 0) {
    echo "<table class='table table-bordered' style='width:90%;margin:auto;'>
            <thead class='table-light'><tr>";
    while ($f = $result->fetch_field()) {
        echo "<th>{$f->name}</th>";
    }
    echo "<th>Actions</th></tr></thead><tbody>";
    while ($r = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($r as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "<td>
                <a href='routines.php?action=edit&id={$r['RoutineID']}' class='btn btn-sm btn-warning'>Edit</a>
                <a href='routines.php?action=delete&id={$r['RoutineID']}' 
                   onclick=\"return confirm('Delete this routine?');\" 
                   class='btn btn-sm btn-danger'>Delete</a>
              </td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p class='text-center'>No routines found.</p>";
}

include '../footer.php';
?>
