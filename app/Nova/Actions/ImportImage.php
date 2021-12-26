<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use App\Models\Store;

class ImportImage extends Action
{
    use InteractsWithQueue, Queueable;

    // protected $shop;
    protected $zip_file;
    protected $dir;
    protected $rel_dir;
    public function init($zip_file)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        // $this->user = $user;
        // $this->shop = $user->shop;
        $this->zip_file = $zip_file;
        // Cache::forget($this->cacheKey());
        
        // 1, uncompress zip
        $zip = new \ZipArchive;
        $this->rel_dir = 'uploads/' . date('Y-m-d');
        $this->dir = \Storage::disk('public')->path($this->rel_dir);
        // $this->success("extract to $this->dir");
        // $this->break();
        if ($zip->open($this->zip_file) === TRUE) {
            $zip->extractTo($this->dir);
            $zip->close();
            \Log::debug("解压文件");
            // $this->break();
            // echo 'ok';
        } else {
            $this->error("解压失败");
            // echo 'failed';
        }
    }
    
    public function import()
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        $categories = Category::pluck('id', 'name')->all();
        if ($dp = opendir($this->dir)) {
            while ( ( $file = readdir($dp) ) !== false  ) {
                // $this->break();
                if ($file[0] == '.') {
                    continue;
                }
                \Log::debug("处理图片: $file ");

                $size = filesize($this->dir . '/' . $file);
                if ($size > config('mall.uploads.img.limit_size', 1024 * 100)) {
                    \Log::debug(' 请选择1M以内的图片');
                    continue;
                }
                
                $info = pathinfo($this->dir . '/' . $file);
                $code = $info['filename'];
                // $code1 = substr($info['filename'], 0, strlen($info['filename'])-2);
                $index = null;

                // get the design
                if (!$id = ($categories[$code] ?? null)){
                    \Log::debug("没有找到分类${code}, 跳过");
                    // $this->break();
                    continue;
                }

                if ($id) {
                    // copy the img
                    $new = md5($file . time()) . '.'.($info['extension'] ?? 'jpg');
                    $newpath = 'category/'.$new;
                    \Storage::disk('public')->move($this->rel_dir . '/'.$file, $newpath);
                    // $this->success("更新款式${code}");
                    \Log::debug("更新分类$code 图片 $newpath");
                    if ($cat = Category::find($id)) {
                        $cat->update(['image' => $newpath]);
                    }
                    // \Log::debug("update ")
                }
                // $this->break();
            }
        }
        // check each jpg or directory with same design code
}

    public function name()
    {
        return __('Import Image');
    }
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        //
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        $file = $fields->get('file');
        $this->init($file);
        $this->import();
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            File::make(__('File'), 'file')
        ];
    }
}
