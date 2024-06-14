@extends('app')

@section('content')
<div class="container">
    <h2>Tambah Jurnal Harian</h2>
    <form method="POST" action="{{ route('journal-entries.store') }}">
        @csrf

        <div class="form-group">
            <label for="date">Tanggal</label>
            <input type="date" id="date" name="date" class="form-control" required>
        </div>

        <div id="activities-container">
            <div class="activity" id="activity1">
                <h3>Aktivitas 1</h3>
                <div class="form-group">
                    <label for="timing1">Timing</label>
                    <select id="timing1" name="activities[0][timing]" class="form-control" required>
                        <option value="sebelum istirahat">Sebelum Istirahat</option>
                        <option value="sesudah istirahat">Sesudah Istirahat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category1">Kategori</label>
                    <select id="category1" name="activities[0][category]" class="form-control" required>
                        <option value="webinar">Webinar</option>
                        <option value="penilaian">Penilaian</option>
                        <option value="admin">Admin</option>
                        <option value="referensi">Referensi</option>
                        <option value="grafis">Grafis</option>
                        <option value="website">Website</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="supervisor_instruction1">Instruksi dari</label>
                    <input type="text" id="supervisor_instruction1" name="activities[0][supervisor_instruction]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description1">Deskripsi Singkat</label>
                    <textarea id="description1" name="activities[0][description]" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="target_completion1">Target Penyelesaian</label>
                    <input type="text" id="target_completion1" name="activities[0][target_completion]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="status1">Status Target</label>
                    <select id="status1" name="activities[0][status]" class="form-control" required>
                        <option value="sudah tercapai">Sudah Tercapai</option>
                        <option value="belum tercapai">Belum Tercapai</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reason_not_achieved1">Alasan Tidak Tercapai (jika ada)</label>
                    <textarea id="reason_not_achieved1" name="activities[0][reason_not_achieved]" class="form-control" rows="3"></textarea>
                </div>
            </div>
        </div>


        <div class="form-group">
            <button type="button" class="btn btn-primary" id="addActivity">Tambah Aktivitas</button>
        </div>


        <div class="form-group">
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let activityIndex = 1;
        const activitiesContainer = document.getElementById('activities-container');

        document.getElementById('addActivity').addEventListener('click', function () {
            activityIndex++;

            const activityTemplate = `
            <div class="activity" id="activity${activityIndex}">
                <h3>Aktivitas ${activityIndex}</h3>
                <div class="form-group">
                    <label for="timing${activityIndex}">Timing</label>
                    <select id="timing${activityIndex}" name="activities[${activityIndex - 1}][timing]" class="form-control" required>
                        <option value="sebelum istirahat">Sebelum Istirahat</option>
                        <option value="sesudah istirahat">Sesudah Istirahat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category${activityIndex}">Kategori</label>
                    <select id="category${activityIndex}" name="activities[${activityIndex - 1}][category]" class="form-control" required>
                        <option value="webinar">Webinar</option>
                        <option value="penilaian">Penilaian</option>
                        <option value="admin">Admin</option>
                        <option value="referensi">Referensi</option>
                        <option value="grafis">Grafis</option>
                        <option value="website">Website</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="supervisor_instruction${activityIndex}">Instruksi dari Pembimbing</label>
                    <input type="text" id="supervisor_instruction${activityIndex}" name="activities[${activityIndex - 1}][supervisor_instruction]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description${activityIndex}">Deskripsi Singkat</label>
                    <textarea id="description${activityIndex}" name="activities[${activityIndex - 1}][description]" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="target_completion${activityIndex}">Target Penyelesaian</label>
                    <input type="text" id="target_completion${activityIndex}" name="activities[${activityIndex - 1}][target_completion]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="status${activityIndex}">Status Target</label>
                    <select id="status${activityIndex}" name="activities[${activityIndex - 1}][status]" class="form-control" required>
                        <option value="sudah tercapai">Sudah Tercapai</option>
                        <option value="belum tercapai">Belum Tercapai</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reason_not_achieved${activityIndex}">Alasan Tidak Tercapai (jika ada)</label>
                    <textarea id="reason_not_achieved${activityIndex}" name="activities[${activityIndex - 1}][reason_not_achieved]" class="form-control" rows="3"></textarea>
                </div>
                <button type="button" class="btn btn-danger remove-activity" data-index="${activityIndex}">Hapus Aktivitas</button>
            </div>
            `;

            const activityWrapper = document.createElement('div');
            activityWrapper.innerHTML = activityTemplate.trim();
            activitiesContainer.appendChild(activityWrapper.firstChild);


            if (activityIndex === 2) {
                const firstActivity = document.getElementById('activity1');
                const removeButton = firstActivity.querySelector('.remove-activity');
                if (removeButton) {
                    removeButton.style.display = 'none';
                }
            }
        });


        activitiesContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-activity')) {
                const index = e.target.getAttribute('data-index');
                const activity = document.getElementById(`activity${index}`);
                if (activity) {
                    activity.remove();
                }
            }
        });
    });
</script>
@endsection
