<?php
    class controller_view_class extends controller_class {
        function detalles() {
            include "include/tabbable.php";
			$this->tabbs=new tabbable("tabb_template");
			$filename=basename(__FILE__, '.php');
            $this->filename=$filename;
            checkPermiso(1,"admin_index");
            
			if(isset($_GET["id"])){
				$result_usuario=$this->connection->query("select id,name,lastname,email,date,last_login,permiso  
				from users where id='?' limit 1",$_GET["id"]);
				if(!isset($result_usuario[0]["id"])){
					header("Location: ".$this->html->link("list_users"));
				}
			}else{
				header("Location: ".$this->html->link("list_users"));
			}
			$u_id=$_GET["id"];
            $this->idUser=$u_id;
            
            /// INICIO TAB 1 ///
            $this->validator->next();
            // $this->validator->setPage($filename);
			if($this->validator->objects[0]->secureSend()){
				$check=array(
					"name"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
					"lastname"=>array('isset'=>'','notempty'=>'Favor de ingresar un Apellido','appendquotes'=>"'"),
					"email"=>array('isset'=>'','notempty'=>'Favor de ingresar un Correo','email'=>'Favor de introducir un correo valido','appendquotes'=>"'"),
					"user_type"=>array('isset'=>'','notempty'=>'Favor de ingresar un Tipo de usuario','appendquotes'=>"'"),
				);
				if($this->validator->objects[0]->validator($check, 'post')){
					if($this->validator->objects[0]->selfUpdate("users",array("id"=>$_GET["id"]))){
						$this->validator->objects[0]->clearValues();
						//header("Location: ".$this->html->link("list_users"));
					}else{
						$error=$this->validator->objects[0]->getError();
					}
				}else{
					$error=$this->validator->objects[0]->getError();
				}
			}
			if(isset($error)){
				$this->data["error"]=$error;
            }
            $result_user_type=$this->connection->query("select id,name from user_type where 1");
            $this->edit->next();
            $this->edit->getLast()->populate(
                array(
                    "users.name"=>array("as"=>"Nombre","link"=>false,"disabled"=>false,"mysqlas"=>"name"),
                    "lastname"=>array("as"=>"Apellido","link"=>false,"disabled"=>false),
                    "email"=>array("as"=>"Correo","link"=>false,"disabled"=>false),
                    "date"=>array("as"=>"Fecha de creacion","link"=>false,"disabled"=>true),
                    "last_login"=>array("as"=>"Ultima conexion","link"=>false,"disabled"=>true),
                    "users.user_type"=>array("display"=>false,"as"=>"Tipo de usuario id","link"=>false,"disabled"=>true,"mysqlas"=>"user_type_id"),
                    //"users.user_type"=>array("display"=>true,"as"=>"Tipo de usuario id","link"=>false,"disabled"=>true,"mysqlas"=>"user_type"),
                    "user_type.name"=>array("as"=>"Tipo de usuario","link"=>false,"disabled"=>true,"type"=>"select","mysqlas"=>"user_type","select"=>$result_user_type,"select_id"=>"user_type_id"),
                ),
                "users",
                $this->sqlbuilder->setTable("users")
                    ->join("user_type", "user_type", "id")
                    ->where(
                        array(
                            "users.id"=>"'?'"
                        )
                    )
                    ->setParams($u_id)
                    ->getSql()
            );
            $this->edit->getLast()->setValidator($this->validator->objects[0]);
            /// FIN TAB 1 ///
            
            /// INICIO TAB 2 ///
			// $this->validator2 = new validate();	
            // $this->validator2->setPage($filename."2");
            $this->validator->next();
			if($this->validator->objects[1]->secureSend()){
				$check = array();
				if($this->validator->objects[1]->validator($check, 'post')){
					$idModPerm_array = $_POST;
					array_pop($idModPerm_array);
					$permiso = serialize($idModPerm_array);
					$this->connection->query($this->sqlbuilder->update("users",array("permiso"=>"'?'",))
					->where(array("id"=>"'?'"))->setParams($permiso,$u_id)->getSql());
					$result_usuario=$this->connection->query("select id,name,lastname,email,date,last_login,permiso 
					from users where id='?' limit 1",$_GET["id"]);
				}else{
					$error = $this->validator->objects[1]->getError();
				}
            }
            $this->sqlbuilder->cleanSql();
            // $this->form = new form_class("form_template");
            $this->form->next();
			$result_modules=$this->connection->query("select id, name from modules where 1");
			$result_tipo_permisos = $this->connection->query("select id, name, suma from tipos_permisos where 1 ");
			$array_inputs=unserialize($result_usuario[0]["permiso"]);
			if(empty($array_inputs)){
				$array_inputs=array();
			}
			foreach($array_inputs as $key=>$value){
				$this->validator->objects[1]->setValue((int)$key,(int)$value);
			}
			$array_form_inputs=array();
			foreach($result_modules as $key=>$value){
				$array_form_inputs[$value["id"]]=array("as"=>$value["name"],"type"=>"select","select"=>$result_tipo_permisos);
			}
			$this->form->objects[0]->populate(
                $array_form_inputs,
                $this->html->link("list_users")
            );
            $this->form->objects[0]->setValidator($this->validator->objects[1]);
            /// FIN TAB 2 ///
            
            $this->view = $this->data;
        }
        function agregar() {
            $filename=basename(__FILE__, '.php');
            $this->filename=$filename;
            checkPermiso(1,"admin_index");

            $this->validator->next();
			if($this->validator->objects[0]->secureSend()){
                $check=array(
					'name'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un nombre'),
                    'lastname'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un apellido'),
                    'user_type'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un perfil'),
					'email'=>array('isset'=>'','notempty'=>'Favor de ingresar un correo','email'=>'Favor de ingresar un correo valido'),
					'password'=>array('isset'=>'', 'notempty'=>'Favor de ingresar una contraseña'),
					'repassword'=>array('isset'=>'', 'notempty'=>'Favor de volver a ingresar la contraseña'),
				);
				if($this->validator->objects[0]->validator($check, 'post')){
					if($this->validator->objects[0]->getValue("password")==$this->validator->objects[0]->getValue("repassword")){
						$result=$this->user->addUser(
                            $this->validator->objects[0]->getValue("email"),$this->validator->objects[0]->getValue("name"),
                            $this->validator->objects[0]->getValue("email"),$this->validator->objects[0]->getValue("lastname"),
                            $this->validator->objects[0]->getValue("user_type"),$this->validator->objects[0]->getValue("password")
                        );
						if($result!==true){
							$error=$result;
						}else{
							header('Location: '.$this->html->link('list_users'));
						}
					}else{
						$error="Las Contraseñas no coinciden";
					}
				}else{
					$error=$this->validator->objects[0]->getError();
				}
			}
			if(isset($error)){
				$this->data["error"]=$error;
            }

            $perfiles=$this->connection->query("select id,name from user_type where 1");
            $this->form->next($this->filename);
			$this->form->objects[0]->populate(
                array(
                    "name"=>array("as"=>"Nombre","type"=>"text"),
                    "lastname"=>array("as"=>"Apellido","type"=>"text"),
                    "user_type"=>array("as"=>"Perfil:","type"=>"select","select"=>$perfiles),
                    "email"=>array("as"=>"Correo","type"=>"email"),
                    "password"=>array("as"=>"Contraseña","type"=>"password"),
                    "repassword"=>array("as"=>"Reingresar Contraseña","type"=>"password"),
                    //"address"=>array("as"=>"Reingresar Contraseña","type"=>"password"), revisar este escenario
                ),
                $this->html->link("list_users")
            );
            $this->form->getLast()->setValidator($this->validator->objects[0]);
            
            $this->view = $this->data;
        }
        function index() {
            $filename=basename(__FILE__, '.php');
            $this->filename=$filename;
            checkPermiso(1,"admin_index");
            $this->table->next($this->filename);
            $this->table->objects[0]->setPreUpdate(
                function($params) {
                    $result_user=$this->connection->query("select count(id) as count from users where name='?'",$params["val"]);
                    if ($result_user[0]["count"]>0) {
                        throw new Exception('Nombre ocupado');
                    }
                }
            );

            $this->table->objects[0]->populate(
                array(
                    "users.id"=>array("display"=>true,'link'=>true,'as'=>'No. Usuario',"sort"=>true,"searchfull"=>true,"mysqlas"=>"id"),
                    "users.name"=>array("display"=>true,'link'=>true,'as'=>'Nombre',"sort"=>true,"searchfull"=>true,"mysqlas"=>"name","td"=>false),
                    "users.lastname"=>array("display"=>true,'link'=>false,'as'=>'Apellido',"mysqlas"=>"lastname"),
                    "users.email"=>array("display"=>true,'link'=>false,'as'=>'Correo',"searchfull"=>false,"mysqlas"=>"email"),
                    "users.last_login"=>array("display"=>true,'link'=>false,'as'=>'Ultimo Acceso',"mysqlas"=>"last_login"),
                    "user_type.name"=>array("display"=>true,'link'=>false,'as'=>'Tipo de Usuario',"mysqlas"=>"user_type"),
                    "'delete'"=>array("display"=>true,'link'=>true,'as'=>'Borrar',"mysqlas"=>"borrar"),
                ),
                "users",
                $this->sqlbuilder->setTable("users")
                    ->join("user_type","user_type","id")
                    ->where(array("1"=>"1",))
                    ->getSql(),
                function($data,$value,$key){
                    global $filename;
                    global $html;
                    switch ($key) {
                        case "id":
                            return $this->table->objects[0]->funcGen("id_btn",array(
                                "name"=>$data,
                                "btn_style"=>"btn-small",
                                "link"=>$html->link("list_users")."/detalles/?id=".$data
                            ));
                        break;
                        case "name":
                            return $this->table->objects[0]->funcGen("update",array(
                                "column"=>$key,
                                "row"=>$value["id"],
                                "data"=>$value[$key]
                            ));
                        break;
                        case "borrar":
                            return $this->table->objects[0]->funcGen("borrar",array(
                                "key"=>$value["id"],
                                "column"=>"id"
                            ));
                        break;
                    }
                }
            );
        }
    }

    // checkPermiso(1,"admin_index");
    $controller = new controller_view_class($registry);
    if($action!="" && is_callable(array($controller, $action))){
    call_user_func_array(array($controller, $action), array());
    }else{
    $controller->index();
    }
?>
