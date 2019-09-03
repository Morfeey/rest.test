<?php


namespace App\Controllers;


use Helpers\Architecture\Controller;
use Helpers\Path\Directory;

class TestController extends Controller
{
    public function index () {

        $templateFile  =   Directory::getDocumentRoot() . "/public/vue-spa/index.html";
        return file_get_contents($templateFile);
    }

    public function test () {
        return view('home');
    }
}