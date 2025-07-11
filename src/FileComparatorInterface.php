<?php

namespace ApprovalTests;

interface FileComparatorInterface
{
    public static function checkFiles(string $approvedFilename, string $receivedFilename);
    public static function clean(string $contents);

}
