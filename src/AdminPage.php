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
use function esc_attr;
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
        add_action('admin_init', [$this, 'addFields']);
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

    public function addFields(): void
    {
        add_settings_section(
            self::SECTION_ID,
            __('Module Toggles', 'szv-test-mode'), // title
            [$this, 'renderSectionDescription'],
            self::PAGE_SLUG
        );

        foreach (ModuleLoader::getInstances() as $instance) {
            $moduleSlug = $instance->getSlug();
            $fieldId = self::OPTION_PREFIX.$moduleSlug.'-button';
            $optionName = self::OPTION_PREFIX.$moduleSlug;

            register_setting(
                self::OPTION_GROUP,
                $optionName,
                [
                    'default' => ModuleLoader::MODE_TESTMODE,
                    'sanitize_callback' => 'sanitize_key',
                ]
            );
            add_settings_field(
                $fieldId,
                $instance->getName(), // title
                [$this, 'renderInputFields'],
                self::PAGE_SLUG,
                self::SECTION_ID,
                [
                    'option_name' => $optionName,
                    'html_label_text' => $instance->getLabel(),
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
            esc_html__('Select mode for each module.', 'szv-test-mode')
        );
    }

    public function renderInputFields($args): void
    {
        echo '<fieldset>';
        foreach ($this->getModes() as $mode => $emoji) {
            printf(
                '<label title="%s" style="font-size: 2rem;"><input type="radio" name="%s" value="%s" style="margin-left: 24px;" %s>%s</label>',
                esc_attr($mode),
                esc_attr($args['option_name']),
                esc_attr($mode),
                checked(get_option($args['option_name']), $mode, false),
                $emoji
            );
        }
        echo '</fieldset>'.esc_html($args['html_label_text']);
    }

    protected function getModes()
    {
        return [
            ModuleLoader::MODE_NOCHANGE => '&#x2796;',
            ModuleLoader::MODE_TESTMODE => '&#x1F6A7;',
            ModuleLoader::MODE_DISABLED => '&#x1F6AB;',
        ];
    }
}
