# Test Mode

Core, plugins and theme go into Test Mode.

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
