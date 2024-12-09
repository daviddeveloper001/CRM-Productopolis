<?php

namespace App\Helpers;

use App\Utils\FileUtils;

class EvolutionAPI
{
    static function get_instance($instance)
    {
        switch ($instance) {
            case 'SALES':
                $instance = ["instance" => "Ventas_MedicalSoft", "api_key" => "A22011EF1B9D-4A92-AA10-F22C76BDB5A1"];
                return $instance;
            case 'SUPPORT':
                $instance = ["instance" => "Soporte_Medical", "api_key" => "ACB7EEDB43A2-43FF-BDF7-772C4E626A35"];
                return $instance;
            default:
                $instance = ["instance" => "", "api_key" => "oEQ0j9ft1FX43QkGLDCEM0arw"];
                return $instance;
                break;
        }
    }

    static function whatsapp_sent_EA($number, $message, $message_type, $instance)
    {
        $instance = self::get_instance($instance);

        if (!empty($number)) {
            $curl = curl_init();

            $data = [
                "number" => $number,
                "text" => $message,
                "linkPreview" => false
            ];
            $headers = [
                "Content-Type: application/json",
                "apikey: " . $instance['api_key']
            ];

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://apiwhatsapp.medicalsoft.ai/message/" . $message_type . "/" . $instance['instance'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $headers,
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                return $response;
            }
        }
        return "-1";
    }

    static function whatsapp_send_media_EA($number, $message, $instance, $media, $filename)
    {
        $instance = self::get_instance($instance);

        if (!empty($number)) {
            $curl = curl_init();

            $mediaData = FileUtils::getMediaData($filename);

            $data = [
                "number" => $number,
                "mediatype" => $mediaData['mediaType'],
                "mimetype" => $mediaData['mimeType'],
                "caption" => $message,
                "media" => $media,
                "fileName" => $filename
            ];

            $headers = [
                "Content-Type: application/json",
                "apikey: " . $instance['api_key']
            ];

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://apiwhatsapp.medicalsoft.ai/message/sendMedia/" . $instance['instance'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $headers,
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                return $response;
            }
        }
        return "-1";
    }

    static function whatsapp_send_audio_EA($number, $instance, $media)
    {
        $instance = self::get_instance($instance);

        if (!empty($number)) {
            $curl = curl_init();

            $data = [
                "number" => $number,
                "audio" => $media,
            ];

            $headers = [
                "Content-Type: application/json",
                "apikey: " . $instance['api_key']
            ];

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://apiwhatsapp.medicalsoft.ai/message/sendWhatsAppAudio/" . $instance['instance'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $headers,
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                return $response;
            }
        }
        return "-1";
    }

    static function whatsapp_send_message_EA($filename, $fileURL, $phone, $content, $instance)
    {
        if (!empty($filename)) {

            $mediaData = FileUtils::getMediaData(filename: $filename);

            if (
                in_array($mediaData['mediaType'], ['audio', 'video']) &&
                in_array($mediaData['mimeType'], ['video/webm'])
            ) {
                self::whatsapp_send_audio_EA(
                    $phone,
                    $instance,
                    $fileURL
                );
            } else {
                self::whatsapp_send_media_EA(
                    $phone,
                    $content,
                    $instance,
                    $fileURL,
                    $filename
                );
            }
        } else {
            self::whatsapp_sent_EA(
                $phone,
                $content,
                'sendText',
                $instance
            );
        }
    }
}
