<?php

$admin_index=[
  "usuarios"=>[
    "path"=>$html->link("list_users"),
    "name"=>"Usuarios",
    "icon"=>"users_blk",
    "sn_icon"=>"user_2",
    "checkPermiso"=>1,
    "submenu"=>[
      [
        "sub_name"=>"Ver Usuarios",
        "sub_path"=>$html->link("list_users")."",
      ],[
        "sub_name"=>"Agregar Usuarios",
        "sub_path"=>$html->link("list_users")."/agregar",
      ]
    ]
  ],
  "clientes"=>[
    "path"=>$html->link("list_clientes"),
    "name"=>"Clientes",
    "icon"=>"users_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>4,
    "submenu"=>[
      [
        "sub_name"=>"Ver Clientes",
        "sub_path"=>$html->link("list_clientes")."",
      ],[
        "sub_name"=>"Agregar Clientes",
        "sub_path"=>$html->link("list_clientes")."/agregar",
      ]
    ]
  ]
];

?>

<div id="sidebar">
  <ul class="side-nav accordion_mnu collapsible">
    <li><a href="<?php echo $html->link("admin_index"); ?>"><span class="white-icons computer_imac"></span> Inicio</a></li>
    <?php foreach($admin_index as $key=>$value){
        if((isset($value["checkPermiso"])&&checkPermiso($value["checkPermiso"]))||!isset($value["checkPermiso"])){
      ?>
      <li><a href="<?php if(!isset($value['submenu'])){echo $value['path']; }else{ echo '#'; } ?>" ><span class="white-icons <?php echo $value["sn_icon"]; ?>"></span><?php echo $value["name"]; ?></a>
        <?php if(isset($value["submenu"])){ ?>
          <ul class="acitem">
          <?php foreach($value["submenu"] as $sub_value){ ?>
            <li><a href="<?php echo $sub_value['sub_path']; ?>"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span><?php echo $sub_value["sub_name"]; ?></a></li>
          <?php } ?>
          </ul>
        <?php } ?>
      </li>
    <?php }
		} ?>
  </ul>
  <div
    id="side-accordion">
  </div>
</div>
