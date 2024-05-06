<?php

namespace IAWP_SCOPED\IAWP\Admin_Page;

use IAWP_SCOPED\IAWP\Capability_Manager;
/** @internal */
class Settings_Page extends Admin_Page
{
    protected function render_page()
    {
        if (Capability_Manager::can_edit()) {
            \IAWP_SCOPED\iawp()->settings->render_settings();
        } else {
            echo '<p class="permission-blocked">' . \esc_html__('You do not have permission to edit the settings.', 'independent-analytics') . '</p>';
        }
    }
}
