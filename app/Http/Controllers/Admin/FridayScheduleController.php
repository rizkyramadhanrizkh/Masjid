<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FridaySchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FridayScheduleController extends Controller
{
    public function index(): View
    {
        $schedules = FridaySchedule::orderBy('date', 'desc')->paginate(10);

        return view('admin.friday-schedules.index', compact('schedules'));
    }

    public function create(): View
    {
        return view('admin.friday-schedules.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'khatib' => ['required', 'string', 'max:255'],
            'imam' => ['required', 'string', 'max:255'],
            'muadzin' => ['nullable', 'string', 'max:255'],
            'theme' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ], [
            'date.required' => 'Tanggal pelaksanaan wajib diisi.',
            'khatib.required' => 'Nama khatib wajib diisi.',
            'imam.required' => 'Nama imam wajib diisi.',
            'theme.required' => 'Tema khutbah wajib diisi.',
        ]);

        FridaySchedule::create($validated);

        return redirect()->route('admin.friday-schedules.index')->with('success', 'Jadwal Khutbah Jumat berhasil ditambahkan!');
    }

    public function edit(FridaySchedule $fridaySchedule): View
    {
        return view('admin.friday-schedules.edit', compact('fridaySchedule'));
    }

    public function update(Request $request, FridaySchedule $fridaySchedule): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'khatib' => ['required', 'string', 'max:255'],
            'imam' => ['required', 'string', 'max:255'],
            'muadzin' => ['nullable', 'string', 'max:255'],
            'theme' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ], [
            'date.required' => 'Tanggal pelaksanaan wajib diisi.',
            'khatib.required' => 'Nama khatib wajib diisi.',
            'imam.required' => 'Nama imam wajib diisi.',
            'theme.required' => 'Tema khutbah wajib diisi.',
        ]);

        $fridaySchedule->update($validated);

        return redirect()->route('admin.friday-schedules.index')->with('success', 'Jadwal Khutbah Jumat berhasil diperbarui!');
    }

    public function destroy(FridaySchedule $fridaySchedule): RedirectResponse
    {
        $fridaySchedule->delete();

        return redirect()->route('admin.friday-schedules.index')->with('success', 'Jadwal Khutbah Jumat berhasil dihapus.');
    }
}
