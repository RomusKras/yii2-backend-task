<?php

namespace app\components;

use Exception;

/**
 * Реализация интерфейса ExternalDataParserInterface для обработки JSON-данных.
 */
class ExternalDataParser implements ExternalDataParserInterface
{
    /**
     * Получает данные от внешнего API (через file_get_contents).
     *
     * @param string $url
     * @return array
     * @throws Exception
     */
    public function fetchData(string $url): array
    {
        $rawData = file_get_contents($url);

        if ($rawData === false) {
            throw new Exception("Ошибка при получении данных с URL {$url}");
        }

        return $this->parseData($rawData);
    }

    /**
     * Парсит JSON-данные в массив.
     *
     * @param string $rawData
     * @return array
     * @throws Exception
     */
    public function parseData(string $rawData): array
    {
        $data = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Ошибка при парсинге JSON: ' . json_last_error_msg());
        }

        return $data;
    }
}