<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include($html->componentView("head")); ?>
    </head>
    <body>
        <header>
            <?php include($html->componentView("topnav")); ?>
            <?php include($html->componentView("sidenav")); ?>
        </header>
        <main style="margin-top: 58px">
            <div class="container py-4">
                <?php include($html->componentView("breadcrumb")); ?>
                <section>
                    <div class="card mb-4">
                        <div class="card-body py-3">
                            <div class="row">
                                <div class="col-sm-6 text-center text-sm-left">
                                    <button class="btn btn-primary btn-lg btn-floating float-left">
                                        <?php
                                            $icon = "";
                                            foreach($admin_index as $value){
                                                // print_r($value);
                                                // echo $$value["path"].' - '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                                                $pathArr = explode('/', $_SERVER['REQUEST_URI']);
                                                // print_r($pathArr);
                                                if($value["path"]=='http://'.$_SERVER['HTTP_HOST'].'/'.$pathArr[1]){
                                                    $icon = $value["icon"];
                                                }
                                            }
                                            if($icon!=""){
                                                echo '<i class="fas '.$icon.'"></i>';
                                            }else{
                                                echo '<i class="fas fa-table"></i>';
                                            }
                                        ?>
                                    </button>
                                    <h5 class="mb-3 mb-sm-0 mt-2 ml-5"><?php echo $edit_title; ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body py-3">
                            <?php
                                if(isset($error)){
                            ?>
                                <div class="d-flex flex-row justify-content-center alert alert-danger alert-dismissible fade show my-3" role="alert">
                                    <p class="small"><strong>¡Error!</strong> <?php echo $error; ?></p>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php
                                }
                            ?>
                            <div class="nav-tabs-navigation">
								<div class="nav-tabs-wrapper">
                                    <?php echo $edit; ?>

                                    <?php if(isset($table_title)){ ?>
                                        <h5 class=""> <?php echo $table_title; ?></h5> 
                                        <?php echo $table; ?>
                                    <?php } ?>
                                    <!-- ************** CONTINUA CODIGO EN include/global_templates/tabb_template.php ************* -->
                                    <!-- ************** PARA TERMINAR DIBUJAR LAS TABS Y CERRAR LOS DIVS ABIERTOS ***************** -->
                        </div>
                    </div>
                </section>
            </div>
        </main>
        <?php include($html->componentView("js")); ?>
        <script src="/js/debounce.js"></script>
        <script>
            pagination=0;
            function construct_table(data){
                construct_table2(data);
            }
        </script>
    </body>
</html>
