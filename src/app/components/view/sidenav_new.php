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
  "inventario"=>[
    "path"=>$html->link("list_inventory"),
    "name"=>"Inventario",
    "icon"=>"shopping_cart_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>2,
    "submenu"=>[
      [
        "sub_name"=>"Ver Inventario",
        "sub_path"=>$html->link("list_inventory")."",
      ],[
        "sub_name"=>"Agregar Producto al Inventario",
        "sub_path"=>$html->link("list_inventory")."/agregar",
      ]
    ]
  ],
  "bodegas"=>[
    "path"=>$html->link("list_storage"),
    "name"=>"Bodegas",
    "icon"=>"home_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>3,
    "submenu"=>[
      [
        "sub_name"=>"Ver Bodegas",
        "sub_path"=>$html->link("list_storage")."",
      ],[
        "sub_name"=>"Agregar Bodega",
        "sub_path"=>$html->link("list_storage")."/agregar",
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
  ],
  "categorias"=>[
    "path"=>$html->link("list_category"),
    "name"=>"Categorias",
    "icon"=>"tags_2_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>5,
    "submenu"=>[
      [
        "sub_name"=>"Ver Categoria",
        "sub_path"=>$html->link("list_category")."",
      ],[
        "sub_name"=>"Agregar Categoria",
        "sub_path"=>$html->link("list_category")."/agregar",
      ]
    ]
  ],
  "tiendas"=>[
    "path"=>$html->link("list_tiendas"),
    "name"=>"Tiendas",
    "icon"=>"apartment_building_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>12,
    "submenu"=>[
      [
        "sub_name"=>"Ver Tiendas",
        "sub_path"=>$html->link("list_tiendas")."",
      ],[
        "sub_name"=>"Agregar Tienda",
        "sub_path"=>$html->link("list_tiendas")."/agregar",
      ]
    ]
  ],
  "cotizaciones"=>[
    "path"=>$html->link("list_cotizacion"),
    "name"=>"Cotizaciones",
    "icon"=>"note_book_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>6,
    "submenu"=>[
      [
        "sub_name"=>"Ver Cotizaciones",
        "sub_path"=>$html->link("list_cotizacion")."",
      ],[
        "sub_name"=>"Agregar Cotizacion",
        "sub_path"=>$html->link("list_cotizacion")."/agregar",
      ]
    ]
  ],
  "ordenes_ventas"=>[
    "path"=>$html->link("list_orden_venta"),
    "name"=>"Ordenes de Venta",
    "icon"=>"shopping_cart_2_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>7,
    "submenu"=>[
      [
        "sub_name"=>"Ver Ordenes de Venta",
        "sub_path"=>$html->link("list_orden_venta")."",
      ]
    ]
  ],
  "tarjeta_regalo"=>[
    "path"=>$html->link("list_tarjeta_regalo"),
    "name"=>"Tarjetas de regalo",
    "icon"=>"post_card_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>8,
    "submenu"=>[
      [
        "sub_name"=>"Ver Tarjetas de regalo",
        "sub_path"=>$html->link("list_tarjeta_regalo")."",
      ],[
        "sub_name"=>"Agregar Tarjeta de regalo",
        "sub_path"=>$html->link("list_tarjeta_regalo")."/agregar",
      ]
    ]
  ],
  "punto_venta"=>[
    "path"=>$html->link("punto_venta"),
    "name"=>"Punto de Venta",
    "icon"=>"cash_register_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>9,
    "submenu"=>[
      [
        "sub_name"=>"Acceder a punto de venta",
        "sub_path"=>$html->link("punto_venta")."",
      ],[
        "sub_name"=>"Ventas Guardadas",
        "sub_path"=>$html->link("punto_venta")."/agregar",
      ]
    ]
  ],
  "ordenes_compra"=>[
    "path"=>$html->link("list_orden_compra"),
    "name"=>"Orden de compra",
    "icon"=>"note_book_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>10,
    "submenu"=>[
      [
        "sub_name"=>"Ver Ordenes de Compra",
        "sub_path"=>$html->link("list_orden_compra")."",
      ],[
        "sub_name"=>"Agregar Orden de Compra",
        "sub_path"=>$html->link("list_orden_compra")."/agregar",
      ]
    ]
  ],
  "proveedores"=>[
    "path"=>$html->link("list_proveedor"),
    "name"=>"Proveedores",
    "icon"=>"note_book_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>11,
    "submenu"=>[
      [
        "sub_name"=>"Ver Proveedores",
        "sub_path"=>$html->link("list_proveedor")."",
      ],[
        "sub_name"=>"Agregar Proveedor",
        "sub_path"=>$html->link("list_proveedor")."/agregar",
      ]
    ]
  ],
  "descuentos"=>[
    "path"=>$html->link("list_discounts"),
    "name"=>"Descuentos",
    "icon"=>"note_book_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>13,
    "submenu"=>[
      [
        "sub_name"=>"Ver Descuentos",
        "sub_path"=>$html->link("list_discounts")."",
      ],[
        "sub_name"=>"Agregar Descuento",
        "sub_path"=>$html->link("list_discounts")."/agregar",
      ]
    ]
  ],
  "cajas_abiertas"=>[
    "path"=>$html->link("list_cajas"),
    "name"=>"Cajas Abiertas",
    "icon"=>"note_book_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>14,
    "submenu"=>[
      [
        "sub_name"=>"Ver Cajas Abiertas",
        "sub_path"=>$html->link("list_cajas")."",
      ]
    ]
  ],
  "reportes"=>[
    "path"=>$html->link("list_reports"),
    "name"=>"Reportes",
    "icon"=>"note_book_blk",
    "sn_icon"=>"list",
    "checkPermiso"=>15,
    "submenu"=>[
      [
        "sub_name"=>"Reportes",
        "sub_path"=>$html->link("list_reports")."",
      ]
    ]
  ],
];

?>

<div class="sidebar"  data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
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
  </div>
</div>

<!-- <div id="sidebar">
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
</div> -->
