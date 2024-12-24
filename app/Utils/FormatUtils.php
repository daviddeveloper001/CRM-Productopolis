<?php

namespace App\Utils;

use App\Enum\EventEnum;
use App\Models\Customer;
use App\Models\Event;
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

    static function replaceCustomerPlaceholders($content, $customer_id)
    {
        $customer = Customer::find($customer_id);

        if (!$customer) {
            return $content;
        }

        $placeholders = [
            '[NOMBRE-CLIENTE]' => $customer->first_name . ' ' . $customer->last_name,
            '[TELEFONO-CLIENTE]' => $customer->phone,
            '[EMAIL-CLIENTE]' => $customer->email,
            '[CIUDAD-CLIENTE]' => $customer->city->name
        ];

        foreach ($placeholders as $placeholder => $value) {
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }
}
