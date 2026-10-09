# FormAPI

FormAPI provides simple, modal, and custom forms for Axolotl-PM plugins. The forms validate client responses before invoking the plugin callback.

## Compatibility

Version 3 targets the Axolotl-PM 5 API and requires PHP 8.1 or newer. The manifest declares API `5.0.0`. Compatibility testing uses a pinned server version in [Build](https://github.com/NhanAZ-Libraries/FormAPI/actions/workflows/build.yml). Other Axolotl-PM 5 releases have not all been tested.

## Installation

Install the released PHAR in the server's `plugins` directory when another plugin declares `FormAPI` as a dependency. Plugin authors can also include the `libFormAPI` virion or load the library with Composer. When embedding the virion, use this repository's `virion.yml` and version 3 rather than the upstream `jojoe77777/FormAPI/libFormAPI` package, which has a separate release history.

## Usage

Create a form and pass a callback that receives the player and validated response. A closed simple or custom form returns `null`. A closed modal form also returns `null`.

```php
use jojoe77777\FormAPI\SimpleForm;

$form = new SimpleForm(static function($player, $choice) : void {
    if($choice === "confirm") {
        $player->sendMessage("Confirmed");
    }
});
$form->setTitle("Confirm")->addButton("Yes", label: "confirm");
$player->sendForm($form);
```

`CustomForm` maps responses to the labels supplied by `addToggle()`, `addSlider()`, `addStepSlider()`, `addDropdown()`, and `addInput()`. Invalid response types or indices throw a form validation exception. `FormAPI` still exposes its deprecated factory methods for older consumers.

## Build and development

The [Build workflow](https://github.com/NhanAZ-Libraries/FormAPI/actions/workflows/build.yml) runs PHPStan at level max, response tests, and the pinned [DevTools](https://github.com/NhanAZ/DevTools) build. The release PHAR is verified before publication. Source users can run `composer install`, `composer phpstan`, and `composer test` with the pinned development dependencies.

## Credits and license

This fork retains the `jojoe77777\FormAPI` namespace and upstream attribution. The original project is [jojoe77777/FormAPI](https://github.com/jojoe77777/FormAPI). The unchanged license text is in [LICENSE](LICENSE). Composer uses the current SPDX identifier `LGPL-3.0-only` for the original `LGPL-3.0` declaration. Upstream source changes through commit `ad0c9631b12fc37e8d9c39436d639058046b9dab` were merged before this Axolotl-PM 5 maintenance release.
