<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StocksMaster;
use League\Csv\Reader;

class ImportStocksMaster extends Command
{
    protected $signature = 'import:stocks'; // Daha kısa ve standart bir isim
    protected $description = 'Import BIST 100 stock symbols and names from CSV';

    public function handle()
    {
        $path = storage_path('app/stocks.csv');

        if (!file_exists($path)) {
            $this->error("CSV file not found at: {$path}");
            return 1;
        }

        // CSV dosyasını oku
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // İlk satır header

        $records = $csv->getRecords();

        foreach ($records as $row) {
            StocksMaster::updateOrCreate(
                ['symbol' => $row['symbol']],
                ['name' => $row['name']]
            );
        }

        $this->info("CSV import completed successfully.");
        return 0;
    }
}
