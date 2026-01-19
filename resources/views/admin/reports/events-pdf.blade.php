<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Event Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #6B46C1;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #6B46C1;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        .summary {
            background-color: #F3F4F6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            color: #6B46C1;
            font-size: 18px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .summary-item {
            background: white;
            padding: 12px;
            border-radius: 6px;
            border-left: 4px solid #6B46C1;
        }
        .summary-item .label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .summary-item .value {
            font-size: 20px;
            font-weight: bold;
            color: #3B82F6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        table thead {
            background-color: #6B46C1;
            color: white;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
        }
        table th {
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        table tbody tr:hover {
            background-color: #F9FAFB;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #E5E7EB;
            text-align: right;
            font-size: 12px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #E0E7FF;
            color: #6B46C1;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>EVENT REPORTS</h1>
        <p>Period: {{ $dateFrom->format('d M Y') }} - {{ $dateTo->format('d M Y') }}</p>
        <p>Generated: {{ now()->format('d M Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total Events</div>
                <div class="value">{{ number_format($events->count()) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Participants</div>
                <div class="value">{{ number_format($events->sum('registrations_count')) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Event Revenue</div>
                <div class="value">RM {{ number_format($events->sum(function($e) { return ($e->registration_fee ?? 0) * $e->registrations_count; }), 0) }}</div>
            </div>
        </div>
    </div>

    <h3 style="color: #6B46C1; margin-top: 25px;">Event Details</h3>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Dojo</th>
                <th>Type</th>
                <th class="text-center">Participants</th>
                <th class="text-right">Fee</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td class="font-bold">{{ $event->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</td>
                    <td>{{ $event->dojo->name ?? 'All Dojos' }}</td>
                    <td><span class="badge">{{ ucfirst($event->type) }}</span></td>
                    <td class="text-center font-bold" style="color: #3B82F6;">{{ $event->registrations_count }}</td>
                    <td class="text-right">RM {{ number_format($event->registration_fee ?? 0, 0) }}</td>
                    <td class="text-right font-bold">RM {{ number_format(($event->registration_fee ?? 0) * $event->registrations_count, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report is generated automatically by the system.</p>
        <p>&copy; {{ now()->year }} Dojo Management System</p>
    </div>
    <script>
    window.onload = function() {
        window.print();
    }
</script>
</body>

</html>

