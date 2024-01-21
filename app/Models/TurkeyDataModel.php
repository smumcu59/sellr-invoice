<?php 

namespace App\Models;

use CodeIgniter\Model;

class TurkeyDataModel extends Model
{

	protected $DBGroup = 'turkeyLocation';

	public function updateData($where,$table,$data)
	{
		
		$data = $this->db->table($table)->where($where)->update($data);

		return $data;
	}

	public function addData($where,$table)
	{
		$data = $this->db->table($table)->insert($where);

		return $data;
	}

	public function deleteData($where,$table)
	{

		$data = $this->db->table($table)->delete($where);

		return $data;
	}

	public function getData($where,$table)
	{
		
		$data = $this->db->table($table)->where($where)->get();

		
	
		return $data->getRow();
	
	}

	public function getDataSelect($select,$where,$table){

		$data = $this->db->table($table)->select($select)->where($where)->get();
		
		return $data->getRow();
	}

	// -------------------------------------------------------------------------

	

	
	


	public function getDataOr2($where,$orWhere1,$orWhere2,$table)
	{
		
		$data = $this->db->table($table)->where($where)->groupStart()->orWhere($orWhere1)->orWhere($orWhere2)->groupEnd()->get();
	
		return $data->getRow();
	
	}

	public function getDataOr3($where,$orWhere1,$orWhere2,$orWhere3,$table)
	{
		
		$data = $this->db->table($table)->where($where)->groupStart()->orWhere($orWhere1)->orWhere($orWhere2)->orWhere($orWhere3)->groupEnd()->get();
	
		return $data->getRow();
	
	}

	public function getListOr2($where,$orWhere1,$orWhere2,$table){

		$data = $this->db->table($table)->where($where)->groupStart()->orWhere($orWhere1)->orWhere($orWhere2)->groupEnd()->get();
	
		return $data->getResult();
	}


	public function getList($where,$table){

		$data = $this->db->table($table)->where($where)->get();

		return $data->getResult();
	}

	public function getListLimit($where,$table,$limit){
		$data = $this->db->table($table)->where($where)->orderBy("id","DESC")->limit($limit)->get();

		return $data->getResult();
	}

	public function getListOrder($where,$table,$order){
		$data = $this->db->table($table)->where($where)->orderBy($order)->get();

		return $data->getResult();
	}

}


?>