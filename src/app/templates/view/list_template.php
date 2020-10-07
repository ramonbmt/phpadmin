<?php
	
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
        <div class="container py-4">
        	<?php include($html->componentView("breadcrumb")); ?>
            <section>
                <div class="card mb-4">
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-sm-6 text-center text-sm-left">
                                <button class="btn btn-primary btn-lg btn-floating float-left">
                                    <?php
                                        $icon = ""; foreach($admin_index as $value){
                                            if($value["path"]=='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']){
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
                                <h5 class="mb-3 mb-sm-0 mt-2 ml-5"><?php echo $table_header; ?></h5>
                            </div>
                            <div class="col-sm-6 text-center text-sm-right">
                                <a href="<?php echo $table_link; ?>" type="button" class="btn btn-success mr-2">
                                    <i class="fas fa-plus mr-1"></i>
                                    Agregar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body py-3">
						<?php
							//$search->setAll("search_template_self",$table);
							//$search->constructSearch();
							echo $search_result;
						?>
                        <div class="table-responsive">
                            <?php echo $table_result; ?>
                        </div>
                        <?php	echo $pagination_result; ?>
                    </div>
                </div>
            </section>
	</div>
</div>
	</div>
	<?php include($html->componentView("js")); ?>
	<script>
		var pagination=0;
		function construct_table(data){
			//alert("hola1");
			construct_table2(data);
		}
	</script>
</body>
</html>
