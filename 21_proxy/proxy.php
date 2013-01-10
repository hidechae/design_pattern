<?php

class Printer implements Printable
{
    private $_name;

    public function __construct($name = null)
    {
        if (is_null($name)) {
            $this->_heabyjob("Creating instance of Printer.");
        } else {
            $this->_name = $name;
            $this->_heabyjob("Creating instance of Printer `$name`.");
        }
    }

    public function setPrinterName($name)
    {
        $this->_name = $name;
    }

    public function getPrinterName()
    {
        return $this->_name;
    }

    public function sprint($string)
    {
        echo "=== $this->_name ===\n";
        echo $string . "\n";
    }

    private function _heabyjob($msg)
    {
        echo $msg . "\n";
        for ($i = 0; $i < 5; $i++) {
            sleep(1);
            echo ".";
        }
        echo "Complete!\n";
    }
}


interface Printable
{
    public function setPrinterName($name);
    public function getPrinterName();
    public function sprint($string);
}

class PrinterProxy implements Printable
{
    private $_name = null;
    private $_real = null;

    public function __construct($name = null)
    {
        if (!is_null($name)) {
            $this->_name = $name;
        }
    }

    public function setPrinterName($name)
    {
        if (!is_null($this->_real)) {
            $this->_real->setPrinterName($name);
        }
        $this->_name = $name;
    }

    public function getPrinterName()
    {
        return $this->_name;
    }

    public function sprint($string)
    {
        $this->_realize();
        $this->_real->sprint($string);
    }

    private function _realize()
    {
        if (is_null($this->_real)) {
            $this->_real = new Printer($this->_name);
        }
    }
}

$p = new PrinterProxy('Alice');
echo "Name is " . $p->getPrinterName() . "\n";

$p = new PrinterProxy('Bob');
echo "Name is " . $p->getPrinterName() . "\n";

$p->sprint("Hello, world.");
