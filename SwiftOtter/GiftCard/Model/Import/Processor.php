<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\Import;

class Processor
{
    public function prepareArray(array $csvData): array
    {
        $headers = array_shift($csvData);
        $rows = [];

        foreach ($csvData as $csvItem) {
            $rows[] = array_combine($headers, $csvItem);
        }

        return $rows;
    }
}
