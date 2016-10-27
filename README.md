# AuthoriType

## What is it 
AuthoriType is a text-to-html parser that's based on MarkDown. It's primary use is to create a controlled parsing environment and it's created to be used as a multi-purpose parser

## How do I use it 
```php
require('AuthoriType.php');

//Create a new class
$blogenviron = new atype(array());
$texttest = $blog_environ->parse('*hi*');
echo $texttest;
```

## Syntax

Headers


> \# hi (by default # = h1 but \#+2-6 w will change it to said number ex[\#2] = <h2>)
