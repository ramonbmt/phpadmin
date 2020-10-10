<style>
    body {
        background-color: #fbfbfb;
    }
    #slim-toggler-icon{
        transition: transform 500ms;
        transform: rotate(-360deg);
    }
    #slim-toggler-icon:hover{
        transition: transform 500ms;
        transform: rotate(360deg);
    }
    @media (min-width: 1400px) {
        main,
        header,
        #main-navbar {
            /* padding-left: 240px; */
        }
    }
</style>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid" id="content">
        <!-- <button
            data-toggle="sidenav"
            data-target="#bmt-sidenav"
            class="btn shadow-0 p-0 mr-3"
            aria-controls="#bmt-sidenav"
            aria-haspopup="true"
        >
            <i class="fas fa-bars fa-lg"></i>
        </button> -->
        <button id="slim-toggler" type="button" class="btn btn-primary btn-floating mr-4">
            <i id="slim-toggler-icon" class="fas fa-angle-left fa-lg"></i>
        </button>
        <div class="d-none d-md-flex input-group w-auto my-auto">
            <a href="<?php echo $html->link("index"); ?>" class="navbar-brand">
                <!-- <img src="/img/logoBMT.png" style="object-fit: contain;" width="auto" height="40" alt="Logo"> -->
                BMT Admin
            </a>
        </div>
        <!-- Right links -->
        <ul class="navbar-nav ml-auto d-flex flex-row">
            <!-- Icons -->
            <!-- <li class="nav-item mr-3 mr-lg-0">
                <a class="nav-link" href="#">
                <i class="fas fa-shopping-cart"></i>
                </a>
            </li> -->
            <!-- Icon dropdown -->
            <li class="nav-item mr-3 mr-lg-0">
                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="navbarDropdown"
                    role="button"
                    data-toggle="dropdown"
                    aria-expanded="false"
                >
                <i class="fas fa-user"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!"><?php echo $user->name; ?></a></li>
                    <li><a class="dropdown-item" href="<?php echo $html->link("logout"); ?>"><strong> Salir</strong></a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
