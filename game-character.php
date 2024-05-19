<?php

interface ICharacterState {
    public function next($character);
    public function previous($character);
    public function getCurrentStatus();
}

class AliveState implements ICharacterState {
    public function next($character) {
        $character->setState(new InjuredState());
    }

    public function previous($character) {
        echo "Bu durumun öncesi yoktur. Karakter zaten canlı. ❌\n";
    }

    public function getCurrentStatus() {
        echo "Karakter Canlı 🟩\n";
    }
}

class InjuredState implements ICharacterState {
    public function next($character) {
        $character->setState(new DeadState());
    }

    public function previous($character) {
        $character->setState(new AliveState());
    }

    public function getCurrentStatus() {
        echo "Karakter Yaralı 🩹\n";
    }
}

class DeadState implements ICharacterState {
    public function next($character) {
        echo "Bu durumun sonrası yoktur. Karakter öldü. ❌\n";
    }

    public function previous($character) {
        $character->setState(new InjuredState());
    }

    public function getCurrentStatus() {
        echo "Karakter Öldü 💀\n";
    }
}

class SleepingState implements ICharacterState {
    public function next($character) {
        echo "Karakter uyandı! ✨\n";
        $character->setState(new AliveState());
    }

    public function previous($character) {
        echo "Bu durumun öncesi yoktur. Karakter zaten uyuyor. ❌\n";
    }

    public function getCurrentStatus() {
        echo "Karakter Uyuyor 😴\n";
    }
}

class PoisonedState implements ICharacterState {
    public function next($character) {
        $character->setState(new InjuredState());
    }

    public function previous($character) {
        echo "Karakter zehirden kurtuldu! ✨\n";
        $character->setState(new AliveState());
    }

    public function getCurrentStatus() {
        echo "Karakter Zehirlenmiş 🤢\n";
    }
}

class HealedState implements ICharacterState {
    public function next($character) {
        echo "Bu durumun sonrası yoktur. Karakter zaten iyileşmiş. ❌\n";
    }

    public function previous($character) {
        $character->setState(new InjuredState());
    }

    public function getCurrentStatus() {
        echo "Karakter İyileşmiş 🌟\n";
    }
}

class Character {
    private $state;

    public function __construct() {
        $this->state = new AliveState();
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function nextState() {
        $this->state->next($this);
    }

    public function previousState() {
        $this->state->previous($this);
    }

    public function getCharacterState() {
        $this->state->getCurrentStatus();
    }
}

$character = new Character();
$character->getCharacterState(); // "Karakter Canlı"
$character->nextState();
$character->getCharacterState(); // "Karakter Yaralı"
$character->nextState();
$character->getCharacterState(); // "Karakter Öldü"
$character->nextState(); // "Bu durumun sonrası yoktur. Karakter öldü."
$character->previousState();
$character->getCharacterState(); // "Karakter Yaralı"
$character->previousState(); // "Bu durumun öncesi yoktur. Karakter zaten canlı."
