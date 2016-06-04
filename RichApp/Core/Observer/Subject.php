<?php
namespace Core\Observer;
/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of Subject
 *
 * @author Richard Testani <richtestani@mac.com>
 */
abstract class Subject {
    abstract public function registerObserver(\Core\Observer\Observer $o);
    abstract public function removeObserver(\Core\Observer\Observer $o);
    abstract public function notifyObserver();
}
