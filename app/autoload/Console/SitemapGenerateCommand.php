<?php

namespace App\Console;

use App\Seo\Sitemap\SitemapGenerator;
use App\Console\BaseCommand;

/** @noinspection PhpUnused */
class SitemapGenerateCommand extends BaseCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('sitemap:generation')
             ->setDescription('Sitemap generation');
    }

    /**
     * Execute the console command.
     *
     * @return null|int
     */
    protected function fire(): ?int
    {
        (new SitemapGenerator())->run();
        $this->info("Sitemap has been generated");

        return 0;
    }
}