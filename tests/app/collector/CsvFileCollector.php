<?php


namespace app\collector;

use elfuvo\postman\collector\AbstractCollector;

/**
 * Class CsvFileCollector
 * @package app\collectors
 */
class CsvFileCollector extends AbstractCollector
{
    /**
     * @return array|null
     */
    public function getRecipients(): ?array
    {
        $file = \Yii::getAlias('@root/tests/_data/recipients.csv');
        if (file_exists($file)) {
            $fh = fopen($file, 'r');
            $data = fgetcsv($fh, null, ',');
            if ($data) {

            }
            fclose($fh);
        }

        return null;
    }
}
