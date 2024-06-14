<?php

namespace App\Http\Controllers;

use App\Models\JurnalEntry;
use App\Models\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JournalEntryController extends Controller
{
    public function index()
    {
        $journalEntries = JurnalEntry::with('activities')->get();
        return view('index', compact('journalEntries'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'activities' => 'required|array|min:1',
            'activities.*.timing' => 'required|in:sebelum istirahat,sesudah istirahat',
            'activities.*.category' => 'required|in:webinar,penilaian,admin,referensi,grafis,website',
            'activities.*.supervisor_instruction' => 'required|string',
            'activities.*.description' => 'required|string',
            'activities.*.target_completion' => 'required|string',
            'activities.*.status' => 'required|in:sudah tercapai,belum tercapai',
            'activities.*.reason_not_achieved' => 'nullable|string',
        ]);

        $journalEntry = JurnalEntry::create(['date' => $request->date]);

        foreach ($request->activities as $activityData) {
            $journalEntry->activities()->create($activityData);
        }

        return redirect()->route('journal-entries.index')->with('success', 'Jurnal harian berhasil disimpan.');
    }

    public function show(JurnalEntry $journalEntry)
    {
        $activities = $journalEntry->activities;
        return view('show', compact('journalEntry', 'activities'));
    }

    public function edit(JurnalEntry $journalEntry)
    {
        $journalDate = Carbon::parse($journalEntry->date);

        if ($journalDate->diffInDays(now()) > 1) {
            return redirect()->route('journal-entries.index')->with('error', 'Anda tidak dapat mengedit jurnal yang lebih dari 1 hari.');
        }

        $activities = $journalEntry->activities;
        return view('edit', compact('journalEntry', 'activities'));
    }

    public function update(Request $request, JurnalEntry $journalEntry)
    {
        $journalDate = Carbon::parse($journalEntry->date);

        if ($journalDate->diffInDays(now()) > 1) {
            return redirect()->route('journal-entries.index')->with('error', 'Anda tidak dapat mengedit jurnal yang lebih dari 1 hari.');
        }

        $request->validate([
            'date' => 'required|date',
            'activities' => 'required|array|min:1',
            'activities.*.id' => 'sometimes|exists:activities,id',
            'activities.*.timing' => 'required|in:sebelum istirahat,sesudah istirahat',
            'activities.*.category' => 'required|in:webinar,penilaian,admin,referensi,grafis,website',
            'activities.*.supervisor_instruction' => 'required|string',
            'activities.*.description' => 'required|string',
            'activities.*.target_completion' => 'required|string',
            'activities.*.status' => 'required|in:sudah tercapai,belum tercapai',
            'activities.*.reason_not_achieved' => 'nullable|string',
        ]);

        $journalEntry->update(['date' => $request->date]);

        foreach ($request->activities as $activityData) {
            if (isset($activityData['id'])) {
                Activity::findOrFail($activityData['id'])->update($activityData);
            } else {
                $journalEntry->activities()->create($activityData);
            }
        }

        return redirect()->route('journal-entries.index')->with('success', 'Jurnal harian berhasil diperbarui.');
    }

    public function destroy(JurnalEntry $journalEntry)
    {
        $journalDate = Carbon::parse($journalEntry->date);

        if ($journalDate->diffInDays(now()) > 1) {
            return redirect()->route('journal-entries.index')->with('error', 'Anda tidak dapat menghapus jurnal yang lebih dari 1 hari.');
        }

        $journalEntry->activities()->delete();
        $journalEntry->delete();

        return redirect()->route('journal-entries.index')->with('success', 'Jurnal harian berhasil dihapus.');
    }
}
