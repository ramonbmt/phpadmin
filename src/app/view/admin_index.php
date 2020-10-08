<?php

forceLogin("login");

?>
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
            <div class="container">
                <?php include($html->componentView("breadcrumb")); ?>
                <h1 class="h3 text-center py-5 mb-0">Dashboard Principal</h1>
                <!--Section: Index-->
                <section>
                    <div class="row">
                        <?php foreach($admin_index as $key=>$value){
                            //echo $user->user_type;
                                if((isset($value["checkPermiso"])&&checkPermiso($value["checkPermiso"]))||!isset($value["checkPermiso"])||(isset($value["userType"])&&$value["userType"]==$user->user_type)){
                            ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <a href="<?php echo $value["path"]; ?>">
                                    <div class="card">
                                        <div class="card-header card-header-<?php echo $value["color"]; ?> card-header-icon">
                                            <div class="card-icon">
                                                <i class="<?php echo $value["icon"]; ?>"></i>
                                            </div>
                                            <!-- <p class="card-category" style="color:black"><?php echo $value["name"]; ?></p> -->
                                            <h4 style="color:black;"><?php echo $value["name"]; ?></h4>
                                            <!-- <a href="<?php echo $value["path"]; ?>"><h3 class="card-title">Ir</h3></a> -->
                                        </div>
                                        <!-- <div class="card-footer">
                                            <div class="stats">
                                                <i class="material-icons"><?php echo $value["icon"]; ?></i>
                                                <a href="<?php echo $value["path"]; ?>"></a>
                                            </div>
                                        </div> -->
                                    </div>
                                </a>
                            </div>
                        <?php }} ?>
                    </div>
                </section>
            </div>
        </main>
        <footer class="mt-5"></footer>
        <?php include($html->componentView("js")); ?>
        <?php if(isset($ajax)) {echo $ajax;} ?>
    </body>
</html>
