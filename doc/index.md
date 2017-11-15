Doc2Test is a tool that turns documentation in markdown into executable test suites.

**Problem.** 
Many libraries have documentation with usage examples. 
It is the quickest way to get started with the library.
But the code evolves and it may happen that the documentation gets outdated. 
Subtle changes in the API, typos, oversightscan make the documentation erroneous and misleading. 

**Solution.** 
You add annotations before code blocks in your documentation to set expectations.
Doc2Test produces a test suite according to the expectations. 

## Usage
```text
$ doc2test <input directory> <output directory>
```
This command will process all `.md` files in `<input directory>` recursively and produce a 
PHPUnit v.6 test suite in `<output directory>`. When the test suite is created, you can run it with
```text
$ phpunit -c <output directory>/phpunit.xml
```

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

Add the following block in the `.md` file to describe the expected behavior:
```html
<!--{
    "assert":"output", 
    "expect":{
        "value": "42"
    }
}-->
```

## Documentation

Look into **the source code** of the documentation files to see usage examples
- [Output Assertions](assertions/output.md) 