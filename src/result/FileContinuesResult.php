<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\result;

use Yii;
use yii\helpers\FileHelper;

/**
 * Class ResultFileContinues
 * @package app\modules\auto\components\postman
 */
class FileContinuesResult extends AbstractResult
{
    protected const FILE_PREFIX = 'result_';

    /**
     * @var string
     */
    public $pointerPath = '@runtime/postman';

    /**
     * @param array $list
     */
    public function setBatch($list): void
    {
        $this->batch = $list;
        $path = Yii::getAlias($this->pointerPath);
        if (!is_dir($path)) {
            FileHelper::createDirectory($path);
        }

        $fh = fopen($this->getLogName(), 'wb');
        fwrite(
            $fh,
            serialize(get_object_vars($this))
        );
        fclose($fh);
    }

    /**
     * @return array|null
     */
    public function getLastBatch()
    {
        if (file_exists($this->getLogName())) {
            $contents = file_get_contents($this->getLogName());
            $stat = unserialize($contents);
            foreach ($stat as $field => $value) {
                if (property_exists($this, $field)) {
                    $this->{$field} = $value;
                }
            }
            // free some memory
            $contents = null;
            $stat = null;
        }

        return $this->batch;
    }

    /**
     * @return bool
     */
    public function resetBatch(): bool
    {
        parent::resetBatch();

        if (file_exists($this->getLogName())) {
            @unlink($this->getLogName());
        }

        return true;
    }

    /**
     * @return string
     */
    protected function getLogName()
    {
        $path = Yii::getAlias($this->pointerPath);

        return $path . '/' . self::FILE_PREFIX . $this->key . '.log';
    }
}
