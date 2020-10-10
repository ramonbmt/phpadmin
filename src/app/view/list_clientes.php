<?php
	class controller_view_class extends controller_class{
		function detalles(){
			include "include/tabbable.php";
			$this->tabbs=new tabbable("tabb_template");
			$filename=basename(__FILE__, '.php');
			//referenciado del filename para que sea parte del objeto (this)
			$this->filename = $filename;

			if(isset($_GET["id"])){
				$result = $this->connection->query("select id,nombre_comercial
				from clients where id='?' limit 1",$_GET["id"]);
				if(!isset($result[0]["id"])){
					header("Location: ".$this->html->link("list_clientes"));
					die();
				}
			}else{
				header("Location: ".$this->html->link("list_clientes"));
				die();
			}
			$u_id=$_GET["id"];

			$this->validator->setPage($filename);
			if($this->validator->secureSend()){
				$check=array(
					"nombre_comercial"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre Comercial','appendquotes'=>"'"),
					"razon_social"=>array('isset'=>'','notempty'=>'Favor de ingresar una Razón Social','appendquotes'=>"'"),
					"rfc"=>array('default'=>'','notempty'=>'Favor de ingresar un RFC','appendquotes'=>"'"),
					"calle"=>array('default'=>'','notempty'=>'Favor de ingresar una Calle','appendquotes'=>"'"),
					"numero_exterior"=>array('default'=>'','notempty'=>'Favor de ingresar un Número Exterior','appendquotes'=>"'"),
					"numero_interior"=>array('default'=>'','appendquotes'=>"'"),
					"colonia"=>array('default'=>'','notempty'=>'Favor de ingresar una Colonia','appendquotes'=>"'"),
					"municipio_o_delegacion"=>array('default'=>'','notempty'=>'Favor de ingresar un Municipio o Delegación','appendquotes'=>"'"),
					"estado"=>array('default'=>'','notempty'=>'Favor de ingresar un Estado','appendquotes'=>"'"),
					"codigo_postal"=>array('default'=>'','notempty'=>'Favor de ingresar un Código Postal','appendquotes'=>"'"),
					"pais"=>array('default'=>'Mexico','notempty'=>'Favor de ingresar un País','appendquotes'=>"'"),
					"telefono"=>array('isset'=>'','notempty'=>'Favor de ingresar un Teléfono','appendquotes'=>"'"),
					"email_contacto"=>array('isset'=>'','notempty'=>'Favor de ingresar un Correo','email'=>'Favor de introducir un correo valido','appendquotes'=>"'"),
					"nombre"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
				);
				if($this->validator->validator($check, 'post')){
					if($this->validator->selfUpdate("clients",array("id"=>$_GET["id"]))){
						$this->validator->clearValues();
					}else{
						$error=$this->validator->getError();
					}
				}else{
					$error=$this->validator->getError();
				}
			}
			if(isset($error)){
				$this->data["error"]=$error;
			}

			$this->edit->populate(
				array(
					"id"=>array("as"=>"No. Cliente:","link"=>false,"disabled"=>true),
					"nombre_comercial"=>array("as"=>"Numero de Cliente:","link"=>false,"disabled"=>false),
					"razon_social"=>array("as"=>"Razón Social:","link"=>false,"disabled"=>false),
					"rfc"=>array("as"=>"RFC:","link"=>false,"disabled"=>false),
					"calle"=>array("as"=>"Calle:","link"=>false,"disabled"=>false),
					"numero_exterior"=>array("as"=>"Número Exterior:","link"=>false,"disabled"=>false),
					"numero_interior"=>array("as"=>"Número Interior:","link"=>false,"disabled"=>false),
					"colonia"=>array("as"=>"Colonia:","link"=>false,"disabled"=>false),
					"municipio_o_delegacion"=>array("as"=>"Municipio o Delegación:","link"=>false,"disabled"=>false),
					"estado"=>array("as"=>"Estado:","link"=>false,"disabled"=>false),
					"codigo_postal"=>array("as"=>"Código Postal:","link"=>false,"disabled"=>false),
					"pais"=>array("as"=>"País:","link"=>false,"disabled"=>false),
					"telefono"=>array("as"=>"Teléfono:","link"=>false,"disabled"=>false),
					"email_contacto"=>array("as"=>"E-mail:","link"=>false,"disabled"=>false),
					"nombre"=>array("as"=>"Nombre:","link"=>false,"disabled"=>false),
				),
				"clients",
				$this->sqlbuilder->setTable("clients")
					->where(array(
							"id"=>"'?'",
						))
					->setParams($u_id)
					->getSql()
			);

			if(isset($_POST["ajax"])&&isset($_POST["page"])&&$_POST["ajax"]==1){
				$data=$this->table->getData();
				$data["success"]=1;
				echo json_encode($data);
				die();
			}

			$this->tabbs->newTabb(
				$this->edit->makeEditString(function($data,$value,$key){},function($data,$value,$key){},"display_user",$this->validator),
				"Datos"
			);
			$this->search->setAll("search_template_self",$this->table);
			$this->pagination->setAll("pagination_template_self",0);
			$this->data["edit"]=$this->tabbs->constructString();
			$this->data["breadcrumb"]=array(
				"admin_index",
				"list_clientes",
				array(
					"link"=>"list_clientes",
					"path"=>"Detalles",
					"id"=>$_GET["id"]
				)
			);
			$this->template="edit_template";
			$this->data["edit_title"]="Información del Cliente: ".$result[0]["nombre_comercial"];
			echo $this->render();
		}

		function agregar(){
			$filename=basename(__FILE__, '.php');
			$this->filename=$filename;

			$this->form = new form_class("form_template");
			$this->form->populate(
				array(
					"nombre"=>array(
						"as"=>"Nombre:","type"=>"text","required"=>true
					),
					"nombre_comercial"=>array(
						"as"=>"Nombre de Cliente:","type"=>"text","required"=>true
					),
					"razon_social"=>array(
						"as"=>"Razón Social:","type"=>"text","required"=>true
					),
					"telefono"=>array(
						"as"=>"Teléfono:","type"=>"text","required"=>true
					),
					"email_contacto"=>array(
						"as"=>"E-mail:","type"=>"text","required"=>true
					),
					"rfc"=>array(
						"as"=>"RFC:","type"=>"text"
					),
					"calle"=>array(
						"as"=>"Calle:","type"=>"text"
					),
					"numero_exterior"=>array(
						"as"=>"Número Exterior:","type"=>"text"
					),
					"numero_interior"=>array(
						"as"=>"Número Interior:","type"=>"text"
					),
					"colonia"=>array(
						"as"=>"Colonia:","type"=>"text"
					),
					"municipio_o_delegacion"=>array(
						"as"=>"Municipio o Delegación:","type"=>"text"
					),
					"estado"=>array(
						"as"=>"Estado:","type"=>"text"
					),
					"codigo_postal"=>array(
						"as"=>"Código Postal:","type"=>"text"
					),
					"pais"=>array(
						"as"=>"País:","type"=>"text"
					),
				),
				$this->html->link("list_clientes")
			);

			$this->validator->setPage($filename);
			if($this->validator->secureSend()){
				$check=array(
					"telefono"=>array(
						'isset'=>'','notempty'=>'Favor de ingresar un Teléfono','appendquotes'=>"'"
					),
					"email_contacto"=>array(
						'isset'=>'','notempty'=>'Favor de ingresar un Correo',
						'email'=>'Favor de introducir un correo valido','appendquotes'=>"'"
					),
					"nombre"=>array(
						'isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"
					),
					"nombre_comercial"=>array(
						'isset'=>'','notempty'=>'Favor de ingresar un Nombre Comercial','appendquotes'=>"'"
					),
					"razon_social"=>array(
						'isset'=>'','notempty'=>'Favor de ingresar una Razón Social','appendquotes'=>"'"
					),
					"rfc"=>array(
						'default'=>'','notempty'=>'Favor de ingresar un RFC','appendquotes'=>"'"
					),
					"calle"=>array(
						'default'=>'','notempty'=>'Favor de ingresar una Calle','appendquotes'=>"'"
					),
					"numero_exterior"=>array(
						'default'=>'','notempty'=>'Favor de ingresar un Número Exterior','appendquotes'=>"'"
					),
					"numero_interior"=>array(
						'default'=>'','appendquotes'=>"'"
					),
					"colonia"=>array(
						'default'=>'','notempty'=>'Favor de ingresar una Colonia','appendquotes'=>"'"
					),
					"municipio_o_delegacion"=>array(
						'default'=>'','notempty'=>'Favor de ingresar un Municipio o Delegación','appendquotes'=>"'"
					),
					"estado"=>array(
						'default'=>'','notempty'=>'Favor de ingresar un Estado','appendquotes'=>"'"
					),
					"codigo_postal"=>array(
						'default'=>'','notempty'=>'Favor de ingresar un Código Postal','appendquotes'=>"'"
					),
					"pais"=>array(
						'default'=>'','notempty'=>'Favor de ingresar un País','appendquotes'=>"'"
					),
				);
				if($this->validator->validator($check, 'post')){
					if($this->validator->selfInsert("clients")){
						$this->validator->clearValues();
						header("Location: ".$this->html->link("list_clientes"));
					}else{
						$error=$this->validator->getError();
					}
				}else{
					$error=$this->validator->getError();
				}
			}
			if(isset($error)){
				$this->data["error"]=$error;
			}
			$this->data["breadcrumb"]=array(
				"admin_index",
				"list_clientes",array(
					"link"=>"list_clientes","path"=>"agregar"
				)
			);
			$this->template="add_template";
			$this->data["title"]="Agregar Cliente";
			$this->data["form"]=$this->form->createFormString(function(){},$this->validator);
			echo $this->render();
		}

		function index(){
			$filename=basename(__FILE__, '.php');
			$this->filename=$filename;

			$this->table->setPreDelete(
                function($params) {
					$result_user_punto_venta=$this->connection->query("select count(idPV) as count 
						from punto_venta where idCliente='?'",$params["id"]);
                    if ($result_user_punto_venta[0]["count"]>0) {
                        throw new Exception('No es posible eliminar el cliente, el cliente tiene ventas generadas por el punto de venta');
					}
					$result_user_cotizaciones=$this->connection->query("select count(idCotizacion) as count 
						from cotizaciones where idCliente='?'",$params["id"]);
                    if ($result_user_cotizaciones[0]["count"]>0) {
                        throw new Exception('No es posible eliminar el cliente, el cliente tiene cotizaciones asignadas');
					}
					$result_user_ordenes_compra=$this->connection->query("select count(idOrden) as count 
						from ordenes_venta where idCliente='?'",$params["id"]);
                    if ($result_user_ordenes_compra[0]["count"]>0) {
                        throw new Exception('No es posible eliminar el cliente, el cliente tiene Ordenes de Venta asignadas');
                    }
                }
            );
			$this->table->populate(
				array(
					"id"=>array("mysqlas"=>"id","display"=>true,'link'=>true,'as'=>'Id',"sort"=>true),
					"nombre_comercial"=>array("mysqlas"=>"name","display"=>true,'link'=>false,'as'=>'Nombre Comercial',"searchfull"=>true,"edit"=>true,"td"=>true),
					"telefono"=>array("mysqlas"=>"telefono","display"=>true,'link'=>false,'as'=>'Teléfono',"sort"=>false,"searchfull"=>false),
					"nombre"=>array("mysqlas"=>"nombre","display"=>true,'link'=>false,'as'=>'Contacto',"sort"=>false,"searchfull"=>true),
					"email_contacto"=>array("mysqlas"=>"email_contacto","display"=>true,'link'=>false,'as'=>'Correo',"sort"=>false,"searchfull"=>true),
					"'delete'"=>array("display"=>true,'link'=>true,'as'=>'Eliminar',"mysqlas"=>"borrar"),
				),
				"clients",
				$this->sqlbuilder->setTable("clients")
					->where(array(
						"1"=>"1",
					))
					//->orderBy("users.id","desc")
					->getSql(),
				function($data,$value,$key){
					global $filename;
					global $html;
					switch($key){
						case "id":
							return $this->table->funcGen("id_btn",array(
								"name"=>$data,
								"btn_style"=>"btn-small",
								"link"=>$html->link("list_clientes")."/detalles/?id=".$data
							));
						break;
						case "borrar":
							return $this->table->funcGen("borrar",array(
								"key"=>$value["id"],
								"column"=>"id"
							));
						break;
					}
				},$filename,$filename
			);

			$this->data["breadcrumb"]=array("admin_index","list_clientes");
			$this->data["table_header"]="Clientes";
			$this->data["table_link"]=$this->html->link("list_clientes")."/Agregar";
			$this->data["filename"]=$filename;

			if(isset($_POST["ajax"])&&isset($_POST["page"])&&$_POST["ajax"]==1){
				$data=$this->table->getData();
				$data["success"]=1;
				echo json_encode($data);
				die();
			}
			$this->search->setAll("search_template_self",$this->table);
			$this->data["search_result"]=$this->search->constructSearchString();
			$this->data["table_result"]= $this->table->createTableString();

			$this->pagination->setAll("pagination_template_self",0);
			$this->data["pagination_result"]=$this->pagination->makePaginationString($this->table->getNum(),$this->table);
			$this->template="list_template";
			echo $this->render();
		}
	}

	$controller = new controller_view_class($registry);
	checkPermiso(4,"admin_index");
	if($action!=""&&is_callable(array($controller, $action))){
		call_user_func_array(array($controller, $action), array());
	}else{
		$controller->index();
	}
?>
