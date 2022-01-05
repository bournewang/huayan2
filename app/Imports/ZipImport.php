<?php

namespace App\Imports;

use Carbon\Carbon;
trait ZipImport
{
    protected $dir;
    public function __construct($zip_file)
    {
        // 1, uncompress zip
        $zip = new \ZipArchive;
        if (!is_dir(storage_path("import"))) {
            mkdir(storage_path("import"), 0755);
        }
        $this->dir = storage_path("import/".Carbon::now()->format('Y-m-d-G:i:s'));

        if ($zip->open($zip_file) === TRUE) {
            $zip->extractTo($this->dir);
            $zip->close();
            // $this->log("解压文件");
            // $this->break();
            // echo 'ok';
        } else {
            // $this->error("解压失败");
            // echo 'failed';
        }
    }
}
