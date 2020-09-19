<?php

namespace Tzsk\Sms;

use Exception;
use ReflectionClass;
use Tzsk\Sms\Contracts\Driver;

class Sms
{
    protected array $config;

    protected array $settings;

    protected string $driver;

    protected Builder $builder;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->setBuilder(new Builder());
        $this->via($this->config['default']);
    }

    public function to($recipients): self
    {
        $this->builder->to($recipients);

        return $this;
    }

    public function via($driver): self
    {
        $this->driver = $driver;
        $this->validateDriver();
        $this->builder->via($driver);
        $this->settings = $this->config['drivers'][$driver];

        return $this;
    }

    public function send($message, $callback = null)
    {
        if ($message instanceof Builder) {
            return $this->setBuilder($message)->dispatch();
        }

        $this->builder->send($message);
        if (! $callback) {
            return $this;
        }

        $driver = $this->getDriverInstance();
        $driver->message($message);
        call_user_func($callback, $driver);

        return $driver->send();

        // return $this->setBuilder($this->builder->send($message))->dispatch();
    }

    public function dispatch()
    {
        $this->driver = $this->builder->getDriver() ?: $this->driver;
        if (empty($this->driver)) {
            $this->via($this->config['default']);
        }
        $driver = $this->getDriverInstance();
        $driver->message($this->builder->getBody());
        $driver->to($this->builder->getRecipients());

        return $driver->send();
    }

    protected function setBuilder(Builder $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    protected function getDriverInstance(): Driver
    {
        $this->validateDriver();
        $class = $this->config['map'][$this->driver];

        return new $class($this->settings);
    }

    protected function validateDriver()
    {
        $conditions = [
            'Driver not selected or default driver does not exist.' => empty($this->driver),
            'Driver not found in config file. Try updating the package.' => empty($this->config['drivers'][$this->driver]) || empty($this->config['map'][$this->driver]),
            'Driver source not found. Please update the package.' => ! class_exists($this->config['map'][$this->driver]),
            'Driver must be an instance of Contracts\Driver.' => ! (new ReflectionClass($this->config['map'][$this->driver]))->isSubclassOf(Driver::class),
        ];

        foreach ($conditions as $ex => $condition) {
            throw_if($condition, new Exception($ex));
        }
    }
}
