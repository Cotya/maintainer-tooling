<?php


namespace Cotya\Maintainer;


class Helper
{

    const rateLimitGraphQl = <<<GRAPHQL
  rateLimit {
    limit
    cost
    remaining
    resetAt
  }
GRAPHQL;

    const commitGraphQl = <<<GRAPHQL
            oid
            messageHeadline
            committedDate
            signature {
              isValid
              email
              payload
              wasSignedByGitHub
              state
            }
            url
            associatedPullRequests(first:5) {
              nodes {
                author {
                  login
                }
                id
                mergedBy {
                  login
                }
                merged
                mergedAt
                number
                title
                url
              }
            }
GRAPHQL;


    public static function getSingleCommitGraphQl($sha)
    {

        $ratelimit = self::rateLimitGraphQl;
        $commit = self::commitGraphQl;
        $graphQl = <<<GRAPHQL
{
$ratelimit
  repository(owner: "OpenMage", name: "magento-lts") {
    object(oid: "$sha") {
      ... on Commit {
        $commit
      }
      id
      oid
    }
  }
}
GRAPHQL;

        return $graphQl;
    }

    public static function getHistoryGraphQl($from, $to)
    {
        $ratelimit = self::rateLimitGraphQl;
        $commit = self::commitGraphQl;
        $graphQl = <<<GRAPHQL
{
$ratelimit
  repository(owner: "OpenMage", name: "magento-lts") {
    object(expression: "$from") {
      ... on Commit {
        oid
        messageHeadline
        committedDate
        history(before: "$to") {
          nodes {
            $commit
          }
        }
      }
    }
  }
}
GRAPHQL;
    }
}
