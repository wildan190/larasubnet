<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher Receipt</title>
    <style>
        
        .container {
            width: 100%;
            max-width: 600px;
            background-color:rgb(244, 244, 244);
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin: 32px auto;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 16px;
        }

        .header h1 {
            font-size: 1.5rem;
            color: #111827;
            margin: 0;
        }

        .header h2 {
            font-size: 1.125rem;
            color: #4b5563;
            margin: 4px 0;
        }

        /* Voucher Code Section */
        .voucher-code-section {
            text-align: center;
            margin-bottom: 16px;
        }

        .voucher-code-section p {
            font-size: 1rem;
            color: #111827;
            font-weight: 500;
        }

        .voucher-code {
            background-color:rgb(144, 144, 144); /* Biru kontras */
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            padding: 10px 20px;
            margin-top: 8px;
            display: inline-block;
            border-radius: 6px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Customer Info Section */
        .customer-info {
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        .customer-info p {
            font-size: 1rem;
            color: #111827;
            margin: 4px 0;
        }

        .customer-info strong {
            font-weight: 600;
        }

        /* Voucher Details Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            font-size: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f9fafb;
            color: #111827;
        }

        /* Footer Section */
        .footer {
            text-align: center;
            color: #111827;
            font-size: 1rem;
        }

        .footer p {
            margin: 4px 0;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .header h1 {
                font-size: 1.25rem;
            }

            .header h2 {
                font-size: 1rem;
            }

            .voucher-code {
                font-size: 1rem;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Main Container -->
    <div class="container">

        <!-- Header Section -->
        <div class="header">
            <h1>Latsubnet</h1>
            <h2>Voucher Receipt</h2>
        </div>

        <!-- Voucher Code Section -->
        <div class="voucher-code-section">
            <p>Voucher Code:</p>
            <div class="voucher-code">
                {{ $voucher_code }}
            </div>
        </div>

        <!-- Customer Info Section -->
        <div class="customer-info">
            <p><strong>Customer Name:</strong> {{ $customer_name }}</p>
            <p><strong>Customer Email:</strong> {{ $customer_email }}</p>
        </div>

        <!-- Voucher Details Table -->
        <table>
            <thead>
                <tr>
                    <th>Voucher Name</th>
                    <th>Duration (Days)</th>
                    <th>Price (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $voucher_name }}</td>
                    <td>{{ $duration }}</td>
                    <td>Rp {{ number_format($price, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer Section -->
        <div class="footer">
            <p>Thank you for your purchase, <strong>{{ $customer_name }}</strong>.</p>
            <p>We appreciate your trust in our service.</p>
        </div>

    </div>

</body>
</html>
