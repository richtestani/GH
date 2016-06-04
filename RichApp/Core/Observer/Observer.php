<?php
namespace Core\Observer;
/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of Observer
 *
 * @author Richard Testani <richtestani@mac.com>
 */
abstract class Observer {
    abstract public function update($data = '');
}
