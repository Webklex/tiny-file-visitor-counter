<?php

/*
* File: counter-backend.php
* Category: -
* Author: MSG
* Created: 19.01.16 20:10
* Updated: -
*
* Description:
*  usage: $counter = new CounterBackend();
*         echo json_encode($counter->getStatistics($json));
*/

class CounterBackend {

    public $options = [
        'api' => 'http://ip-api.com/json',
        'file' => 'counter.db',
        'json' => false,
        'countTime' => 15
    ];

    protected $database = [
        'file' => 'database/counter.db',
        'backup' => 'database/backup',
        'resource' => null
    ];

    /* Example request
     * as : "Vodafone Kabel Deutschland GmbH"
     * city : "Hamburg"
     * country : "Germany"
     * countryCode : "DE"
     * isp : "Vodafone Kabel Deutschland"
     * lat : 53.55
     * lon : 10
     * org : "Vodafone Kabel Deutschland"
     * query : "XX.XX.XXX.XXX"
     * region : "HH"
     * regionName : "Hamburg"
     * status : "success"
     * timezone : "Europe/Berlin"
     * zip : "20099"
     * */
    protected $requests = [
        'as'   => 'Unknown',
        'city' => 'Unknown',
        'country'     => 'Unknown',
        'countryCode' => 'Unk',
        'isp' => 'Unknown',
        'lat' => '0',
        'lon' => '0',
        'org' => 'Unknown',
        'query'      => null,
        'region'     => 'Unk',
        'regionName' => 'Unknown',
        'status'     => 'success',
        'timezone'   => 'Unknown',
        'zip' => '0'
    ];

    protected $statistics = [];

    protected $fileSource = null;

    public function __construct($options = [], $database = []){
        $this->initSession();
        $this->setOptions($options);
        $this->setDatabase($database);
        $this->checkDatabase();

        $this->getRequest();
        $this->loadStatistics();

        $this->locate();
        $this->save();
        $this->backup();
    }

    protected function initSession(){
        if (!session_id()) {
            session_start();
        }
    }

    protected function checkDatabase(){
        if(!is_dir($this->database['backup'])){
            if (is_writable($this->database['backup']) && is_dir($this->database['backup'])) {
                mkdir($this->database['backup'], 0777, true);
            } else {
                die("Unable to open directory! ".$this->database['backup']);
            }
        }
    }

    public function backup(){
        $backupFile = $this->database['backup'].DIRECTORY_SEPARATOR.date('Ymd').'.db.bak';
        if(!file_exists($backupFile)){
            $backup = fopen($backupFile, "w") or die("Unable to open file! ".$backupFile);
            fwrite($backup, json_encode($this->statistics));
            fclose($backup);
        }
    }

    public function __destruct(){
        fclose($this->database['resource']);
    }

    protected function locate(){
        $this->requests['query'] = md5($this->requests['query']);

        if($_SESSION['basic-file-visitor-counter-counted'] + (60*$this->options['countTime']) < time()){
            foreach($this->requests as $key => $value){
                $this->statistics[$key][$value]++;
            }
            $this->statistics['onlineHolder'][$this->requests['query'].'-'.time()][time()] = true;
            $this->statistics[date('Y')][date('m')][date('d')][time()] = $this->requests;
            $_SESSION['basic-file-visitor-counter-counted'] = time();
        }
    }

    public function save(){
        if(!empty($this->statistics)){
            fwrite($this->database['resource'], json_encode($this->statistics));
        }
    }

    protected function loadStatistics(){
        if(file_exists($this->database['file'])){
            $readFileResource = fopen($this->database['file'], "r") or die("Unable to open file! ".$this->database['file']);
            $this->statistics = json_decode(fread($readFileResource, filesize($this->database['file'])), true);
            $this->database['resource'] = fopen($this->database['file'], "w+") or die("Unable to open file! ".$this->database['file']);
        }else{
            $backupFile = $this->database['backup'].DIRECTORY_SEPARATOR.date('Ymd').'.db.bak';
            if(file_exists($backupFile)){
                $backup = fopen($backupFile, "r") or die("Unable to open file! ".$backupFile);
                $this->statistics = json_decode(fread($backup, filesize($backupFile)), true);
                fclose($backup);
            }
            $this->database['resource'] = fopen($this->database['file'], "x+") or die("Unable to open file! ".$this->database['file']);
        }
    }

    public function setOptions($options){
        foreach($options as $key => $option){
            if(isset($this->options[$key])){
                $this->options[$key] = $option;
            }
        }
    }
    public function setDatabase($options){
        foreach($options as $key => $option){
            if(isset($this->database[$key])){
                $this->database[$key] = $option;
            }
        }
    }

    public function setRequest($requests){
        foreach($requests as $key => $request){
            if(isset($this->requests[$key])){
                $this->requests[$key] = $request;
            }
        }
    }

    protected function getRequest(){
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->options['api'],
            //CURLOPT_USERAGENT => 'Codular Sample cURL Request'
        ]);
        // Send the request & save response to $resp
        $this->setRequest(json_decode(curl_exec($curl), true));
        // Close request to clear up some resources
        curl_close($curl);
    }

    public function getStatistics(){
        $hours24 = 60*60*24;
        $day     = time() - $hours24;
        $day7    = time() - ($hours24 * 7);
        $day30   = time() - ($hours24 * 30);
        $year    = time() - ($hours24 * 356);

        $this->statistics['counter'] = [
            'day'   => 0,
            'day7'  => 0,
            'day30' => 0,
            'year'  => 0,
        ];

        for($i = $year; $i <= time(); $i += $hours24){
            foreach($this->statistics[date('Y', $i)][date('m', $i)][date('d', $i)] as $time => $request){
                foreach($this->statistics['counter'] as $key => $val){
                    if($$key <= $i){
                        $this->statistics['counter'][$key]++;
                    }
                }
            }
        }

        foreach($this->statistics['onlineHolder'] as $hash => $statistic){
            $time = array_pop(array_keys($statistic));
            if($time < time() - (60*$this->options['countTime'])){
                unset($this->statistics['onlineHolder'][$hash][$time]);
            }else{
                $this->statistics['counter']['online']++;
            }
        }

        if($this->options['json']){
            return $this->statistics['counter'];
        }
        return json_decode($this->statistics['counter'], true);
    }
}