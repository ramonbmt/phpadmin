<?php if (isset($breadcrumb) && sizeof($breadcrumb) > 0){ ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb shadow-5">
            <?php  
                $bread_last=array_pop($breadcrumb);
                foreach($breadcrumb as $value){
                    if(is_array($value)){
                        $link="";$path="";
                        $link=$html->link($value["link"]);
                        $path=$html->getPage($value["link"]);
                        if(isset($value["path"])){
                            $link.="/".$value["path"];
                            $path.="/".$value["path"];
                        }
                        if(isset($value["id"])){
                            $link.="/?id=".$value["id"];
                            $path.="/?id=".$value["id"];
                        }
            ?>
            <li class="breadcrumb-item"><a href="<?php echo $link; ?>"><?php echo $path; ?></a></li>
            <?php
                    }else{
            ?>
            <li class="breadcrumb-item"><a href="<?php echo $html->link($value); ?>"><?php echo $html->getPage($value); ?></a></li>
            <?php
                    }
                }
            ?>
            <?php 		if(is_array($bread_last)){
                        $path=$html->getPage($bread_last["link"]);
                        if(isset($bread_last["path"])){
                            $path.="/".$bread_last["path"];
                        }
                        if(isset($bread_last["id"])){
                            $path.="/?id=".$bread_last["id"];
                        }
            ?>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $path; ?></li>
            <?php }else{ ?>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $html->getPage($bread_last); ?></li>
            <?php } ?>
        </ol>
    </nav>
<?php } ?>
