<?php

PageMaker::makeWelcomePage("hideyuki.utsunomiya@xxxx.net", "welcome.html");
//PageMaker::makeWelcomePage("koichi.osanai@gree.net", "welcome.html");

class Database
{
    private static $_instance = null;
    private $_dbname = null;

    // not to create by `new Database()`
    private function __construct($dbname)
    {
        $this->_dbname = $dbname;
    }

    public function getInstance($dbname)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($dbname);
        }
        return self::$_instance;
    }

    private function _getProperties()
    {
        $filename = $this->_dbname . ".txt";
        $data = file($filename);
        $prop = array();
        foreach ($data as $d) {
            $prop[] = explode(":", $d);
        }
        return $prop;
    }

    public function getUserName($mailaddr)
    {
        $prop = $this->_getProperties();
        foreach ($prop as $p) {
            if ($p[0] == $mailaddr) {
                return trim($p[1]);
            }
        }
        die ("Not found [$mailaddr].");
    }
}

class HtmlWriter
{
    private $_writer;

    public function __construct(FileWriter $writer)
    {
        $this->_writer = $writer;
    }

    public function title($title)
    {
        $this->_writer->write(
"<html>
  <head>
    <title>$title</title>
  </head>
  <body>
    <h1>$title</h1>
");
    }

    public function link($href, $caption)
    {
        $this->paragraph("<a href=\"$href\">$caption</a>");
    }

    public function paragraph($msg)
    {
        $this->_writer->write("    <p>$msg</p>\n");
    }

    public function mailto($mailaddr, $username)
    {
        $this->link("$mailaddr", $username);
    }

    public function close()
    {
        $this->_writer->write(
"  </body>
</html>
");
        $this->_writer->close();
    }
}

class PageMaker
{
    private function __construct()
    {
    }

    public static function makeWelcomePage($mailaddr, $filename)
    {
        $database = Database::getInstance("maildata");
        $username = $database->getUserName($mailaddr);
        $filewriter = FileWriter::getInstance($filename, 'w');
        $writer = new HtmlWriter($filewriter);
        $writer->title("Welcome to $username's page!");
        $writer->paragraph("Hello! This is $username's page!");
        $writer->paragraph("I'm waiting your mail.");
        $writer->mailto($mailaddr, $username);
        $writer->close();
        echo "$filename is created for $mailaddr ($username).\n";
    }
}

class FileWriter
{
    private static $_instance = null;
    private $_fp = null;

    private function __construct($filename, $opt)
    {
        $this->_fp = fopen($filename, $opt);
    }

    public static function getInstance($filename, $opt)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($filename, $opt);
        }
        return self::$_instance;
    }

    public function write($text)
    {
        return fwrite($this->_fp, $text);
    }

    public function close() {
        return fclose($this->_fp);
    }
}
