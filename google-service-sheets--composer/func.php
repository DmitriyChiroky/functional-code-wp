<?php


/**
 * google_sheets_update
 */
function google_sheets_update($data) {
  require_once get_theme_file_path('/composer/vendor/autoload.php');

  // Reading data from spreadsheet.

  $client = new \Google_Client();

  $client->setApplicationName('test_name');

  $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);

  $client->setAccessType('offline');

  $tokenPath = get_theme_file_path('/composer/client_credentials.json');

  $client->setAuthConfig($tokenPath);

  $service = new Google_Service_Sheets($client);

  $spreadsheetId = get_field('spreadsheet_id', 'option');

  if (empty($spreadsheetId)) {
    $spreadsheetId = "test_id"; //It is present in your URL
  }

  $range = 'list';

  $values = [
    $data
  ];

  $body = new Google_Service_Sheets_ValueRange([
    'values' => $values
  ]);

  $params = [
    'valueInputOption' => "RAW"
  ];

  $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
}
