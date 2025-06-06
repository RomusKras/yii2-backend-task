<?php

namespace app\components;

use Exception;

/**
 * Интерфейс для всех сервисов обработки данных.
 */
interface ExternalDataParserInterface
{
    /**
     * Получает данные от внешнего API.
     *
     * @param string $url URL внешнего сервиса
     * @return array Декодированные данные
     * @throws Exception Если что-то пошло не так
     */
    public function fetchData(string $url): array;

    /**
     * Парсит данные из внешнего источника.
     *
     * @param string $rawData Сырые данные (в формате JSON)
     * @return array Спарсенные данные
     * @throws Exception Если парсинг завершился с ошибкой
     */
    public function parseData(string $rawData): array;
}