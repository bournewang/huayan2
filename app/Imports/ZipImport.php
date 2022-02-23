<?php

namespace App\Imports;

use Carbon\Carbon;
trait ZipImport
{
    public function extractZip($zip_file)
    {
        // 1, uncompress zip
        $zip = new \ZipArchive;
        if (!is_dir(storage_path("import"))) {
            mkdir(storage_path("import"), 0755);
        }
        $dir = storage_path('app/tmp/');
        if (!file_exists($dir)) {
            mkdir($dir, 0755);
        }
        $dir .= md5($zip_file);
        \Log::debug("extract to: ".$dir);

        if ($zip->open($zip_file) === TRUE) {
            $zip->extractTo($dir);
            $zip->close();

            return $dir;
        } else {
            return null;
        }
    }
}
