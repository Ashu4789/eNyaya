<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hearing;

class HearingApiController extends Controller
{
    public function index()
    {
        return Hearing::with('legalCase')->orderBy('scheduled_at')->paginate(15);
    }
}
