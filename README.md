# Test Mode

Core, plugins and theme go into Test Mode.

There are three possible modes for each module.

1. no change ✅
2. test mode 🚧
3. disabled 🚫

## Available modules

- `WP Environment`: sets `WP_ENVIRONMENT_TYPE` to `staging` in test mode, or `local` when disabled.
- `Disallow robots`: sets `blog_public` to `0` in both test mode and disabled mode.
- `Cron events`: stops cron requests in both test mode and disabled mode.
- `Mail`: redirects all outgoing mail to the site admin in test mode, or disables mail sending entirely.
- `MakeCommerce payment gateway`: uses the sandbox in test mode, or disables its payment and shipping methods entirely.

## Add your own module

Implement
[`Module`](https://github.com/szepeviktor/test-mode/blob/master/src/Modules/Module.php)
interface.

```php
add_filter(
    'plugins_loaded',
    static function () {
        add_filter(
            'szepeviktor/test-mode/modules',
            static function (array $modules): array {
                $modules[] = \My\TestMode\Module::class;
                return $modules;
            },
            10,
            1
        );
    },
    10, // Before 11!
    0
);
```

## Debug WordPress

See https://github.com/szepeviktor/debug-wordpress
