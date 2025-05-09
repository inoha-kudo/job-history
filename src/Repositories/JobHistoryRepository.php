<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Repositories;

use Miraiportal\JobHistory\Entities\JobHistory;

interface JobHistoryRepository
{
    public function save(JobHistory $jobHistory): JobHistory;

    /** @param ?array<string, mixed> $params */
    public function getLatest(int $jobId, ?array $params = null): ?JobHistory;
}
