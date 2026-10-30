<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\MembershipApplicationRequest;
use App\Models\Category;
use App\Models\MembershipApplication;
use App\Services\MediaService;

class MembershipController extends Controller
{
    public function create()
    {
        return view('site.membership', ['types' => Category::ofType('membership')->get()]);
    }

    public function store(MembershipApplicationRequest $request)
    {
        $data = $request->safe()->except(['document', 'website', 'consent']);

        if ($request->hasFile('document')) {
            $data['document'] = MediaService::storeFile($request->file('document'), 'applications');
        }

        $application = MembershipApplication::create($data + [
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('membership.thanks')->with('reference', $application->reference_no);
    }

    public function thanks()
    {
        $reference = session('reference');

        if (! $reference) {
            return redirect()->route('membership.create');
        }

        return view('site.membership-thanks', compact('reference'));
    }
}
