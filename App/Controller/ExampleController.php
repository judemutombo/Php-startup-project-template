<?php
namespace App\Controller;

//create your own controller as a child class of MainController

class ExampleController extends MainController{

    public function example() : void
    {
        $this->render("example","example"); //give tha name of the page and the template to render
    }
}