This is an example file.
This html comment block describes the entire document.
<!-- this comment should be ignored -->
The <span>following</span> inline blocks should be properly parsed: `block one` 
and <!--{"name":"my_inline_block"}--> `block two`.

This is an example of php code:
```php
<?php
echo 'hello world';
```

Example of json with <!-- ignore me --> multiline meta:
<!-- {
    "foo": "bar",
    "bar": "foo"
} -->
```json
{
  "hello": "world"
}
```

Here is a marked plain text:
```text
This block is marked as text
```

Here is an unmarked plain text:
<!--{"name":"plain text block"}-->
```
This block has no language assigned
```
