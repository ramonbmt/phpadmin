<?php

$admin_index=[
  "usuarios"=>[
    "path"=>$html->link("list_users"),
    "name"=>"Usuarios",
    "icon"=>"fa-users",
    "sn_icon"=>"users",
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
    "icon"=>"user-tie",
    "sn_icon"=>"user-tie",
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

<!-- Sidenav -->
<div
    id="bmt-sidenav"
    class="sidenav"
    role="navigation"
    data-mode="side"
    data-hidden="false"
    data-accordion="true"
    data-content="#content"
>
    <a class="ripple d-flex justify-content-center py-4" href="<?php echo $html->link("admin_index"); ?>" data-ripple-color="primary">
        <img height="60" width="auto" style="object-fit: contain;" id="BMT-logo" src="/img/logoBMT.png" alt="BMT Logo" draggable="false"/>
    </a>

    <ul class="sidenav-menu">
        <li class="sidenav-item">
            <a class="sidenav-link" href="<?php echo $html->link("admin_index"); ?>">
                <i class="fas fa-chart-area fa-fw mr-3"></i><span>Inicio</span>
            </a>
        </li>
        <?php foreach($admin_index as $key=>$value){
            if((isset($value["checkPermiso"])&&checkPermiso($value["checkPermiso"]))||!isset($value["checkPermiso"])){
        ?>
        <li class="sidenav-item">
            <a class="sidenav-link" href="<?php if(!isset($value['submenu'])){echo $value['path']; }else{ echo '#!'; } ?>">
                <i class="fas fa-cogs fa-fw mr-3"></i><span><?php echo $value["name"]; ?></span>
            </a>
            <?php if(isset($value["submenu"])){ ?>
                <ul class="sidenav-collapse">
                    <?php foreach($value["submenu"] as $sub_value){ ?>
                        <li class="sidenav-item">
                            <a href="<?php echo $sub_value['sub_path']; ?>" class="sidenav-link"><?php echo $sub_value["sub_name"]; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </li>
    <?php }
		} ?>
    </ul>
</div>
<!-- Sidenav -->
