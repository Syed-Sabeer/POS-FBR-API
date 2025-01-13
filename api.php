<?php

$curl = curl_init();

$data = '{
    "InvoiceNumber": "",
    "POSID": 152404,
    "USIN": "USIN0",
    "DateTime": "2024-11-11 12:00:00",
    "BuyerNTN": "1234567-8",
    "BuyerCNIC": "12345-1234567-8",
    "BuyerName": "Syed Sabeer Tester",
    "BuyerPhoneNumber": "0000-0000000",
    "items": [
        {
            "ItemCode": "IT_1011",
            "ItemName": "Test Item",
            "Quantity": 1.0,
            "PCTCode": "11001010",
            "TaxRate": 0.0,
            "SaleValue": 0.0,
            "TotalAmount": 3000.0,
            "TaxCharged": 0.0,
            "Discount": 0.0,
            "FurtherTax": 0.0,
            "InvoiceType": 1,
            "RefUSIN": null
        }
    ],
    "TotalBillAmount": 1,
    "TotalQuantity": 1,
    "TotalSaleValue": 1,
    "TotalTaxCharged": 1,
    "PaymentMode": 2,
    "InvoiceType": 1
}';

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://gw.fbr.gov.pk/imsp/v1/api/Live/PostData',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer 5eb323b5-a38a-390b-a90d-72fa15439f1e'
    ),
));

// Execute the cURL request
$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$curl_error = curl_error($curl);

// Prepare logging data
$log_file = 'curl_debug.log';
$log_data = [
    'Request URL' => 'https://gw.fbr.gov.pk/imsp/v1/api/Live/PostData',
    'Request Data' => json_decode($data, true),
    'Response' => $response ? json_decode($response, true) : null,
    'HTTP Code' => $http_code,
    'Error (if any)' => $curl_error
];

// Display response and status
if ($response === false) {
    echo "cURL Error: " . $curl_error . "\n";
} else {
    echo "HTTP Code: " . $http_code . "\n";
    echo "Response:\n";
    echo json_encode(json_decode($response), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
}

// Log to file
file_put_contents($log_file, print_r($log_data, true), FILE_APPEND);

curl_close($curl);
?>
