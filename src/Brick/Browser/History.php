<?php

namespace Brick\Browser;

use Brick\Http\Request;

/**
 * Stores the history of a Browser's requests.
 */
class History
{
    /**
     * @var \Brick\Http\Request[]
     */
    private $requests = array();

    /**
     * @var integer
     */
    private $position = -1;

    /**
     * Clears the browser history.
     *
     * @return void
     */
    public function clear()
    {
        $this->requests = array();
        $this->position = -1;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->position == -1;
    }

    /**
     * @param \Brick\Http\Request $request
     * @return void
     */
    public function add(Request $request)
    {
        $this->requests = array_slice($this->requests, 0, $this->position + 1);
        $this->requests[] = $request;
        $this->position++;
    }

    /**
     * @return \Brick\Http\Request
     * @throws \LogicException
     */
    public function back()
    {
        if ($this->position == -1) {
            throw new \LogicException('Cannot move back: the history is empty.');
        }
        if ($this->position == 0) {
            throw new \LogicException('Cannot move back: already on the first page.');
        }

        return $this->requests[--$this->position];
    }

    /**
     * @return \Brick\Http\Request
     * @throws \LogicException
     */
    public function forward()
    {
        if ($this->position == -1) {
            throw new \LogicException('Cannot move forward: the history is empty.');
        }
        if ($this->position == count($this->requests) - 1) {
            throw new \LogicException('Cannot move forward: already on the last page.');
        }

        return $this->requests[++$this->position];
    }

    /**
     * @return \Brick\Http\Request
     * @throws \LogicException
     */
    public function current()
    {
        if ($this->position == -1) {
            throw new \LogicException('The history is empty.');
        }

        return $this->requests[$this->position];
    }
}
