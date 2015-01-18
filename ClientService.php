<?php

namespace Services\Client;
use Library\Adwords\AdwordsService;

/**
 * ClientService Class, containing all useful methods shares Business Logic between UsersController and PdfController to collect
 * Google Client Data from Google Adwords API
 */
class ClientService {

    protected $adwordsService;

    public function __construct(adwordsService $adwordsService) {
        $this->adwordsService = $adwordsService;
    }


    function downloadReport($client_id, $refresh_token, $reportType, $reportFields, $startDate = NULL, $endDate = NULL, $predefinedTime = NULL) {
        $data = array();

        try {
            // Set Report Settings and initializes the Variables in Adwords Service Class
            $this->adwordsService->setClientId($client_id);
            $this->adwordsService->setClientRefreshToken($refresh_token);
            $this->adwordsService->setReportStartDate($startDate);
            $this->adwordsService->setReportEndDate($endDate);
            $this->adwordsService->setReportType($reportType);
            $reportName = $client_id . "_" . time() . "_" . $reportType;
            $this->adwordsService->setReportName($reportName);
            $fileName = $reportName . ".csv";
            $this->adwordsService->setFileName($fileName);
            $this->adwordsService->setReportFields($reportFields);

            // Download Google Adwords Report
            $this->adwordsService->downloadReport();
            
            // Process Report
            if ($reportType == 'ACCOUNT_PERFORMANCE_REPORT') {
                $data = $this->processAccountReport($fileName);
            } else if ($reportType == 'KEYWORDS_PERFORMANCE_REPORT') {
                $data = $this->processKeywordReport($fileName);
            }
        } catch (Exception $ex) {
            // Incase of Error it's logged in adwords service log file
        }

        return $data;
    }

}

?>
