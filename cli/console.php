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

    public static function cleanupTmp(InputInterface $input, OutputInterface $output)
    {
        self::_initConfig($input);

        $serverRootPath = Config::getServerRootPath();
        $tmpDir = $serverRootPath . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'assets';
        if (file_exists($tmpDir) === false || is_dir($tmpDir) === false) {
            return true;
        }

        $files = array();
        foreach (scandir($tmpDir) as $file) {
            if ($file !== '.' && $file !== '..') {
                array_push($files, $tmpDir . DIRECTORY_SEPARATOR . $file);
            }
        }
        if (empty($files)) {
            return true;
        }


        $precompileFiles = Config::getPrecompile();
        if (empty($precompileFiles)) {
            return true;
        }

        $usefulFiles = array();
        foreach ($precompileFiles as $precompileFile) {
            $parts = explode('.', $precompileFile);
            if (count($parts) < 2) {
                continue;
            }

            $type = strtolower($parts[1]);
            if ($type !== 'js' && $type !== 'css') {
                continue;
            }

            $compiledFiles = Helper::loadCompiledFiles($parts[0], $type);
            if (empty($compiledFiles)) {
                continue;
            }

            $uglifyClass = '\\Assets\\Uglify\\' . ucfirst($type);
            foreach ($compiledFiles as $compiledFile) {
                $compiledFile = $serverRootPath . $compiledFile;
                if (file_exists($compiledFile)) {
                    array_push($usefulFiles, $compiledFile);
                }

                $minFile = $uglifyClass::parseMinFilePath($compiledFile);
                if (file_exists($minFile)) {
                    array_push($usefulFiles, $minFile);
                }
            }
        }

        $unusefulFiles = array_diff($files, $usefulFiles);
        foreach ($unusefulFiles as $unusefulFile) {
            if (unlink($unusefulFile)) {
                $output->writeln('<info>Remove unuseful file</info> : ' . $unusefulFile);
            }
        }
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
            file_put_contents(
                $serverRootPath . DIRECTORY_SEPARATOR . '.assetsrc',
                serialize($compiledFiles)
            );
        }

        $uglifiedPath = DIRECTORY_SEPARATOR . 'uglified' . DIRECTORY_SEPARATOR . 'assets';
        $files = scandir($serverRootPath . $uglifiedPath);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $file = $uglifiedPath . DIRECTORY_SEPARATOR . $file;
            if (in_array($file, $compiledFiles)) {
                continue;
            }

            $file = $serverRootPath . $file;
            if (unlink($file)) {
                $output->writeln('<info>Remove unuseful file</info> : ' . $file);
            }
        }
    }
}