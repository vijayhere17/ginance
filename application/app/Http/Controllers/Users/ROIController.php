<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ROIService;

class ROIController extends Controller
{
    protected $roiService;

    public function __construct(ROIService $roiService)
    {
        $this->roiService = $roiService;
    }

    /**
     * Show ROI Investment Page
     */
    public function index()
{
    $data['page_titel'] = 'ROI Investment';

    return view('users.roi.index', $data);
}
}