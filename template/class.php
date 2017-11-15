<?='<?php'?>

declare(strict_types=1);
<?php if($namespace): ?>namespace <?=$namespace?>;
<?php endif ?>
use PHPUnit\Framework\TestCase;
class <?=$name?> extends TestCase {
<?=$content?>

}