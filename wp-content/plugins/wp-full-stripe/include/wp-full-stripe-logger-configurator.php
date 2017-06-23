<?php
/**
 * Created by PhpStorm.
 * User: codex
 * Date: 23/06/15
 * Time: 19:15
 */

if (!class_exists("LoggerAutoloader")) {
    require_once('log4php/Logger.php');
}

class WPFSLoggerConfigurator implements LoggerConfigurator {

    public function configure(LoggerHierarchy $hierarchy, $input = null)
    {
        $rootLogger = $hierarchy->getRootLogger();
        $rootLogger->setLevel(LoggerLevel::getLevelDebug());

        $layout = new LoggerLayoutPattern();
        $layout->setConversionPattern("%date [%logger] %level %C->%M(): %message%newline");
        $layout->activateOptions();

        $appFile = new LoggerAppenderFile('myFileAppender');
        $appFile->setFile(plugin_dir_path(__FILE__) . '/../../wp_full_stripe.log');
        $appFile->setAppend(true);
        $appFile->setLayout($layout);
        $appFile->setThreshold(LoggerLevel::getLevelDebug());
        $appFile->activateOptions();

        $logger = $hierarchy->getLogger("WPFS");
        $logger->addAppender($appFile);
        $logger->setParent($rootLogger);
    }
}

Logger::configure( null, new WPFSLoggerConfigurator());

?>
