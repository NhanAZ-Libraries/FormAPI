<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use pocketmine\plugin\PluginBase;

class FormAPI extends PluginBase{

    /** @deprecated Use new CustomForm($callback). */
    public function createCustomForm(?callable $function = null) : CustomForm {
        return new CustomForm($function);
    }

    /** @deprecated Use new SimpleForm($callback). */
    public function createSimpleForm(?callable $function = null) : SimpleForm {
        return new SimpleForm($function);
    }

    /** @deprecated Use new ModalForm($callback). */
    public function createModalForm(?callable $function = null) : ModalForm {
        return new ModalForm($function);
    }
}
