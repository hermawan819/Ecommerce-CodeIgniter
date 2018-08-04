<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Account_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
	function Login($username,$password)
	{
		
		$this->db->where(array("username"=>$username,"password"=>$password));
		$result = $this->db->get("user")->row();
		
		if($result)
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
    /*
     * Get order by OrderId
     */
    function get_order($OrderId)
    {
        return $this->db->query("select o.OrderId,os.OrderStatus,o.OrderNotes,o.CreatedDate as OrderDate,p.ProductId,p.ProductName,c.FirstName,c.LastName,
		o.CODAmount,o.ShippingCost,c.CustomerId,
c.Cell1,c.Cell2,c.ShippingAddress,c.City,c.NearMostFamousPlace,c.Email
from Orders o 
join OrderStatus os on o.OrderStatusId = os.OrderStatusId
join product p on o.ProductId=p.ProductId
join customer c on o.customerid = c.CustomerId
where o.OrderId=".$OrderId."
order by o.OrderId desc
")->row_array();
    }
    
    /*
     * Get all orders count
     */
    function get_all_orders_count($orderStatusId)
    {
		$this->db->where('isdelete',0);
		$this->db->where('orderstatusid',$orderStatusId);
        $this->db->from('orders');
        return $this->db->count_all_results();
    }
        
    /*
     * Get all orders
     */
    function get_all_orders($params = array())
    {        
		
		return $this->db->query("select o.OrderId,os.OrderStatus,o.OrderNotes,o.CreatedDate as OrderDate,p.ProductName,concat(c.FirstName,' ',c.LastName) as CustomerName, (o.CODAmount+o.ShippingCost) as TotalCOD,
c.Cell1,c.Cell2,c.ShippingAddress,c.City
from Orders o 
join OrderStatus os on o.OrderStatusId = os.OrderStatusId
join product p on o.ProductId=p.ProductId
join customer c on o.customerid = c.CustomerId
where o.isdelete=0
and os.OrderStatusId=".$params['orderstatusid']."
order by o.OrderId desc
limit ".$params['limit']." offset ".$params['offset']."

")->result_array();
		
        
    }
        
    /*
     * function to add new order
     */
    function add_order($params)
    {
        $this->db->insert('orders',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update order
     */
    function update_order($OrderId,$params)
    {
        $this->db->where('OrderId',$OrderId);
        return $this->db->update('orders',$params);
    }
	
	function update_order_status($OrderId,$orderStatusId)
    {
        $this->db->where('OrderId',$OrderId);
		
		$params = array("OrderStatusId"=>$orderStatusId);
		
        return $this->db->update('orders',$params);
    }
    
    /*
     * function to delete order
     */
    function delete_order($OrderId)
    {
		
		$this->db->where('OrderId',$OrderId);
        return $this->db->update('orders',array("isdelete"=>1));
		
    }
}
