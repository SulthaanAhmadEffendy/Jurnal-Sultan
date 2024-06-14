@extends('app')

@section('content')
<div class="container">
    <h1>Detail Jurnal Harian</h1>
    <p>Tanggal: {{ $journalEntry->date }}</p>

    <h2>Aktivitas</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Timing</th>
                <th>Kategori</th>
                <th>Deskripsi Singkat</th>
                <th>Status</th>
                <th>Tidak Tercapai (Jika Ada)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $activity->timing }}</td>
                    <td>{{ $activity->category }}</td>
                    <td>{{ $activity->description }}</td>
                    <td>{{ $activity->status }}</td>
                    <td>{{ $activity->reason_not_achieved }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('journal-entries.index') }}" class="btn btn-primary">Kembali</a>
</div>
@endsection
