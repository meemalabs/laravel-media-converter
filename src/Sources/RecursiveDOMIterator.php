<?php

namespace Meema\MediaConvert\Sources;

use DOMNode;
use RecursiveIterator;

class RecursiveDOMIterator implements RecursiveIterator
{
    /**
     * Current Position in DOMNodeList.
     * @var int
     */
    protected $_position;

    /**
     * The DOMNodeList with all children to iterate over.
     * @var DOMNodeList
     */
    protected $_nodeList;

    /**
     * @param DOMNode $domNode
     */
    public function __construct(DOMNode $domNode)
    {
        $this->_position = 0;
        $this->_nodeList = $domNode->childNodes;
    }

    /**
     * Returns the current DOMNode.
     * @return DOMNode
     */
    public function current()
    {
        return $this->_nodeList->item($this->_position);
    }

    /**
     * Returns an iterator for the current iterator entry.
     * @return RecursiveDOMIterator
     */
    public function getChildren()
    {
        return new self($this->current());
    }

    /**
     * Returns if an iterator can be created for the current entry.
     * @return bool
     */
    public function hasChildren()
    {
        return $this->current()->hasChildNodes();
    }

    /**
     * Returns the current position.
     * @return int
     */
    public function key()
    {
        return $this->_position;
    }

    /**
     * Moves the current position to the next element.
     * @return void
     */
    public function next()
    {
        $this->_position++;
    }

    /**
     * Rewind the Iterator to the first element.
     * @return void
     */
    public function rewind()
    {
        $this->_position = 0;
    }

    /**
     * Checks if current position is valid.
     * @return bool
     */
    public function valid()
    {
        return $this->_position < $this->_nodeList->length;
    }
}
