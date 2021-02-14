<?php


namespace Cotya\Maintainer\Command;

use Cotya\Maintainer\Helper;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ReleasePreparation extends Command
{

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'release:prepare';

    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $cache = new FilesystemAdapter();

        // gh api repos/OpenMage/magento-lts/compare/v19.4.10...1.9.4.x
        $from = 'v19.4.10';
        $to= '1.9.4.x';


        $value = $cache->get('my_cache_key', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            // ... do some HTTP request or heavy computations
            $computedValue = 'foobar';

            return $computedValue;
        });

        $process = new Process(["gh","api", "repos/OpenMage/magento-lts/compare/$from...$to"]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }


        $compareResult = json_decode($process->getOutput(), true);



        $resultLines = [];
        $lastRateLimit = null;

        foreach ($compareResult['commits'] as $commit) {

            $graphQl = Helper::getSingleCommitGraphQl($commit['sha']);
            $process = new Process(['gh', 'api', 'graphql', '-f', "query=$graphQl"]);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $fullCommit = json_decode($process->getOutput(), true);
            $lastRateLimit = $fullCommit['data']['rateLimit'];
            $fullCommit = $fullCommit['data']['repository']['object'];
            $pr = isset($fullCommit['associatedPullRequests']['nodes'][0]) ? $fullCommit['associatedPullRequests']['nodes'][0] : null;
            $result = [
                'sha' => $commit['sha'],
                'author_id' => $commit['author']['login'],
                'author_url' => $commit['author']['url'],
                'commit_message' => $commit['commit']['message'],
                'pr_number' => $pr ? $pr['number'] : null,
                'pr_message' => $pr ? $pr['title'] : null,
                'pr_url' => $pr ? $pr['url'] : null,
            ];
            $resultLines[] = $result;
        }

        foreach ($resultLines as $resultEntry)
        {
            $prNumber = isset($resultEntry['pr_number']) ? "#".$resultEntry['pr_number'].' ' : '';
            $message = isset($resultEntry['pr_message']) ? $resultEntry['pr_message'] : $resultEntry['commit_message'];
            $output->writeln($prNumber."".$message);
        }

        $output->writeln("rateLimit:"."".var_export($lastRateLimit,true));

        // ... put here the code to create the user

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }
}
