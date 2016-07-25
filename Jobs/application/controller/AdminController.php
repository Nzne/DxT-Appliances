<?php

/**
 * This controller shows an area that's only visible for logged in users (because of Auth::checkAuthentication(); in line 16)
 */
class AdminController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // this entire controller should only be visible/usable by logged in users, so we put authentication-check here
        Auth::checkAuthentication();
    }

    /**
     * This method controls what happens when you move to /dashboard/index in your app.
     */

    /**
      * This is the index function which brings you to the admin panel.
      */
    public function index()
    {
        $this->View->render('admin/index');
    }

    /**
      * This is the user functions which allows you to add, update and remove user accounts.
      */
    public function updateuser()
    {
    	$this->View->render('admin/user/updateuser', array("users" => DashboardModel::getAllUsers()));
    }
    public function adduser()
    {
        $this->View->render('admin/user/adduser');
    }
    public function deleteuser()
    {
        $this->View->render('admin/user/deleteuser', array("users" => DashboardModel::getAllUsers()));
    }

    /**
      * This is the jobs functions which allows you to add, remove, assign, update jobs. Also Sends invoices.
      */
    public function sendinvoice()
    {
        $this->View->render('admin/jobs/sendinvoice', array("jobs" => AdminModel::getAllJobs()));
    }
    public function updatejob()
    {
        $this->View->render('admin/jobs/updatejob', array("jobs" => AdminModel::getAllJobs()));
    }
    public function deletejob()
    {
        $this->View->render('admin/jobs/deletejob', array("jobs" => AdminModel::getAllJobs()));
    }
    public function assignjob()
    {
        $this->View->render('admin/jobs/assignjob', array("jobs" => AdminModel::getAllJobs(), "engineers" => AdminModel::getAllEngineers()));
    }
    public function addjob()
    {
        $this->View->render('admin/jobs/addjob', array('users' => AdminModel::getAccounts(), 'user' => AdminModel::getAllUsers()));
    }
    public function viewdeletedjobs()
    {
        $this->View->render('admin/jobs/viewdeletedjobs', array("jobs" => AdminModel::getAllJobs()));
    }

    /**
      * This is the stock functions which allows you to add, move, remove and view stock. Also allows you add now stock locations.
      */
    public function moveitem()
    {
        $this->View->render('admin/stock/moveitem', array("stock" => AdminModel::GetAllStock(), "engineers" => AdminModel::getAllEngineers()));
    }
    public function removestock()
    {
        $this->View->render('admin/stock/removestock', array("invent" => AdminModel::getAllStock()));
    }
    public function stocksearch()
    {
        $this->View->render('admin/stock/searchstock', array("invent" => AdminModel::getAllStock()));
    }
    public function stockreorder()
    {
        $this->View->render('admin/stock/stockreorder', array("invent" => AdminModel::getAllStock()));
    }
    public function addstocklocation()
    {
        $this->View->render('admin/stock/addstocklocation', array("engineers" => AdminModel::getAllEngineers()));
    }
    public function addstock()
    {
        $this->View->render('admin/stock/addstock', array("invent" => AdminModel::getAllStock()));
    }
    public function viewstock()
    {
        $this->View->render('admin/stock/viewallstock', array("loc" => AdminModel::getAllVehicles()));
    }

    /**
     * Register page action
     * POST-request after form submit
     */
    public function register_action()
    {
        $registration_successful = RegistrationModel::registerNewUser();

        if ($registration_successful) {
            Redirect::to('admin/index');
        } else {
            Redirect::to('admin/user/adduser');
        }
    }

    public function movestock()
    {
	   AdminModel::stockMove(Request::post('item_code'), Request::post('item_quant_move'), Request::post('item_move_name'));
	   Redirect::to('admin');
    }

    public function stocklocation()
    {
        AdminModel::createStockLocation(Request::post('v_type'), Request::post('v_make'), Request::post('v_model'), Request::post('v_assigned'), Request::post('v_reg'), Request::post('v_mot'), Request::post('v_tax'));
        Redirect::to('admin');
    }

    public function userupdate()
    {
	   AdminModel::updateUser(Request::post('user_id'), Request::post('user_account_type'));
	   Redirect::to('admin');
    }

    public function userdelete()
    {
       AdminModel::deleteUser(Request::post('user_id'));
       Redirect::to('admin');
    }

    public function addtostock()
    {
	   AdminModel::addItemToStock(Request::post('item_code'), Request::post('item_name'), Request::post('item_description'), Request::post('item_make'), Request::post('item_cost'), Request::post('item_resell'), Request::post('item_location'), Request::post('item_quant'));
	   Redirect::to('admin/stock/additem');
    }

    public function search()
    {
	   AdminModel::search(Request::post('search_terms'));
    }

    public function jobassign()
    {
        AdminModel::assignJobs(Request::post('job_id'), Request::post('job_for'));
        Redirect::to('admin');
    }

    public function removefromstock()
    {
        AdminModel::removeFromStock(Request::post('item_code'));
        Redirect::to('admin');
    }

     public function create()
    {
        AdminModel::createJob(Request::post('job_name'), Request::post('job_address_number'), Request::post('custom_field'), Request::post('address3'), Request::post('postcode'), Request::post('job_tel'), Request::post('job_fi'), Request::post('job_mt'), Request::post('job_fault'), Request::post('job_date'), Request::post('job_time'), Request::post('job_keys'), Request::post('job_account'), Request::post('job_account_name'));
        Redirect::to('admin');
    }

    public function declinejob()
    {
        AdminModel::declineJob(Request::post('job_id'));
        Redirect::to('dashboard');
    }
}
