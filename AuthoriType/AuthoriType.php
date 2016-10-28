<?php
/*
AuthoriType Created By Carter Bland @ carterbland.com

To Use:
require('AuthoriType.php');

$textenv = new atype(array());
echo $textenv->parse(text); (Outputs HTML)

XSS:
BY DEFAULT EVERYTHING WILL BE PARSED, to escape things add keys to the array with the desired escape key
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
    	'orderedlists' => '0',
    	'codesnippets' => '0',
    	'italics' => '0',
    	'bold' => '0',
    	'strike' => '0',
        'comments' => '0'
    );
    
    //How codesnippets interpretes code, if language isn't defined it will log
    public $languages = array(
    	'lua' => array(
        	'reserved_words' => 'and/break/do/else/elseif/end/false/for/function/if/in/local/nil/not/or/repeat/return/then/true/until/while',
        	'default_color' => 'rgb(0,0,0)',
        	'special_word_colors' => array(
                'false' => 'rgb(200,0,0)',
                'nil' => 'rgb(200,0,0)',
                'true' => 'rgb(200,0,0)'
            ),
        	'string_concats' => '.',
            'concat_color' => 'rgb',
        	'string_identifiers' => '3' //1 = " 2 = ' 3 = " and '
        	)
    );
    
    public $p_log = array();
    
    //Creates a custom class called by $newclass -> new atype(array())
    public function __construct($const = array())
    {
        if (is_array($const)) {
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
            array_push($this->p_log,'New Class Created');
        } else {
            throw new Exception('Error Construction Class: Wrong Format'); //Throws if Class is built incorrectly
        }
    }
    
    public function parse($text = "") {
    	if (is_string($text)) {
            
            //Escapes Important Characters
    		$text = htmlEntities($text);
            
    		//Builds Headers
    		if ($this->constraints['headers']=="0") {
    			$text = $text = preg_replace('/[#]{2}[ ]{1}([^\n]+)\n/', '<h1>$1</h1>', $text);
    			$text = $text = preg_replace('/[#]{2}([1-6]{1})[ ]{1}([^\n]+)\n/', '<h$1>' . '$2' . '</h$1>', $text);
    		} else {
    			array_push($this->p_log,'Parsing Parameter Caught: Header');
    		}
    		
    		//Builds Images
    		if ($this->constraints['images']=="0") {
    			$text = preg_replace('/\!\[([a-zA-Z0-9 ]+)\]\(([^\[\]\(\)]+)\)/', '<img src="$2" alt="$1" style="position:relative;width:80%;height:auto;left:10%"></img>', $text);
    		} else {
    			array_push($this->p_log,'Parsing Parameter Caught: Images');
    		}
    		
            //Builds Links
    		if ($this->constraints['links']=="0") {
    			$text = preg_replace('/(?<!\!)\[([a-zA-Z0-9 ]+)\]\(([^\[\]]+)\)/', '<a href="$2">$1</a>', $text);
    		} else {
    			array_push($this->p_log,'Parsing Parameter Caught: Links');
    		}
    		//Builds Ordered Lists
    		if ($this->constraints['orderedlists']=="0") {
    			$text = $text;
    		} else {
    			array_push($this->p_log,'Parsing Parameter Caught: Ordered Lists');
    		}
    		
    		//Builds Code Snippets
    		if ($this->constraints['codesnippets']=="0") {
    			$text = $text;
    		} else {
    			array_push($this->p_log,'Parsing Parameter Caught: Code Snippets');
    		}
    		
    		//Builds Italics
    		if ($this->constraints['italics']=="0") {
    			$text = preg_replace('/(?<!\*)\*{1}([^*]+)[\*]{1}(?! *\<\/img>|\<\/a>)/','<i>$1</i>',$text);
    		} else {
    			array_push($this->p_log,'Parsing Parameter Caught: Italics');
    		}
            
    		//Builds Bold
    		if ($this->constraints['bold']=="0") {
    			$text = preg_replace('/(?<!\*)\*{2}([^*]+)[\*]{2}(?! *\<\/img>|\<\/a>)/','<b>$1</b>',$text);
    		} else {
    			array_push($this->p_log,'Parsing Parameter Caught: Bold');
    		}
            
            //Builds Strike
    		if ($this->constraints['strike']=="0") {
    			$text = preg_replace('/(?<!\-)\-{1}([^-]+)[\-]{1}(?! *\<\/img>|\<\/a>)/','<strike>$1</strike>',$text);
    		} else {
    			array_push($this->p_log,'Parsing Parameter Caught: Strike');
    		}
            
             
            //Builds Comments
    		if ($this->constraints['comments']=="0") {
    			$text = preg_replace('/\|{1}([^\|\n]+)/','<div style="position:relative;height:auto;width:auto;"><span style="opacity:.8;padding-left:10px">$1</span><div style="position:absolute;height:100%;width:5px;top:0%;background-color:rgb(150,150,150)"></div></div>',$text);
    		} else {
    			array_push($this->p_log,'Parsing Parameter Caught: Comments');
    		}
            
    		//Builds Color Text
    		if ($this->constraints['textcolor']) {
    			
    		} else {
    			array_push($this->p_log,'Parsing Parameter Caught: Color Text');
    		}
 			//Returns parsed text
            $text = preg_replace('/\n/',' <br> ',$text);
    		return $text;
    	} else {
    		throw new Exception('Parsing Error: Wrong Format'); //Throws if $text isn't string.
    	}
    }

}
