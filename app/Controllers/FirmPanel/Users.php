<?php

namespace App\Controllers\FirmPanel;

class Users extends FirmPanelController
{
    public function index()
    {
        return view('firm-panel/login-page');
    }

    public function loginUser(){

        if($this->request->getMethod() == "post"){

            // İşlemler yapılsın

            if($this->request->getPost('param1')){

                if($this->request->getPost('param2')){

                    $eMailAddress = $this->request->getPost('param1');
                    $password = $this->request->getPost('param2');

                    $userCheck = $this->common_model->getData(['e_mail_address'=>$eMailAddress,'password'=>sha1(md5(sha1($password)))],'users');
                    
                    if($userCheck){
                        // Kullanıcı varsa

                        // Atama kontrolü işlemleri
                        
                        $assignmentCheck = $this->common_model->getData(['user_id'=>$userCheck->id,'status'=>1],'firm_assignments');
                        
                        if($assignmentCheck){

                            // KULLANICI GİRİŞ YAPTI

                            $hash = sha1(md5(sha1(rand(245784,974421))));

                            $updateUser = $this->common_model->updateData(['id'=>$userCheck->id],'users',['hash'=>$hash]);

                            if($updateUser){

                                // Kullanıcının hash'i güncellendiyse
                                $newdata = [

                                    'e_mail_address'  => $userCheck->e_mail_address,
                                    'name_surname' => $userCheck->name_surname,
                                    'id'=> $userCheck->id,
                                    'firm_id'=>$assignmentCheck->firm_id,
                                    'token'=>$hash,
                
                                ];

                                $this->sess->set($newdata);

                                $response = [
                                    'status' => 'OK',
                                    'message' => 'Başarılı giriş yaptınız. Yönlendiriliyorsunuz...',
                                    'result' => "",
                                ];
                                return $this->response->setJSON($response);



                            }
                            else{

                                $response = [
                                    'status' => 'ERROR',
                                    'message' => 'İşleminiz yapılamadı.',
                                    'result' => "Bilgileriniz işlenirken bir hata ile karşılaşıldı. Lütfen daha sonra tekrar deneyiniz.",
                                ];
                                return $this->response->setJSON($response);


                            }
                            

                        }
                        else{

                            $response = [
                                'status' => 'ERROR',
                                'message' => 'İşleminiz yapılamadı.',
                                'result' => "Size ait herhangi bir firma ataması görünmemektedir. Yeni bir firma oluşturunuz ya da lütfen firma yöneticiniz ile iletişime geçiniz.",
                            ];
                            return $this->response->setJSON($response);

                        }

                    }

                    else{

                        $response = [
                            'status' => 'ERROR',
                            'message' => 'İşleminiz yapılamadı.',
                            'result' => "Girmiş olduğunuz bilgiler, veritabanımızdaki bilgiler ile eşleşmemektedir. Lütfen bilgilerinizin doğruluğunu kontrol ediniz.",
                        ];
                        return $this->response->setJSON($response);


                    }

                }
                else{

                    $response = [
                        'status' => 'ERROR',
                        'message' => 'İşleminiz yapılamadı.',
                        'result' => "Lütfen tüm kutucukları doldurduğunuzdan emin olun.",
                    ];
                    return $this->response->setJSON($response);


                }

            }

            else{

                $response = [
                    'status' => 'ERROR',
                    'message' => 'İşleminiz yapılamadı.',
                    'result' => "Lütfen tüm kutucukları doldurduğunuzdan emin olun.",
                ];
                return $this->response->setJSON($response);

            }

        }

    }

}
