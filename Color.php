<?php

namespace Etp\Service\Helpers;

class Color
{
    const COLOR_DIFFERENCE = 70;
    const BLACK = '#000000';

    /** @var array */
    public $colors = [];

    /** @var int */
    private $minColorValue = 0;

    /** @var int */
    private $maxColorValue = 120;

    /**
     * Color constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if (!empty($params['minColorValue'])) {
            $this->minColorValue = $params['minColorValue'];
        }
        if (!empty($params['maxColorValue'])) {
            $this->minColorValue = $params['maxColorValue'];
        }
    }

    /**
     * Новый цвет, отсутствующий в коллекции
     *
     * @return string
     */
    public function getRandomColor(): string
    {
        for ($i = 0; $i <= 100; $i++) {
            $color = $this->randomColorPart() . $this->randomColorPart() . $this->randomColorPart();

            if (!$this->checkColorExists($color)) {
                array_push($this->colors, $color);
                return '#' . $color;
            }
        }

        return static::BLACK;
    }

    /**
     * Генерировать часть цвета
     *
     * @return string
     */
    private function randomColorPart(): string
    {
        return str_pad(dechex(mt_rand($this->minColorValue, $this->maxColorValue)), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Возвращает расстояние между двумя цветами
     *
     * @param  string $color
     * @param  string $newColor
     * @return float
     */
    private function getDistanceFromColor($color, $newColor): float
    {
        list($r1, $g1, $b1) = $this->getRGBPart($color);
        list($r2, $g2, $b2) = $this->getRGBPart($newColor);

        return sqrt(pow($r2 - $r1, 2) + pow($g2 - $g1, 2) + pow($b2 - $b1, 2));
    }

    /**
     * Проверяет что в массиве нет похожего цвета
     *
     * @param  string $newColor
     * @return bool
     */
    private function checkColorExists(string $newColor): bool
    {
        foreach ($this->colors as $color) {
            if ($this->getDistanceFromColor($color, $newColor) < static::COLOR_DIFFERENCE) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  string $color
     * @return array
     */
    private function getRGBPart(string $color): array
    {
        $colorVal = hexdec($color);
        $r = 0xFF & ($colorVal >> 0x10);
        $g = 0xFF & ($colorVal >> 0x8);
        $b = 0xFF & $colorVal;

        return [$r, $g, $b];
    }
}
