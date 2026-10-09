<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use InvalidArgumentException;
use pocketmine\form\Form as IForm;
use pocketmine\player\Player;

abstract class Form implements IForm{

    /** @var array<string, mixed> */
    protected array $data = [];
    /** @var callable|null */
    private $callable;

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        $this->callable = $callable;
    }

    /**
     * @param Player $player
     * @throws InvalidArgumentException
     * @deprecated
     * @see Player::sendForm()
     */
    public function sendToPlayer(Player $player) : void {
        $player->sendForm($this);
    }

    public function getCallable() : ?callable {
        return $this->callable;
    }

    public function setCallable(?callable $callable) : void {
        $this->callable = $callable;
    }

    public function handleResponse(Player $player, mixed $data) : void {
        $this->processData($data);
        $callable = $this->getCallable();
        if($callable !== null) {
            $callable($player, $data);
        }
    }

    public function processData(mixed &$data) : void {
    }

    /** @return array<string, mixed> */
    public function jsonSerialize() : array {
        return $this->data;
    }
}
