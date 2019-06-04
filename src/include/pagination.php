<?php

class pagination_class
{
	public $template = null;
	public $pagination_ajax = null;
	public $num_display = null;
	public $current_page = null;
	public $page_type = null;
	public $n_pages = null;
	public $args = null;
	public $self = false;
	public $filename = null;

	function __construct($template)
	{
		global $config;
		$this->template = $template;
		$this->num_display = $config['default_pagination'];
	}

	function setAll($template,$current_page,$page_type="",$args=null,$self=false,$num_display=false)
	{
		global $config;
		if($num_display==false)
		{
			$num_display = $config['default_pagination'];
		}

		if((array)$args===$args)
		{
			$this->args=$args;
		}

		$this->template=$template;
		$this->num_display=$num_display;
		$this->current_page=$current_page;
		$this->page_type=$page_type;
		$this->self=$self;
	}
	function setNPages($n_pages){
		$this->n_pages=$n_pages;
	}
	function setNumDisplay($num_display){
		$this->num_display=$num_display;
	}
	function makePagination($num=null,$table=null){
		global $html;
		if($num!=null){
			$result['count']=$num;
			$this->page_type=$table->bodyId;
			$this->filename=$table->page_name;
			$this->n_pages=(int)$result['count']/$this->num_display;
			ob_start();
			include($html->sistemComponentView($this->template));
			$page = ob_get_contents();
			ob_end_clean();
			echo $page;
		}else{
			$result=$this->getNumPages($this->page_type,$this->args);
			if(isset($result['count'])){
				$this->n_pages=(int)$result['count']/$this->num_display;
				ob_start();
				include($html->sistemComponentView($this->template));
				$page = ob_get_contents();
				ob_end_clean();
				echo $page;
			}
		}
	}
	function makePaginationString($num=null,$table=null){
		global $html;
		if($num!=null){
			$result['count']=$num;
			$this->page_type=$table->bodyId;
			$this->filename=$table->page_name;
			$this->n_pages=(int)$result['count']/$this->num_display;
			ob_start();
			include($html->sistemComponentView($this->template));
			$page = ob_get_contents();
			ob_end_clean();
			return $page;
		}else{
			$result=$this->getNumPages($this->page_type,$this->args);
			if(isset($result['count'])){
				$this->n_pages=(int)$result['count']/$this->num_display;
				ob_start();
				include($html->sistemComponentView($this->template));
				$page = ob_get_contents();
				ob_end_clean();
				return $page;
			}
		}
	}
	function getNumPages($page_type, $args=null, $ajax=false, $page=false){
		global $connection;
		global $user;
		if($page!=false){
			$this->current_page=$page;
		}
		switch ($page_type) {
			case 'list_users':
				$result_pagination=$connection->query("select count(id) as count
				from users
				where 1");
				$result_pagination['count']=$result_pagination[0]['count'];
				if($ajax){
					$result_pagination['page_result']=$connection->query("select users.id,users.name,users.lastname,users.email,
					user_type.name as user_type
					from users
					inner join user_type on users.user_type=user_type.id
					where 1 order by users.id asc limit ?,?",$this->current_page*$this->num_display,
					$this->num_display);
					$result_pagination['success']=1;
				}
			break;
			case 'list_clients':
				if($user->user_type==2){
					$result_pagination=$connection->query("select count(idcliente) as count
					from clients
					where 1");
					$result_pagination['count']=$result_pagination[0]['count'];
					if($ajax){
						$result_pagination['page_result']=$connection->query("select clients.idcliente,clients.direccion,
						clients.entrecalles,clients.colonia,clients.ciudad,clients.estado,clients.codigopostal,clients.telefono1,
						clients.telefono2,clients.nombre,clients.correo,clients.paginainternet,clients.vendedor,clients.clientefacturacion,
						clients.precios,clients.razonsocial,users.id as user_id,users.name as user_name
						from clients
						inner join users on clients.user_id=users.id
						where 1
						order by idcliente desc limit ?,?",$this->current_page*$this->num_display,
						$this->num_display);
						$result_pagination['success']=1;
					}
				}else{
					$result_pagination=$connection->query("select count(idcliente) as count
					from clients
					where idcliente in (select clients.idcliente from clients
					left join clients_access on clients_access.client_id=clients.idcliente
					where clients_access.user_id='?' or clients.user_id='?')",$user->user_id,$user->user_id);
					$result_pagination['count']=$result_pagination[0]['count'];
					if($ajax){
						$result_pagination['page_result']=$connection->query("select clients.idcliente,clients.direccion,
						clients.entrecalles,clients.colonia,clients.ciudad,clients.estado,clients.codigopostal,clients.telefono1,
						clients.telefono2,clients.nombre,clients.correo,clients.paginainternet,clients.vendedor,clients.clientefacturacion,
						clients.precios,clients.razonsocial,users.id as user_id,users.name as user_name
						from clients
						inner join users on clients.user_id=users.id
						where idcliente in (select clients.idcliente from clients
						left join clients_access on clients_access.client_id=clients.idcliente
						where clients_access.user_id='?' or clients.user_id='?')
						order by idcliente desc limit ?,?",$user->user_id,$user->user_id,
						$this->current_page*$this->num_display,$this->num_display);
						$result_pagination['success']=1;
					}
				}
			break;
			case 'list_cotizacion':
				if($user->user_type==2){
					$result_pagination=$connection->query("select count(invoice.id) as count
					from invoice
					where invoice.pedido=0");
					$result_pagination['count']=$result_pagination[0]['count'];
					if($ajax){
						$result_pagination['page_result']=$connection->query("select invoice.id as id,users.name as name,
						users.id as user_id,clients.razonsocial as razonsocial,clients.idcliente as client_id,
						invoice_status_type.name as status,invoice.date as date
						from invoice
						inner join users on invoice.user_id=users.id
						inner join clients on invoice.client_id=clients.idcliente
						inner join invoice_status_type on invoice.status=invoice_status_type.id
						where invoice.pedido=0 order by invoice.date desc limit ?,?",$this->current_page*$this->num_display,
						$this->num_display);
						$result_pagination['success']=1;
					}
				}else{
					$result_pagination=$connection->query("select count(invoice.id) as count
					from invoice
					where invoice.pedido=0 and invoice.client_id in (select clients.idcliente from clients
					left join clients_access on clients_access.client_id=clients.idcliente
					where clients_access.user_id='?' or clients.user_id='?')",$user->user_id,$user->user_id);
					$result_pagination['count']=$result_pagination[0]['count'];
					if($ajax){
						$result_pagination['page_result']=$connection->query("select invoice.id as id,users.name as name,
						users.id as user_id,clients.razonsocial as razonsocial,clients.idcliente as client_id,
						invoice_status_type.name as status,invoice.date as date
						from invoice
						inner join users on invoice.user_id=users.id
						inner join clients on invoice.client_id=clients.idcliente
						inner join invoice_status_type on invoice.status=invoice_status_type.id
						where invoice.pedido=0 and invoice.client_id in (select clients.idcliente from clients
						left join clients_access on clients_access.client_id=clients.idcliente
						where clients_access.user_id='?' or clients.user_id='?')
						order by invoice.date desc limit ?,?",$user->user_id,$user->user_id,
						$this->current_page*$this->num_display,$this->num_display);
						$result_pagination['success']=1;
					}
				}
			break;
			case 'list_pedido':
				if($user->user_type==2){
					$result_pagination=$connection->query("select count(invoice.id) as count
					from invoice
					where invoice.pedido=1");
					$result_pagination['count']=$result_pagination[0]['count'];
					if($ajax){
						$result_pagination['page_result']=$connection->query("select invoice.id as id,users.name as name,
						users.id as user_id,clients.razonsocial as razonsocial,clients.idcliente as client_id,
						invoice_status_type.name as status,invoice.date as date
						from invoice
						inner join users on invoice.user_id=users.id
						inner join clients on invoice.client_id=clients.idcliente
						inner join invoice_status_type on invoice.status=invoice_status_type.id
						where invoice.pedido=1 order by invoice.date desc limit ?,?",$this->current_page*$this->num_display,
						$this->num_display);
						$result_pagination['success']=1;
					}
				}else{
					$result_pagination=$connection->query("select count(invoice.id) as count
					from invoice
					where invoice.pedido=1 and invoice.client_id in (select clients.idcliente from clients
					left join clients_access on clients_access.client_id=clients.idcliente
					where clients_access.user_id='?' or clients.user_id='?')",$user->user_id,$user->user_id);
					$result_pagination['count']=$result_pagination[0]['count'];
					if($ajax){
						$result_pagination['page_result']=$connection->query("select invoice.id as id,users.name as name,
						users.id as user_id,clients.razonsocial as razonsocial,clients.idcliente as client_id,
						invoice_status_type.name as status,invoice.date as date
						from invoice
						inner join users on invoice.user_id=users.id
						inner join clients on invoice.client_id=clients.idcliente
						inner join invoice_status_type on invoice.status=invoice_status_type.id
						where invoice.pedido=1 and invoice.client_id in (select clients.idcliente from clients
						left join clients_access on clients_access.client_id=clients.idcliente
						where clients_access.user_id='?' or clients.user_id='?')
						order by invoice.date desc limit ?,?",$user->user_id,$user->user_id,
						$this->current_page*$this->num_display,$this->num_display);
						$result_pagination['success']=1;
					}
				}
			break;
			case 'display_client_pedidos':
				$result_pagination=$connection->query("select count(invoice.id) as count
				from invoice
				where invoice.client_id='?' and invoice.pedido='1'",$args[0]);
				$result_pagination['count']=$result_pagination[0]['count'];
				if($ajax){
					$result_pagination['page_result_pedidos']=$connection->query("select invoice.id,
					sum(invoice_products.qty*invoice_products.price) as price,users.name as vendedor,users.id as user_id
					from invoice
					inner join invoice_products on invoice_products.invoice_id=invoice.id
					inner join users on invoice.user_id=users.id
					where invoice.client_id='?' and invoice.pedido='1'
					group by invoice_products.invoice_id
					order by invoice.id desc limit ?,?",$args[0],$this->current_page*$this->num_display,
					$this->num_display);
					$result_pagination['success']=1;
				}
			break;
			case 'list_events':
				$result_pagination=$connection->query("select count(events.id) as count
				from events
				where client_id='?' and user_id='?'",$args[0],$user->user_id);
				$result_pagination['count']=$result_pagination[0]['count'];
				if($ajax){
					$result_pagination["page_result_event"]=$connection->query("select events.id,events.name,events.shared,
					events.date_start,events.date_end,event_type.name as client_type
					from events
					left join event_type on events.event_type=event_type.id
					where client_id='?' and user_id='?'
					order by events.id desc limit ?,?",$args[0],$user->user_id,$this->current_page*$this->num_display,
					$this->num_display);
					$result_pagination['success']=1;
				}
			break;
			case 'report_productos_cliente':
				$result_pagination=$connection->query("select count(clients.idcliente) as count
				from clients
				where 1");
				$result_pagination['count']=$result_pagination[0]['count'];
				if($ajax){
					$result_pagination["page_result"]=$connection->query("select idcliente,nombre,telefono1,correo,paginainternet
					from clients
					where 1
					order by clients.idcliente desc limit ?,?",$this->current_page*$this->num_display,$this->num_display);
					$result_pagination['success']=1;
				}
			break;
			case 'metric_productos_cliente_clients':
				$result_pagination=$connection->query("select count(clients.idcliente) as count
				from clients
				where idcliente in ?",$args["send_clients"]);
				$result_pagination['count']=$result_pagination[0]['count'];
				if($ajax){
					$result_pagination["page_result"]=$connection->query("select idcliente,razonsocial,nombre,telefono1,correo,paginainternet
					from clients
					where idcliente in ?
					order by clients.idcliente desc limit ?,?",$args["send_clients"],
					$this->current_page*$this->num_display,$this->num_display);
					$result_pagination['success']=1;
				}
			break;
			case 'metric_productos_cliente_productos':
				$result_pagination=$connection->query("select count(invoice_products.id) as count
				from invoice_products
				inner join invoice on invoice.id=invoice_products.invoice_id
				inner join products on invoice_products.product_id=products.id
				inner join clients on invoice.client_id=clients.idcliente
				where invoice.client_id in ?
				group by invoice_products.product_id",$args["send_clients"]);
				$result_pagination['count']=$result_pagination[0]['count'];
				if($ajax){
					$result_pagination["page_result_products"]=$connection->query("select products.id,products.clave,products.nombre,products.precio1,
					products.precio2,products.precio3,clients.idcliente,clients.nombre as nombrecliente
					from invoice_products
					inner join invoice on invoice.id=invoice_products.invoice_id
					inner join products on invoice_products.product_id=products.id
					inner join clients on invoice.client_id=clients.idcliente
					where invoice.client_id in ?
					group by invoice_products.product_id order by invoice.client_id limit ?,?",$args["send_clients"],
					$this->current_page*$this->num_display,$this->num_display);
					$result_pagination['success']=1;
				}
			break;
			case 'report_clientes_vendedor':
				$result_pagination=$connection->query("select count(users.id) as count
				from users
				where 1");
				$result_pagination['count']=$result_pagination[0]['count'];
				if($ajax){
					$result_pagination["page_result_products"]=$connection->query("select id,name,lastname,email,date
					from users
					order by users.id desc limit ?,?",$this->current_page*$this->num_display,$this->num_display);
					$result_pagination['success']=1;
				}
			break;
			case 'metric_events_clients':
				$result_pagination=$connection->query("select count(clients.idcliente) as count
				from clients
				where idcliente in ?",$args["send_clients"]);
				$result_pagination['count']=$result_pagination[0]['count'];
				if($ajax){
					$result_pagination["page_result_campain_table"]=$connection->query("select idcliente,razonsocial,nombre,correo,
					paginainternet,telefono1
					from clients
					where clients.idcliente in ?
					order by clients.idcliente desc limit ?,?",$args["send_clients"],
					$this->current_page*$this->num_display,$this->num_display);
					$result_pagination['success']=1;
				}
			break;
			case 'metric_events_clients_events':
				$result_pagination=$connection->query("select count(events.id) as count
				from events
				left join event_type on events.event_type=event_type.id
				left join clients on events.client_id=clients.idcliente
				left join users on events.user_id=users.id
				where events.client_id in ?
				and date(events.date_start) >= '?'
				and date(events.date_start) <= '?' ",$args["send_clients"],$args["send_date_start"],$args["send_date_end"]);
				$result_pagination['count']=$result_pagination[0]['count'];
				if($ajax){
					$result_pagination["page_result_productos_cliente"]=$connection->query("select events.id as event_id,
					events.name as event_name,events.shared as shared,events.date_start as date_start,events.date_end as date_end,
					events.user_id as user_id,users.name as user_name,event_type.name as event_type,events.client_id as client_id,
					clients.nombre as client_name,events.mn_reminder as mn_reminder
					from events
					left join event_type on events.event_type=event_type.id
					left join clients on events.client_id=clients.idcliente
					left join users on events.user_id=users.id
					where events.client_id in ?
					and date(events.date_start) >= '?'
					and date(events.date_start) <= '?'
					order by events.id desc limit ?,?",$args["send_clients"],$args["send_date_start"],$args["send_date_end"],
					$this->current_page*$this->num_display,$this->num_display);
					$result_pagination['success']=1;
				}
			break;
			default:
				$result_pagination=array();
			break;
		}

		return $result_pagination;
	}

}
$pagination = new pagination_class("");
//$pagination->setAll($template,$num_display,$current_page);
//$pagination->makePagination();
?>
