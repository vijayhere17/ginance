@extends('users.master')
@section('extra')
@endsection
@section('content')
<div class="pc-container">
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL::to('/') }}/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">{{ $page_titel }}</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">{{ $page_titel }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <div class="row g-3 mt-0 mb-4">
            <div class="col-sm-3">
                <div class="bg-body p-3 rounded">
                    <p class="mb-0 text-muted">Active Directs</p>
                    <h6 class="mb-0">{{ $progress['directs'] }}</h6>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="bg-body p-3 rounded">
                    <p class="mb-0 text-muted">Team Members</p>
                    <h6 class="mb-0">{{ $progress['team_members'] }}</h6>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="bg-body p-3 rounded">
                    <p class="mb-0 text-muted">Self Business</p>
                    <h6 class="mb-0">${{ number_format($progress['self_business'], 2) }}</h6>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="bg-body p-3 rounded">
                    <p class="mb-0 text-muted">Team Business</p>
                    <h6 class="mb-0">${{ number_format($progress['team_business'], 2) }}</h6>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($allrewards as $rank)
                @php
                    $status = $achievements->get($rank->id);
                    $is_achieved = ($status != null);
                @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card price-card price-popular h-100">
                        <div class="card-body">
                            <div class="price-head">
                                @if($is_achieved)
                                    <span class="badge f-12 bg-success mb-3">Achieved</span>
                                @else
                                    <span class="badge f-12 bg-warning mb-3">Pending</span>
                                @endif

                                <h5 class="mb-0">{{ $rank->reward }}</h5>
                                <p class="text-muted mb-2">Reward Level {{ $rank->id }}</p>

                                @if(!empty($rank->img))
                                <div class="price-price mt-3 mb-3">
                                    <img src="{{ URL::to('/') }}/assets/{{ $rank->img }}" style="max-width: 100%; border-radius: 10px;" alt="{{ $rank->reward }}" />
                                </div>
                                @endif

                                <ul class="list-unstyled text-start mb-3">
                                    <li class="mb-1">
                                        <strong>Direct:</strong>
                                        {{ $progress['directs'] }} / {{ $rank->direct }}
                                    </li>
                                    <li class="mb-1">
                                        <strong>Team Members:</strong>
                                        {{ $progress['team_members'] }} / {{ $rank->team_members }}
                                    </li>
                                    <li class="mb-1">
                                        <strong>Self Business:</strong>
                                        ${{ number_format($progress['self_business'], 2) }} / ${{ number_format($rank->self_business, 2) }}
                                    </li>
                                    <li class="mb-1">
                                        <strong>Team Business:</strong>
                                        ${{ number_format($progress['team_business'], 2) }} / ${{ number_format($rank->team_business, 2) }}
                                    </li>
                                    <li class="mb-1">
                                        <strong>Weekly Salary:</strong>
                                        ${{ number_format($is_achieved ? $status->weekly_salary : $rank->weekly_salary, 2) }}
                                    </li>
                                    <li class="mb-1">
                                        <strong>Achieve Date:</strong>
                                        @if($is_achieved)
                                            {{ date('d/m/Y H:i:s', strtotime($status->achieve_date ?: $status->created_at)) }}
                                        @else
                                            --/--/---- --:--:--
                                        @endif
                                    </li>
                                </ul>

                                <div class="d-grid">
                                    <a class="btn btn-primary mt-2" href="#">
                                        {{ $rank->main_leg }}% / {{ $rank->other_leg / 2 }}% - {{ $rank->other_leg / 2 }}%
                                        <br>Business Leg
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
@section('jscontent')
@endsection
