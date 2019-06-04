<button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
<div class="nav-collapse collapse visible-phone visible-tablet">
<ul class="nav">
	<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="white-icons list"></span> Usuarios<b class="caret"></b></a>
	  <ul class="dropdown-menu">
		<li><a href="<?php echo $html->link("list_users"); ?>"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Ver usuarios</a></li>
		<li><a href="<?php echo $html->link("list_users")."/agregar"; ?>"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Agregar usuarios</a></li>
	  </ul>
	</li>
</ul>
</div>