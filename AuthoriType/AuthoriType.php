<?php
/*
	AuthoriType Created By Carter Bland @ carterbland.com
	
	To Use:
	require('AuthoriType.php');
	
	$textenv = new atype(array());
	echo $textenv->parse(text); (Outputs HTML)
	
	XSS:
	Be default everything is parsed, to escape things add keys to the array with the desired escape key
	example: $textenv = new atype(array('images','links')); - escapes links and images
*/
class atype
{	
	//constraints are read as 0 = true 1 = false; change
    public $constraints = array(
    	'headers' => '0',
    	'textcolor' => '0',
    	'links' => '0',
    	'images' => '0',
    	'ordered lists' => '0',
    	'codesnippets' => '0'
    );
    
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
                		$this->constraints[$superkey] = '1';
                	}
                }
            }
        } 
    } // If no const it's assumed all are allowed
    
    //Attaches Function To The Class
    public function parse($text)
    {
    	//Escapes Important Characters
    	$text = preg_replace('/[<]/','&lt;',$text);
    	$text = preg_replace('/[>]/','&gt;',$text);
    	$text = preg_replace('/["]/','&quot;',$text);
    	$text = preg_replace("/[']/",'&#39;',$text);
    	$text = preg_replace('/[&]/','&amp;',$text);
    	
    	//Parses Headers
        if ($this->constraints['headers'] == '0') {
			$text = preg_replace('/[#]{2}[ ]{1}(\w[a-zA-Z0-9 ]+)/', '<h1 style="margin:0px;top:5px">' . '$2' . '</h1>', $text);
			$text = preg_replace('/[#]{2}([2-6]{1})[ ]{1}(\w[a-zA-Z0-9 ]+)/', '<h$1 style="margin:0px;top:5px">$2</h$1>', $text);
        }
        
        //Parses Links
        if ($this->constraints['links'] == '0') {
			$text = preg_replace('/[[]{1}(\w[a-zA-Z0-9 \-\.\_\~\:\/\?\\#\[\]\@\!\$\&\(\)\*\+\,\;\=\']+)[]][(](\w+)[)]/', '<a href="$1">$2</a>', $text);
        }
        
        //Parses TextColor
        if ($this->constraints['textcolor'] == '0') {
            //RGB
            $text = preg_replace('/test/','<span style="color:rgb($1,$2,$3)">$4</span>',$text);
            //Hex Colors
            $text = preg_replace('/test/','<span style="color:$1">$2</span>',$text);
        }
        
        //Returns Parsed HTML
        return $text;
    }
}
