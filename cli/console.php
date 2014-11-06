<?php
namespace Assets;

use SplFileInfo;
use Assets\Helper;
use Assets\Config;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Console
{
    private static function _initConfig(InputInterface $input)
    {
        $configFilePath = $input->getArgument('config');
        if ($configFilePath === null) {
            $env = getenv('ASSETS_ENV');
            if (empty($env)) {
                $env = 'development';
            }

            $configFilePath = getcwd() . "/configs/{$env}/assets.php";
        }

        Config::init($configFilePath);
    }

    public static function clean(InputInterface $input)
    {
        self::_initConfig($input);
        $serverRootPath = Config::getServerRootPath() . DIRECTORY_SEPARATOR;
        Helper::removePath($serverRootPath . '.assetsrc');
        Helper::removePath($serverRootPath . 'uglified');
        Helper::removePath($serverRootPath . 'tmp' . DIRECTORY_SEPARATOR . 'assets');
    }

    public static function precompile(InputInterface $input, OutputInterface $output)
    {
        self::_initConfig($input);

        $compiledFiles = array();
        $files = Config::getPrecompile();
        $serverRootPath = Config::getServerRootPath();
        foreach ($files as $file) {
            $file = ltrim($file, DIRECTORY_SEPARATOR);
            $fileInfo = new SplFileInfo($file);
            $extName = strtolower($fileInfo->getExtension());
            if ($extName !== 'js' && $extName !== 'css') {
                continue;
            }

            $uglifyClass = '\\Assets\\Uglify\\' . ucfirst($extName);
            $distFile = $uglifyClass::uglify($file);

            if ($distFile === false) {
                $output->writeln("<error>Can't compile file : {$file}</error>");
            } else {
                $compiledFiles[$file] = $distFile;
                $output->writeln('<info>Create file</info> : ' . $serverRootPath . $distFile);
            }
        }

        if (!empty($compiledFiles)) {
            file_put_contents($serverRootPath . DIRECTORY_SEPARATOR . '.assetsrc', serialize($compiledFiles));
        }
    }
}