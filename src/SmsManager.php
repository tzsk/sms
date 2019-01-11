<?php

namespace Tzsk\Sms;

class SmsManager
{
    /**
     * Sms Configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * Sms Driver Settings.
     *
     * @var array
     */
    protected $settings;

    /**
     * Sms Driver Name.
     *
     * @var string
     */
    protected $driver;

    /**
     * @var SmsBuilder
     */
    protected $builder;

    /**
     * SmsManager constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->setBuilder(new SmsBuilder());
        $this->via($this->config['default']);
    }

    /**
     * @param string|array $recipients
     * @return self
     */
    public function to($recipients)
    {
        $this->builder->to($recipients);

        return $this;
    }

    /**
     * Change the driver on the fly.
     *
     * @param $driver
     * @return $this
     * @throws \Exception
     */
    public function via($driver)
    {
        $this->driver = $driver;
        $this->validateDriver();
        $this->builder->via($driver);
        $this->settings = $this->config['drivers'][$driver];

        return $this;
    }

    /**
     * Send message.
     *
     * @param $message
     * @param $callback
     * @return mixed
     * @throws \Exception
     */
    public function send($message, $callback = null)
    {
        if ($message instanceof SmsBuilder) {
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
    }

    /**
     * @return mixed
     */
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

    /**
     * @param SmsBuilder $builder
     * @return self
     */
    protected function setBuilder(SmsBuilder $builder)
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * Generate driver instance.
     *
     * @return mixed
     */
    protected function getDriverInstance()
    {
        $this->validateDriver();
        $class = $this->config['map'][$this->driver];

        return new $class($this->settings);
    }

    /**
     * Validate Parameters before sending.
     *
     * @throws \Exception
     */
    protected function validateDriver()
    {
        if (empty($this->driver)) {
            throw new \Exception('Driver not selected or default driver does not exist.');
        }

        if (empty($this->config['drivers'][$this->driver]) || empty($this->config['map'][$this->driver])) {
            throw new \Exception('Driver not found in config file. Try updating the package.');
        }

        if (! class_exists($this->config['map'][$this->driver])) {
            throw new \Exception('Driver source not found. Please update the package.');
        }

        $reflect = new \ReflectionClass($this->config['map'][$this->driver]);

        if (! $reflect->implementsInterface(Contracts\DriverInterface::class)) {
            throw new \Exception("Driver must be an instance of Contracts\DriverInterface.");
        }
    }
}
