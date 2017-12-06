<?php
/**
 * MyClass File Doc Comment
 *
 * @category MyClass
 * @package  MyPackage
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hashbangcode.com/
 *
 */
namespace AppBundle\Entity;

/**
 * MyClass Class Doc Comment
 *
 * @category Class
 * @package  Fiszki
 * @author   krilek, Vengard
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/krilek/fiszki
 */

class Word
{
    //FIXME: Change id to private when working on db, changed temporarily
    public $_id;
    private $_wordEn;
    private $_wordPl;

    public function __construct($wordEn = null, $wordPl = null)
    {
        if ($wordEn != null && $wordPl != null) {
            $this->_wordEn = $wordEn;
            $this->_wordPl = $wordPl;
        }
    }
    public function getId()
    {
        return $this->_id;
    }
    public function getWordEn()
    {
        return $this->_wordEn;
    }
    public function getWordPl()
    {
        return $this->_wordPl;
    }
    public function setWordPl($word)
    {
        $this->_wordPl = $word;
    }
    public function setWordEn($word)
    {
        $this->_wordEn = $word;
    }
}
