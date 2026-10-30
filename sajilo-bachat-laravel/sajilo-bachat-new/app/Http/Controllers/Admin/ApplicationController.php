<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MembershipApplication;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = MembershipApplication::with('membershipType')->latest();

        if (($term = trim((string) $request->input('q'))) !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(fn ($w) => $w->where('full_name', 'like', $like)
                ->orWhere('reference_no', 'like', $like)
                ->orWhere('phone', 'like', $like)
                ->orWhere('email', 'like', $like));
        }
        if (array_key_exists((string) $request->input('status'), MembershipApplication::STATUSES)) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('type')) {
            $query->where('membership_type_id', $request->integer('type'));
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to'));
        }

        return view('admin.applications.index', [
            'applications' => $query->paginate(15)->withQueryString(),
            'types' => Category::ofType('membership')->get(),
        ]);
    }

    public function show(MembershipApplication $application)
    {
        return view('admin.applications.show', ['application' => $application->load('membershipType')]);
    }

    public function update(Request $request, MembershipApplication $application)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(MembershipApplication::STATUSES))],
            'admin_note' => ['nullable', 'string', 'max:3000'],
        ]);

        $application->update($data + ['reviewed_at' => now()]);

        return redirect()->route('admin.applications.show', $application)->with('success', 'Application updated.');
    }

    public function document(MembershipApplication $application)
    {
        $path = MediaService::absolutePath($application->document);
        abort_unless($path && File::isFile($path), 404);

        return response()->download($path);
    }

    public function destroy(MembershipApplication $application)
    {
        if ($application->document) {
            MediaService::delete($application->document);
        }
        $application->delete();

        return redirect()->route('admin.applications.index')->with('success', 'Application deleted.');
    }
}
