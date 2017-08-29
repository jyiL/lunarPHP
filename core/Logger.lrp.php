<?php

namespace LunarPHP\Core;

class Logger
{
	public $conf = array(
		"separator" => "\t",
		"log_file" => ""
	);

	private $fileHandle;

    public function __construct()
    {
        $conf = json_decode(LOG_CONF,true);
        $this->conf['separator'] = $conf['separator'];
        $this->conf['log_file'] = SITE_PATH.$conf['log_file'].'/lunar_err_'.date("Y-m-d").'.log';;
    }

	protected function getFileHandle()
	{
		if (null === $this->fileHandle)
		{
			if (empty($this->conf["log_file"]))
			{
				trigger_error("no log file spcified.");
			}
			$logDir = dirname($this->conf["log_file"]);
			if (!is_dir($logDir))
			{
				mkdir($logDir, 0777, true);
			}
			$this->fileHandle = fopen($this->conf["log_file"], "a");
		}
		return $this->fileHandle;
	}

	public function log($logmsg)
	{
        $logData = array(
            date("Y-m-d H:i:s"),
            $logmsg,
        );
		if ("" == $logData || array() == $logData)
		{
			return false;
		}
		if (is_array($logData))
		{
			$logData = implode($this->conf["separator"], $logData);
		}
		$logData = $logData. "\n";
		fwrite($this->getFileHandle(), $logData);
	}
}