<?php

class migration_1_1_1 implements \SmartcatSupport\util\Migration {

    function version () {
        return '1.1.1';
    }

    /**
     *
     * @return bool|WP_Error
     */
    function migrate () {
        return true;
    }

}

return new migration_1_1_1();
