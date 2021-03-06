<?php

/**
 * @author oshine <oyjqdlp@126.com>
 * @link https://github.com/ouyangjunqiu/ou.cloud
 * @see https://github.com/ouyangjunqiu/ou.cloud
 */
namespace cloud\core\components;

use yii\base\Component;
use yii\helpers\ArrayHelper;

class Setting extends Component {

    private $_G = array();

    public function toArray() {
        return $this->_G;
    }

    public function copyFrom( $setting ) {
        $this->_G = $setting;
    }

    public function mergeWith( $value ) {
        $this->_G = ArrayHelper::merge( $this->_G, $value );
    }

    /**
     * 获取一个配置项里的值
     * @param string $key 斜杠分割的字符串,例如'setting/user/userNumber'
     * @return mixed 没有找到返回null
     */
    public function get( $key ) {
        $keyArr = explode( '/', $key );
        $setting = $this->toArray();
        foreach ( $keyArr as $keyPart ) {
            if ( !isset( $setting[$keyPart] ) ) {
                return null;
            }
            $setting = &$setting[$keyPart];
        }
        return $setting;
    }

    /**
     * 设置全局数组里的值，注意，这个可能比较消耗性能
     * @param string $key 斜杠分割的字符串,例如'setting/user/userNumber'
     * @param mixed $value 要设置的值
     * @return boolean
     */
    public function set( $key, $value ) {
        $setting = $this->toArray();
        $key = explode( '/', $key );
        $p = &$setting;
        foreach ( $key as $k ) {
            if ( !isset( $p[$k] ) || !is_array( $p[$k] ) ) {
                $p[$k] = array();
            }
            $p = &$p[$k];
        }
        $p = $value;
        $this->copyFrom( $setting );
        return true;
    }

}
