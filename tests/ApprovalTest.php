<?php

namespace ApprovalTests\Tests;

use Exception;
use ApprovalTests\Approvals;
use ApprovalTests\FileComparatorInterface;
use ApprovalTests\Namers\Namer;
use PHPUnit\Framework\TestCase;
use ApprovalTests\Writers\Writer;
use ApprovalTests\Reporters\QuietReporter;
use PHPUnit\Framework\MockObject\Generator\MockClass;

# begin-snippet: array_example
class ApprovalTest extends TestCase
{
    public function testVerifyArray()
    {
        $list = ['zero', 'one', 'two', 'three', 'four', 'five'];
        Approvals::verifyList($list);
    }
    # end-snippet

    public function testFailedVerifyArray()
    {
        $this->expectException(Exception::class);
        $list = ['zero', 'one', 'two', 'three', 'four', 'five'];
        Approvals::verifyList($list, new QuietReporter());
    }

    public function testVerifyMap()
    {
        $list = [
            'zero' => 'Lance',
            'one' => 'Jim',
            'two' => 'James',
            'three' => 'LLewellyn',
            'four' => 'Asaph',
            'five' => 'Dana'
        ];
        Approvals::verifyList($list);
    }

    public function testVerifyString()
    {
        $fudge = 'fudge';
        Approvals::verifyString($fudge);
    }

    # begin-snippet: verify_as_json
    public function testVerifyAsJson()
    {
        $obj = [
            "color" => "black",
            "category" => "hue",
            "type" => "primary",
            "code" => [
                "rgba" => [255, 255, 255, 1],
                "hex" => "#000",
            ]
        ];
        Approvals::verifyAsJson($obj);
    }
    # end-snippet

    public function testVerifyTransformedList()
    {
        $list = [
          'apple', 'banana', 'cherry'
        ];

        $callbackObject = new UpperClassHelper();

        Approvals::verifyTransformedList($list, $callbackObject, 'toUpper');
    }

    public function testCreatesEmptyApprovedFileIfItDoesntExist()
    {
        /** @var \ApprovalTests\Namers\Namer&\PHPUnit\Framework\MockObject\MockObject */
        $mockNamer = $this->createMock(Namer::class);
        $mockNamer->method('getApprovedFile')
                    ->willReturn('fileName');

        $mockNamer->method('getApprovalsDirectory')
                   ->willReturn('approvalsFolder');

        $mockNamer->method('getReceivedFile')
                   ->willReturn('txt');

        /** @var \ApprovalTests\Writers\Writer&\PHPUnit\Framework\MockObject\MockObject */
        $mockWriter = $this->createMock(Writer::class);
        $mockWriter->method('getExtensionWithoutDot')
                    ->willReturn('txt');

        $mockWriter->method('write')
                    ->willReturn('approvalsFolder/fileName');

        $mockWriter->expects($this->once())
                       ->method('writeEmpty')
                       ->with('fileName', 'approvalsFolder');

        /** @var \ApprovalTests\FileComparatorInterface&\PHPUnit\Framework\MockObject\MockObject */
        $mockFileComparator = $this->createMock(FileComparatorInterface::class);

        $mockFileComparator->expects($this->once())
                        ->method('checkFiles')
                        ->willReturn(true);

        Approvals::setFileComparator($mockFileComparator);

        Approvals::verify($mockWriter, $mockNamer);

        $this->tearDown();

    }

    public function tearDown(): void
    {
        Approvals::setFileComparator(null);
    }
}

class UpperClassHelper
{
    public function toUpper(string $input)
    {
        return strtoupper($input);
    }
}
