@extends('app')

@section('content')
<div class="container">
    <a href="{{ route('journal-entries.index') }}" class="btn btn-primary">Kembali</a>
    <h1>Edit Jurnal Harian</h1>

    <form method="POST" action="{{ route('journal-entries.update', $journalEntry->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="date" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ $journalEntry->date }}" required>
        </div>

        <div id="activities-container">
            @foreach($activities as $index => $activity)
            <div class="activity" data-index="{{ $index }}">
                <h3>Aktivitas {{ $index + 1 }}</h3>
                <input type="hidden" name="activities[{{ $index }}][id]" value="{{ $activity->id }}">
                <div class="mb-3">
                    <label for="timing{{ $index }}" class="form-label">Timing</label>
                    <select class="form-control" id="timing{{ $index }}" name="activities[{{ $index }}][timing]" required>
                        <option value="sebelum istirahat" @if($activity->timing == 'sebelum istirahat') selected @endif>Sebelum Istirahat</option>
                        <option value="sesudah istirahat" @if($activity->timing == 'sesudah istirahat') selected @endif>Sesudah Istirahat</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="category{{ $index }}" class="form-label">Kategori</label>
                    <select class="form-control" id="category{{ $index }}" name="activities[{{ $index }}][category]" required>
                        <option value="webinar" @if($activity->category == 'webinar') selected @endif>Webinar</option>
                        <option value="penilaian" @if($activity->category == 'penilaian') selected @endif>Penilaian</option>
                        <option value="admin" @if($activity->category == 'admin') selected @endif>Admin</option>
                        <option value="referensi" @if($activity->category == 'referensi') selected @endif>Referensi</option>
                        <option value="grafis" @if($activity->category == 'grafis') selected @endif>Grafis</option>
                        <option value="website" @if($activity->category == 'website') selected @endif>Website</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="supervisor_instruction{{ $index }}" class="form-label">Instruksi Dari</label>
                    <textarea class="form-control" id="supervisor_instruction{{ $index }}" name="activities[{{ $index }}][supervisor_instruction]" required>{{ $activity->supervisor_instruction }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="description{{ $index }}" class="form-label">Deskripsi singkat</label>
                    <textarea class="form-control" id="description{{ $index }}" name="activities[{{ $index }}][description]" required>{{ $activity->description }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="target_completion{{ $index }}" class="form-label">Target Penyelesaian</label>
                    <textarea class="form-control" id="target_completion{{ $index }}" name="activities[{{ $index }}][target_completion]" required>{{ $activity->target_completion }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="status{{ $index }}" class="form-label">Status Target</label>
                    <select class="form-control" id="status{{ $index }}" name="activities[{{ $index }}][status]" required>
                        <option value="sudah tercapai" @if($activity->status == 'sudah tercapai') selected @endif>Sudah Tercapai</option>
                        <option value="belum tercapai" @if($activity->status == 'belum tercapai') selected @endif>Belum Tercapai</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="reason_not_achieved{{ $index }}" class="form-label">Alasan Tidak Tercapai (jika ada)</label>
                    <textarea class="form-control" id="reason_not_achieved{{ $index }}" name="activities[{{ $index }}][reason_not_achieved]">{{ $activity->reason_not_achieved }}</textarea>
                </div>
                <button type="button" class="btn btn-danger mb-2 remove-activity" data-index="{{ $index }}">Hapus Aktivitas</button>
                <br>
            </div>
            @endforeach
        </div>
        <button type="button" id="add-activity" class="btn btn-secondary mb-3">Tambah Aktivitas</button>

        <button type="submit" class="btn btn-success mb-3">Update Jurnal</button>


    </form>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const activitiesContainer = document.getElementById('activities-container');
    const addActivityButton = document.getElementById('add-activity');
    let activityIndex = {{ $activities->count() }};


    function addRemoveEventListener() {
        const removeButtons = document.querySelectorAll('.remove-activity');
        removeButtons.forEach(button => {
            button.removeEventListener('click', handleRemoveActivity);
            button.addEventListener('click', handleRemoveActivity);
        });
    }


    function handleRemoveActivity(e) {
        const index = e.target.getAttribute('data-index');
        const activityDiv = document.querySelector(`.activity[data-index='${index}']`);
        if (activityDiv) {
            activityDiv.remove();
            updateIndices();
            handleRemoveButtonStates();
        }
    }

    addRemoveEventListener();

    addActivityButton.addEventListener('click', function () {
        const activityDiv = document.createElement('div');
        activityDiv.classList.add('activity');
        activityDiv.setAttribute('data-index', activityIndex);

        activityDiv.innerHTML = `
            <h3>Activity ${activityIndex + 1}</h3>
            <input type="hidden" name="activities[${activityIndex}][id]" value="">
            <div class="mb-3">
                <label for="timing${activityIndex}" class="form-label">Timing</label>
                <select class="form-control" id="timing${activityIndex}" name="activities[${activityIndex}][timing]" required>
                    <option value="sebelum istirahat">Sebelum Istirahat</option>
                    <option value="sesudah istirahat">Sesudah Istirahat</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="category${activityIndex}" class="form-label">Category</label>
                <select class="form-control" id="category${activityIndex}" name="activities[${activityIndex}][category]" required>
                    <option value="webinar">Webinar</option>
                    <option value="penilaian">Penilaian</option>
                    <option value="admin">Admin</option>
                    <option value="referensi">Referensi</option>
                    <option value="grafis">Grafis</option>
                    <option value="website">Website</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="supervisor_instruction${activityIndex}" class="form-label">Supervisor Instruction</label>
                <textarea class="form-control" id="supervisor_instruction${activityIndex}" name="activities[${activityIndex}][supervisor_instruction]" required></textarea>
            </div>
            <div class="mb-3">
                <label for="description${activityIndex}" class="form-label">Description</label>
                <textarea class="form-control" id="description${activityIndex}" name="activities[${activityIndex}][description]" required></textarea>
            </div>
            <div class="mb-3">
                <label for="target_completion${activityIndex}" class="form-label">Target Completion</label>
                <textarea class="form-control" id="target_completion${activityIndex}" name="activities[${activityIndex}][target_completion]" required></textarea>
            </div>
            <div class="mb-3">
                <label for="status${activityIndex}" class="form-label">Status</label>
                <select class="form-control" id="status${activityIndex}" name="activities[${activityIndex}][status]" required>
                    <option value="sudah tercapai">Sudah Tercapai</option>
                    <option value="belum tercapai">Belum Tercapai</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="reason_not_achieved${activityIndex}" class="form-label">Reason Not Achieved</label>
                <textarea class="form-control" id="reason_not_achieved${activityIndex}" name="activities[${activityIndex}][reason_not_achieved]"></textarea>
            </div>
            <button type="button" class="btn btn-danger remove-activity" data-index="${activityIndex}">Hapus Aktivitas</button>
        `;

        activitiesContainer.appendChild(activityDiv);
        activityIndex++;

        addRemoveEventListener();
        handleRemoveButtonStates();
    });

    function updateIndices() {
        const activityDivs = document.querySelectorAll('.activity');
        activityDivs.forEach((div, newIndex) => {
            div.setAttribute('data-index', newIndex);
            div.querySelector('h3').textContent = `Activity ${newIndex + 1}`;
            div.querySelector('input[name^="activities"]').name = `activities[${newIndex}][id]`;
            div.querySelector('label[for^="timing"]').setAttribute('for', `timing${newIndex}`);
            div.querySelector('select[id^="timing"]').setAttribute('id', `timing${newIndex}`).name = `activities[${newIndex}][timing]`;
            div.querySelector('label[for^="category"]').setAttribute('for', `category${newIndex}`);
            div.querySelector('select[id^="category"]').setAttribute('id', `category${newIndex}`).name = `activities[${newIndex}][category]`;
            div.querySelector('label[for^="supervisor_instruction"]').setAttribute('for', `supervisor_instruction${newIndex}`);
            div.querySelector('textarea[id^="supervisor_instruction"]').setAttribute('id', `supervisor_instruction${newIndex}`).name = `activities[${newIndex}][supervisor_instruction]`;
            div.querySelector('label[for^="description"]').setAttribute('for', `description${newIndex}`);
            div.querySelector('textarea[id^="description"]').setAttribute('id', `description${newIndex}`).name = `activities[${newIndex}][description]`;
            div.querySelector('label[for^="target_completion"]').setAttribute('for', `target_completion${newIndex}`);
            div.querySelector('textarea[id^="target_completion"]').setAttribute('id', `target_completion${newIndex}`).name = `activities[${newIndex}][target_completion]`;
            div.querySelector('label[for^="status"]').setAttribute('for', `status${newIndex}`);
            div.querySelector('select[id^="status"]').setAttribute('id', `status${newIndex}`).name = `activities[${newIndex}][status]`;
            div.querySelector('label[for^="reason_not_achieved"]').setAttribute('for', `reason_not_achieved${newIndex}`);
            div.querySelector('textarea[id^="reason_not_achieved"]').setAttribute('id', `reason_not_achieved${newIndex}`).name = `activities[${newIndex}][reason_not_achieved]`;
            div.querySelector('.remove-activity').setAttribute('data-index', newIndex);
        });
    }

    function handleRemoveButtonStates() {
        const removeButtons = document.querySelectorAll('.remove-activity');
        if (removeButtons.length === 1) {
            removeButtons[0].setAttribute('disabled', true);
        } else {
            removeButtons.forEach(button => button.removeAttribute('disabled'));
        }
    }

    handleRemoveButtonStates();
});


    </script>



@endsection
