<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\form\FormValidationException;

function expectInvalid(callable $callback) : void{
    try{
        $callback();
    }catch(FormValidationException){
        return;
    }
    throw new RuntimeException('Expected FormValidationException');
}

$simple = (new SimpleForm(null))->setTitle('Question')->setContent('Choose')->addButton('Yes', label: 'yes')->addButton('No', label: 'no');
$simpleData = $simple->jsonSerialize();
if($simple->getTitle() !== 'Question' || $simple->getContent() !== 'Choose' || !is_array($simpleData['buttons'] ?? null) || count($simpleData['buttons']) !== 2){
    throw new RuntimeException('Simple form serialization failed');
}
$choice = 0;
$simple->processData($choice);
if($choice !== 'yes'){
    throw new RuntimeException('First button response was not mapped');
}
$choice = null;
$simple->processData($choice);
if($choice !== null){
    throw new RuntimeException('Closed simple form changed its response');
}
expectInvalid(static function() use ($simple) : void{
    $choice = -1;
    $simple->processData($choice);
});
expectInvalid(static function() use ($simple) : void{
    $choice = '0';
    $simple->processData($choice);
});

$modal = (new ModalForm(null))->setTitle('Confirm')->setContent('Proceed?')->setButton1('Yes')->setButton2('No');
if($modal->getTitle() !== 'Confirm' || $modal->getContent() !== 'Proceed?' || $modal->getButton1() !== 'Yes' || $modal->getButton2() !== 'No'){
    throw new RuntimeException('Modal form getters failed');
}
$answer = null;
$modal->processData($answer);
$answer = false;
$modal->processData($answer);
expectInvalid(static function() use ($modal) : void{
    $answer = 0;
    $modal->processData($answer);
});

$custom = (new CustomForm(null))->setTitle('Settings')->addToggle('Enabled', label: 'enabled')->addDropdown('Mode', ['One', 'Two'], label: 'mode')->addInput('Note', label: 'note');
$values = [true, 1, 'saved'];
$custom->processData($values);
if($values !== ['enabled' => true, 'mode' => 1, 'note' => 'saved']){
    throw new RuntimeException('Custom form labels were not mapped');
}
$values = null;
$custom->processData($values);
if($values !== null){
    throw new RuntimeException('Closed custom form changed its response');
}
expectInvalid(static function() use ($custom) : void{
    $values = ['true', 1, 'saved'];
    $custom->processData($values);
});
expectInvalid(static function() use ($custom) : void{
    $values = [true];
    $custom->processData($values);
});
expectInvalid(static function() use ($custom) : void{
    $values = [2 => 'saved', 1 => 1, 0 => true];
    $custom->processData($values);
});

echo "PASS: Form serialization and response validation\n";
