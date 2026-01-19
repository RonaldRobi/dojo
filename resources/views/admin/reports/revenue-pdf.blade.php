<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Revenue Report</title>
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
            color: #10B981;
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
        .total-row {
            background-color: #F3F4F6;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REVENUE REPORT</h1>
        <p>Period: {{ $dateFrom->format('d M Y') }} - {{ $dateTo->format('d M Y') }}</p>
        <p>Generated: {{ now()->format('d M Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total Revenue</div>
                <div class="value">RM {{ number_format($totalRevenue, 0) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Invoices</div>
                <div class="value">{{ number_format($invoices->count()) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Average per Invoice</div>
                <div class="value">RM {{ $invoices->count() > 0 ? number_format($totalRevenue / $invoices->count(), 0) : 0 }}</div>
            </div>
        </div>
    </div>

    <h3 style="color: #6B46C1; margin-top: 25px;">Invoice Details</h3>
    <table>
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Dojo</th>
                <th>Member</th>
                <th>Type</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->invoice_date ? $invoice->invoice_date->format('d M Y') : $invoice->created_at->format('d M Y') }}</td>
                    <td>{{ $invoice->member->dojo->name ?? 'N/A' }}</td>
                    <td>{{ $invoice->member->fullname ?? 'N/A' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $invoice->invoice_type)) }}</td>
                    <td class="text-right">RM {{ number_format($invoice->total_amount, 0) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL</td>
                <td class="text-right">RM {{ number_format($totalRevenue, 0) }}</td>
            </tr>
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


