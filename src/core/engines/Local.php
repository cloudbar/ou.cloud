<?php

/**
 * Cloud本地环境引擎
 *
 * @package application.core.engines
 * @author oshine <oyjqdlp@126.com>
 * @version $Id:
 */

namespace cloud\core\engines;

use cloud\core\engines\local\LocalIo;
use yii\helpers\ArrayHelper;

class Local extends Engine {

    /**
     * 本地引擎初始化配置方法
     */
    protected function init() {
        // 本地环境使用安装时配置的数据库信息
        $localConfig = require_once PATH_DATA."/config/local.php";

        $databases = isset($localConfig["databases"]) && is_array($localConfig["databases"])?$localConfig["databases"]:array();

        $components = array();
        foreach($databases as $key => $value){
            $connectionString = "mysql:host={$value['host']};port={$value['port']};dbname={$value['dbname']}";
            $components[$key] = array(
                'class' => '\yii\db\Connection',
                'connectionString' => $connectionString,
                'username' => $value['username'],
                'password' => $value['password'],
                'tablePrefix' => $value['tableprefix'],
                'charset' => $value['charset']
            );
        }
        $config = array(
            'runtimePath' => PATH_ROOT . DIRECTORY_SEPARATOR . 'data/runtime',
            'language' => $localConfig['env']['language'],
            'theme' => $localConfig['env']['theme'],
            'components' => $components
        );
        unset($localConfig['databases']);
        unset($localConfig['env']);
        unset($localConfig['runtimePath']);
        unset($localConfig['language']);
        unset($localConfig['theme']);

      //  Cloud::setPathOfAlias( 'data', PATH_ROOT . DIRECTORY_SEPARATOR . 'data' );
        // 设置引擎驱动别名
      //  Cloud::setPathOfAlias( 'engineDriver', Cloud::getPathOfAlias( 'cloud.core.engines.local' ) );

        $this->setConfig(ArrayHelper::merge($config,$localConfig));
    }

    /**
     * 获取 IO 接口
     * @staticvar null $io
     * @return \cloud\core\engines\local\LocalIo
     */
    public function io() {
        static $io = null;
        if ( $io == null ) {
            $io = new LocalIo();
        }
        return $io;
    }

}