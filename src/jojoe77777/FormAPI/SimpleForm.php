<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use pocketmine\form\FormValidationException;

class SimpleForm extends Form {

    const IMAGE_TYPE_PATH = 0;
    const IMAGE_TYPE_URL = 1;

    /** @var string */
    private string $content = "";

    /** @var list<string|int> */
    private array $labelMap = [];

    /** @var list<array{text: string, image?: array{type: string, data: string}}> */
    private array $buttons = [];

    private string $title = "";

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        parent::__construct($callable);
        $this->data["type"] = "form";
        $this->data["title"] = "";
        $this->data["content"] = $this->content;
        $this->data["buttons"] = [];
    }

    public function processData(mixed &$data) : void {
        if($data !== null) {
            if(!is_int($data)) {
                throw new FormValidationException("Expected an integer response, got " . gettype($data));
            }
            $count = count($this->buttons);
            if($data >= $count || $data < 0) {
                throw new FormValidationException("Button $data does not exist");
            }
            $data = $this->labelMap[$data] ?? null;
        }
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title) : self {
        $this->title = $title;
        $this->data["title"] = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() : string {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent() : string {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content) : self {
        $this->content = $content;
        $this->data["content"] = $content;
        return $this;
    }

    /**
     * @param string $text
     * @param int $imageType
     * @param string $imagePath
     * @param string|null $label
     * @return $this
     */
    public function addButton(string $text, int $imageType = -1, string $imagePath = "", ?string $label = null) : self {
        $content = ["text" => $text];
        if($imageType !== -1) {
            $content["image"] = ["type" => $imageType === self::IMAGE_TYPE_PATH ? "path" : "url", "data" => $imagePath];
        }
        $this->buttons[] = $content;
        $this->data["buttons"] = $this->buttons;
        $this->labelMap[] = $label ?? count($this->labelMap);
        return $this;
    }

}
