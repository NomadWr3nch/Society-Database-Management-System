<?php
// residents_amenities.php
include '../config.php';
include '../header.php';

// Determine action and keys
$action = $_GET['action'] ?? '';
$resID  = isset($_GET['ResidentID']) ? (int)$_GET['ResidentID'] : 0;
$amenID = isset($_GET['AmenityID'])  ? (int)$_GET['AmenityID']  : 0;

// DELETE
if ($action === 'delete' && $resID && $amenID) {
    $stmt = $conn->prepare(
        "DELETE FROM residents_amenities 
         WHERE ResidentID = ? AND AmenityID = ?"
    );
    $stmt->bind_param("ii", $resID, $amenID);
    $stmt->execute();
    header("Location: residents_amenities.php");
    exit;
}

// ADD
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $r  = (int)$_POST['ResidentID'];
    $a  = (int)$_POST['AmenityID'];
    $dt = $_POST['AccessDateTime'];

    $stmt = $conn->prepare(
        "INSERT INTO residents_amenities 
         (ResidentID, AmenityID, AccessDateTime)
         VALUES (?, ?, ?)"
    );
    $stmt->bind_param("iis", $r, $a, $dt);
    $stmt->execute();
    echo "<div class='alert alert-success'>Record added.</div>";
}

// EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && $resID && $amenID) {
    $dt = $_POST['AccessDateTime'];

    $stmt = $conn->prepare(
        "UPDATE residents_amenities 
         SET AccessDateTime = ?
         WHERE ResidentID = ? AND AmenityID = ?"
    );
    $stmt->bind_param("sii", $dt, $resID, $amenID);
    $stmt->execute();
    echo "<div class='alert alert-info'>Record updated.</div>";
}

// SHOW ADD/EDIT FORM
if ($action === 'add' || ($action === 'edit' && $resID && $amenID)) {
    $row = ['ResidentID'=>'','AmenityID'=>'','AccessDateTime'=>''];
    if ($action === 'edit') {
        $stmt = $conn->prepare(
            "SELECT ResidentID, AmenityID, AccessDateTime 
             FROM residents_amenities
             WHERE ResidentID = ? AND AmenityID = ?"
        );
        $stmt->bind_param("ii", $resID, $amenID);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
    }
    ?>
    <h2 class="text-center mb-4"><?= ucfirst($action) ?> Access Record</h2>
    <form method="post" style="max-width:600px;margin:auto">
      <div class="mb-3">
        <label class="form-label">Resident ID</label>
        <input type="number" name="ResidentID" class="form-control"
               value="<?= htmlspecialchars($row['ResidentID']) ?>"
               required <?= $action==='edit'?'readonly':'' ?>>
      </div>
      <div class="mb-3">
        <label class="form-label">Amenity ID</label>
        <input type="number" name="AmenityID" class="form-control"
               value="<?= htmlspecialchars($row['AmenityID']) ?>"
               required <?= $action==='edit'?'readonly':'' ?>>
      </div>
      <div class="mb-3">
        <label class="form-label">Access Date & Time</label>
        <input type="datetime-local" name="AccessDateTime" class="form-control"
               value="<?php
                 if ($row['AccessDateTime']) {
                   echo date('Y-m-d\TH:i', strtotime($row['AccessDateTime']));
                 }
               ?>" required>
      </div>
      <button type="submit" class="btn btn-primary"><?= ucfirst($action) ?></button>
      <a href="residents_amenities.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
    <?php
    include '../footer.php';
    exit;
}

// DEFAULT LIST VIEW
?>
<h2 class="text-center mb-4">Residents-Amenities Access</h2>
<div style="max-width:90%;margin:auto;text-align:left" class="mb-3">
  <a href="residents_amenities.php?action=add" class="btn btn-success me-2">Add New</a>
  <a href="../index.php" class="btn btn-secondary">Home</a>
</div>

<?php
$result = $conn->query("SELECT * FROM residents_amenities");
if ($result && $result->num_rows > 0) {
    echo "<table class='table table-bordered' style='width:90%;margin:auto'>
            <thead class='table-light'><tr>
              <th style='padding:12px'>ResidentID</th>
              <th style='padding:12px'>AmenityID</th>
              <th style='padding:12px'>AccessDateTime</th>
              <th style='padding:12px'>Actions</th>
            </tr></thead><tbody>";
    while ($r = $result->fetch_assoc()) {
        $rid = $r['ResidentID'];
        $aid = $r['AmenityID'];
        $dt  = htmlspecialchars($r['AccessDateTime']);
        echo "<tr>
                <td style='padding:12px'>{$rid}</td>
                <td style='padding:12px'>{$aid}</td>
                <td style='padding:12px'>{$dt}</td>
                <td style='padding:12px'>
                  <a href='residents_amenities.php?action=edit&ResidentID={$rid}&AmenityID={$aid}' class='btn btn-sm btn-warning'>Edit</a>
                  <a href='residents_amenities.php?action=delete&ResidentID={$rid}&AmenityID={$aid}' onclick=\"return confirm('Delete this record?');\" class='btn btn-sm btn-danger'>Delete</a>
                </td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p class='text-center'>No records found.</p>";
}

include '../footer.php';
?>
