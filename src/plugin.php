<?php

namespace djs\expackage;

use Composer\Plugin\PluginInterface;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Composer\Json\JsonFile;

class Plugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
{
    $io->write("<info>Activating djs/expackage plugin...</info>");
    self::modifyComposerScripts(new Event('post-install-cmd', $composer, $io));
}

    public function deactivate(Composer $composer, IOInterface $io)
    {
        // Optional: Code to handle deactivation (e.g., cleanup)
        $io->write("<info>djs/expackage plugin deactivated</info>");
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // Optional: Code to handle uninstallation
        $io->write("<info>djs/expackage plugin uninstalled</info>");
    }

    public static function modifyComposerScripts(Event $event)
    {
        $io = $event->getIO();
    
        try {
            $composerJsonPath = './composer.json';
            $jsonFile = new JsonFile($composerJsonPath);
    
            // Load the existing composer.json
            if (!file_exists($composerJsonPath)) {
                $io->writeError("<error>composer.json file not found</error>");
                return;
            }
    
            $composerData = $jsonFile->read();
    
            // Ensure the "scripts" key exists
            if (!isset($composerData['scripts'])) {
                $composerData['scripts'] = [];
            }
    
            // Add the post-install-cmd script
            if (!isset($composerData['scripts']['post-install-cmd'])) {
                $composerData['scripts']['post-install-cmd'] = [];
            }
    
            if (!in_array("djs\\expackage\\Installer::setup", $composerData['scripts']['post-install-cmd'])) {
                $composerData['scripts']['post-install-cmd'][] = "djs\\expackage\\Installer::setup";
                $jsonFile->write($composerData);
    
                $io->write("<info>Added djs\\expackage\\Installer::setup to post-install-cmd</info>");
            } else {
                $io->write("<info>djs\\expackage\\Installer::setup is already in post-install-cmd</info>");
            }
        } catch (\Exception $e) {
            $io->writeError("<error>Error modifying composer.json: {$e->getMessage()}</error>");
        }
    }
    
}
