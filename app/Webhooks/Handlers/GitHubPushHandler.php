<?php

namespace App\Webhooks\Handlers;

use App\Webhooks\AbstractWebhookHandler;

class GitHubPushHandler extends AbstractWebhookHandler
{
    public function handle(array $payload, array $headers)
    {
        // Handle GitHub push event
        $repository = $payload['repository']['full_name'];
        $branch = explode('/', $payload['ref'])[2];
        $commits = $payload['commits'];

        // Process the push event
        // For example, trigger a deployment
        $this->triggerDeployment($repository, $branch, $commits);

        return [
            'repository' => $repository,
            'branch' => $branch,
            'commits_count' => count($commits)
        ];
    }

    protected function triggerDeployment($repository, $branch, $commits)
    {
        // Implementation
    }
} 