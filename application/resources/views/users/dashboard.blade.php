@php use Illuminate\Support\Facades\Auth; @endphp
@extends('users.master')
@section('extra')
<style>
    h3, .h3 {
        font-size: 1rem;
    }

    .example-box {
        width: 100%;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
        background-size: cover;
        color: white;
        font-family: sans-serif;
        font-weight: 200;
        z-index: 1;
    }

    .example-box * {
        z-index: 2;
    }

    .background-shapes {
        content: "";
        position: absolute;
        z-index: 2;
        left: 0;
        top: 0;
        width: 100%;
        height: 5076px;
        background-size: 100%;
        animation: 120s infiniteScroll linear infinite;
        background-repeat-x: repeat;
        background-image: url({{ URL::to('/') }}/assets/images/circles.svg);
    }

    @-webkit-keyframes infiniteScroll {
        0% { -webkit-transform: translate3d(0, 0, 0); transform: translate3d(0, 0, 0); }
        100% { -webkit-transform: translate3d(0, -1692px, 0); transform: translate3d(0, -1692px, 0); }
    }
    @keyframes infiniteScroll {
        0% { -webkit-transform: translate3d(0, 0, 0); transform: translate3d(0, 0, 0); }
        100% { -webkit-transform: translate3d(0, -1692px, 0); transform: translate3d(0, -1692px, 0); }
    }

    img.vert-move {
        -webkit-animation: mover 1s infinite alternate;
        animation: mover 1s infinite alternate;
    }
    @-webkit-keyframes mover {
        0% { transform: translateY(0); }
        100% { transform: translateY(-10px); }
    }
    @keyframes mover {
        0% { transform: translateY(0); }
        100% { transform: translateY(-10px); }
    }

    .modal-open .modal-backdrop {
        backdrop-filter: blur(7px);
        background-color: rgba(0, 0, 0, 0.6);
        opacity: 1 !important;
    }

    .custom-alert {
        background: linear-gradient(120deg, #9c7a22, #d4af37 55%, #f4d78a);
        border-left: 6px solid #9c7a22;
        border-radius: 12px;
        padding: 20px 30px;
        color: #0b0b0d;
        font-weight: 500;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(212, 175, 55, 0.15);
        font-size: 14px;
        max-width: 100%;
        width: 100%;
        position: relative;
    }
    .custom-alert a { color: #0b0b0d !important; text-decoration: underline; }
    .custom-alert strong { font-weight: 700; }

    .dash-coin-hero {
        width: 100%;
        max-width: 260px;
        aspect-ratio: 1;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at 35% 30%, #f4d78a, #d4af37 55%, #9c7a22 100%);
        box-shadow: 0 0 60px rgba(212, 175, 55, 0.35), inset 0 0 30px rgba(0, 0, 0, 0.25);
    }
    .dash-coin-hero svg { width: 45%; height: 45%; color: #0b0b0d; }

    .progress-thin { height: 8px; border-radius: 6px; background: rgba(255,255,255,0.06); }

    .rank-tier-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        background: rgba(212, 175, 55, 0.14);
        color: var(--gt-gold-2, #d4af37);
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endsection
@section('content')
<div class="pc-container">
    <div class="pc-content">
        <!-- [ Refer link banner ] -->
        <div class="row">
            <div class="col-md-12 col-xxl-12">
                <div class="custom-alert">
                    <strong>Refer Link :.</strong> Use your referral link to spread the good vibes and earn some perks too! Let's build something amazing together!&nbsp;&nbsp;<a href="javascript:toClip(`{{ URL::to('/') }}/sign-up?ref={{ Auth::user()->username }}`)">Copy Link...</a>
                </div>
                <br>
            </div>
        </div>

        <!-- [ Profile / Package / Quick Actions ] -->
        <div class="row">
            <div class="col-md-6 col-xxl-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{ URL::to('/') }}/assets/images/user/avatar-1.jpg" alt="user" class="user-avtar wid-50 rounded-circle" />
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h5>
                                <p class="text-muted mb-0">{{ obscureAddress(Auth::user()->username) }}</p>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Email</span>
                                <span>{{ Auth::user()->email ?? '-' }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Current Rank</span>
                                @if($object->current_rank)
                                    <span class="rank-tier-pill"><i class="ti ti-award"></i> {{ $object->current_rank->rank }}</span>
                                @else
                                    <span class="rank-tier-pill">Not Ranked Yet</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Package Details</h5>
                        </div>
                        @if(Auth::user()->kit)
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Package</span>
                                    <span>{{ Auth::user()->kit->name }}</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Invested Amount</span>
                                    <span>{{ Auth::user()->kit->amount }}</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Daily ROI</span>
                                    <span>{{ Auth::user()->kit->percantage }}%</span>
                                </li>
                            </ul>
                        @else
                            <p class="text-muted mb-0">No active package yet.</p>
                            <div class="d-grid mt-3">
                                <a href="{{ URL::to('/') }}/buy-robo" class="btn btn-primary btn-sm">Stake Now</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xxl-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ URL::to('/') }}/buy-robo" class="btn btn-primary w-100"><svg class="pc-icon me-1" style="width:16px;height:16px;"><use xlink:href="#custom-cpu-charge"></use></svg> Stake Now</a>
                            </div>
                            <div class="col-6">
                                <a href="{{ URL::to('/') }}/new-withdrawal" class="btn btn-light-primary w-100"><i class="ti ti-wallet me-1"></i> Withdraw</a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:toClip(`{{ URL::to('/') }}/sign-up?ref={{ Auth::user()->username }}`)" class="btn btn-light-primary w-100"><i class="ti ti-link me-1"></i> Copy Referral Link</a>
                            </div>
                            <div class="col-6">
                                <a href="{{ URL::to('/') }}/create-ticket" class="btn btn-light-primary w-100"><i class="ti ti-headset me-1"></i> Support</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- [ Wallet / Income / Team stats ] -->
        <div class="row">
            <div class="col-md-6 col-xxl-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar avtar-s bg-light-primary">
                                <svg class="pc-icon"><use xlink:href="#custom-wallet-2"></use></svg>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Wallet Balance</h6>
                            </div>
                        </div>
                        <div class="bg-body p-3 mt-3 rounded">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h4 class="mb-0">{{ $object->total_balance }}</h4>
                                    <p class="text-primary mb-0">Earning Wallet</p>
                                </div>
                                <div class="col-6 text-end">
                                    <h4 class="mb-0">{{ $object->total_pw_balance }}</h4>
                                    <p class="text-primary mb-0">Potential Wallet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar avtar-s bg-light-success">
                                <svg class="pc-icon"><use xlink:href="#custom-dollar-square"></use></svg>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Income</h6>
                            </div>
                        </div>
                        <div class="bg-body p-3 mt-3 rounded">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h4 class="mb-0">{{ $object->total_earning }}</h4>
                                    <p class="text-primary mb-0">Total Income</p>
                                </div>
                                <div class="col-6 text-end">
                                    <h4 class="mb-0">{{ $object->total_income_today }}</h4>
                                    <p class="text-primary mb-0">Today's Income</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar avtar-s bg-light-primary">
                                <svg class="pc-icon"><use xlink:href="#custom-profile-2user-outline"></use></svg>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Direct Team</h6>
                            </div>
                        </div>
                        <div class="bg-body p-3 mt-3 rounded">
                            <div class="row align-items-center text-center">
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $object->total_referral }}</h4>
                                    <p class="text-primary mb-0">Total</p>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $object->total_a_referral }}</h4>
                                    <p class="text-primary mb-0">Active</p>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $object->total_ia_referral }}</h4>
                                    <p class="text-primary mb-0">Inactive</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar avtar-s bg-light-primary">
                                <svg class="pc-icon"><use xlink:href="#custom-profile-2user-outline"></use></svg>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Total Team</h6>
                            </div>
                        </div>
                        <div class="bg-body p-3 mt-3 rounded">
                            <div class="row align-items-center text-center">
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $object->total_team }}</h4>
                                    <p class="text-primary mb-0">Total</p>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $object->total_a_team }}</h4>
                                    <p class="text-primary mb-0">Active</p>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $object->total_ia_team }}</h4>
                                    <p class="text-primary mb-0">Inactive</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- [ Locked Reward Bonus ] -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar avtar-s bg-light-warning">
                                <svg class="pc-icon"><use xlink:href="#custom-dollar-square"></use></svg>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Locked Reward Bonus</h6>
                            </div>
                        </div>
                        <div class="bg-body p-3 mt-3 rounded">
                            <h3 class="mb-1">${{ $object->locked_reward_bonus }}</h3>
                            <p class="text-muted mb-2">
                                Expiry:
                                @if(!empty($object->reward_expiry_date))
                                    {{ date('d/m/Y', strtotime($object->reward_expiry_date)) }}
                                @else
                                    —
                                @endif
                            </p>
                            <p class="mb-0 text-primary">Remaining Days: {{ $object->reward_remaining_days }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar avtar-s bg-light-success">
                                <svg class="pc-icon"><use xlink:href="#custom-wallet-2"></use></svg>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Unlocked Reward Bonus</h6>
                            </div>
                        </div>
                        <div class="bg-body p-3 mt-3 rounded">
                            <h3 class="mb-1">${{ $object->unlocked_reward_bonus }}</h3>
                            <p class="text-muted mb-2">Progress</p>
                            @php
                                $gt_unlock_pct = ((float)$object->locked_reward_total > 0)
                                    ? min(100, round(((float)$object->unlocked_reward_bonus / (float)$object->locked_reward_total) * 100, 1))
                                    : 0;
                            @endphp
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $gt_unlock_pct }}%;" aria-valuenow="{{ $gt_unlock_pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="mb-0 text-primary">Unlocked {{ $object->unlocked_reward_bonus }} / {{ $object->locked_reward_total }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- [ Bonus Summary / Achievement Progress / Rank Progress ] -->
        <div class="row">
            <div class="col-md-6 col-xxl-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Bonus Summary</h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Direct Sponsor Income</span>
                                <span>{{ $object->total_referral_bonus }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Daily ROI Income</span>
                                <span>{{ $object->total_daily_roi_bonus }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Daily Level Income</span>
                                <span>{{ $object->total_daily_level_bonus }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Team Level Income</span>
                                <span>{{ $object->total_team_level_bonus }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Salary Bonus</span>
                                <span>{{ $object->total_salary_bonus }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Turnover Bonus</span>
                                <span>{{ $object->total_turnover_bonus }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Locked Reward Unlock</span>
                                <span>{{ $object->total_locked_reward_unlock }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Achievement Progress</h5>
                        </div>
                        @php
                            $gt_effective_cap = $object->total_earning + $object->total_3x_remain;
                            $gt_used_pct = $gt_effective_cap > 0 ? min(100, round(($object->total_earning / $gt_effective_cap) * 100, 1)) : 0;
                        @endphp
                        <p class="text-muted mb-1">Earning Cap Usage</p>
                        <div class="progress progress-thin mb-2">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $gt_used_pct }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted f-12">{{ $object->total_earning }} earned</span>
                            <span class="text-muted f-12">{{ $gt_used_pct }}% used</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Remaining Headroom</span>
                            <span>{{ $object->total_3x_remain }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xxl-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Rank Progress</h5>
                        </div>
                        @php
                            $gt_next_rank = null;
                            if($object->current_rank && $object->allsalary) {
                                $gt_found_current = false;
                                foreach($object->allsalary as $gt_tier) {
                                    if($gt_found_current) { $gt_next_rank = $gt_tier; break; }
                                    if($gt_tier->id == $object->current_rank->id) { $gt_found_current = true; }
                                }
                            } elseif(!$object->current_rank && $object->allsalary && count($object->allsalary) > 0) {
                                $gt_next_rank = $object->allsalary[0];
                            }
                            $gt_team_business = Auth::user()->team_investment ?? 0;
                            $gt_rank_pct = ($gt_next_rank && $gt_next_rank->business > 0) ? min(100, round(($gt_team_business / $gt_next_rank->business) * 100, 1)) : 0;
                        @endphp
                        <p class="text-muted mb-1">Current: {{ $object->current_rank->rank ?? 'Not Ranked Yet' }}</p>
                        @if($gt_next_rank)
                            <div class="progress progress-thin mb-2">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $gt_rank_pct }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted f-12">Next: {{ $gt_next_rank->rank }}</span>
                                <span class="text-muted f-12">{{ $gt_rank_pct }}%</span>
                            </div>
                        @else
                            <p class="text-muted mb-0">Highest rank achieved.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- [ Recent Income / Recent Platform Activity ] -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Recent Income</h5>
                            <a href="{{ URL::to('/') }}/earning-wallet" class="link-primary f-12">View All</a>
                        </div>
                        @if($object->recent_earning && count($object->recent_earning) > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($object->recent_earning as $log)
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="d-block">{{ $log->description }}</span>
                                        <span class="text-muted f-12">{{ date('d M Y, H:i', strtotime($log->created_at)) }}</span>
                                    </div>
                                    <span class="text-success">+{{ $log->amount }}</span>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No income yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Recent Platform Activations</h5>
                        </div>
                        @if($object->recent_staking && count($object->recent_staking) > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($object->recent_staking as $stake)
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="d-block">{{ $stake->member ? obscureAddress($stake->member->username) : 'Member' }}</span>
                                        <span class="text-muted f-12">{{ date('d M Y, H:i', strtotime($stake->created_at)) }}</span>
                                    </div>
                                    <span class="text-primary">{{ $stake->amount }}</span>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No recent activations.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- [ Coin price + earnings summary ] -->
        <div class="row">
            <div class="col-md-6 col-xxl-4 mb-4" align="center">
                <div class="dash-coin-hero mx-auto">
                    <svg class="pc-icon"><use xlink:href="#custom-dollar-square"></use></svg>
                </div>
            </div>

            <div class="col-md-6 col-xxl-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Stake's Details</h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Stake's</span>
                                <span>{{ $object->total_self_investment }}</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Withdraw</span>
                                <span>{{ $object->total_withdrawal }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xxl-4 mb-4">
                <div class="card card-gold h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-0 text-opacity-75" style="color:#0b0b0d;">Coin Price</p>
                                <h4 class="mb-0" style="color:#0b0b0d;">${{ getcoinrate() }}</h4>
                            </div>
                            <div class="avtar">
                                <svg class="pc-icon" style="color:#0b0b0d;"><use xlink:href="#custom-status-up"></use></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-primary available-balance-card mt-3">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-0 text-white text-opacity-75">Total Referral's Earning</p>
                                <h4 class="mb-0 text-white">{{ $object->total_withdrawal }}</h4>
                            </div>
                            <div class="avtar">
                                <i class="ti ti-arrows-left-right f-18"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('jscontent')
<script>
    function toClip(text) {
        var copy = document.createElement("textarea");
        document.body.appendChild(copy);
        copy.value = text;
        copy.select();
        document.execCommand("copy");
        document.body.removeChild(copy);

        successalert('Refer link copy successfylly!')
    }
</script>
@endsection
