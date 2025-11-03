@extends('layouts.app')
@section('content')
<form method="POST" action="{{ route('nurse.emr.notes.update', $record) }}">
    @csrf @method('PUT')
    <textarea name="nurse_notes" class="form-control" rows="5">{{ $record->nurse_notes }}</textarea>
    <button type="submit" class="btn btn-primary mt-3">Update Notes</button>
</form>
@endsection