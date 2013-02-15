<?php

class Cart {

  const PRESENT_ITEM_ID = '123456';
  
  private $items = array();
  
  public function addItem($itemId) {
    $item = $this->getItemById($itemId);
    $this->items[] = $item;
    
    if ($item->isNew()) {
      
      $this->doSomething();
      
      $itemBonus = $this->getItemById(self::PRESENT_ITEM_ID);
      $this->items[] = $itemBonus;
    }
    
  }
  
  public function getNbItems() {
    return count($this->items);
  }

  public function getAmount() {
    $amount = 0.0;
    foreach ($this->items as $item) {
      $amount += $item->getPrice();
    }
    return $amount;
  }
  
  protected function getItemById($itemId) {
    // Let's assume that this function 
    // access to a database.
    // If we want to test a method that calls
    // this method, we need to mock this function
    return null;
  }
  
  protected function doSomething() {
    // do something
  }
  
}