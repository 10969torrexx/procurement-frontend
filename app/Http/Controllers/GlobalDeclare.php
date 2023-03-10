<?php

namespace App\Http\Controllers;
// use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;


class GlobalDeclare extends Controller
{
    //

    public function Fund_Source($id){
        $out = "";
        switch ($id){
            case 1:
                $out = "GoP";
                break;
            case 2:
                $out = "Foreign";
                break;  
            case 3:
                $out = "Special Purpose Fund";
                break;
            case 4:
                $out = "Corporate Budget";
                break;
            case 5:
                $out = "Income";
                break;
        }
        return $out;
    }

    public function Campus_location($id){
        $out = "";
        switch ($id){
            case 1:
                $out = "Sogod";
                break;
            case 2:
                $out = "Maasin";
                break;  
            case 3:
                $out = "Tomas Oppus";
                break;
            case 4:
                $out = "Bontoc";
                break;
            case 5:
                $out = "San Juan";
                break;
            case 6:
                $out = "Hinunangan";
                break;
        }
        return $out;
    }

    public function Campus($id){
        $out = "";
        switch ($id){
            case 1:
                $out = "Main";
                break;
            case 2:
                $out = "Maasin";
                break;  
            case 3:
                $out = "Tomas Oppus";
                break;
            case 4:
                $out = "Bontoc";
                break;
            case 5:
                $out = "San Juan";
                break;
            case 6:
                $out = "Hinunangan";
                break;
        }
        return $out;
    }

    public function Account_Type($id){
        $out = "";
        switch ($id){
            case 1:
                $out = "Administrator";
                break;
            case 2:
                $out = "Budget Officer";
                break;  
            case 3:
                $out = "Canvasser";
                break;
            case 4:
                $out = "Department";
                break;
            case 5:
                $out = "Supply Officer";
                break;
            case 6:
                $out = "Supply Custodian";
                break;
            case 7:
                $out = "Procurement Officer";
                break;
            case 8:
                $out = "Employee";
                break;
            case 9:
                $out = "Supplier";
                break;
            case 10:
                $out = "BAC Secretariat";
                break;
            case 11:
                $out = "Immediate Supervisor";
                break;
            case 12:
                $out = "President/HOPE";
                break;
            case 13:
                $out = "BOR Secretariat";
                break;
            case 14:
                $out = "BAC Committee";
                break;
    }
        return $out;
    }

    public function Department($id){
        $out = "";
        switch ($id){
            case 1:
                $out = "Department 1";
                break;
            case 2:
                $out = "Department 2";
                break;  
            case 3:
                $out = "Department 3";
                break;
            case 4:
                $out = "Department 4";
                break;
            case 5:
                $out = "Department 5";
                break;
            case 6:
                $out = "Department 6";
                break;
            case 7:                
                $out = "Department 7";
                break;
        }
        return $out;
    }

    

    public function Year($id){
        $out = "";
        switch ($id){
            case 1:
                $out = "2023";
                break;
            case 2:
                $out = "2024";
                break;  
            case 3:
                $out = "2025";
                break;
            case 4:
                $out = "2026";
                break;
            case 5:
                $out = "2027";
                break;
            case 6:
                $out = "2028";
                break;
            case 7:                
                $out = "2029";
                break;
            case 8:                
                $out = "2030";
                break;
            case 9:                
                $out = "2031";
                break;
            case 10:                
                $out = "2032";
                break;
            case 11:                
                $out = "2033";
                break;
            case 12:                
                $out = "2034";
                break;
            case 13:                
                $out = "2035";
                break;
            case 14:                
                $out = "2036";
                break;
            case 15:                
                $out = "2037";
                break;
            case 16:                
                $out = "2038";
                break;
            case 17:                
                $out = "2039";
                break;
            case 18:                
                $out = "2040";
                break;
        }
        return $out;
    }


    # this will determine the month
    public function Month($id) {
        $out = "";
        switch ($id){
            case 1:
                $out = "January";
                break;
            case 2:
                $out = "February";
                break;  
            case 3:
                $out = "March";
                break;
            case 4:
                $out = "April";
                break;
            case 5:
                $out = "May";
                break;
            case 6:
                $out = "June";
                break;
            case 7:
                $out = "July";
                break;
            case 8:
                $out = "August";
                break;
            case 9:
                $out = "September";
                break;
            case 10:
                $out = "October";
                break;
            case 11:
                $out = "November";
                break;
            case 12:
                $out = "December";
                break;
        }
        return $out;
    }    

    # this will determine the month
    public function MonthString($id) {
        $out = $id;
        switch ($id){
            case '1':
                $out = "January";
                break;
            case '2':
                $out = "February";
                break;  
            case '3':
                $out = "March";
                break;
            case '4':
                $out = "April";
                break;
            case '5':
                $out = "May";
                break;
            case '6':
                $out = "June";
                break;
            case '7':
                $out = "July";
                break;
            case '8':
                $out = "August";
                break;
            case '9':
                $out = "September";
                break;
            case '10':
                $out = "October";
                break;
            case '11':
                $out = "November";
                break;
            case '12':
                $out = "December";
                break;
        }
        return $out;
    }    

    # this will determine the fund source or source of fund
    public function FundSource($id) {
        $out = "";
        switch ($id){
            case 1:
                $out = "GoP";
                break;
            case 2:
                $out = "Foreign";
                break;  
            case 3:
                $out = "Corporate Budget";
                break;
            case 4:
                $out = "Income";
                break;
            case 5:
                $out = "RA";
                break;
        }
        return $out;
    }

