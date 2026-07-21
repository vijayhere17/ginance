<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Session;
use App\Jobs\PostActivationWork;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\ParentList;
use App\Models\BinaryPoints;

use App\Models\SalaryMaster;
use App\Models\SalaryAchiever;
use App\Models\RewardAchiever;

use Log;
use DB;
use Carbon\Carbon;

class SalaryController extends Controller
{
    public function indexachievers()
    {
        $page_titel = 'Income Potential Bonus';    
        
        $allsalary = SalaryMaster::get();
    
        $user_id = Auth::user()->id;
        
        $leg_data = []; // Initialize as an associative array
        
        $leg1 = User::where('referral_id', '=', $user_id)->orderByRaw('all_investment desc')->first(); // Power leg (60%)
        $leg2 = User::where('referral_id', '=', $user_id)->orderByRaw('all_investment desc')->skip(1)->first(); // Weaker leg (20%)
        
        $leg_data['leg_1_username'] = $leg1 ? obscureAddress($leg1->username) : '';
        $leg_data['leg_2_username'] = $leg2 ? obscureAddress($leg2->username) : '';
        $leg_data['leg_3_username'] = 'Other';
        
        $leg_1_business = ($leg1 == null ? 0 : $leg1->all_investment);
        $leg_2_business = ($leg2 == null ? 0 : $leg2->all_investment);
        $leg_3_business = (Auth::user()->all_investment - $leg_1_business - $leg_2_business);
        
        if($leg_3_business > $leg_1_business)
        {
            $leg_data['leg_1_username'] = 'Other'; 
            $leg_data['leg_3_username'] = $leg1 ? obscureAddress($leg1->username) : '';
            
            $leg_1_business = $leg_3_business;
            $leg_3_business = ($leg1 == null ? 0 : $leg1->all_investment);
        }
        
        $leg_data['leg_1_business'] = $leg_1_business;
        $leg_data['leg_2_business'] = $leg_2_business; 
        $leg_data['leg_3_business'] = $leg_3_business;
        
        return view('users.salary-master')->with([ 'page_titel' => $page_titel,  'allsalary' => $allsalary,  'user_id' => $user_id,  'leg_data' => $leg_data ])->toJS();
    }

    
    public function getstatus($member_id, $salary_id)
    {
        $achievement = SalaryAchiever::where('member_id','=',$member_id)->where('salary_id','=',$salary_id)->first();
        
        return $achievement;
    } 
    
    // ==========================================================================================================================================================================================
    
    public function runSalaryAchiever()
    {
        $current_date = date("Y-m-d");
        
        $dashboardCon = app('App\Http\Controllers\Users\DashboardController');
        
        $allrank = SalaryMaster::get();
        
        foreach($allrank as $rank)
        {
            $members = User::where('kit_id', '>', 0)->where('salary_id', '=', $rank->id-1)->get();
            
            foreach($members as $member)
            {
                $leg1 = User::where('referral_id','=',$member->id)->orderByRaw('all_investment desc')->first(); // power leg (60%)
        		$leg2 = User::where('referral_id','=',$member->id)->orderByRaw('all_investment desc')->skip(1)->first(); // weeker leg (20%)
        		// $leg3 = User::where('referral_id','=',$member->id)->orderByRaw('all_investment desc')->skip(2)->first(); // weeker leg (20%)
        		
        		if($leg1 != null && $leg2 != null)
        		{
        		    $temp_business = 0;
        		    
        		    $leg1_business = $leg1->all_investment;
        		    $leg2_business = $leg2->all_investment;
        		    $leg3_business = ($member->all_investment - $leg1_business - $leg2_business);
        		    
        		    if($leg3_business > $leg1_business)
                    {
        		        $temp_business = $leg1_business;
        		        $leg1_business = $leg3_business; // power leg
        		        $leg3_business = $temp_business;
        		    }
        		    
        		    // $total_business = $dashboardCon->getTeamBusiness($member->id, 0);
                    
                    $power_business = $rank->business * 0.60;
                    $weeker_business_1 = $rank->business * 0.20;
                    $weeker_business_2 = $rank->business * 0.20;
                    
                    if($leg1_business >= $power_business && $leg2_business >= $weeker_business_1 && $leg3_business >= $weeker_business_2)
                    {
                        $check = $this->getstatus($member->id, $rank->id);
                        
                        if($check == null)
                        {
                            // stop old salary earning
                            SalaryAchiever::where('member_id', '=', $member->id)->update([ 'status'=> 1 ]);
                             
                            $objachiever = new SalaryAchiever;
                            $objachiever->member_id = $member->id;
                            $objachiever->salary_id = $rank->id;
                            $objachiever->bonus = $rank->bonus;
                            $objachiever->weeks = $rank->weeks;
                            $objachiever->return_date = date('Y-m-d', strtotime($current_date. ' + 7 days'));
                            $objachiever->status = 0;
                            $objachiever->save();
                            
                            // update member 
                            $member->salary_id = $rank->id;
                            $member->save();
                            
                            if($rank->instead_reward > 0)
                            {
                                $walletCon = app('App\Http\Controllers\Users\EarningWalletController');
                                $description = 'Potential Instead Reward #'.$rank->rank;
                                $walletCon->addpotentialwalletlog($member->id, 1, 1, $description, $rank->instead_reward, 0, 0, date("Y-m-d H:i:s")); 
                            }
                        }
                    }
        		}
            }
        }
    }
    
