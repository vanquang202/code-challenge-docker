<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AppController extends Controller
{
    private function acrMultyLgCode($lg = [] , $imageBuildDocker = null , $path = null)
    {
        // $path = "D:/htdocs/codetesting/CodeChallenge";
        $extensionFile = $lg['extensionFile']; // php,js,c
        $type = $lg['type']; // php,javascript
        $forder = "/tmp/".time(); // forder
        mkdir($path.$forder, 0777, true); // create forder
        $mainPhp = fopen($path.$forder."/main.$extensionFile", 'a+'); // opend file php,js,c
        fopen($path.$forder.'/output.txt', 'a+'); // opend output

        $content = request() -> content; // content php,js,c
        fwrite($mainPhp, $content);  // put content -> file create mainPhp
        fclose($mainPhp); // close file

        $command = "docker run --rm -v $path$forder:/$forder $imageBuildDocker $type $forder/main.$extensionFile $forder/output.txt"; // command
        $content = system($command); // Exec run file
        $read = file("$path.$forder/output.txt"); // Read file ouput
        $result = '';
        foreach ($read as $line) {
            $result.=$line;
        }

        // Remove file
        unlink("$path.$forder/output.txt");
        unlink("$path.$forder/main.$extensionFile");
        rmdir($path.$forder);

        // retun
        return [
            'result' => $result,
            'time' => $content
        ];
    }

    public function local(Request $r)
    {
        $result = $this -> acrMultyLgCode(
            [
                "extensionFile" => "php",
                "type" => "php",
            ],
            "ntcd_php",
            "D:/htdocs/codetesting/CodeChallenge"
        );
        dd($result);

    }

    // public function localJs(Request $r)
    // {

    //     $path = "D:/htdocs/codetesting/CodeChallenge";
    //     $forder = "/tmp/".time();
    //     mkdir($path.$forder, 0777, true);
    //     $mainPhp = fopen($path.$forder.'/main.js', 'a+');
    //     fopen($path.$forder.'/output.txt', 'a+');

    //     $content = $r -> content;

    //     fwrite($mainPhp, $content);
    //     fclose($mainPhp);

    //     $command = "docker run --rm -v $path$forder:/$forder ntcd_javascript node  $forder/main.js $forder/output.txt";
    //     $content = system($command,$txt);
    //     $read = file("$path.$forder/output.txt");
    //     $result = '';
    //     foreach ($read as $line) {
    //         $result.=$line;
    //     }
    //     unlink("$path.$forder/output.txt");
    //     unlink("$path.$forder/main.js");
    //     rmdir($path.$forder);
    //     dd($result);
    // }
}