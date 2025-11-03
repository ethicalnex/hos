<!DOCTYPE html>
<html>
<head>
    <title>Lab Report #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .header { text-align: center; margin-bottom: 30px; }
        .hospital-name { font-size: 24px; font-weight: bold; }
        .report-title { font-size: 20px; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="hospital-name">{{ $order->hospital->name }}</div>
        <div>{{ $order->hospital->address ?? '' }}</div>
        <div>Lab Report #{{ $order->id }}</div>
        <div>Date: {{ $order->created_at->format('M j, Y') }}</div>
    </div>
    
    <div class="patient-info">
        <strong>Patient Information:</strong><br>
        Name: {{ $order->patient->name }}<br>
        MRN: {{ $order->patient->patient->medical_record_number }}<br>
        Doctor: {{ $order->doctor->name }}
    </div>
    
    <div class="report-title">Laboratory Test Results</div>
    
    <table>
        <thead>
            <tr>
                <th>Test</th>
                <th>Result</th>
                <th>Unit</th>
                <th>Normal Range</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->tests as $orderTest)
            <tr>
                <td>{{ $orderTest->test->name }}</td>
                <td>{{ $orderTest->result_value ?? '—' }}</td>
                <td>{{ $orderTest->test->unit ?? '—' }}</td>
                <td>{{ $orderTest->test->normal_range ?? '—' }}</td>
                <td>
                    @if($orderTest->result_value)
                        {{ $orderTest->result_status }}
                    @else
                        Pending
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        This is a computer-generated report. Signature not required.
    </div>
</body>
</html>