<?php

namespace App\Utils;

use App\Models\Sale;


class FormatUtils
{

    static function parseWhatsAppFormatting($text)
    {
        $text = preg_replace('/\*(.*?)\*/', '<strong>$1</strong>', $text);

        $text = preg_replace('/_(.*?)_/', '<em>$1</em>', $text);

        $text = preg_replace('/~(.*?)~/', '<del>$1</del>', $text);

        $text = preg_replace('/```(.*?)```/', '<code>$1</code>', $text);

        $text = preg_replace('/`(.*?)`/', '<code>$1</code>', $text);

        return nl2br($text);
    }

    static function replaceSalePlaceholders($content, $saleId)
    {
        $sale = Sale::find($saleId);

        if (!$sale) {
            return $content;
        }

        $placeholders = [
            '[NOMBRE-CLIENTE]' => $sale->customer->customer_name,
            '[TELEFONO-CLIENTE]' => $sale->customer->phone,
            '[EMAIL-CLIENTE]' => $sale->customer->email,
            '[CIUDAD-CLIENTE]' => $sale->customer->city->name,
            '[VENDEDOR]' => $sale->seller->name,
            '[TIENDA]' => $sale->shop->name,
        ];

        foreach ($placeholders as $placeholder => $value) {
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }
}
