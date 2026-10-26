<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DkmMember;
use App\Services\ImageOptimizerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DkmMemberController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $imageOptimizer
    ) {}

    public function index(): View
    {
        $members = DkmMember::orderBy('order', 'asc')->paginate(15);

        return view('admin.dkm-members.index', compact('members'));
    }

    public function create(): View
    {
        return view('admin.dkm-members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'name.required' => 'Nama pengurus wajib diisi.',
            'position.required' => 'Jabatan/Amanah wajib diisi.',
            'period.required' => 'Periode kepengurusan wajib diisi.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $this->imageOptimizer->optimizeAndStore($request->file('photo'), 'dkm');
        }

        DkmMember::create([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'period' => $validated['period'],
            'phone' => $validated['phone'],
            'order' => $validated['order'] ?? 0,
            'photo' => $photoPath,
        ]);

        return redirect()->route('admin.dkm-members.index')->with('success', 'Data pengurus DKM berhasil ditambahkan!');
    }

    public function edit(DkmMember $dkmMember): View
    {
        return view('admin.dkm-members.edit', compact('dkmMember'));
    }

    public function update(Request $request, DkmMember $dkmMember): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'name.required' => 'Nama pengurus wajib diisi.',
            'position.required' => 'Jabatan/Amanah wajib diisi.',
            'period.required' => 'Periode kepengurusan wajib diisi.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'position' => $validated['position'],
            'period' => $validated['period'],
            'phone' => $validated['phone'],
            'order' => $validated['order'] ?? 0,
        ];

        if ($request->hasFile('photo')) {
            $updateData['photo'] = $this->imageOptimizer->optimizeAndStore($request->file('photo'), 'dkm');
        }

        $dkmMember->update($updateData);

        return redirect()->route('admin.dkm-members.index')->with('success', 'Data pengurus DKM berhasil diperbarui!');
    }

    public function destroy(DkmMember $dkmMember): RedirectResponse
    {
        $dkmMember->delete();

        return redirect()->route('admin.dkm-members.index')->with('success', 'Data pengurus DKM berhasil dihapus.');
    }
}
