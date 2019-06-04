<?php
if(isset($_SESSION['campain_logged_id'])){
	header("Location: ".$html->link("index"));
	die();
}
if(checkLogin()){
	//var_dump($user->user_id);
	logout("index",$user->user_id);
}else{
	header("Location: ".$html->link("login"));
}
//
?>
<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
  <?php //include("components/head.html"); 
  include($html->componentView("head"));?>
</head>
<body>
  <div id="wrap" class="boxed">
   <header>
     <?php //include("components/header.html"); 
	 include($html->componentView("header"));?>
   </header><!-- <<< End Header >>> -->
   <div class="page-title">
     <div class="container clearfix">
       <div class="sixteen columns"> 
         <h1>Login</h1>
       </div>
     </div><!-- End Container -->
   </div>
   <div style="margin-top:25px;"></div>
   
   <!-- Start google map -->
    <!--<section class="">
    	<div class="">
        	<div class="">
            	<div class="">
                    <div class="" id="googleMa" style="width:500px;height:380px;">
                    </div>
            	</div>
            </div>
        </div>
    </section>-->
	<!--<div style="margin-bottom:20px;">
		<div id="googleMap" style="margin:auto;width:720px;height:250px;"></div>
    </div>-->
	<!-- End google map -->
   
   <!-- Start main content -->
   <div class="container main-content clearfix">
	<div style="background-color:#00487A;overflow:hidden;margin:auto;width:660px;">
	<?php //include($html->componentView("login")); ?>
	<?php //include($html->componentView("registro_usuario")); ?>
    </div>
   </div><!-- <<< End Container >>> -->
   
   <footer>
     <?php //include("components/footer.html"); 
	 //include($html->componentView("footer"));?>
   </footer><!-- <<< End Footer >>> -->
   
  </div><!-- End wrap -->
  
  <!-- Start JavaScript -->
  <?php include($html->componentView("js")); ?>
  
  <!-- End JavaScript -->
    
</body>
</html>