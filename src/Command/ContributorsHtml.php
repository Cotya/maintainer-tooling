<?php
declare(strict_types=1);

namespace Cotya\Maintainer\Command;

use Cotya\Maintainer\Helper;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ContributorsHtml extends Command
{

    protected static $defaultName = 'contributors:html';

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
