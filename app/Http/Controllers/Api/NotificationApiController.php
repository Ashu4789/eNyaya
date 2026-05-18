<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaseNotification;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    public function index(Request $request)
    {
        return CaseNotification::with('legalCase')
            ->where('user_id', $request->user()?->id)
            ->latest()
            ->paginate(15);
    }
}
