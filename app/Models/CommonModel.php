<?php 

namespace App\Models;

use CodeIgniter\Model;

class CommonModel extends Model
{

	protected $DBGroup = 'default';

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

	

	
	public function sendMail($to,$subject,$message){

		$email = \Config\Services::email();

		$data = $this->db->table('statik_bilgiler')->where(['id'=>1])->get();

		
		$emailData = $data->getRow();

        $config['protocol'] = 'smtp';
        $config['SMTPHost'] = $emailData->smtp_host;
        $config['SMTPPort'] = $emailData->smtp_port;
        $config['SMTPUser'] = $emailData->smtp_username;
        $config['fromName'] = $emailData->website_name;
        $config['SMTPPass'] = $emailData->smtp_password;
        $config['wordWrap'] = TRUE;
        $config['newline'] = "\r\n";
        $config['mailType'] = 'html';  



        $email->initialize($config);

        


        $email->setFrom($config['SMTPUser'], $config['fromName']);
        $email->setTo($to);
        
        $email->setSubject($subject);
        

        $email->setMessage($message);
       
        if($email->send()) {
            return "OKEY";        
        } else {
            return "ERROR";
        }

              
	}


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

	public function adminDataCheck($identityNumber,$userRoll){
		

		$data = $this->db->table('pay_admins')->where(['identity_number'=>$identityNumber,'user_role_id'=>$userRoll])->groupStart()->orWhere(['status'=>1])->orWhere(['status'=>3])->groupEnd()->get();
		
		return $data->getRow();
	}

	public function customerDataCheck($identityNumber){
		$data = $this->db->table('pay_users')->where(['identity_number'=>$identityNumber,'account_type'=>1])->groupStart()->orWhere(['status'=>1])->orWhere(['status'=>2])->orWhere(['status'=>3])->orWhere(['status'=>4])->orWhere(['status'=>6])->groupEnd()->get();

		return $data->getRow();
	}

	public function customerDataCheckId($userId){
		$data = $this->db->table('pay_users')->where(['id'=>$userId,'account_type'=>1])->groupStart()->orWhere(['status'=>1])->orWhere(['status'=>2])->orWhere(['status'=>3])->orWhere(['status'=>4])->orWhere(['status'=>6])->groupEnd()->get();

		return $data->getRow();
	}

	public function merchantDataCheckId($userId){
		$data = $this->db->table('pay_users')->where(['id'=>$userId,'account_type'=>2])->groupStart()->orWhere(['status'=>1])->orWhere(['status'=>2])->orWhere(['status'=>3])->orWhere(['status'=>4])->orWhere(['status'=>6])->groupEnd()->get();

		return $data->getRow();
	}

	public function merchantDataCheck($identityNumber){
		$data = $this->db->table('pay_users')->where(['identity_number'=>$identityNumber,'account_type'=>2])->groupStart()->orWhere(['status'=>1])->orWhere(['status'=>2])->orWhere(['status'=>3])->orWhere(['status'=>4])->orWhere(['status'=>6])->groupEnd()->get();

		return $data->getRow();
	}

	public function defaultCurrency(){
		$data = $this->db->table('system_settings')->where(['id'=>1])->get();

		$data = $data->getRow();

		$currency = $this->db->table('pay_currencies')->where(['id'=>$data->default_currency_id])->get();

		return $currency->getRow();

		
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