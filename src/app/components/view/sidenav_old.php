<div id="sidebar">
  <ul class="side-nav accordion_mnu collapsible">
    <li><a href="<?php echo $html->link("admin_index"); ?>"><span class="white-icons computer_imac"></span> Inicio</a></li>
    <?php
		if($user->user_type==2){
	?>
	<li><a href="#"><span class="white-icons list"></span> Usuarios</a>
      <ul class="acitem">
        <li><a href="<?php echo $html->link("list_users"); ?>"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Ver usuarios</a></li>
        <li><a href="<?php echo $html->link("list_users")."/agregar"; ?>"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Agregar usuarios</a></li>
      </ul>
    </li>
	<li><a href="#"><span class="white-icons list"></span> Carrusel</a>
		<ul class="acitem">
			<li><a href="<?php echo $html->link("list_carrousel"); ?>"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Ver Carrusel</a></li>
			<li><a href="<?php echo $html->link("list_carrousel")."/agregar"; ?>"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Agregar Imagen a Carrusel</a></li>
		</ul>
	</li>
	<li><a href="<#"><span class="white-icons list"></span> Galeria</a>
		<ul class="acitem">
			<li><a href="<?php echo $html->link("list_gallery"); ?>"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Ver Galeria</a></li>
			<li><a href="<?php echo $html->link("list_gallery")."/agregar"; ?>"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Agregar Imagen a Galeria</a></li>
		</ul>
	</li>
	<li><a href="<?php echo $html->link("list_category_gallery"); ?>"><span class="white-icons list"></span> Categoria de Galeria</a>
    </li>
	<li><a href="<?php echo $html->link("list_videos"); ?>"><span class="white-icons list"></span> Videos</a>
    </li>
	<li><a href="<?php echo $html->link("list_contact"); ?>"><span class="white-icons list"></span> Contacto</a>
    </li>
	<?php
		}else{
	?>
	
	<?php
		}
	?>
  </ul>
  <div id="side-accordion">
    
  </div>
</div>