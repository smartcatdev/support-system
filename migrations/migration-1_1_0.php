<?php

class migration_1_1_0 implements \SmartcatSupport\util\Migration {

    function version () {
        return '1.1.0';
    }

    function migrate () {


        return true;
    }

}

return new migration_1_1_0();
