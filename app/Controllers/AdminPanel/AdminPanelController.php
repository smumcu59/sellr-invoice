<?php

namespace App\Controllers\AdminPanel;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class AdminPanelController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['url','session','database'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */

     public $common_model; // GENEL VERİTABANI SELLR_GENERAL
     public $firm_model; // SELLR_FİRM
     public $security_model; // FİLTERS
     public $turkey_location_model; // TÜRKİYEDEKİ İLLER, İLÇELER, SEMTLER, MAHALLER
     public $world_location_model; // DÜNYADAKİ ***
     public $sess; // Oturum verileri
     public $firmDetail; // FİRMAYA AİT TÜM VERİLER
     public $userDetail; // KULLANICIYA AİT TÜM VERİLER
     public $generalSettings;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        $this->common_model = new \App\Models\CommonModel();
        //$this->turkey_location_model = new \App\Models\TurkeyDataModel();
        //$this->world_location_model = new \App\Models\WorldDataModel();
        $this->firm_model = new \App\Models\FirmModel();
        $this->security_model = new \App\Models\SecurityModel();
        $this->sess = \Config\Services::session();

        //$firmConfig = config('sellerInvoiceDB');

        //$this->firmDetail = $this->common_model->getData(['firm_uniq_code'=>$firmConfig->firmUniqCode],'firms');

        $this->generalSettings = $this->common_model->getData(['id'=>1],'general_settings');

        $this->userDetail = $this->common_model->getData(['e_mail_address'=>$this->sess->e_mail_address,'status'=>1,'hash'=>$this->sess->token],'users');

        if($this->userDetail){
            //$this->userPermission = $this->firm_model->getList(['group_id'=>$this->userDetail->general_permission_id],'role_permissions');
        }
    }
}
