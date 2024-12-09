<?php

namespace App\Utils;

use Exception;

class FileUtils
{
    static function getMediaData($filename)
    {
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $mediaType = '';
        $mimeType = '';

        switch ($fileExtension) {
                // Imágenes
            case 'jpg':
            case 'jpeg':
                $mediaType = 'image';
                $mimeType = 'image/jpeg';
                break;
            case 'png':
                $mediaType = 'image';
                $mimeType = 'image/png';
                break;
            case 'gif':
                $mediaType = 'image';
                $mimeType = 'image/gif';
                break;
            case 'bmp':
                $mediaType = 'image';
                $mimeType = 'image/bmp';
                break;
            case 'webp':
                $mediaType = 'image';
                $mimeType = 'image/webp';
                break;

                // Videos
            case 'mp4':
                $mediaType = 'video';
                $mimeType = 'video/mp4';
                break;
            case '3gp':
                $mediaType = 'video';
                $mimeType = 'video/3gpp';
                break;
            case 'mkv':
                $mediaType = 'video';
                $mimeType = 'video/x-matroska';
                break;
            case 'webm':
                $mediaType = 'video';
                $mimeType = 'video/webm';
                break;

                // Documentos
            case 'pdf':
                $mediaType = 'document';
                $mimeType = 'application/pdf';
                break;
            case 'doc':
                $mediaType = 'document';
                $mimeType = 'application/msword';
                break;
            case 'docx':
                $mediaType = 'document';
                $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                break;
            case 'xls':
                $mediaType = 'document';
                $mimeType = 'application/vnd.ms-excel';
                break;
            case 'xlsx':
                $mediaType = 'document';
                $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;
            case 'ppt':
                $mediaType = 'document';
                $mimeType = 'application/vnd.ms-powerpoint';
                break;
            case 'pptx':
                $mediaType = 'document';
                $mimeType = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
                break;
            case 'txt':
                $mediaType = 'document';
                $mimeType = 'text/plain';
                break;

                // Audios
            case 'mp3':
                $mediaType = 'audio';
                $mimeType = 'audio/mpeg';
                break;
            case 'aac':
                $mediaType = 'audio';
                $mimeType = 'audio/aac';
                break;
            case 'wav':
                $mediaType = 'audio';
                $mimeType = 'audio/wav';
                break;
            case 'ogg':
                $mediaType = 'audio';
                $mimeType = 'audio/ogg';
                break;
            case 'amr':
                $mediaType = 'audio';
                $mimeType = 'audio/amr';
                break;

            default:
                throw new Exception("Unsupported file type: $fileExtension");
        }

        // Preparar los datos para enviar a la API de Evolution
        return [
            "mediaType" => $mediaType,
            "mimeType" => $mimeType
        ];
    }
}
