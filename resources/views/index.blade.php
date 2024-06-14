@extends('app')

@section('content')
<div class="container">
    <h1>Jurnal Harian</h1>
    <a href="{{ route('journal-entries.create') }}" class="btn btn-primary">Tambah Jurnal</a>

    <a href="{{ route('journal-entries.index') }}" class="btn btn-light">Refresh</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($journalEntries as $entry)
                <tr>
                    <td>{{ $entry->date }}</td>
                    <td>
                        <a href="{{ route('journal-entries.show', $entry->id) }}" class="btn btn-info">View</a>
                        @if(now()->diffInDays(\Carbon\Carbon::parse($entry->date)) <= 1)
                            <a href="{{ route('journal-entries.edit', $entry->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('journal-entries.destroy', $entry->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
