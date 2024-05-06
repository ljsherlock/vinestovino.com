<?php

namespace IAWP_SCOPED\IAWP\Migrations;

use IAWP_SCOPED\IAWP\Query;
/** @internal */
class Migration_27 extends Step_Migration
{
    /**
     * @return int
     */
    protected function database_version() : int
    {
        return 27;
    }
    /**
     * @return array
     */
    protected function queries() : array
    {
        return [$this->add_virtual_page_id()];
    }
    private function add_virtual_page_id() : string
    {
        $resources_table = Query::get_table_name(Query::RESOURCES);
        return "\n            ALTER TABLE {$resources_table} ADD COLUMN virtual_page_id VARCHAR(32) AFTER author_id;\n        ";
    }
}
