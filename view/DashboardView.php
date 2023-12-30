<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'secciones/head.php';?>
<style>
        body {
            margin: 0;
            padding: 0;
        }

        .content-header {
            background-color: #00209f; /* Cambiar el color a azul oscuro */
            color: white; /* Cambiar el color del texto si es necesario */
            padding: 20px; /* Añadir espacio interno según sea necesario */
            position: relative; /* Hacer que la posición sea relativa */
            top: 20px; /* Ajustar según sea necesario para que esté debajo de la sección anterior */
        }
        </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
      <?php  include 'secciones/navbar.php'?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php  include 'secciones/menu.php'?>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">404 Error Page</li>
            </ol>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-6">
             <h1>404 Error Page | Text Here</h1>
          </div>
          <div class="col-sm-6">
             <h1>404 Error Page</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-warning"> 404</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>

          <p>
            We could not find the page you were looking for.
            Meanwhile, you may <a href="../../index.html">return to dashboard</a> or try using the search form.
          </p>

          <form class="search-form">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search">

              <div class="input-group-append">
                <button type="submit" name="submit" class="btn btn-warning"><i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            <!-- /.input-group -->
          </form>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->
  </div>

  <?php  include 'secciones/footer.php'?>

</div>

</body>
</html>
