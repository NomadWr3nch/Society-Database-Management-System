<?php
// header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Society SMS</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- AOS CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: url('assets/mountain_house.jpg') no-repeat center center fixed;
      background-size: cover;
      background-color: #000; /* fallback */
      color: #fff;
      display: flex;
      flex-direction: column;
    }
    /* module pages: dark background */
    <?php if(basename($_SERVER['PHP_SELF']) !== 'index.php'): ?>
    body {
      background-color: #000;
      background-image: none;
    }
    .table, th, td {
      background-color: #1e1e1e !important;
      color: #fff !important;
      border-color: #333 !important;
    }
    <?php endif; ?>

    /* navbar only on index.php */
    .site-nav {
      <?php if(basename($_SERVER['PHP_SELF']) !== 'index.php'): ?>
        display: none;
      <?php endif; ?>
      background: rgba(0,0,0,0.7) !important;
      backdrop-filter: blur(10px);
    }
    .nav-link {
      color: #fff !important;
      text-decoration: none;
      transition: color .3s;
    }
    .nav-link:hover {
      color: #f0e68c !important;
    }
    .btn-module {
      background-color: #6a5d4d;
      color: #fff;
      border: none;
      border-radius: 5px;
      padding: 12px 24px;
      margin: 0.5rem;
      text-transform: uppercase;
      font-weight: 600;
      text-align: center;
      transition: background .3s, transform .3s;
      display: inline-block;
      min-width: 160px;
      text-decoration: none;
    }
    .btn-module:hover {
      background-color: #5a4d3d;
      transform: translateY(-2px);
      text-decoration: none;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark site-nav mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Society SMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
      </ul>
    </div>
  </div>
</nav>
