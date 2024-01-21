<?php 

namespace App\Models;

use CodeIgniter\Model;

class FirmModel extends Model
{

	protected $DBGroup = 'firm';

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

	public function getCheckDate($where, $table, $column, $dateRange)
	{
    	$data = $this->db->table($table)->where($where)->whereBetween($column, $dateRange)->get()->getRow();
    	return $data;
	}

	public function getListGetWhereNotNull($where,$nullColumn,$table){
		$data = $this->db->table($table)->where($where)->where($nullColumn." IS NOT NULL")->get();

		return $data->getResult();
	}

	public function getListCount($where,$table){
		
		$data = $this->db->table($table)->where($where)->get()->getResult();
		return count($data);
	}

	public function getDataSelect($select,$where,$table){

		$data = $this->db->table($table)->select($select)->where($where)->get();
		
		return $data->getRow();
	}

	// -------------------------------------------------------------------------

	

	
	public function sendMail($to,$subject,$message){

		$email = \Config\Services::email();

		$data = $this->db->table('smtp_configurations')->where(['status'=>1])->get();

		
		$emailData = $data->getResult();

		$this->common_model = new \App\Models\CommonModel();
		$session = session();

		$firmConfig = config('RednetFirm');
		$firmDetail = $this->common_model->getData(['firm_uniq_code'=>$firmConfig->firmUniqCode],'firms');

		$smtpHost = "";
		$smtpPort = "";
		$smtpUser = "";
		$smtpPass = "";

		foreach ($emailData as $emailDetail) {
			if($emailDetail->hourly_sending_limit > 0){

				$smtpHost = $emailDetail->hostname;
				$smtpPort = $emailDetail->port;
				$smtpUser = $emailDetail->username;
				$smtpPass = $emailDetail->password;
				$hourlySendingLimit = $emailDetail->hourly_sending_limit;
				$hourlySendingLimit--;

				$updateMail = $this->db->table('smtp_configurations')->where(['id'=>$emailDetail->id])->update(['hourly_sending_limit'=>$hourlySendingLimit]);

				break;
			}
			else{
				continue;
			}
		}

        $config['protocol'] = 'smtp';
        $config['SMTPHost'] = $smtpHost;
        $config['SMTPPort'] = $smtpPort;
        $config['SMTPUser'] = $smtpUser;
        $config['fromName'] = $firmDetail->name;
        $config['SMTPPass'] = $smtpPass;
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

	public function getDataOr4($where,$orWhere1,$orWhere2,$orWhere3,$orWhere4,$table)
	{
		
		$data = $this->db->table($table)->where($where)->groupStart()->orWhere($orWhere1)->orWhere($orWhere2)->orWhere($orWhere3)->orWhere($orWhere4)->groupEnd()->get();
	
		return $data->getRow();
	
	}

	public function getListOr2($where,$orWhere1,$orWhere2,$table){

		$data = $this->db->table($table)->where($where)->groupStart()->orWhere($orWhere1)->orWhere($orWhere2)->groupEnd()->get();
	
		return $data->getResult();
	}

	public function getListOr3($where,$orWhere1,$orWhere2,$orWhere3,$table){

		$data = $this->db->table($table)->where($where)->groupStart()->orWhere($orWhere1)->orWhere($orWhere2)->orWhere($orWhere3)->groupEnd()->get();
	
		return $data->getResult();
	}

	public function getListOr4($where,$orWhere1,$orWhere2,$orWhere3,$orWhere4,$table){

		$data = $this->db->table($table)->where($where)->groupStart()->orWhere($orWhere1)->orWhere($orWhere2)->orWhere($orWhere3)->orWhere($orWhere4)->groupEnd()->get();
	
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