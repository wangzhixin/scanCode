<?php

namespace app\provider;

use Endroid\QrCode\QrCode;

class ErCode
{
    /**
     * @Time    :   2021/12/31 11:35:35
     * @Author  :   wangZhixin 
     * @Desc    :   生成二维码
     */
    public function getCode($content, $color = array(), $logoUrl = "")
    {
        $returnPath = "";
        try {
            $qrCode = new QrCode($content);
            $qrCode->setForegroundColor($color);
            if ($logoUrl) {
                $qrCode->setLogoPath($logoUrl);
                $qrCode->setLogoWidth(50);
            }
            $returnPath = "/" . 'qrcode/' . get_guid() . '.png';
            $filePath = public_path() . $returnPath;
            $qrCode->writeFile($filePath);
        } catch (\Throwable $e) {
            getErrorMessage($e);
        }
        return $returnPath;
    }
}
