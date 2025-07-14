<?php

namespace ApprovalTests;

interface FileComparatorInterface
{
    public function checkFiles(string $approvedFilename, string $receivedFilename);
    public function clean(string $contents);

}
