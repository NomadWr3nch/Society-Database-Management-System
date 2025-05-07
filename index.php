<?php
// index.php
include 'header.php';
?>
<style>
  .main-content {
    flex: 1 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem;
  }
  .box-heading {
    background: rgba(0,0,0,0.7);
    padding: 2rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    display: inline-block;
  }
  .box-buttons {
    background: rgba(0,0,0,0.7);
    padding: 2rem;
    border-radius: 8px;
    display: inline-block;
  }
</style>

<div class="main-content" data-aos="fade-in">
  <div class="box-heading" data-aos="fade-down">
    <h1>Society Management Dashboard</h1>
  </div>
  <div class="box-buttons" data-aos="fade-up">
    <?php
      $modules = [
        'Residents'          => 'modules/residents.php',
        'Owners'             => 'modules/owners.php',
        'Apartments'         => 'modules/apartments.php',
        'Amenities'          => 'modules/amenities.php',
        'Guests'             => 'modules/guests.php',
        'Entry/Exit'         => 'modules/entryexit.php',
        'Maintenance'        => 'modules/maintenance.php',
        'Resident Amenities' => 'modules/residents_amenities.php',
        'Routines'           => 'modules/routines.php'
      ];
      $i = 0;
      foreach ($modules as $label => $link) {
        echo "<a href=\"$link\" class=\"btn-module\" data-aos=\"fade-up\">$label</a>";
        $i++;
        if ($i === 5) {
          // after 5 buttons, break into second row
          echo '<br>';
        }
      }
    ?>
  </div>
</div>

<?php include 'footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init({ duration: 800, once: true });
</script>
