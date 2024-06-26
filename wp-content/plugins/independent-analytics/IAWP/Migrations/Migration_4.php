<?php

namespace IAWP_SCOPED\IAWP\Migrations;

use IAWP_SCOPED\IAWP\Known_Referrers;
/** @internal */
class Migration_4 extends Migration
{
    /**
     * @var string
     */
    protected $database_version = '4';
    /**
     * @return void
     */
    protected function migrate() : void
    {
        Known_Referrers::replace_known_referrers_table();
    }
}
