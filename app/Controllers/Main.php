<?php

namespace App\Controllers;
use App\Models\Auth;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Payslip;
use App\Models\Loan;
use App\Models\PayslipEarnings;
use App\Models\PayslipDeductions;
class Main extends BaseController
{   
    protected $request;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();
        $this->auth_model = new Auth;
        $this->dept_model = new Department;
        $this->desg_model = new Designation;
        $this->emp_model = new Employee;
        $this->payroll_model = new Payroll;
        $this->payslip_model = new Payslip;
        $this->payslip_earn_model = new PayslipEarnings;
        $this->payslip_ded_model = new PayslipDeductions;
        $this->loan_model = new Loan;
        $this->data = ['session' => $this->session,'request'=>$this->request];
    }

    public function index()
    {
        $this->data['page_title']="Home";
        $this->data['departments']=$this->dept_model->countAll();
        $this->data['designations']=$this->desg_model->countAll();
        $this->data['employees']=$this->emp_model->countAll();
        $this->data['payrolls']=$this->payroll_model->countAll();
        return view('pages/home', $this->data);
    }

    public function users(){
        $this->data['page_title']="Users";
        $this->data['page'] =  !empty($this->request->getVar('page')) ? $this->request->getVar('page') : 1;
        $this->data['perPage'] =  10;
        $this->data['total'] =  $this->auth_model->where("id != '{$this->session->login_id}'")->countAllResults();
        $this->data['users'] = $this->auth_model->where("id != '{$this->session->login_id}'")->paginate($this->data['perPage']);
        $this->data['total_res'] = is_array($this->data['users'])? count($this->data['users']) : 0;
        $this->data['pager'] = $this->auth_model->pager;
        return view('pages/users/list', $this->data);
    }
    public function user_add(){
        if($this->request->getMethod() == 'post'){
            extract($this->request->getPost());
            if($password !== $cpassword){
                $this->session->setFlashdata('error',"Password does not match.");
            }else{
                $udata= [];
                $udata['name'] = $name;
                $udata['email'] = $email;
                if(!empty($password))
                $udata['password'] = password_hash($password, PASSWORD_DEFAULT);
                $checkMail = $this->auth_model->where('email',$email)->countAllResults();
                if($checkMail > 0){
                    $this->session->setFlashdata('error',"User Email Already Taken.");
                }else{
                    $save = $this->auth_model->save($udata);
                    if($save){
                        $this->session->setFlashdata('main_success',"User Details has been updated successfully.");
                        return redirect()->to('Main/users');
                    }else{
                        $this->session->setFlashdata('error',"User Details has failed to update.");
                    }
                }
            }
        }

        $this->data['page_title']="Add User";
        return view('pages/users/add', $this->data);
    }
    public function user_edit($id=''){
        if(empty($id))
        return redirect()->to('Main/users');
        if($this->request->getMethod() == 'post'){
            extract($this->request->getPost());
            if($password !== $cpassword){
                $this->session->setFlashdata('error',"Password does not match.");
            }else{
                $udata= [];
                $udata['name'] = $name;
                $udata['email'] = $email;
                if(!empty($password))
                $udata['password'] = password_hash($password, PASSWORD_DEFAULT);
                $checkMail = $this->auth_model->where('email',$email)->where('id!=',$id)->countAllResults();
                if($checkMail > 0){
                    $this->session->setFlashdata('error',"User Email Already Taken.");
                }else{
                    $update = $this->auth_model->where('id',$id)->set($udata)->update();
                    if($update){
                        $this->session->setFlashdata('success',"User Details has been updated successfully.");
                        return redirect()->to('Main/user_edit/'.$id);
                    }else{
                        $this->session->setFlashdata('error',"User Details has failed to update.");
                    }
                }
            }
        }

        $this->data['page_title']="Edit User";
        $this->data['user'] = $this->auth_model->where("id ='{$id}'")->first();
        return view('pages/users/edit', $this->data);
    }
    
    public function user_delete($id=''){
        if(empty($id)){
                $this->session->setFlashdata('main_error',"user Deletion failed due to unknown ID.");
                return redirect()->to('Main/users');
        }
        $delete = $this->auth_model->where('id', $id)->delete();
        if($delete){
            $this->session->setFlashdata('main_success',"User has been deleted successfully.");
        }else{
            $this->session->setFlashdata('main_error',"user Deletion failed due to unknown ID.");
        }
        return redirect()->to('Main/users');
    }


    // Department
    public function departments(){
        $this->data['page_title']="Departments";
        $this->data['page'] =  !empty($this->request->getVar('page')) ? $this->request->getVar('page') : 1;
        $this->data['perPage'] =  10;
        $this->data['total'] =  $this->dept_model->countAllResults();
        $this->data['departments'] = $this->dept_model->paginate($this->data['perPage']);
        $this->data['total_res'] = is_array($this->data['departments'])? count($this->data['departments']) : 0;
        $this->data['pager'] = $this->dept_model->pager;
        return view('pages/departments/list', $this->data);
    }
    public function department_add(){
        if($this->request->getMethod() == 'post'){
            extract($this->request->getPost());
            $udata= [];
            $udata['code'] = $code;
            $udata['name'] = $name;
            $checkCode = $this->dept_model->where('code',$code)->countAllResults();
            if($checkCode){
                $this->session->setFlashdata('error',"Department Code Already Taken.");
            }else{
                $save = $this->dept_model->save($udata);
                if($save){
                    $this->session->setFlashdata('main_success',"Department Details has been updated successfully.");
                    return redirect()->to('Main/departments/');
                }else{
                    $this->session->setFlashdata('error',"Department Details has failed to update.");
                }
            }
        }

        $this->data['page_title']="Add New Department";
        return view('pages/departments/add', $this->data);
    }
    public function department_edit($id=''){
        if(empty($id))
        return redirect()->to('Main/departments');
        if($this->request->getMethod() == 'post'){
            extract($this->request->getPost());
            $udata= [];
            $udata['code'] = $code;
            $udata['name'] = $name;
            $checkCode = $this->dept_model->where('code',$code)->where("id!= '{$id}'")->countAllResults();
            if($checkCode){
                $this->session->setFlashdata('error',"Department Code Already Taken.");
            }else{
                $update = $this->dept_model->where('id',$id)->set($udata)->update();
                if($update){
                    $this->session->setFlashdata('success',"Department Details has been updated successfully.");
                    return redirect()->to('Main/department_edit/'.$id);
                }else{
                    $this->session->setFlashdata('error',"Department Details has failed to update.");
                }
            }
        }

        $this->data['page_title']="Edit Department";
        $this->data['department'] = $this->dept_model->where("id ='{$id}'")->first();
        return view('pages/departments/edit', $this->data);
    }
    
    public function department_delete($id=''){
        if(empty($id)){
                $this->session->setFlashdata('main_error',"Department Deletion failed due to unknown ID.");
                return redirect()->to('Main/departments');
        }
        $delete = $this->dept_model->where('id', $id)->delete();
        if($delete){
            $this->session->setFlashdata('main_success',"Department has been deleted successfully.");
        }else{
            $this->session->setFlashdata('main_error',"Department Deletion failed due to unknown ID.");
        }
        return redirect()->to('Main/departments');
    }

    // Designation
    public function designations(){
        $this->data['page_title']="Designations";
        $this->data['page'] =  !empty($this->request->getVar('page')) ? $this->request->getVar('page') : 1;
        $this->data['perPage'] =  10;
        $this->data['total'] =  $this->desg_model->countAllResults();
        $this->data['designations'] = $this->desg_model->paginate($this->data['perPage']);
        $this->data['total_res'] = is_array($this->data['designations'])? count($this->data['designations']) : 0;
        $this->data['pager'] = $this->desg_model->pager;
        return view('pages/designations/list', $this->data);
    }
    public function designation_add(){
        if($this->request->getMethod() == 'post'){
            extract($this->request->getPost());
            $udata= [];
            $udata['code'] = $code;
            $udata['name'] = $name;
            $checkCode = $this->desg_model->where('code',$code)->countAllResults();
            if($checkCode){
                $this->session->setFlashdata('error',"Designation Code Already Taken.");
            }else{
                $save = $this->desg_model->save($udata);
                if($save){
                    $this->session->setFlashdata('main_success',"Designation Details has been updated successfully.");
                    return redirect()->to('Main/designations/');
                }else{
                    $this->session->setFlashdata('error',"Designation Details has failed to update.");
                }
            }
        }

        $this->data['page_title']="Add New Designation";
        return view('pages/designations/add', $this->data);
    }
    public function designation_edit($id=''){
        if(empty($id))
        return redirect()->to('Main/designations');
        if($this->request->getMethod() == 'post'){
            extract($this->request->getPost());
            $udata= [];
            $udata['code'] = $code;
            $udata['name'] = $name;
            $checkCode = $this->desg_model->where('code',$code)->where("id!= '{$id}'")->countAllResults();
            if($checkCode){
                $this->session->setFlashdata('error',"Designation Code Already Taken.");
            }else{
                $update = $this->desg_model->where('id',$id)->set($udata)->update();
                if($update){
                    $this->session->setFlashdata('success',"Designation Details has been updated successfully.");
                    return redirect()->to('Main/designation_edit/'.$id);
                }else{
                    $this->session->setFlashdata('error',"Designation Details has failed to update.");
                }
            }
        }

        $this->data['page_title']="Edit Designation";
        $this->data['designation'] = $this->desg_model->where("id ='{$id}'")->first();
        return view('pages/designations/edit', $this->data);
    }
    
    public function designation_delete($id=''){
        if(empty($id)){
                $this->session->setFlashdata('main_error',"Designation Deletion failed due to unknown ID.");
                return redirect()->to('Main/designations');
        }
        $delete = $this->desg_model->where('id', $id)->delete();
        if($delete){
            $this->session->setFlashdata('main_success',"Designation has been deleted successfully.");
        }else{
            $this->session->setFlashdata('main_error',"Designation Deletion failed due to unknown ID.");
        }
        return redirect()->to('Main/designations');
    }

    
    // employee
    public function employees(){
        $this->data['page_title']="Employees";
        $this->data['page'] =  !empty($this->request->getVar('page')) ? $this->request->getVar('page') : 1;
        $this->data['perPage'] =  10;
        if(!empty($this->request->getVar('search'))){
            $search = $this->request->getVar('search');
            $this->emp_model->where(" employees.`code` like '%{$search}%' or employees.`first_name` like '%{$search}%' or employees.`middle_name` like '%{$search}%' or employees.`last_name` like '%{$search}%' or CONCAT(employees.last_name, ',', employees.first_name, COALESCE(CONCAT(' ', employees.middle_name), '')) like '%{$search}%'");
        }
        $this->data['total'] =  $this->emp_model->countAllResults();
        if(!empty($this->request->getVar('search'))){
            $search = $this->request->getVar('search');
            $this->emp_model->where(" employees.`code` like '%{$search}%' or employees.`first_name` like '%{$search}%' or employees.`middle_name` like '%{$search}%' or employees.`last_name` like '%{$search}%' or CONCAT(employees.last_name, ',', employees.first_name, COALESCE(CONCAT(' ', employees.middle_name), '')) like '%{$search}%'");
        }
        $this->data['employees'] = $this->emp_model
                                    ->select("employees.*, CONCAT(employees.last_name, ',', employees.first_name, COALESCE(CONCAT(' ', employees.middle_name), '')) as `name`, CONCAT(departments.code,' - ', departments.name) as department, CONCAT(designations.code,' - ', designations.name) as designation")
                                    ->join('departments'," employees.department_id = departments.id ",'inner')
                                    ->join('designations'," employees.designation_id = designations.id ",'inner')
                                    ->paginate($this->data['perPage']);
        $this->data['total_res'] = is_array($this->data['employees'])? count($this->data['employees']) : 0;
        $this->data['pager'] = $this->emp_model->pager;
        return view('pages/employees/list', $this->data);
    }
    

    public function employee_add(){
        $this->data['departments'] = $this->dept_model->findAll();
        $this->data['designations'] = $this->desg_model->findAll();

            $last_employee = $this->emp_model->orderBy('id', 'DESC')->first();
            $increemented_code = $last_employee ? intval($last_employee['code']) + 1 : 1;
            $this->data['increemented_code'] = $increemented_code;


        if($this->request->getMethod() == 'post'){
            extract($this->request->getPost());
            $udata= [];
            $udata['code'] = $code;
            $udata['first_name'] = $first_name;
            $udata['middle_name'] = $middle_name;
            $udata['last_name'] = $last_name;
            $udata['dob'] = $dob;
            $udata['gender'] = $gender;
            $udata['email'] = $email;
            $udata['date_hired'] = $date_hired;
            $udata['department_id'] = $department_id;
            $udata['designation_id'] = $designation_id;
            // $udata['salary'] = $salary;
            $udata['status'] = $status;
            $checkCode = $this->emp_model->where('code',$code)->countAllResults();
            if($checkCode){
                $this->session->setFlashdata('error',"Employee Code Already Taken.");
            }else{
                $save = $this->emp_model->save($udata);
                if($save){
                    $id = $this->emp_model->insertID();
                    $this->session->setFlashdata('main_success',"Employee Details has been updated successfully.");
                    return redirect()->to('Main/employee_view/'.$id);
                }else{
                    $this->session->setFlashdata('error',"Employee Details has failed to update.");
                }
            }
        }

        $this->data['page_title']="Add New Employee";
        return view('pages/employees/add', $this->data);
    }
    public function employee_edit($id=''){
        $this->data['departments'] = $this->dept_model->findAll();
        $this->data['designations'] = $this->desg_model->findAll();
        if(empty($id))
        return redirect()->to('Main/employees');
        if($this->request->getMethod() == 'post'){
            extract($this->request->getPost());
            $udata= [];
            $udata['code'] = $code;
            $udata['first_name'] = $first_name;
            $udata['middle_name'] = $middle_name;
            $udata['last_name'] = $last_name;
            $udata['dob'] = $dob;
            $udata['gender'] = $gender;
            $udata['email'] = $email;
            $udata['date_hired'] = $date_hired;
            $udata['department_id'] = $department_id;
            $udata['designation_id'] = $designation_id;
            $udata['salary'] = $salary;
            $udata['status'] = $status;
            $checkCode = $this->emp_model->where('code',$code)->where("id!= '{$id}'")->countAllResults();
            if($checkCode){
                $this->session->setFlashdata('error',"Employee Code Already Taken.");
            }else{
                $update = $this->emp_model->where('id',$id)->set($udata)->update();
                if($update){
                    $this->session->setFlashdata('main_success',"Employee Details has been updated successfully.");
                    return redirect()->to('Main/employee_view/'.$id);
                }else{
                    $this->session->setFlashdata('error',"Employee Details has failed to update.");
                }
            }
        }

        $this->data['page_title']="Edit Employee";
        $this->data['employee'] = $this->emp_model->where("id ='{$id}'")->first();
        return view('pages/employees/edit', $this->data);
    }
    
    public function employee_delete($id=''){
        if(empty($id)){
                $this->session->setFlashdata('main_error',"Employee Deletion failed due to unknown ID.");
                return redirect()->to('Main/employees');
        }
        $delete = $this->emp_model->where('id', $id)->delete();
        if($delete){
            $this->session->setFlashdata('main_success',"Employee has been deleted successfully.");
        }else{
            $this->session->setFlashdata('main_error',"Employee Deletion failed due to unknown ID.");
        }
        return redirect()->to('Main/employees');
    }

    public function employee_view($id=''){
        if(empty($id)){
            $this->session->setFlashdata('main_error',"Employee ID is unknown.");
            return redirect()->to('Main/employees');
        }
        $this->data['page_title']="Employee Details";
        $this->data['details'] = $this->emp_model
                                    ->select("employees.*, CONCAT(employees.last_name, ',', employees.first_name, COALESCE(CONCAT(' ', employees.middle_name), '')) as `name`, CONCAT(departments.code,' - ', departments.name) as department, CONCAT(designations.code,' - ', designations.name) as designation")
                                    ->join('departments'," employees.department_id = departments.id ",'inner')
                                    ->join('designations'," employees.designation_id = designations.id ",'inner')
                                    ->where("employees.id = $id")->first();
        return view('pages/employees/view', $this->data);
        
    }
   


    // Payroll
    public function payrolls(){
        $this->data['page_title']="Payrolls";
        $this->data['page'] =  !empty($this->request->getVar('page')) ? $this->request->getVar('page') : 1;
        $this->data['perPage'] =  10;
        $this->data['total'] =  $this->payroll_model->countAllResults();
        $this->data['payrolls'] = $this->payroll_model->paginate($this->data['perPage']);
        $this->data['total_res'] = is_array($this->data['payrolls'])? count($this->data['payrolls']) : 0;
        $this->data['pager'] = $this->payroll_model->pager;
        return view('pages/payrolls/list', $this->data);
    }
    public function payroll_add(){
        if($this->request->getMethod() == 'post'){
            extract($this->request->getPost());
            $udata= [];
            $udata['code'] = $code;
            $udata['title'] = $title;
            $udata['from_date'] = $from_date;
            $udata['to_date'] = $to_date;
            $checkCode = $this->payroll_model->where('code',$code)->countAllResults();
            if($checkCode){
                $this->session->setFlashdata('error',"Payroll Code Already Taken.");
            }else{
                $save = $this->payroll_model->save($udata);
                if($save){
                    $this->session->setFlashdata('main_success',"Payroll Details has been updated successfully.");
                    return redirect()->to('Main/payrolls/');
                }else{
                    $this->session->setFlashdata('error',"Payroll Details has failed to update.");
                }
            }
        }

        $this->data['page_title']="Add New Payroll";
        return view('pages/payrolls/add', $this->data);
    }
    public function payroll_edit($id=''){
        if(empty($id))
        return redirect()->to('Main/payrolls');
        if($this->request->getMethod() == 'post'){
            extract($this->request->getPost());
            $udata= [];
            $udata['code'] = $code;
            $udata['title'] = $title;
            $udata['from_date'] = $from_date;
            $udata['to_date'] = $to_date;
            $checkCode = $this->payroll_model->where('code',$code)->where("id!= '{$id}'")->countAllResults();
            if($checkCode){
                $this->session->setFlashdata('error',"Payroll Code Already Taken.");
            }else{
                $update = $this->payroll_model->where('id',$id)->set($udata)->update();
                if($update){
                    $this->session->setFlashdata('success',"Payroll Details has been updated successfully.");
                    return redirect()->to('Main/payroll_edit/'.$id);
                }else{
                    $this->session->setFlashdata('error',"Payroll Details has failed to update.");
                }
            }
        }

        $this->data['page_title']="Edit Payroll";
        $this->data['payroll'] = $this->payroll_model->where("id ='{$id}'")->first();
        return view('pages/payrolls/edit', $this->data);
    }
    
    public function payroll_delete($id=''){
        if(empty($id)){
                $this->session->setFlashdata('main_error',"Payroll Deletion failed due to unknown ID.");
                return redirect()->to('Main/payrolls');
        }
        $delete = $this->payroll_model->where('id', $id)->delete();
        if($delete){
            $this->session->setFlashdata('main_success',"Payroll has been deleted successfully.");
        }else{
            $this->session->setFlashdata('main_error',"Payroll Deletion failed due to unknown ID.");
        }
        return redirect()->to('Main/payrolls');
    }

    // payslip
    public function payslips(){
        $this->data['page_title']="Payslips";
        $this->data['page'] =  !empty($this->request->getVar('page')) ? $this->request->getVar('page') : 1;
        $this->data['perPage'] =  10;
        $this->data['total'] =  $this->payslip_model->countAllResults();
        $this->data['payslips'] = $this->payslip_model
                                        ->select("`payslips`.*, CONCAT(employees.last_name, ',', employees.first_name, COALESCE(CONCAT(' ', employees.middle_name), '')) as `employee_name`, employees.code as employee_code, payrolls.code as payroll_code, payrolls.title as payroll_name")
                                        ->join('payrolls'," payslips.payroll_id = payrolls.id ",'inner')
                                        ->join('employees'," payslips.employee_id = employees.id ",'inner')
                                        ->paginate($this->data['perPage']);
        $this->data['total_res'] = is_array($this->data['payslips'])? count($this->data['payslips']) : 0;
        $this->data['pager'] = $this->payslip_model->pager;
        return view('pages/payslips/list', $this->data);
    }
    public function payslip_add() {
        // var_dump($this->input->post());die();
        if ($this->request->getMethod() == 'post') {
            $postData = $this->request->getPost();

            //  var_dump($postData); die();

            $udata = [];
            foreach ($postData as $k => $v) {
                if (!is_array($v)) {
                    $udata[$k] = $v;
                }
            }

            //  var_dump($udata); die();
    
            $employee_id = isset($postData['employee_id']) ? $postData['employee_id'] : null;
            $payroll_id = isset($postData['payroll_id']) ? $postData['payroll_id'] : null;
            $salary = isset($postData['salary']) ? $postData['salary'] : 0;
            $late_undertime = isset($postData['late_undertime']) ? $postData['late_undertime'] : 0;
    
            $daily = $salary / 6; // Assuming 6 working days
            $hourly = $daily / 8;
            $min = $hourly / 60;
    
            $late_undertime_deduction = $late_undertime * $min;
            $udata['late_undertime_deduction'] = $late_undertime_deduction;
    
            $present = isset($postData['present']) ? $postData['present'] : 0;
            $present_amount = $present * $hourly;
    
            // overtime, legal holiday, and special holiday pay
            $ot_rate = isset($postData['dynamicOvertime']) ? $postData['dynamicOvertime'] : 0;
            $ot_hr = isset($postData['overtime']) ? $postData['overtime'] : 0;
    
            $leg_rate = isset($postData['dynamicLegal']) ? $postData['dynamicLegal'] : 0;
            $leg_hr = isset($postData['legal']) ? $postData['legal'] : 0;
    
            $sp_rate = isset($postData['dynamicSpecial']) ? $postData['dynamicSpecial'] : 0;
            $sp_hr = isset($postData['special']) ? $postData['special'] : 0;
    

            $udata['present_amount'] = $present_amount;
            $udata['ot_rate'] = $ot_rate;
            $udata['ot_hr'] = $ot_hr;
            $udata['leg_rate'] = $leg_rate;
            $udata['leg_hr'] = $leg_hr;
            $udata['sp_rate'] = $sp_rate;
            $udata['sp_hr'] = $sp_hr;
    

            $udata['total_present'] = $salary * $present;
            $udata['total_overtime'] = $ot_rate * $ot_hr;
            $udata['total_legal'] = $leg_rate * $leg_hr;
            $udata['total_special'] = $sp_rate * $sp_hr;



            $checkCode = $this->payslip_model->where('employee_id', $employee_id)->where('payroll_id', $payroll_id)->countAllResults();
            if ($checkCode) {
                $this->session->setFlashdata('main_error', "Employee has Already a Payslip Record on the selected Payroll.");
            } else {
                $save = $this->payslip_model->save($udata);
                if ($save) {
                    $payslip_id = $this->payslip_model->insertID();
                    if (isset($postData['deduction_name']) && isset($postData['deduction_amount'])) {
                        foreach ($postData['deduction_name'] as $k => $v) {
                            if (!empty($v) && !empty($postData['deduction_amount'][$k])) {
                                $this->payslip_ded_model->save([
                                    'payslip_id' => $payslip_id,
                                    'name' => $v,
                                    'amount' => $postData['deduction_amount'][$k]
                                ]);
                            }
                        }
                    }
    

                    $this->session->setFlashdata('main_success', "Employee's Payslip has been updated successfully.");
                    return redirect()->to('Main/payslips/');
                } else {
                    $this->session->setFlashdata('main_error', "Payslip Details has failed to update.");
                }
            }
        }
        
        $this->data['employees'] = $this->emp_model->select("*, CONCAT(employees.last_name, ',', employees.first_name, COALESCE(CONCAT(' ', employees.middle_name), '')) as `name`")->findAll();
        $this->data['payrolls'] = $this->payroll_model->findAll();
        $this->data['page_title'] = "Add New Payslip";
        return view('pages/payslips/add', $this->data);
    }
    
    
    
    public function payslip_delete($id=''){
        if(empty($id)){
                $this->session->setFlashdata('main_error',"Payslip Deletion failed due to unknown ID.");
                return redirect()->to('Main/payslips');
        }
        $delete = $this->payslip_model->where('id', $id)->delete();
        if($delete){
            $this->session->setFlashdata('main_success',"Payslip has been deleted successfully.");
        }else{
            $this->session->setFlashdata('main_error',"Payslip Deletion failed due to unknown ID.");
        }
        return redirect()->to('Main/payslips');
    }
    public function payslip_view($id=''){
        if(empty($id)){
            $this->session->setFlashdata('main_error', "Payslip ID is unknown.");
            return redirect()->to('Main/payslips');
        }
        $this->data['page_title'] = "Payslip Details";
        $details = $this->payslip_model
            ->select("payslips.*, 
                      CONCAT(employees.last_name, ',', employees.first_name, COALESCE(CONCAT(' ', employees.middle_name), '')) as `name`, 
                      employees.code, 
                      CONCAT(departments.code, ' - ', departments.name) as department, 
                      CONCAT(designations.code, ' - ', designations.name) as designation, 
                      payrolls.code as payroll_code, 
                      payrolls.title as payroll_name, 
                      payslips.present, 
                      payslips.late_undertime,
                      payslips.late_undertime_deduction,
                      payslips.ot_rate,
                      payslips.ot_hr,
                      payslips.leg_rate,
                      payslips.leg_hr,
                      payslips.sp_rate,
                      payslips.sp_hr,
                      payslips.salary,")
            ->join('payrolls', "payslips.payroll_id = payrolls.id", 'inner')
            ->join('employees', "payslips.employee_id = employees.id", 'inner')
            ->join('departments', "employees.department_id = departments.id", 'inner')
            ->join('designations', "employees.designation_id = designations.id", 'inner')
            ->join('loans', "employees.id = loans.employee_id", 'left')
            ->where("payslips.id = $id")
            ->first();
    
           

        // Calculating totals
        $salary = $details['salary'];
        $present = $details['present'];
        $ot_rate = $details['ot_rate'];
        $ot_hr = $details['ot_hr'];
        $leg_rate = $details['leg_rate'];
        $leg_hr = $details['leg_hr'];
        $sp_rate = $details['sp_rate'];
        $sp_hr = $details['sp_hr'];
    
        $daily = $salary / 6; // Assuming 6 working days
        $hourly = $daily / 8;

        $details['total_present'] = $hourly * $present;
        $details['total_overtime'] = $ot_rate * $ot_hr;
        $details['total_legal'] = $leg_rate * $leg_hr;
        $details['total_special'] = $sp_rate * $sp_hr;
    
        $this->data['details'] = $details;
        $this->data['earnings'] = $this->payslip_earn_model->where('payslip_id', $id)->findAll();
        $this->data['deductions'] = $this->payslip_ded_model->where('payslip_id', $id)->findAll();
        return view('pages/payslips/view', $this->data);
    }
    
  


    //loans 
    public function loans() {
        $this->data['page_title'] = "Loans";
        $this->data['page'] =  !empty($this->request->getVar('page')) ? $this->request->getVar('page') : 1;
        $this->data['perPage'] = 10;
        $this->data['total'] = $this->loan_model->countAllResults();
        
        $this->data['loans'] = $this->loan_model
                                    ->select('loans.*, CONCAT(employees.last_name, ", ", employees.first_name, COALESCE(CONCAT(" ", employees.middle_name), "")) as employee_name')
                                    ->join('employees', 'loans.employee_id = employees.id', 'inner')
                                    ->paginate($this->data['perPage']);


        $this->data['pager'] = $this->loan_model->pager;
        $this->data['employees'] = $this->emp_model->findAll();
        return view('pages/loans/list', $this->data);
    }
    
    
    public function addLoan() {
        if ($this->request->isAJAX()) {

            // var_dump($this->input->post()); die();

            $loanName = $this->request->getPost('loanName');
            $loanAmount = $this->request->getPost('loanAmount');
            $employeeId = $this->request->getPost('employee');
            $interest = $this->request->getPost('interest');
            $weeksPay = $this->request->getPost('weeks_pay');
    
            $totalInterest = $loanAmount * ($interest / 100) * $weeksPay;
            $totalRepay = $loanAmount + $totalInterest;
            $totalLoan = $totalRepay / $weeksPay;

            // echo $totalLoan; die();

            $data = [
                'loan_name' => $loanName,
                'loan_amount' => $loanAmount,
                'employee_id' => $employeeId,
                'interest' => $interest,
                'weeks_pay' => $weeksPay,
                'total_loan' => $totalLoan,
            ];
    
            if ($this->loan_model->insert($data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Loan added successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to add loan']);
            }
        } else {
            $this->data['page_title'] = "Loans";
            return view('pages/loans/list', $this->data);
        }
    }
    
 
    
    public function deleteLoan($id) {
        if ($this->request->isAJAX()) {
            if ($this->loan_model->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Loan deleted successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete loan']);
            }
        }
    }

    public function getEmployeeLoans($employee_id){
        
        $loanModel = new \App\Models\Loan();
        $loans = $loanModel->where('employee_id', $employee_id)->findAll();

        return $this->response->setJSON($loans);
    }


    //print
    public function fetchAllPayslips()
    {
        $data['payslips'] = $this->payslip_model->findAll();
        return view('pages/payslips/payslip_view_template', $data);
    }


    
    
   
}
