<?php

namespace IAWP_SCOPED\IAWP\Admin_Page;

use IAWP_SCOPED\IAWP\Capability_Manager;
use IAWP_SCOPED\IAWP\Dashboard_Options;
use IAWP_SCOPED\IAWP\Env;
use IAWP_SCOPED\IAWP\Migrations\Migrations;
use IAWP_SCOPED\IAWP\Report_Finder;
/** @internal */
abstract class Admin_Page
{
    protected abstract function render_page();
    public function render() : void
    {
        if (!Capability_Manager::can_view()) {
            return;
        }
        if (Migrations::is_migrating()) {
            echo \IAWP_SCOPED\iawp_blade()->run('interrupt.migration-is-running');
            return;
        }
        $options = new Dashboard_Options();
        $tab = (new Env())->get_tab();
        ?>
        
        <div id="iawp-parent" class="iawp-parent <?php 
        \esc_attr_e($tab);
        ?>">
            <div id="iawp-layout" class="iawp-layout <?php 
        echo $options->is_sidebar_collapsed() ? 'collapsed' : '';
        ?>">

                <?php 
        echo \IAWP_SCOPED\iawp_blade()->run('partials.sidebar', ['favorite_report' => Report_Finder::get_favorite(), 'report_finder' => new Report_Finder(), 'is_white_labeled' => Capability_Manager::white_labeled(), 'can_edit_settings' => Capability_Manager::can_edit(), 'is_dark_mode' => \get_option('iawp_dark_mode')]);
        ?>

                <div class="iawp-layout-main">
                    <div class="iawp-tab-content">
                        <div id="iawp-dashboard" class="iawp-dashboard">
                            <?php 
        $this->render_page();
        ?>
                        </div>
                    </div>
                    <div id="loading-icon" class="loading-icon">
                        <img src="<?php 
        echo \esc_url(\IAWP_SCOPED\iawp_url_to('img/loading.svg'));
        ?>" />
                    </div>
                    <button id="scroll-to-top" class="scroll-to-top"><span class="dashicons dashicons-arrow-up-alt"></span></button>
                </div>
            </div>
        </div>

        <?php 
    }
}
