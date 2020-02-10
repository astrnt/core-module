<?php

namespace Modules\Core\Hooks;

abstract class CwaHook
{
    /**
    * Holds the event listeners
    * @var array
    */
    protected $listeners = [];

    /**
    * Adds a listener
    * @param string  $hook      Hook name
    * @param mixed   $callback  Function to execute
    * @param integer $priority  Priority of the action
    * @param integer $arguments Number of arguments to accept
    */
    public function listen($hook, $callback, $priority = 20, $arguments = 1)
    {
        $this->listeners[$priority][$hook] = compact('callback', 'arguments');
    }

    /**
    * Gets a sorted list of all listeners
    * @return array
    */
    public function getListeners()
    {
        // sort by priority
        uksort($this->listeners, function ($a, $b) {
            return strnatcmp($a, $b);
        });
        return $this->listeners;
    }

    /**
    * Gets the function
    * @param  mixed $callback Callback
    * @return mixed           A closure, an array if "class@method" or a string if "function_name"
    */
    protected function getFunction($callback)
    {
        if (is_string($callback)) {
            if (strpos($callback, '@')) {
                $callback = explode('@', $callback);
                return array(app('\\'. $callback[0]), $callback[1]);
            }
            
            return $callback;
        } elseif ($callback instanceof \Closure) {
            return $callback;
        }
    }

    /**
    * Fires a new action
    * @param  string $action Name of action
    * @param  array  $args   Arguments passed to the action
    */
    abstract protected function fire($action, $args);
}
