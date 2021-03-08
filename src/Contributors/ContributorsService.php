<?php
declare(strict_types=1);


namespace Cotya\Maintainer\Contributors;


class ContributorsService
{

    public function generateHtml(string $contributorSrcJson): string
    {
        $jsonObject = json_decode($contributorSrcJson, true);

        $lineChunks = array_chunk($jsonObject['contributors'], 7);
        $result = '';
        foreach ($lineChunks as $chunk)
        {
            $result .= $this->generateLine($jsonObject, $chunk);

        }

        $result = <<<HTML

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
$result</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

HTML;

        return $result;
    }

    protected function generateLine($jsonObject, $contributorsArray): string
    {
        $line = '  <tr>' . PHP_EOL;
        foreach ($contributorsArray as $entry)
        {
            $line .= $this->generateCell($entry) . PHP_EOL;
        }

        $line .= '  </tr>' . PHP_EOL;
        return $line;
    }

    protected function generateCell($contributorsEntry): string
    {

        $img = "<img src=\"{$contributorsEntry['avatar_url']}?s=100\" width=\"100px;\" alt=\"\"/>";

        $profileUrl = $contributorsEntry['profile'];
        $contributionUrl = "https://github.com/OpenMage/magento-lts/commits?author={$contributorsEntry['login']}";
        $contributionsHtmlArray = [];
        if (in_array('code', $contributorsEntry['contributions'])) {
            $icon = "💻";
            $title = 'Code';
            $contributionsHtmlArray[] = <<<HTML
<a href="{$contributionUrl}" title="{$title}">{$icon}</a>
HTML;
        }
        if (in_array('doc', $contributorsEntry['contributions'])) {
            $icon = "📖";
            $title = 'Documentation';
            $contributionsHtmlArray[] = <<<HTML
<a href="{$contributionUrl}" title="{$title}">{$icon}</a>
HTML;
        }

        $text = "<sub><b>{$contributorsEntry['name']}</b></sub>";
        $contributionsHtml = implode(" ", $contributionsHtmlArray);

        return <<<HTML
    <td align="center"><a href="{$profileUrl}">{$img}<br />{$text}</a><br />{$contributionsHtml}</td>
HTML;

    }


}
