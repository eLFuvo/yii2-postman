<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\result;

use Yii;
use yii\caching\TagDependency;

/**
 * Class CacheContinuesResult
 * @package elfuvo\postman\result
 */
class CacheContinuesResult extends AbstractResult
{
    protected const CACHE_DURATION = 3600;
    protected const CACHE_KEY = 'postman';

    /**
     * @param array $list
     */
    public function setBatch($list): void
    {
        $key = [
            self::CACHE_KEY,
            $this->key,
        ];

        $dependency = new TagDependency([
            'tags' => [
                self::CACHE_KEY,
                $this->key,
            ]
        ]);

        Yii::$app->cache->set(
            $key,
            get_object_vars($this),
            self::CACHE_DURATION,
            $dependency
        );
    }

    /**
     * @return array|null
     */
    public function getLastBatch()
    {
        $key = [
            self::CACHE_KEY,
            $this->key,
        ];

        if (($stat = Yii::$app->cache->get($key)) !== false) {
            foreach ($stat as $field => $value) {
                if (property_exists($this, $field)) {
                    $this->{$field} = $value;
                }
            }
            // free some memory
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

        TagDependency::invalidate(Yii::$app->cache, [$this->key]);

        return true;
    }
}
