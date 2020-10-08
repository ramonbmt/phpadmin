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
                <section class="text-center p-5 shadow-5 bg-white">
                    <?php
                        if(isset($error)){
                    ?>
                        <div class="d-flex flex-row justify-content-center alert alert-danger alert-dismissible fade show" role="alert">
                            <p class="small"><strong>¡Error!</strong> <?php echo $error; ?></p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php
                        }
                    ?>
                    <h3 class="mb-3"><?php echo $title; ?></h3>
                    <?php if(isset($description)){ echo '<p>'.$description.'</p>'; }; ?>
                    <hr class="my-4"/>
                    <div class="row d-flex justify-content-center pt-2">
                        <div class="col-md-6 mb-4">
                            <?php echo $form; ?>
                        </div>
                    </div>
                </section>
			</div>
		</main>
	</body>
	<?php include($html->componentView("js")); ?>
	<script>
		var pagination=0;
		function construct_table(data){
			//alert("hola1");
			construct_table2(data);
		}
	</script>
</html>
