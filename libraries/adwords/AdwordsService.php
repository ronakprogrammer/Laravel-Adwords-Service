<?php

/* * ***********************************************************************
 * @ File Name : AdwordsService.php
 * 
 * @ Description : Common Adwords service class acts a bridge between the 
 *   the application and Google adwords and retrived the performance reports 
 *   from Google Adwords API and provides the data to the application
 * 
 * @ Author  : Ronak Shah
 * ********************************************************************** */

namespace Library\Adwords;

use \AdWordsUser,
    \ReportDefinition,
    \Selector,
    \DateRange,
    \ReportUtils,
    \Config,
    \Exception;   
    

define('FILE_PATH', Config::get('constants.FILE_PATH'));

class AdwordsService {

    public $predefinedDateRange;
    public $startDate;
    public $endDate;
    public $filePath = FILE_PATH;
    public $reportType;
    public $reportName;
    public $campaigns = array();
    public $refreshToken;
    public $client_id;
    public $reportFields = array();
    public $reportPredicates = array();
    public $fileName;
    public $includeZeroImpressions = false;

    public function setReportType($reportType) {
        $this->reportType = $reportType;
    }

    public function getReportType() {
        return $this->reportType;
    }

    public function setReportName($reportName) {
        $this->reportName = $reportName;
    }

    public function getReportName() {
        return $this->reportName;
    }

    public function setPredefinedDateRange($predefinedDateRange) {
        $this->predefinedDateRange = $predefinedDateRange;
    }

    public function getPredefinedDateRange() {
        return $this->predefinedDateRange;
    }

    public function setReportStartDate($startDate) {
        $this->startDate = $startDate;
    }

    public function getReportStartDate() {
        return $this->startDate;
    }

    public function setReportEndDate($endDate) {
        $this->endDate = $endDate;
    }

    public function getReportEndDate() {
        return $this->endDate;
    }

    public function setClientId($client_id) {
        $this->client_id = $client_id;
    }

    public function getClientId() {
        return $this->client_id;
    }

    public function setClientRefreshToken($refreshToken) {
        $this->refreshToken = $refreshToken;
    }

    public function getClientRefreshToken() {
        return $this->refreshToken;
    }

    public function setReportFields($reportFields) {
        $this->reportFields = $reportFields;
    }

    public function getReportFields() {
        return $this->reportFields;
    }

    public function setReportPredicates($predicates) {
        $this->reportPredicates = $predicates;
    }

    public function getReportPredicates() {
        return $this->reportPredicates;
    }

    public function setFileName($fileName) {
        $this->fileName = $fileName;
    }

    public function getFileName() {
        return $this->fileName;
    }

    function downloadReport() {
        try {
            $refresh_token = $this->getClientRefreshToken();
            $client_id = $this->getClientId();

            $adwords_version = Config::get('constants.ADWORDS_VERSION');
            $oauth2_info = array('client_id' => Config::get('constants.AW_CLIENT_ID'),
                'client_secret' => Config::get('constants.AW_CLIENT_SECRET'),
                'refresh_token' => $refresh_token
            );

            $user = new AdWordsUser();
            $user->SetClientCustomerId($client_id);
            $user->SetOAuth2Info($oauth2_info);

            // Log every SOAP XML request and response.
            //$user->LogDefaults();
            $user->LogAll();

            // Load the service, so that the required classes are available.
            $user->LoadService('ReportDefinitionService', $adwords_version);

            // Create selector.
            $selector = new Selector();
            $reportFields = $this->getReportFields();
            $selector->fields = $reportFields;

            // Filter out deleted criteria.
            $reportPredicates = $this->getReportPredicates();
            if (!empty($reportPredicates)) {
                $selector->predicates = $reportPredicates;
            }

            $reportName = $this->getReportName();
            $reportType = $this->getReportType();
            $predefinedDateRange = $this->getPredefinedDateRange();
            $startDate = $this->getReportStartDate();
            $endDate = $this->getReportEndDate();


            // Create report definition.
            $reportDefinition = new ReportDefinition();
            $reportDefinition->selector = $selector;
            $reportDefinition->reportName = $reportName;
            $reportDefinition->reportType = $reportType;

            if (!empty($predefinedDateRange)) {
                $reportDefinition->dateRangeType = $predefinedDateRange;
            } else {

                $start_date = date('Ymd', strtotime($startDate));
                $end_date = date('Ymd', strtotime($endDate));

                $dateRange = new DateRange();
                $dateRange->min = $start_date;
                $dateRange->max = $end_date;
                $reportDefinition->dateRangeType = 'CUSTOM_DATE';
                $selector->dateRange = $dateRange;
            }

            $fileName = $this->getFileName();

            $reportDefinition->downloadFormat = 'CSV';

            // Set Zero Impressions Flag.
            $reportDefinition->includeZeroImpressions = $this->includeZeroImpressions;


            // Set additional options.
            $options = array('version' => $adwords_version);

            $filePath = $this->filePath . $fileName;

            // Download report.
            ReportUtils::DownloadReport($reportDefinition, $filePath, $user, $options);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

}

?>