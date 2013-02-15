<?php 

require(__DIR__.'/../src/Cart.php');

class CartTest extends PHPUnit_Framework_TestCase {

  /** @test */
  public function ANewCartIsEmpty() {
    $cart = new Cart();
    
    $this->assertEquals(0, $cart->getNbItems());
  }
  
  /**
   * @test 
   * 
   * This test is green with returnValueMap
   */
  public function addingANewItemWillAddAnAdditionalBonusItem() {
    $cart = $this->getMockBuilder('Cart')
                 ->setMethods(array('getItemById', 'doSomething'))
                 ->getMock();
    
    $item = $this->getItemMocked(2.00, true);
    $bonusItem = $this->getItemMocked(1.00);
    
    $getItemByIdReturnValues = array(
        array('666', $item),
        array('123456', $bonusItem)
    );
    $cart->expects($this->any())
         ->method('getItemById')
         ->will($this->returnValueMap($getItemByIdReturnValues));
        
    $cart->addItem('666');
    
    $this->assertEquals(2, $cart->getNbItems());
    $this->assertEquals(3.0, $cart->getAmount());
  }
  
  private function getItemMocked($itemPrice, $isNew = false) {
    $itemMocked = $this->getMockBuilder('Item')
                      ->setMethods(array('isNew', 'getPrice'))
                      ->getMock();
    $itemMocked->expects($this->any())
               ->method('getPrice')
               ->will($this->returnValue($itemPrice));
    $itemMocked->expects($this->any())
               ->method('isNew')
               ->will($this->returnValue($isNew));
    return $itemMocked;
  }
  
  /**
   * @test 
   * 
   * This test 
   * is   RED  with at(0), at(1)
   * but green with at(0), at(2)
   * just because another function doSomething is called between the two mocked calls ?
   */
  public function whyAtPositionDoesNotTakeIntoAccountMethodName() {
    $cart = $this->getMockBuilder('Cart')
                 ->setMethods(array('getItemById', 'doSomething'))
                 ->getMock();
    
    $item = $this->getItemMocked(2.00, true);
    $bonusItem = $this->getItemMocked(1.00);

    $cart->expects($this->at(0))
         ->method('getItemById')
         ->with('666')
         ->will($this->returnValue($item));
    $cart->expects($this->at(2))
         ->method('getItemById')
         ->with('123456')
         ->will($this->returnValue($bonusItem));
    
    $cart->addItem('666');
    
    $this->assertEquals(2, $cart->getNbItems());
    $this->assertEquals(3.0, $cart->getAmount());
  }
  
}