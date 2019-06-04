<div class="navbar navbar-fixed-top">
  <div class="navbar-inner top-nav">
    <div class="container-fluid">
      <div class="branding">
        <div class="logo"> <a href="<?php echo $html->link("index"); ?>"><img src="/img/logo_nuevo_s.png" style="height:40px;" alt="Logo"></a> </div>
      </div>
      <ul class="nav pull-right">
        <!--<li class="dropdown search-responsive"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="nav-icon magnifying_glass"></i><b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li class="top-search">
              <form action="#" method="get">
                <div class="input-prepend"> <span class="add-on"><i class="icon-search"></i></span>
                  <input type="text" id="searchIcon">
                </div>
              </form>
            </li>
          </ul>
        </li>-->
        <li class="dropdown">
			<a data-toggle="dropdown" class="dropdown-toggle" href="#">
				<i class="lang-icons">
					<img src="/img/flag-icons/mx.png" width="16" height="11" alt="language">
				</i>
				<!--<b class="caret"></b>-->
			</a>
          <!--<ul class="dropdown-menu">
            <li><a href="#"><i class="lang-icons"><img src="img/flag-icons/gb.png" width="16" height="11" alt="language"></i> English UK</a></li>
            <li><a href="#"><i class="lang-icons"><img src="img/flag-icons/fr.png" width="16" height="11" alt="language"></i> French</a></li>
            <li><a href="#"><i class="lang-icons"><img src="img/flag-icons/sa.png" width="16" height="11" alt="language"></i> Arabic</a></li>
            <li><a href="#"><i class="lang-icons"><img src="img/flag-icons/it.png" width="16" height="11" alt="language"></i> Italian</a></li>
          </ul>-->
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
            <!--<li><a href="#"><i class="icon-inbox"></i> Inbox <span class="alert-noty">10</span></a></li>
            <li><a href="#"><i class="icon-envelope"></i> Notifications <span class="alert-noty">15</span></a></li>
            <li><a href="#"><i class="icon-briefcase"></i> My Account</a></li>
            <li><a href="#"><i class="icon-file"></i> View Profile</a></li>
            <li><a href="#"><i class="icon-pencil"></i> Edit Profile</a></li>
            <li><a href="#"><i class="icon-cog"></i> Account Settings</a></li>-->
            <li class="divider"></li>
            <li><a href="<?php echo $html->link("logout"); ?>"><i class="icon-off"></i><strong> Salir</strong></a></li>
          </ul>
        </li>
		<li class="branding" style=""><img src="/img/logo_nuevo_s.png" width="40" height="40" alt="Logo"></li>
      </ul>
      <?php include($html->componentView("topnav_collapse")); ?>
    </div>
  </div>
</div>