    # this will determine the statuses of the ppmp, Project title
    public function Status($id) {
        $out = '';
        switch($id) {
            case 0:
                $out = "Draft";
                break;
            case 1:
                $out = "Pending for Immediate Supervisor's Approval";
                break;
            case 2:
                $out = "Accepted by Immediate Supervisor";
                break;  
            case 3:
                $out = "Disapproved by Immediate Supervisor";
                break;
            case 4:
                $out = "Accepted by Budget Officer";
                break;
            case 5:
                $out = "Dispproved by Budget Officer";
                break;
            case 6:
                $out = "Revised Project";
                break;
        }
        return $out;
    }
    # this will determine the project category
    
    # this will determine the project category | tagging of project type
    public function project_category($id) {
        $out = '';
        switch ($id) {
            case 0:
                $out = 'Indicative';
            break;
            case 1:
                $out = 'PPMP';
            break;
            case 2:
                $out = 'Supplemental';
            break;
        }
        return $out;
    }

    # this will determine the project category
    public function project_category_num($id) {
        $out = '';
        switch ($id) {
            case 'Indicative':
                $out = 0;
            break;
            case 'PPMP':
                $out = 1;
            break;
            case 'Supplemental':
                $out = 2;
            break;
        }
        return $out;
    }

    # this will determine the app is approved or disaaproved
    public function app_status($id,$code) {
        $out = '';
        if($code == "list"){
            switch ($id) {
                case 0:
                    $out = 'Pending';
                break;
                case 1:
                    $out = 'Approved by the BAC Committee';
                break;
                case 2:
                    $out = 'Disapproved by the BAC Committee';
                break;
                case 3:
                    $out = 'Approved by the President / Hope';
                break;
                case 4:
                    $out = 'Disapproved by the BAC Committee';
                break;
            }
            return $out;
        }else{
            switch ($id) {
                case 0:
                    $out = 'Pending';
                break;
                case 1:
                    $out = 'Approved ';
                break;
            }
            return $out;
        }
    }

    # this will determine the color of status
    public function app_status_color($id,$code) {
        $out = '';
        if($code == "list"){
            switch ($id) {
                case 0:
                    $out = 'primary';
                break;
                case 1:
                    $out = 'success';
                break;
                case 2:
                    $out = 'danger';
                break;
                case 3:
                    $out = 'success';
                break;
                case 4:
                    $out = 'danger';
                break;
            }
            return $out;
        }else{
            switch ($id) {
                case 0:
                    $out = 'primary';
                break;
                case 1:
                    $out = 'success';
                break;
            }
            return $out;
        }
        
    }

    # this will determine the app is approved or disaaproved
    public function endorse($id) {
        $out = '';
        switch ($id) {
            case 0:
                $out = 'Pending';
            break;
            case 1:
                $out = 'Endorsed';
            break;
            // case 3:
            //     $out = 'Disapproved';
            // break;
        }
        return $out;
    }

    # this will determine the color of status
    public function endorse_color($id,$code) {
        $out = '';
        if($code == "html"){
            switch ($id) {
                case 0:
                    $out = 'blue';
                break;
                case 1:
                    $out = 'coral';
                break;
                // case 3:
                //     $out = 'red';
                // break;
            }
        }else{
            switch ($id) {
                case 0:
                    $out = 'primary';
                break;
                case 1:
                    $out = 'warning';
                break;
                // case 3:
                //     $out = 'red';
                // break;
            }
        }
        
        return $out;
    }

    
    # this will determine the app is recommended or disaaproved
    public function bac_committee_status($id) {
        $out = '';
        switch ($id) {
            case 0:
                $out = 'Pending';
            break;
            case 1:
                $out = 'Recommended';
            break;
            case 2:
                $out = 'Disapproved';
            break;
        }
        return $out;
    }

    # this will determine the color of status
    public function bac_committee_status_color($id) {
        $out = '';
        switch ($id) {
            case 0:
                $out = 'primary';
            break;
            case 1:
                $out = 'success';
            break;
            case 2:
                $out = 'danger';
            break;
        }
        
        return $out;
    }


    /**
     * * TORREXX additionals
     * TODO 1: Get the department
     */
    public function get_department($in) {
        try {
            $department = \DB::table('departments')
                ->where('id', $in)
                ->whereNull('deleted_at')
                ->get([
                    'department_name',
                    'description'
                ]);
            return $department;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    # this will determine the statuses of the ppmp, Project title
    public function ppmp_status($id) {
        $out = '';
        switch($id) {
            case 0:
                $out = "Draft";
                break;
            case 1:
                $out = "Pending";
                break;
            case 2:
                $out = "Accepted by Supervisor";
                break;  
            case 3:
                $out = "To be Revise";
                break;
            case 4:
                $out = "Accepted by Budget Officer";
                break;
            case 5:
                $out = "Disapproved by Budget Officer";
                break;
            case 6:
                $out = "Revised";
                break;
        }
        return $out;
    }
    
    # this will determine the color of the statuses of the ppmp, Project title
    public function ppmp_status_color($id) {
        $out = '';
        switch($id) {
            case 0:
                $out = "Draft";
                break;
            case 1:
                $out = "primary";
                break;
            case 2:
                $out = "success";
                break;  
            case 3:
                $out = "danger";
                break;
            case 4:
                $out = "info";
                break;
            case 5:
                $out = "danger";
                break;
            case 6:
                $out = "warning";
                break;
        }
        return $out;
    }
}
