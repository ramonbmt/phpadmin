<?php
class search_class{
	public $template;
	public $page_type;
	public $args;
	public $page_name;
	private $columns;

	function __construct($template){
		$this->template=$template;
	}
	function setAll($template,$table,$args=null){
		$this->template=$template;
		$this->page_type=$table->bodyId;
		$this->page_name=$table->page_name;
		$this->args=$args;
		$this->columns=$table->getColumns();
	}

	// function search($type,$str,$args=null,$page=0){
	// 	global $connection;
	// 	global $user;
	// 	$result_search=array();
	// 	$result_search["success"]=1;
	// 	switch ($type) {
	// 		case 'list_clients':
	// 			if($user->user_type==2){
	// 				$result_search["page_result"]=$connection->query("select idcliente,direccion,entrecalles,colonia,ciudad,
	// 				estado,codigopostal,telefono1,telefono2,nombre,correo,paginainternet,vendedor,clientefacturacion,
	// 				precios,razonsocial
	// 				from clients
	// 				where nombre like '%?%' or correo like '%?%' or razonsocial like '%?%' limit 10",$str,$str,$str);
	// 			}else{
	// 				$result_search["page_result"]=$connection->query("select idcliente,direccion,entrecalles,colonia,ciudad,
	// 				estado,codigopostal,telefono1,telefono2,nombre,correo,paginainternet,vendedor,clientefacturacion,
	// 				precios,razonsocial
	// 				from clients
	// 				where idcliente in (select clients.idcliente from clients
	// 				left join clients_access on clients_access.client_id=clients.idcliente
	// 				where clients_access.user_id='?' or clients.user_id='?')
	// 				and (nombre like '%?%' or correo like '%?%' or razonsocial like '%?%') limit 10",$user->user_id,$user->user_id,
	// 				$str,$str,$str);
	// 			}
	// 		break;
	// 		case 'list_users':
	// 			$result_search["page_result"]=$connection->query("select users.id,users.name,users.lastname,users.email,
	// 			user_type.name as user_type
	// 			from users
	// 			inner join user_type on users.user_type=user_type.id
	// 			where (CONCAT(users.name, ' ', users.lastname)) like '%?%' or users.email like '%?%' limit 10",$str,$str);
	// 		break;
	// 		case 'list_cotizacion':
	// 			if($user->user_type==2){
	// 				$result_search["page_result"]=$connection->query("select invoice.id as id,users.name as name,
	// 				users.id as user_id,clients.razonsocial as razonsocial,clients.idcliente as client_id,
	// 				invoice_status_type.name as status,invoice.date as date
	// 				from invoice
	// 				inner join users on invoice.user_id=users.id
	// 				inner join clients on invoice.client_id=clients.idcliente
	// 				inner join invoice_status_type on invoice.status=invoice_status_type.id
	// 				where invoice.pedido=0
	// 				and (clients.razonsocial like '%?%' or clients.nombre like '%?%' or clients.correo like '%?%')
	// 				order by invoice.date desc ",$str,$str,$str);
	// 			}else{
	// 				$result_search["page_result"]=$connection->query("select invoice.id as id,users.name as name,
	// 				users.id as user_id,clients.razonsocial as razonsocial,clients.idcliente as client_id,
	// 				invoice_status_type.name as status,invoice.date as date
	// 				from invoice
	// 				inner join users on invoice.user_id=users.id
	// 				inner join clients on invoice.client_id=clients.idcliente
	// 				inner join invoice_status_type on invoice.status=invoice_status_type.id
	// 				where invoice.pedido=0 and invoice.client_id in (select clients.idcliente from clients
	// 				left join clients_access on clients_access.client_id=clients.idcliente
	// 				where clients_access.user_id='?' or clients.user_id='?')
	// 				and (clients.razonsocial like '%?%' or clients.nombre like '%?%' or clients.correo like '%?%')
	// 				order by invoice.date desc ",$user->user_id,$user->user_id,
	// 				$str,$str,$str);
	// 			}
	//
	// 		break;
	// 		case 'list_pedidos':
	// 			if($user->user_type==2){
	// 				$result_search["page_result"]=$connection->query("select invoice.id as id,users.name as name,
	// 				users.id as user_id,clients.razonsocial as razonsocial,clients.idcliente as client_id,
	// 				invoice_status_type.name as status,invoice.date as date
	// 				from invoice
	// 				inner join users on invoice.user_id=users.id
	// 				inner join clients on invoice.client_id=clients.idcliente
	// 				inner join invoice_status_type on invoice.status=invoice_status_type.id
	// 				where invoice.pedido=1
	// 				and (clients.razonsocial like '%?%' or clients.nombre like '%?%' or clients.correo like '%?%')
	// 				order by invoice.date desc ",$str,$str,$str);
	// 			}else{
	// 				$result_search["page_result"]=$connection->query("select invoice.id as id,users.name as name,
	// 				users.id as user_id,clients.razonsocial as razonsocial,clients.idcliente as client_id,
	// 				invoice_status_type.name as status,invoice.date as date
	// 				from invoice
	// 				inner join users on invoice.user_id=users.id
	// 				inner join clients on invoice.client_id=clients.idcliente
	// 				inner join invoice_status_type on invoice.status=invoice_status_type.id
	// 				where invoice.pedido=1 and invoice.client_id in (select clients.idcliente from clients
	// 				left join clients_access on clients_access.client_id=clients.idcliente
	// 				where clients_access.user_id='?' or clients.user_id='?')
	// 				and (clients.razonsocial like '%?%' or clients.nombre like '%?%' or clients.correo like '%?%')
	// 				order by invoice.date desc ",$user->user_id,$user->user_id,
	// 				$str,$str,$str);
	// 			}
	//
	// 		break;
	// 		default:
	//
	// 		break;
	// 	}
	// 	return $result_search;
	// }

	function constructSearch(){
		global $html;
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		echo $page;
	}
	function constructSearchString(){
		global $html;
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}

}
$search = new search_class("");
?>
