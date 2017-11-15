## Output assertions
### Simple string

The following code is expected to output "42".
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

### Output defined in another block

<!--{
    "assert": "output", 
    "expect": {
        "block": "my_expected_text"
    }
}-->
```php
<?=json_encode(['foo' => 'bar'], JSON_PRETTY_PRINT)?>
```
Should output the following:
<!--{"name":"my_expected_text"}-->
```json
{
    "foo": "bar"
}
```