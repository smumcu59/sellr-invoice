<?php 

namespace App\Models;

use CodeIgniter\Model;

class SecurityModel extends Model
{



	
	public function isLoginCheck(){
		$session = session();

		// Session geldi.

		// Session değerine göre değerlendirme yapılacak.

		

		if($session->access){
			// Access bilgisi var mı.

			// Varsa bir sonraki adım.


			if($session->id){
				// Admin id bilgisi var mı
				
				// Varsa bir sonraki adım

				if($session->username){
					
					// Username bilgisi var mı
				
					// Varsa bir sonraki adım

					if($session->accessToken){
						// Erişim kodu var mı
						
						// Varsa giriş yapmış sayılır. Sonrasında admin paneline yönlendirme yaptırılır.

						return "OK";
						
					}
					else{
						// Giriş yapmamış. Yarım
						$session->destroy();
						return "GIRIS";
					}
				}
				else{
					// Giriş yapmamış. Yarım
					$session->destroy();
					return "GIRIS";
				}
			}
			else{
				// Giriş yapmamış.
				$session->destroy();
				return "GIRIS";
			}
		}
		else{
			// Giriş yapmamış. Yarım

			return "GIRIS";
		}
	}

	public function maintenance(){

		$commonModel = new \App\Models\CommonModel();
		$firmModel = new \App\Models\firmModel();

		$session = session();

		$firmId = $session->firm_id;
		$userID = $session->id;

		$maintenanceData = $commonModel->getDataSelect("id,block_type,access_groups,maintenance_status",['firm_id'=>$firmId],'maintenance_settings');

		if($maintenanceData){
			if($maintenanceData->maintenance_status == 1){
				// OPERATIONAL

				$blockType = $maintenanceData->block_type;
				$accessGroups = $maintenanceData->access_groups;

				$userCheck = $firmModel->getDataOr2(['id'=>$userID],['status'=>1],['status'=>6],'users');
				$userType = 0;
				// 1 : General Center 2 : Office User
				$checkAssignment = $firmModel->getData(['office_id'=>"generalCenter",'user_id'=>$userID,'status'=>1],'office_assignments');

				if($userCheck){
					
					// Check assignment


					if($checkAssignment){

						// GENERAL CENTER USER

						$userType = 1;

					}
					else{

						$extraCheck = $firmModel->getData(['user_id'=>$userID,'status'=>1],'office_assignments');

						if($extraCheck){

							// OFFICE USER

							$userType = 2;
						}

					}


				}
				else{
					// USER NOT FOUND

					return "ERROR";
					exit;
				}

				if($blockType == 1){
					// ONLY OFFICE USERS

					if($userType == 2){
						
						return "OFFICE";

					}
					else{
						// GENERAL CENTER USER

						return "OK";

					}
					

				}
				else if($blockType == 2){

					// ONLY GENERAL CENTER USER


					if($userType == 1){
						// GENERAL CENTER USER

						$accessGroups = explode(",",$accessGroups);

						$permissionType = $userCheck->general_permission_id;

						$accessStatus = 0;

						foreach($accessGroups as $group){
							
							if($permissionType == $group){
								
								// ACCESS

								$accessStatus = 1;

							}
							
						}

						if($accessStatus == 1){
							return "OK";

						}
						else{
							// MAINTENANCE

							return "NOACCESS";

						}
						
					}
					else{
						// OFFICE USER

						return "OK";

					}



				}
				else{

					if($userType == 1){

						// GENERAL CENTER USER

						$accessGroups = explode(",",$accessGroups);

						$permissionType = $userCheck->general_permission_id;

						$accessStatus = 0;

						foreach($accessGroups as $group){
							
							if($permissionType == $group){
								
								// ACCESS

								$accessStatus = 1;

							}
							
						}

						if($accessStatus == 1){
							return "OK";

						}
						else{
							// MAINTENANCE

							return "NOACCESS";

						}
					}
					else{
						// OFFICE USER

						// ONLY OFFICE USERS

						if($userType == 2){
						
							return "OFFICE";

						}
						else{
							// GENERAL CENTER USER

							return "OK";

						}
					}

				}


			}
			else{
				// DONT MAINTENANCE

				return "OK";
			}
		}

	}

	public function userSecurity(){

		$firmModel = new \App\Models\FirmModel();

		$session = session();

		// session check control

		$systemSession = $session->systemSession;
		$userId = $session->id;
		
		$eMailAddress = $session->e_mail_address;
		$token = $session->token;
		$nameSurname = $session->name_surname;
		$firmId = $session->firm_id;
		$officeId = $session->office_id;



		

		$user = $firmModel->getData(['id'=>$userId,'e_mail_address'=>$eMailAddress,'access_token'=>$token,'status'=>1],'users');

		
		if($user){

			

			$dbToken = $user->access_token;


			if($session->systemSession){
				

				if($systemSession == sha1(md5('rednetTurkiye'))){
					

					if($session->id){
						
						
						if($userId != 0){
							
							

							if($session->e_mail_address){
								
								
								if($eMailAddress != "" OR $eMailAddress != null){
									

									if($session->token){
									
										

										if($token == null OR $token == ""){
											

											$session->destroy();
											return "FATAL";
											return redirect()->to(base_url('login'));
										
											exit;
										}
										else{
											

											if($officeId == null OR $officeId == ""){
												$session->destroy();
												return "FATAL";
												return redirect()->to(base_url('login'));
										
												exit;
											}
											else{
												
												$assignCheck = $firmModel->getData(['office_id'=>$officeId,'user_id'=>$user->id,'status'=>1],'office_assignments');

												if($assignCheck){
													// OKEY
													if($token == $dbToken){

														if($user->general_permission_id == null && $user->general_permission_title == null){
															return "OK";
														}
														else{
															$assignment = $firmModel->getData(['user_id'=>$user->id,'status'=>1],'office_assignments');
															if($assignment){
																if($assignment->office_id == "generalCenter"){
																	return "OK";
																}
																else{
																	//$session->destroy();
																	return "FATAL";
																	return redirect()->to(base_url('login'));
													
																	exit;
																}
															}
															else{
																
																return "FATAL";
																return redirect()->to(base_url('login'));
													
																exit;
															}
															
														}
														
		
		
													}
													else{
														
														
		
														$session->destroy();
														return "FATAL TOKEN";
														$session->set(['message'=>"Oturumunuz zaman aşımına uğradı! Lütfen tekrar giriş yapın."]);
														return redirect()->to(base_url('login'));
												
														exit;
													}


												}
												else{
													$session->destroy();
													return "FATAL";
													return redirect()->to(base_url('login'));
										
													exit;
												}

											}

											
										}
									}
									else{
										
										
										$session->destroy();
										return "FATAL";
										return redirect()->to(base_url('login'));
										
										exit;
									
									}
								

								}
								else{
									
									$session->destroy();
									return "FATAL";
									return redirect()->to(base_url('login'));
										
									exit;

								}
							}
							else{
								
								$session->destroy();
								return "FATAL";
								return redirect()->to(base_url('login'));
										
								exit;
							}
						}
						else{
							
							$session->destroy();
							return "FATAL";
							return redirect()->to(base_url('login'));
										
							exit;
						}
					}
					else{
						
						$session->destroy();
						return "FATAL";
						return redirect()->to(base_url('login'));
										
						exit;
					}
				}
				else{
					
					$session->destroy();
					return "FATAL";
					return redirect()->to(base_url('login'));
										
					exit;
				}
			}
			else{
				
				$session->destroy();
				return "FATAL8";
				return redirect()->to(base_url('login'));
										
				exit;
			}
		}

		

	}


	


}


?>