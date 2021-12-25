<?php

namespace App\Iot;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Device
{
    private $productKey;
    private $deviceName;
    private $deviceData;
    public function __construct($productKey, $deviceName)
    {
        $this->productKey = $productKey;
        $this->deviceName = $deviceName;
        $this->deviceData = [
            'IotInstanceId' => config('iot.iot_instance_id'),
            'ProductKey' => $this->productKey,
            'DeviceName' => $this->deviceName,
        ];
    }
    
    public function detail()
    {    
        return IotClient::request('QueryDeviceDetail', $this->deviceData);
    }
    
    public function status()
    {
        return IotClient::request('GetDeviceStatus', $this->deviceData);
    }
    
    public function shadow()
    {
        return IotClient::request('GetDeviceShadow', $this->deviceData);
    }

    public function property($data = [])
    {
        $url = 'QueryDevicePropertyStatus';
        if ($data) {
            $url = 'SetDeviceProperty';
            $this->deviceData['Items'] = json_encode($data);
        }
        return IotClient::request($url, $this->deviceData);
    }
}