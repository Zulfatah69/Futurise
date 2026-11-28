<?php

namespace App\Filament\Concerns;

use Symfony\Component\HttpFoundation\StreamedResponse;

trait StreamsCsvExport
{
    /**
     * Stream an Excel-safe UTF-8 CSV download without external package dependencies.
     *
     * @param  array<int, string>  $headers
     * @param  iterable<int, array<int, mixed>>  $rows
     */
    protected function streamCsvDownload(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            // UTF-8 BOM agar karakter Indonesia terbaca benar di Excel
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                $sanitized = array_map(static function (mixed $cell): string {
                    $value = (string) ($cell ?? '');
                    if ($value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
                        return "'" . $value;
                    }

                    return $value;
                }, $row);

                fputcsv($handle, $sanitized);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
