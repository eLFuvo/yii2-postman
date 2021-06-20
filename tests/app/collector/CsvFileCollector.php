<?php


namespace app\collector;

use elfuvo\postman\collector\AbstractCollector;
use elfuvo\postman\models\Recipient;
use Yii;

/**
 * Class CsvFileCollector
 * @package app\collector
 */
class CsvFileCollector extends AbstractCollector
{
    /**
     * @var array
     */
    protected $wrongRecipients = [];

    /**
     * @return array|null
     */
    public function getRecipients(): ?array
    {
        $file = dirname(dirname(__DIR__)) . '/_data/recipients.csv';
        $emails = [];
        if (is_file($file)) {
            $fh = fopen($file, 'r');
            $index = 0;
            while ($row = fgetcsv($fh)) {
                if (count($row) > 0) {
                    $recipient = new Recipient(['email' => trim($row[0]), 'name' => trim($row[1])]);
                    if ($recipient->validate()) {
                        array_push($emails, $recipient);
                    } else {
                        array_push($this->wrongRecipients, 'row: ' . $index . ' - "' . $recipient->email . '"');
                    }
                } else {
                    array_push($this->wrongRecipients, 'row: ' . $index . ' - no data');
                }
                $index++;
            }
            fclose($fh);
        } else {
            Yii::error('file "' . $file . '" does not exists');
        }

        return $emails;
    }

    /**
     * @return array|null
     */
    public function getWrongRecipients(): ?array
    {
        return $this->wrongRecipients;
    }
}
