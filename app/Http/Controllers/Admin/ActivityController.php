<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Services\ImageOptimizerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $imageOptimizer
    ) {}

    public function index(Request $request): View
    {
        $search = $request->input('q');
        $category = $request->input('category');

        $query = Activity::latest('date');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('ustadz', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $activities = $query->paginate(10)->withQueryString();

        return view('admin.activities.index', compact('activities', 'search', 'category'));
    }

    public function create(): View
    {
        return view('admin.activities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'ustadz' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'time_start' => ['required', 'string', 'max:10'],
            'time_end' => ['nullable', 'string', 'max:10'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul kegiatan wajib diisi.',
            'category.required' => 'Kategori kegiatan wajib dipilih.',
            'date.required' => 'Tanggal kegiatan wajib diisi.',
            'time_start.required' => 'Jam mulai kegiatan wajib diisi.',
            'location.required' => 'Lokasi kegiatan wajib diisi.',
            'image.image' => 'File poster/foto harus berupa gambar.',
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Activity::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->imageOptimizer->optimizeAndStore($request->file('image'), 'activities');
        }

        Activity::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'ustadz' => $validated['ustadz'],
            'category' => $validated['category'],
            'date' => $validated['date'],
            'time_start' => $validated['time_start'],
            'time_end' => $validated['time_end'],
            'location' => $validated['location'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.activities.index')->with('success', 'Kegiatan/Kajian berhasil ditambahkan!');
    }

    public function edit(Activity $activity): View
    {
        return view('admin.activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'ustadz' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'time_start' => ['required', 'string', 'max:10'],
            'time_end' => ['nullable', 'string', 'max:10'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul kegiatan wajib diisi.',
            'category.required' => 'Kategori kegiatan wajib dipilih.',
            'date.required' => 'Tanggal kegiatan wajib diisi.',
            'time_start.required' => 'Jam mulai kegiatan wajib diisi.',
            'location.required' => 'Lokasi kegiatan wajib diisi.',
            'image.image' => 'File poster/foto harus berupa gambar.',
        ]);

        $updateData = [
            'title' => $validated['title'],
            'ustadz' => $validated['ustadz'],
            'category' => $validated['category'],
            'date' => $validated['date'],
            'time_start' => $validated['time_start'],
            'time_end' => $validated['time_end'],
            'location' => $validated['location'],
            'description' => $validated['description'],
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            $updateData['image'] = $this->imageOptimizer->optimizeAndStore($request->file('image'), 'activities');
        }

        $activity->update($updateData);

        return redirect()->route('admin.activities.index')->with('success', 'Kegiatan berhasil diperbarui!');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()->route('admin.activities.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
