<?php

require 'vendor/autoload.php';

use Goutte\Client;

$option = [
    'timeout' => 300,
    // 'headers' => [
        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:91.0) Gecko/20100101 Firefox/91.0',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.5',
        'Upgrade-Insecure-Requests' => '1',
        'Sec-Fetch-Dest' => 'document',
        'Sec-Fetch-Mode' => 'navigate',
        'Sec-Fetch-Site' => 'same-origin',
        'Sec-Fetch-User' => '?1',
        'Sec-GPC' => '1',
        'Cache-Control' => 'max-age=0, no-cache',
        'Pragma' => 'no-cache'
    // ],
];


// $client->request('');

for ($x = 1; $x <= 539; $x++) {
    $client = new Client();
$tanepsAwardModel = array(['Tender No', 'Tender Url', 'Procuring Entity', 'Supplier Name', 'Award Date', 'Award Amount']);
echo "The page number is: $x";

// $client = new GuzzleHttp\Client();
$tenderNumbers = [];
$tenderLinks=[];
$procuringEntity=[];
$supplierName=[];
$awardDate=[];
$awardAmount=[];
$url="http://www.taneps.go.tz/epps/viewAllAwardedContracts.do?d-3998960-p=$x&selectedItem=viewAllAwardedContracts.do&T01_ps=100";
echo "\nurl :".$url."\n";
try {

    $res = $client->request('GET', $url, $option);
    echo "\nThe response got it is: $x";
    
    $res->filter('#T01 tbody tr td a')->each(function ($node,$i) use(&$tenderNumbers,&$tenderLinks)  {
        static $j=1;
        if($i%2==0){
            echo "\n{$j} {$node->text()}";
            echo 'https://www.taneps.go.tz'.$node->attr('href');
    $tenderNumbers[]=$node->text();
    $tenderLinks[]= 'https://www.taneps.go.tz'.$node->attr('href');
    $j++;
}
});

$res->filter('#T01 tbody tr td:nth-child(2)')->each(function ($node,$i) use(&$procuringEntity)  {
    static $j=1;
        echo "\n{$j} {$node->text()}";
        $procuringEntity[]=$node->text();
        $j++;
    });
    
    $res->filter('#T01 tbody tr td:nth-child(3)')->each(function ($node,$i) use(&$supplierName)  {
            static $j=1;
            echo "\n{$j} {$node->text()}";
            $supplierName[]=$node->text();
            $j++;
            });  
            
            $res->filter('#T01 tbody tr td:nth-child(4)')->each(function ($node,$i) use(&$awardDate)  {
                static $j=1;
                echo "\n{$j} {$node->text()}";
                $awardDate[]=$node->text();
                $j++;
            });  
            $res->filter('#T01 tbody tr td:nth-child(5)')->each(function ($node,$i) use(&$awardAmount)  {
                static $j=1;
                echo "\n{$j} {$node->text()}";
                $awardAmount[]=$node->text();
                $j++;
            });  
            
                
            echo "\nThe starting push array $x";
            
            
            for($k=0;$k<sizeof($awardDate);$k++){
                array_push($tanepsAwardModel,[$tenderNumbers[$k], $tenderLinks[$k], $procuringEntity[$k], $supplierName[$k], $awardDate[$k], $awardAmount[$k]]);
            }
    } catch (Exception $e) {
            print_r("Error timeout");
        }
            // Open a file in write mode ('w')
            echo "\nThe starting csv $x";

    // if($i==1)
    $fp = fopen("csv/page-{$x}.csv", 'w');
                    // else{
                    // $fp= fopen('site.csv', 'a');
                    // }
                      
                    // Loop through file pointer and a line
                    foreach ($tanepsAwardModel as $fields) {
                        fputcsv($fp, $fields);
                    }
                      
                    fclose($fp);
                    $tanepsAwardModel=[];
                    echo "\nEnd 100 $x\n";
    sleep(2);
                } 