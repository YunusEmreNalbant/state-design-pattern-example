<?php

/***
Durumlar (States)

* Yeni Sipariş Alındı (NewOrderState)
* Sipariş İşleniyor/Hazırlanıyor (ProcessingState)
* Sipariş Paketleniyor (PackagingState)
* Sipariş Yolda/Gönderimde (OnTheWayState)
* Sipariş Teslim Edildi (DeliveredState)
* Sipariş Tamamlandı (CompletedState)
*/

interface IOrderState {
    public function next($order);
    public function previous($order);
    public function getCurrentStatus();
}

class Order {
    private $state;

    public function __construct() {
        $this->state = new NewOrderState();
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

    public function getOrderState() {
        $this->state->getCurrentStatus();
    }
}

class CompletedState implements IOrderState {
    public function next($order) {
        echo "Bu durumun sonrası yoktur. Bu son durumdur. ❌\n";
    }

    public function previous($order) {
        $order->setState(new DeliveredState());
    }

    public function getCurrentStatus() {
        echo "Sipariş Tamamlandı ✅\n";
    }
}

class DeliveredState implements IOrderState {
    public function next($order) {
        $order->setState(new CompletedState());
    }

    public function previous($order) {
        $order->setState(new OnTheWayState());
    }

    public function getCurrentStatus() {
        echo "Sipariş Teslim Edildi 🚚\n";
    }
}

class OnTheWayState implements IOrderState {
    public function next($order) {
        $order->setState(new DeliveredState());
    }

    public function previous($order) {
        $order->setState(new PackagingState());
    }

    public function getCurrentStatus() {
        echo "Sipariş Yolda 🛣️\n";
    }
}

class ProcessingState implements IOrderState {
    public function next($order) {
        $order->setState(new PackagingState());
    }

    public function previous($order) {
        $order->setState(new NewOrderState());
    }

    public function getCurrentStatus() {
        echo "Sipariş İşleme Alınıyor 🏭\n";
    }
}

class PackagingState implements IOrderState {
    public function next($order) {
        $order->setState(new OnTheWayState());
    }

    public function previous($order) {
        $order->setState(new ProcessingState());
    }

    public function getCurrentStatus() {
        echo "Sipariş Paketleniyor 📦\n";
    }
}

class NewOrderState implements IOrderState {
    public function next($order) {
        $order->setState(new ProcessingState());
    }

    public function previous($order) {
        echo "Bu durumun öncesi yoktur. Bu ilk durumdur. ❌\n";
    }

    public function getCurrentStatus() {
        echo "Sipariş Verildi 📝\n";
    }
}


$order = new Order(); // Sipariş Oluşturuluyor...

$order->getOrderState(); // Sipariş Verildi 📝
$order->nextState();
$order->getOrderState(); // Sipariş Paketleniyor 📦
$order->nextState();
$order->getOrderState(); // Sipariş İşleme Alınıyor 🏭
$order->nextState();
$order->getOrderState(); // Sipariş Yolda 🛣️
$order->nextState();
$order->getOrderState(); // Sipariş Teslim Edildi 🚚
$order->nextState();
$order->getOrderState(); // Sipariş Tamamlandı ✅
$order->nextState(); // Bu durumun sonrası yoktur. Bu son durumdur. ❌
