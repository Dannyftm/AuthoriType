<?php
/*
    AuthoriType Created By Carter Bland @ carterbland.com
*/

class atype
{
    //constraints are read as 0 = true 1 = false; change
    public $constraints = array('headers' => '0', 'textcolor' => '0', 'links' => '0', 'images' => '0', 'ordered lists' => '0', 'codesnippets' => '0');
    
    //Creates a custom class called by $newclass -> new atype(array())
    public function __construct($const)
    {
        //Checks if there are any constraints
        if (isset($const[0])) {
            //Cycles through constraints to compare
            foreach ($this->constraints as $superkey => $superval) {
                //Cycles through the parameter $const
                foreach ($const as $setkey => $setval) {
                    //Sets $constraints[$superconst] if your parameter contains an array that matches it
                    if ($setval == $superkey) {
                        $this->constraints[$superkey] = "1";
                    }
                }
            }
        } // If no const it's assumed all are allowed
    }
    
    public function parse($text)
    {
        if ($this->constraints['headers'] == "0") {
            $text = preg_replace('/(?!*)[*]{1}(\w[a-zA-Z0-9 ]+)[*]{1}(?<!*)/', "<h3 style='margin:0px;top:5px'>" . '$1' . "</h3>", $text);
            $text = preg_replace('/(?!*)[*]{2}(\w[a-zA-Z0-9 ]+)[*]{2}(?<!*)/', "<h4 style='margin:0px;top:5px'>" . '$1' . "</h4>", $text);
        }
        return $text;
    }
}

/*
Example

Creates a a new atype
$test = new atype(array('images','textcolor'));
Sends through the new params
echo $test->parse('*hi*');
*/
