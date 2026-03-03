<?php
namespace App\Dao;

class Logger
{
    private $logFile;

    public function __construct(string $logFile)
    {
        $this->logFile = $logFile;
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
    }

    public function error(string $message): void
    {
        $this->log('ERROR', $message);
    }

    public function info(string $message): void
    {
        $this->log('INFO', $message);
    }

    private function log(string $level, string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[$date] $level: $message" . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}