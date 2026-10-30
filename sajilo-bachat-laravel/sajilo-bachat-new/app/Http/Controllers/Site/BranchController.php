<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Branch;

class BranchController extends Controller
{
    public function __invoke()
    {
        return view('site.branches', [
            'branches' => Branch::active()->orderByDesc('is_head_office')->ordered()->get(),
        ]);
    }
}
