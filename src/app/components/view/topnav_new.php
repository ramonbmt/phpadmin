<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
    <div class="container-fluid">
      <div class="navbar-wrapper">
        <a class="navbar-brand" href="#pablo">Dashboard</a>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
        <span class="sr-only">Toggle navigation</span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end">
        <form class="navbar-form">
          <div class="input-group no-border">
            <input type="text" value="" class="form-control" placeholder="Search...">
            <button type="submit" class="btn btn-white btn-round btn-just-icon">
              <i class="material-icons">search</i>
              <div class="ripple-container"></div>
            </button>
          </div>
        </form>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#pablo">
              <i class="material-icons">dashboard</i>
              <p class="d-lg-none d-md-block">
                Stats
              </p>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="material-icons">notifications</i>
              <span class="notification">5</span>
              <p class="d-lg-none d-md-block">
                Some Actions
              </p>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="#">Mike John responded to your email</a>
              <a class="dropdown-item" href="#">You have 5 new tasks</a>
              <a class="dropdown-item" href="#">You're now friend with Andrew</a>
              <a class="dropdown-item" href="#">Another Notification</a>
              <a class="dropdown-item" href="#">Another One</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="material-icons">person</i>
              <p class="d-lg-none d-md-block">
                Account
              </p>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
              <a class="dropdown-item" href="#">Profile</a>
              <a class="dropdown-item" href="#">Settings</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Log out</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>

<!-- <div class="sidebar"  data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
    <div class="logo">
      <a href="<?php echo $html->link("index"); ?>" class="simple-text logo-normal">
        INICIO
      </a>
    </div>
    <div class="sidebar-wrapper">
      <ul class="nav">

        <li class="nav-item active">
          <a class="nav-link" href="./dashboard.html">
            <i class="material-icons">dashboard</i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo $html->link("logout"); ?>">
          <i class="material-icons">dashboard</i>
            <strong>Salir</strong>
          </a>
        </li>

      </ul>
    </div> -->
    <!-- <div class="navbar-inner top-nav">
        <div class="container-fluid">
            <div class="branding">
                <div class="logo"> 
                  <a href="<?php echo $html->link("index"); ?>">
                    <img src="/img/logo_nuevo_s.png" style="height:40px;" alt="Logo">
                  </a> 
                </div>
            </div>
            <ul class="nav pull-right">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="lang-icons">
                            <img src="/img/flag-icons/mx.png" width="16" height="11" alt="language">
                        </i>
                    </a>
                </li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <?php echo $user->name; ?>
                        <span class="unseen_alert_permit">

                        </span>
                        <i class="white-icons admin_user"></i>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="divider"></li>
                        <li>
                          <a href="<?php echo $html->link("logout"); ?>">
                            <i class="icon-off"></i>
                            <strong>Salir</strong>
                          </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div> -->
</div>