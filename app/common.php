<?php
// use Cache;

function cache1($tags, $key, $callback, $expires = null)
{
    // \Log::debug(__FUNCTION__);
    $v = Cache::store('redis')->tags($tags)->get($key);
    if ($v !== null) {
        // \Log::debug(" ------- cache $key");
        return json_decode($v, 1);
    }
    $value = call_user_func($callback);
    // \Log::debug(" +++++++ data $key ");
    Cache::store('redis')->tags($tags)->put($key, json_encode($value), $expires ?? 3600 * 24);
    return $value;
}

function tag_user($user) {
    if (is_int($user) || is_string($user)) {
        return "user.$user";
    }else{
        return "user.$user->id";
    }
}

function flush_tag($tag){
    Cache::store('redis')->tags($tag)->flush();
}

function hash2array($hash)
{
    $arr = [];
    foreach($hash as $val => $label){
        $arr[] = ['id' => $val, 'name' => $label];
    }
    return $arr;
}

function money($val)
{
    return !$val ? null : sprintf(__('RMB')."%.2f", $val);
}

function storage_url($img)
{
    return $img ? url(\Storage::url($img)) : null;
}