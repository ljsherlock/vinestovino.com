<?php

namespace IAWP_SCOPED\IAWP\Admin_Page;

use IAWP_SCOPED\IAWP\Campaign_Builder;
/** @internal */
class Campaign_Builder_Page extends Admin_Page
{
    protected function render_page()
    {
        (new Campaign_Builder())->render_campaign_builder();
    }
}
