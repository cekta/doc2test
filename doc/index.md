Doc2Test is a tool that turns documentation in markdown into executable test suites.

Many libraries have documentation with usage examples. It is the quickest way to get started with the library.
But the code evolves and it may happen that the documentation gets outdated. Subtle changes in the API, typos, oversights
can make the documentation erroneous and misleading. Doc2Test helps you test the documentation.

## Usage
```text
$ doc2test <input directory> <output directory>
```
This command will process all `.md` files in `<input directory>` recursively and produce a 
PHPUnit v.6 test suite in `<output directory>`. When the test suite is created, you can run it with
```text
$ phpunit -c <output directory>/phpunit.xml
```

Please read the source code of this file for more examples.

## Quick example

The following code is expected to output 42:
<!--{
    "assert":"output", 
    "expect":{
        "value": "42"
    }
}-->
```php
<?php
$a = 23;
$b = 19;
echo $a +  $b;
```
