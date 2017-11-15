<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

class MdFilesIterator extends \FilterIterator
{
    public function accept(): bool
    {
        $file = $this->current();
        return $file instanceof \SplFileInfo
            && $file->isFile()
            && $file->getExtension() === 'md';
    }
}
