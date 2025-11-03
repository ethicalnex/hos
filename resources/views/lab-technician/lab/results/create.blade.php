@extends('layouts.lab-technician')

@section('page-title', 'Enter Lab Results')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Lab Order #{{ $order->id }}</h5>
        <p class="mb-0">
            <strong>Patient:</strong> {{ $order->patient->name }} (MRN: {{ $order->patient->patient->medical_record_number }})<br>
            <strong>Doctor:</strong> {{ $order->doctor->name }}
        </p>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('lab-technician.lab.results.store', $order) }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Test</th>
                            <th>Unit</th>
                            <th>Normal Range</th>
                            <th>Result Value</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->tests as $orderTest)
                        <tr>
                            <td>{{ $orderTest->test->name }}</td>
                            <td>{{ $orderTest->test->unit ?? '—' }}</td>
                            <td>{{ $orderTest->test->normal_range ?? '—' }}</td>
                            <td>
                                <input type="hidden" name="results[{{ $loop->index }}][test_id]" value="{{ $orderTest->test_id }}">
                                <input type="number" step="0.0001" name="results[{{ $loop->index }}][result_value]" 
                                       class="form-control" value="{{ old("results.{$loop->index}.result_value", $orderTest->result_value) }}">
                            </td>
                            <td>
                                <input type="text" name="results[{{ $loop->index }}][notes]" 
                                       class="form-control" value="{{ old("results.{$loop->index}.notes", $orderTest->notes) }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <button type="submit" class="btn btn-primary">Submit Results</button>
        </form>
    </div>
</div>
@endsection