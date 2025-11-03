<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy Report</title>
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
        <div class="hospital-name">{{ auth()->user()->hospital->name }}</div>
        <div>{{ auth()->user()->hospital->address ?? '' }}</div>
        <div>Pharmacy Report</div>
        <div>Date: {{ now()->format('M j, Y') }}</div>
    </div>
    
    <div class="report-title">{{ $data['type'] }} Report</div>
    
    @if($data['type'] == 'Daily')
        <div><strong>Date:</strong> {{ $data['date'] }}</div>
    @elseif($data['type'] == 'Weekly')
        <div><strong>Week:</strong> {{ $data['start_date'] }} to {{ $data['end_date'] }}</div>
    @elseif($data['type'] == 'Monthly')
        <div><strong>Month:</strong> {{ $data['month'] }}</div>
    @else
        <div><strong>Period:</strong> {{ $data['start_date'] }} to {{ $data['end_date'] }}</div>
    @endif
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    @if($data['type'] == 'Inventory')
                        <th>Item</th>
                        <th>Category</th>
                        <th>Form</th>
                        <th>Strength</th>
                        <th>Price (₦)</th>
                        <th>Available Quantity</th>
                        <th>Expiry Date</th>
                        <th>Batch Number</th>
                    @else
                        <th>Patient</th>
                        <th>Pharmacist</th>
                        <th>Amount (₦)</th>
                        <th>Date</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($data['type'] == 'Inventory')
                    @foreach($data['items'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['category'] }}</td>
                        <td>{{ $item['form'] }}</td>
                        <td>{{ $item['strength'] }}</td>
                        <td>{{ number_format($item['price'], 2) }}</td>
                        <td>{{ $item['available_quantity'] }}</td>
                        <td>{{ $item['expiry_date'] }}</td>
                        <td>{{ $item['batch_number'] }}</td>
                    </tr>
                    @endforeach
                @else
                    @foreach($data['sales'] as $sale)
                    <tr>
                        <td>{{ $sale['patient']['name'] }}</td>
                        <td>{{ $sale['pharmacist']['name'] }}</td>
                        <td>{{ number_format($sale['total_amount'], 2) }}</td>
                        <td>{{ $sale['created_at'] }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    
    <div class="footer">
        This is a computer-generated report. Signature not required.
    </div>
</body>
</html>