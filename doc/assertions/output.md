## Output testing
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