<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    private function rules(string $ignore = ''): array
    {
        return [
            'subject_code' => 'required|string|max:20',
            'subject_name' => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'units'        => 'required|integer|min:1|max:6',
            'semester'     => 'required|in:1st Semester,2nd Semester,Summer',
        ];
    }

    // ── Index ─────────────────────────────────────────────────
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        $subjects = Subject::where('user_id', Auth::id())
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('subject_code', 'like', "%{$search}%")
                       ->orWhere('subject_name', 'like', "%{$search}%")
                       ->orWhere('description', 'like', "%{$search}%")
                       ->orWhere('semester', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('subjects.index', compact('subjects', 'search', 'perPage'));
    }

    // ── Store ─────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), [
            'subject_code.required' => 'Subject code is required.',
            'subject_name.required' => 'Subject name is required.',
            'units.required'        => 'Number of units is required.',
            'units.min'             => 'Units must be at least 1.',
            'units.max'             => 'Units cannot exceed 6.',
            'semester.required'     => 'Semester is required.',
            'semester.in'           => 'Invalid semester value.',
        ]);

        $validated['user_id'] = Auth::id();
        Subject::create($validated);

        return redirect()->route('subjects.index')
            ->with('toast_success', 'Subject "' . $validated['subject_name'] . '" added successfully!');
    }

    // ── Update ────────────────────────────────────────────────
    public function update(Request $request, Subject $subject)
    {
        $this->authorizeSubject($subject);

        $validated = $request->validate($this->rules());
        $subject->update($validated);

        return redirect()->route('subjects.index')
            ->with('toast_success', 'Subject "' . $subject->subject_name . '" updated successfully!');
    }

    // ── Destroy ───────────────────────────────────────────────
    public function destroy(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $name = $subject->subject_name;
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('toast_success', 'Subject "' . $name . '" deleted successfully!');
    }

    // ── Show (JSON for modal) ─────────────────────────────────
    public function show(Subject $subject)
    {
        $this->authorizeSubject($subject);
        return response()->json($subject);
    }

    // ── Edit (JSON for modal) ─────────────────────────────────
    public function edit(Subject $subject)
    {
        $this->authorizeSubject($subject);
        return response()->json($subject);
    }

    private function authorizeSubject(Subject $subject): void
    {
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
