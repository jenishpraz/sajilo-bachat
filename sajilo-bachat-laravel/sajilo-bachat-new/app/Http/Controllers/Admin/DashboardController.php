<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\LoanService;
use App\Models\MembershipApplication;
use App\Models\Notice;
use App\Models\Person;
use App\Models\SavingPlan;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $counts = [
            ['label' => 'Membership applications', 'value' => MembershipApplication::count(), 'sub' => MembershipApplication::where('status', 'pending')->count().' pending', 'icon' => 'users', 'route' => route('admin.applications.index')],
            ['label' => 'Saving plans', 'value' => SavingPlan::count(), 'sub' => SavingPlan::active()->count().' active', 'icon' => 'savings', 'route' => route('admin.resource.index', 'saving_plans')],
            ['label' => 'Loan services', 'value' => LoanService::count(), 'sub' => LoanService::active()->count().' active', 'icon' => 'loan', 'route' => route('admin.resource.index', 'loan_services')],
            ['label' => 'Notices', 'value' => Notice::count(), 'sub' => Notice::where('type', 'auction')->count().' auction', 'icon' => 'bell', 'route' => route('admin.resource.index', 'notices')],
            ['label' => 'Board members', 'value' => Person::inGroup(Person::BOARD)->count(), 'sub' => Person::inGroup(Person::EMPLOYEE)->count().' employees', 'icon' => 'users', 'route' => route('admin.resource.index', 'board')],
            ['label' => 'Branches', 'value' => Branch::count(), 'sub' => Branch::active()->count().' active', 'icon' => 'building', 'route' => route('admin.resource.index', 'branches')],
        ];

        $activities = collect()
            ->merge(Notice::latest()->take(5)->get()->map(fn ($n) => ['type' => $n->type === 'auction' ? 'Auction notice' : 'Notice', 'title' => $n->tr('title'), 'at' => $n->created_at, 'url' => route('admin.resource.edit', [$n->type === 'auction' ? 'auction_notices' : 'notices', $n->id])]))
            ->merge(Event::latest()->take(5)->get()->map(fn ($e) => ['type' => 'Event', 'title' => $e->tr('title'), 'at' => $e->created_at, 'url' => route('admin.resource.edit', ['events', $e->id])]))
            ->sortByDesc('at')->take(6)->values();

        return view('admin.dashboard', [
            'counts' => $counts,
            'applications' => MembershipApplication::latest()->take(6)->get(),
            'messages' => ContactMessage::latest()->take(6)->get(),
            'activities' => $activities,
        ]);
    }
}
