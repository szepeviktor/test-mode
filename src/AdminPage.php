<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode;

use SzepeViktor\TestMode\Support\Strings;

use function __;
use function add_action;
use function add_options_page;
use function add_settings_field;
use function add_settings_section;
use function checked;
use function do_settings_sections;
use function esc_html__;
use function esc_textarea;
use function get_option;
use function register_setting;
use function sanitize_title;
use function settings_fields;
use function submit_button;

class AdminPage
{
    const OPTION_PREFIX = 'test-mode-';
    const MENU_SLUG = 'test_mode';
    const PAGE_SLUG = 'test-mode-page';
    const SECTION_ID = 'test-mode-section';
    const OPTION_GROUP = 'test_mode_modules';

    public function boot(): void
    {
        add_action('admin_menu', [$this, 'addSettingsPage']);
        add_action('admin_init', [$this, 'settings_init']);
    }

    public function addSettingsPage(): void
    {
        $hook_suffix = add_options_page(
            __('Test Mode', 'szv-test-mode'), // page_title
            __('Test Mode', 'szv-test-mode'), // menu_title
            'manage_options', // capability
            self::MENU_SLUG,
            [$this, 'renderSettingsPage']
        );
    }

    public function settings_init(): void
    {
        add_settings_section(
            self::SECTION_ID,
            __('Module Toggles', 'szv-test-mode'), // title
            [$this, 'renderSectionDescription'],
            self::PAGE_SLUG
        );

        foreach (ModuleLoader::getInstances() as $instance) {
            $moduleName = $instance->getName();
            $moduleSlug = sanitize_title($moduleName);
            $label = $instance->getLabel();

            $optionName = 'test-mode-'.$moduleSlug;
            $fieldId = 'test-mode-'.$moduleSlug.'-checkbox';

            register_setting(
                self::OPTION_GROUP,
                $optionName,
                [
                    'default' => 'testmode',
                    'sanitize_callback' => 'sanitize_key',
                ]
            );
            add_settings_field(
                $fieldId,
                $moduleName, // title
                [$this, 'renderCheckboxField'],
                self::PAGE_SLUG,
                self::SECTION_ID,
                [
                    'option_name' => $optionName,
                    'html_id' => $fieldId,
                    'html_label_text' => $label,
                ]
            );
        }
    }

    public function renderSettingsPage(): void
    {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Test Mode Dashboard', 'szv-test-mode'); ?></h1>
            <form action="options.php" method="POST">
            <?php
                // Title
                settings_fields(self::OPTION_GROUP);
                // Input fields
                do_settings_sections(self::PAGE_SLUG);
                // Submit button
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    public function renderSectionDescription(): void
    {
        printf(
            '<p>%s</p>',
            esc_html__('Select mode for each Test Mode module.', 'szv-test-mode')
        );
    }

    public function renderCheckboxField($args): void
    {
        $modes = [
            'no-change' => '&#x2796;',
            'testmode' => '&#x1F6A7;',
            'disabled' => '&#x1F6AB;',
        ];

        echo '<fieldset>';
        foreach ($modes as $mode => $emoji) {
            printf(
                '<label title="%s" style="font-size: 2rem;"><input type="radio" name="%s" value="%s" style="margin-left: 24px;" %s>%s</label>',
                $mode,
                $args['option_name'],
                $mode,
                checked(get_option($args['option_name']), $mode, false),
                $emoji
            );
        }
        echo '</fieldset>'.esc_html($args['html_label_text']);
/*
        printf(
            '<label for="%s"><input type="checkbox" name="%s" id="%s" value="1" %s>&nbsp;%s</label>',
            $args['html_id'],
            $args['option_name'],
            $args['html_id'],
            checked(get_option($args['option_name']), '1', false),
            esc_html($args['html_label_text'])
        );
*/
    }
}
