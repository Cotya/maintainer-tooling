# maintainer-tooling
some scripts to help maintaining OpenSource Projects



Example call `php console.php release:prepare` currently produces this output:

```
#1248 mark trigger_recollect before collectTotals
#1418 Fix regression in configuration scope code. Refs #1417
#1281 remove-reference-to-magentocommerce
#1383 Remove latest occurrences of XmlConnect
#1429 Revert "Removed 2 unneeded function calls. Local var is already there."
Ignore media/captcha directory.
#1412 Update static-code-analyses.yml
#1441 Fixed menu cursor
#1160 Updated README.md, closes #985 #992
#1407 Reduced multiple dispatch events in login form for other themes.
rateLimit:array (
  'limit' => 5000,
  'cost' => 1,
  'remaining' => 4967,
  'resetAt' => '2021-02-14T22:38:37Z',
)
```

The Version Range is currently hardcoded

