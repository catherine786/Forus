<style>
  .navbar-light{
    background: white;
  }
  nav{
    border-bottom: 2.5px solid #2322;
  }
  #usuarios{
    margin-right: 1%;
  }
</style>
<nav class="navbar navbar-expand-lg navbar-light bg-light"> 
      <div div="navbarsito" class="container">
        <a class="navbar-brand" href="index.php">
          <img src="images/LogoMakr-56L0gt.png" width ="50">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a id="inicio" class="nav-link" href="index.php">Inicio</a>
            </li>
            <li class="nav-item">
              <a id="recetas" class="nav-link" href="recetas.php">Recetas</a>
            </li>
            <li class="nav-item">
              <a id="donaciones" class="nav-link" href="donations.php">Donaciones</a>
            </li>
            </li>
          </ul>
          <ul id="usuarios" class="navbar-nav mb-2 mb-lg-0">
          <?php 
    

          if (!$loggedin){
            ?>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="login.php">Iniciar Sesión</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="register.php">Registrarse</a>
            </li>
            <?php
          } else {
            ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo htmlspecialchars($_SESSION["user"]) ?>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li class="navlink-disabled text-center">Mi Actividad</li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-center" href="misrecetas.php">Mis recetas</a></li>
                <li><a class="dropdown-item text-center" href="favoritosUser.php">Favoritos</a></li>
				<li><a id="logout" class="dropdown-item text-center" href="database/logout.php?url=<?php echo $_SERVER['REQUEST_URI'];  ?>">Cerrar Sesion</a></li>
              </ul>

            <?php
          }
          
          ?>
          </ul>
          <form id="search" class="d-flex mt-3" action="search.php">
            <input name="q" class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search" value=<?php echo isset($_GET['q']) ? $_GET['q']:'' ; ?>>
            <button class="btn btn-outline-success" type="submit">Buscar</button>
          </form>
        </div>
      </div>
    </nav>
