# Self-test
This file illustrates capabilities of Doc2Test and also serves a self-test. 

To generate an example test case, run 
```
$ ./doc2test
```

## Execution assertion

If a code block has no annotation, Doc2Test will execute it anyway. The test is passed if no error or exception occur.
This code should execute without errors/exceptions:
```php
<?php
$a = 2 * 3 * 4;
```

## Output assertion

This php code
<!-- assert=output expect=hello_world -->
```php
<?php
$a = 'Hello';
$b = 'world';
echo "$a $b";
```
should output
<!-- name=hello_world -->
```text
Hello world
```
