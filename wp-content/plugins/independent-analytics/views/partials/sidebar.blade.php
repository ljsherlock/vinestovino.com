@php /** @var \IAWP\Report|null $favorite_report */ @endphp
@php /** @var \IAWP\Report_Finder $report_finder */ @endphp
@php /** @var bool $is_white_labeled */ @endphp
@php /** @var bool $can_edit_settings */ @endphp
@php /** @var bool $is_dark_mode */ @endphp

<div id="iawp-layout-sidebar" class="iawp-layout-sidebar">
    <div class="inner">
        @if(!$is_white_labeled)
            <div class="logo">
                <img class="full-logo" src="{{$is_dark_mode ? iawp_url_to('img/logo-white.png') : iawp_url_to('img/logo.png')}}" data-testid="logo"/>
                <img class="favicon" src="{{iawp_url_to('img/favicon.png')}}"
                     data-testid="favicon"/>
            </div>
        @endif
        @if($env->is_free() && !$is_white_labeled)
            <div class="pro-ad">
                <a href="https://independentwp.com/pricing/?utm_source=User+Dashboard&utm_medium=WP+Admin&utm_campaign=Upgrade+to+Pro&utm_content=Sidebar"
                target="_blank">
                    <span class="upgrade-text">{{ __('Upgrade to Pro (45% off)', 'independent-analytics') }}</span>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                </a>
            </div>
        @endif
        <div class="collapse-container">
            <button id="collapse-sidebar" class="collapse-sidebar iawp-text-button" data-testid="collapse-button"><span
                        class="dashicons dashicons-admin-collapse"></span><span
                        class="text">{{__('Collapse sidebar', 'independent-analytics')}}</span>
            </button>
            <span class="collapsed-label">{{__('Expand sidebar', 'independent-analytics')}}</span>
        </div>
        <div class="mobile-menu">
            <button id="mobile-menu-toggle" class="mobile-menu-toggle iawp-button ghost-purple">
                <span class="dashicons dashicons-menu"></span> <span class="text"><?php
                    esc_html_e('Open menu', 'independent-analytics'); ?>
                </span>
            </button>
        </div>
        <div id="menu-container" class="menu-container">
            <div class="reports-list">
                <?php
                // REAL-TIME
                if ($env->is_pro()) {
                    echo iawp_blade()->run('partials.sidebar-menu-section', [
                        'favorite_report'   => $favorite_report,
                        'can_edit_settings' => $can_edit_settings,
                        'current'           => $report_finder->is_real_time(),
                        'report_name'       => esc_html__('Real-time', 'independent-analytics'),
                        'slug'              => 'real-time',
                        'reports'           => null,
                        'collapsed_label'   => esc_html__('Open Real-time analytics', 'independent-analytics'),
                        'has_reports'       => false,
                        'url'               => iawp_dashboard_url(['tab' => 'real-time']),
                        'external'          => false,
                        'upgrade'           => false
                    ]);
                }
                // PAGES
                echo iawp_blade()->run('partials.sidebar-menu-section', [
                    'favorite_report'   => $favorite_report,
                    'can_edit_settings' => $can_edit_settings,
                    'current'           => $report_finder->is_page_report(),
                    'report_name'       => esc_html__('Pages', 'independent-analytics'),
                    'slug'              => 'views',
                    'reports'           => $report_finder->fetch_page_reports(),
                    'collapsed_label'   => '',
                    'has_reports'       => true,
                    'url'               => iawp_dashboard_url(['tab' => 'views']),
                    'external'          => false,
                    'upgrade'           => false
                ]);
                // REFERRERS
                echo iawp_blade()->run('partials.sidebar-menu-section', [
                    'favorite_report'   => $favorite_report,
                    'can_edit_settings' => $can_edit_settings,
                    'current'           => $report_finder->is_referrer_report(),
                    'report_name'       => esc_html__('Referrers', 'independent-analytics'),
                    'slug'              => 'referrers',
                    'reports'           => $report_finder->fetch_referrer_reports(),
                    'collapsed_label'   => '',
                    'has_reports'       => true,
                    'url'               => iawp_dashboard_url(['tab' => 'referrers']),
                    'external'          => false,
                    'upgrade'           => false
                ]);
                // GEOGRAPHIC
                echo iawp_blade()->run('partials.sidebar-menu-section', [
                    'favorite_report'   => $favorite_report,
                    'can_edit_settings' => $can_edit_settings,
                    'current'           => $report_finder->is_geographic_report(),
                    'report_name'       => esc_html__('Geographic', 'independent-analytics'),
                    'slug'              => 'geo',
                    'reports'           => $report_finder->fetch_geographic_reports(),
                    'collapsed_label'   => '',
                    'has_reports'       => true,
                    'url'               => iawp_dashboard_url(['tab' => 'geo']),
                    'external'          => false,
                    'upgrade'           => false
                ]);
                // DEVICES
                echo iawp_blade()->run('partials.sidebar-menu-section', [
                    'favorite_report'   => $favorite_report,
                    'can_edit_settings' => $can_edit_settings,
                    'current'           => $report_finder->is_device_report(),
                    'report_name'       => esc_html__('Devices', 'independent-analytics'),
                    'slug'              => 'devices',
                    'reports'           => $report_finder->fetch_device_reports(),
                    'collapsed_label'   => '',
                    'has_reports'       => true,
                    'url'               => iawp_dashboard_url(['tab' => 'devices']),
                    'external'          => false,
                    'upgrade'           => false
                ]);
                // CAMPAIGNS
                if ($env->is_pro()) {
                    echo iawp_blade()->run('partials.sidebar-menu-section', [
                        'favorite_report'   => $favorite_report,
                        'can_edit_settings' => $can_edit_settings,
                        'current'           => $report_finder->is_campaign_report(),
                        'report_name'       => esc_html__('Campaigns', 'independent-analytics'),
                        'slug'              => 'campaigns',
                        'reports'           => $report_finder->fetch_campaign_reports(),
                        'collapsed_label'   => '',
                        'has_reports'       => true,
                        'url'               => iawp_dashboard_url(['tab' => 'campaigns']),
                        'external'          => false,
                        'upgrade'           => false
                    ]);
                    // CAMPAIGN BUILDER
                    echo iawp_blade()->run('partials.sidebar-menu-section', [
                        'favorite_report'   => $favorite_report,
                        'can_edit_settings' => $can_edit_settings,
                        'current'           => $report_finder->is_campaign_builder_page(),
                        'report_name'       => esc_html__('Campaign Builder', 'independent-analytics'),
                        'slug'              => 'campaign-builder',
                        'reports'           => null,
                        'collapsed_label'   => esc_html__('Open Campaign Builder', 'independent-analytics'),
                        'has_reports'       => false,
                        'url'               => iawp_dashboard_url(['page' => 'independent-analytics-campaign-builder']),
                        'external'          => false,
                        'upgrade'           => false
                    ]);
                }
                ?>
                @if($can_edit_settings)
                <?php
                // SETTINGS
                echo iawp_blade()->run('partials.sidebar-menu-section', [
                    'favorite_report'   => $favorite_report,
                    'can_edit_settings' => $can_edit_settings,
                    'current'           => $report_finder->is_settings_page(),
                    'report_name'       => esc_html__('Settings', 'independent-analytics'),
                    'slug'              => 'settings',
                    'reports'           => null,
                    'collapsed_label'   => esc_html__('Open Settings', 'independent-analytics'),
                    'has_reports'       => false,
                    'url'               => iawp_dashboard_url(['page' => 'independent-analytics-settings']),
                    'external'          => false,
                    'upgrade'           => false
                ]);
                ?>
                @endif
                @if(!$is_white_labeled)
                <?php
                // KNOWLEDGEBASE
                echo iawp_blade()->run('partials.sidebar-menu-section', [
                    'favorite_report'   => $favorite_report,
                    'can_edit_settings' => $can_edit_settings,
                    'current'           => false,
                    'report_name'       => esc_html__('Knowledgebase', 'independent-analytics'),
                    'slug'              => 'knowledgebase',
                    'reports'           => null,
                    'collapsed_label'   => esc_html__('Visit Knowledgebase', 'independent-analytics'),
                    'has_reports'       => false,
                    'url'               => 'https://independentwp.com/knowledgebase/',
                    'external'          => true,
                    'upgrade'           => false
                ]);
                // LEAVE US A REVIEW
                echo iawp_blade()->run('partials.sidebar-menu-section', [
                    'favorite_report'   => $favorite_report,
                    'can_edit_settings' => $can_edit_settings,
                    'current'           => false,
                    'report_name'       => esc_html__('Leave us a review', 'independent-analytics'),
                    'slug'              => 'reviews',
                    'reports'           => null,
                    'collapsed_label'   => esc_html__('Leave us a review', 'independent-analytics'),
                    'has_reports'       => false,
                    'url'               => 'https://wordpress.org/support/plugin/independent-analytics/reviews/',
                    'external'          => true,
                    'upgrade'           => false
                ]);
                ?>
                @endif
                @if($env->is_free() && ! $env->is_white_labeled())
                <?php
                    // CAMPAIGNS UPGRADE
                    echo iawp_blade()->run('partials.sidebar-menu-section', [
                        'favorite_report'   => $favorite_report,
                        'can_edit_settings' => $can_edit_settings,
                        'current'           => false,
                        'report_name'       => esc_html__('Campaigns', 'independent-analytics'),
                        'slug'              => 'campaigns',
                        'reports'           => null,
                        'collapsed_label'   => esc_html__('Get Campaigns', 'independent-analytics'),
                        'has_reports'       => false,
                        'url'               => 'https://independentwp.com/features/campaigns/?utm_source=User+Dashboard&utm_medium=WP+Admin&utm_campaign=Campaigns+menu+item&utm_content=Sidebar',
                        'external'          => true,
                        'upgrade'           => true
                    ]);
                // REAL-TIME UPGRADE
                echo iawp_blade()->run('partials.sidebar-menu-section', [
                    'favorite_report'   => $favorite_report,
                    'can_edit_settings' => $can_edit_settings,
                    'current'           => false,
                    'report_name'       => esc_html__('Real-Time', 'independent-analytics'),
                    'slug'              => 'real-time-free',
                    'reports'           => null,
                    'collapsed_label'   => esc_html__('Get Real-time analytics', 'independent-analytics'),
                    'has_reports'       => false,
                    'url'               => 'https://independentwp.com/features/real-time/?utm_source=User+Dashboard&utm_medium=WP+Admin&utm_campaign=Real-time+menu+item&utm_content=Sidebar',
                    'external'          => true,
                    'upgrade'           => true
                ]);
                ?>
                @endif
                @if(!$is_white_labeled)
                <?php
                // CHANGELOG UPDATES
                // https://api.wordpress.org/plugins/info/1.0/independent-analytics.json
                $version_history    = ['2.2.0', '2.1.0']; // Newest first. Oldest last.
                $last_update_viewed = get_option('iawp_last_update_viewed', '0');
                $notification_html  = '';
                $unseen_versions    = array_filter($version_history, function ($version) use ($last_update_viewed) {
                    return version_compare($last_update_viewed, $version, '<');
                });

                if (count($unseen_versions) > 0) {
                    $notification_html = '<span class="notification">' . count($unseen_versions) . '</span>';
                }

                $report_name = sprintf(_x("What's new in %s", 'Plugin version e.g. 2.1', 'independent-analytics'), $version_history[0]) . $notification_html;
                echo iawp_blade()->run('partials.sidebar-menu-section', [
                    'favorite_report'   => $favorite_report,
                    'can_edit_settings' => $can_edit_settings,
                    'current'           => false,
                    'report_name'       => $report_name,
                    'slug'              => 'updates',
                    'reports'           => null,
                    'collapsed_label'   => $report_name,
                    'has_reports'       => false,
                    'url'               => '#',
                    'external'          => false,
                    'upgrade'           => false
                ]);
                ?>
                @endif
            </div>
        </div>
    </div>
</div>
<?php echo iawp_blade()->run('partials.updates'); ?>