<?php

namespace ApprovalTests;

use ApprovalTests\FileComparatorInterface;

class FileApprover implements FileComparatorInterface
{
    public function checkFiles(string $approvedFilename, string $receivedFilename): bool
    {
        $approvedContents = FileApprover::clean(file_get_contents($approvedFilename));
        $receivedContents = FileApprover::clean(file_get_contents($receivedFilename));

        return $approvedContents === $receivedContents;
    }

    public function clean(string $contents): string
    {
        return str_replace("\r\n", "\n", $contents);
    }
}