    public function runSalaryEarning()
    {
        $walletCon = app('App\Http\Controllers\Users\EarningWalletController');

        // $objects = SalaryAchiever::where('status', '=', 0)->where('return_date', '<=', date("Y-m-d"))->where('weeks', '>', 0)->get();
        
        $objects = SalaryAchiever::where('status', '=', 0)->where('weeks', '>', 0)->get();

        foreach($objects as $log)
        {
            $rank = SalaryMaster::where('id','=',$log->salary_id)->first();

            $description = 'Potential Bonus #'.$rank->rank;
        
            $commission = $log->bonus;

            $earning_type = 5;

            $dashboardCon = app('App\Http\Controllers\Users\DashboardController');
            $remain_commission = $dashboardCon->check3xEarningLimit($log->member_id, $commission);

            if($remain_commission > 0)
            {
                $walletCon->addearningwalletlog($log->member_id, 1, $earning_type, $description, $remain_commission, 0, 0, date("Y-m-d H:i:s")); 

                $log->weeks -= 1;
                $log->return_date = date('Y-m-d', strtotime(date("Y-m-d"). ' + 7 days'));
                $log->save();
            }
            else
            {
                $walletCon->addearningwalletlog($log->member_id, 3, $earning_type, $description, $commission, 0, 0, date("Y-m-d H:i:s")); 
            }
        }
    }

    /**
     * Weekly Reward Salary - reuses the same wallet / cap pattern as runSalaryEarning.
     * Pays reward_achiever.weekly_salary for the member's highest achieved reward only.
     * Scheduler: pay when today >= return_date, then set return_date = today + 7 days.
     */
    public function runRewardSalaryEarning()
    {
        $walletCon = app('App\Http\Controllers\Users\EarningWalletController');
        $dashboardCon = app('App\Http\Controllers\Users\DashboardController');

        $today = date('Y-m-d');
        $member_ids = RewardAchiever::where('weekly_salary', '>', 0)
            ->distinct()
            ->pluck('member_id');

        foreach ($member_ids as $member_id) {
            // Highest achieved reward only (reward_id desc matches reward level order).
            $log = RewardAchiever::where('member_id', '=', $member_id)
                ->where('weekly_salary', '>', 0)
                ->orderBy('reward_id', 'desc')
                ->first();

            if ($log == null) {
                continue;
            }

            // Pay only when due: today >= return_date (null treated as due).
            if ($log->return_date != null && $log->return_date > $today) {
                continue;
            }

            $commission = $log->weekly_salary;
            $description = 'Reward Weekly Salary #'.$log->reward_id;
            // earning_type 5 = Salary (legacy Potential Bonus / Salary Bonus). No dedicated Reward Salary type exists.
            $earning_type = (int) config('income.reward_weekly_salary_earning_type', 5);

            $remain_commission = $dashboardCon->check3xEarningLimit($log->member_id, $commission);

            if ($remain_commission > 0) {
                $walletCon->addearningwalletlog($log->member_id, 1, $earning_type, $description, $remain_commission, 0, 0, date('Y-m-d H:i:s'));

                $log->return_date = date('Y-m-d', strtotime($today.' + 7 days'));
                $log->save();
            } else {
                $walletCon->addearningwalletlog($log->member_id, 3, $earning_type, $description, $commission, 0, 0, date('Y-m-d H:i:s'));
            }
        }
    }
}
