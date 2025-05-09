<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Services;

use Miraiportal\JobHistory\Entities\JobHistory;
use Miraiportal\JobHistory\Entities\JobHistoryState;
use Miraiportal\JobHistory\Repositories\JobHistoryRepository;
use RuntimeException;

final readonly class JobHistoryService
{
    public function __construct(
        private JobHistoryRepository $jobHistoryRepository,
    ) {}

    public function add(JobHistory $jobHistory, bool $isUnique = false): JobHistory
    {
        if (
            $isUnique
            && ($latestJobHistory = $this->getLatest($jobHistory))
            && $latestJobHistory->isProcessing()
        ) {
            throw new RuntimeException('Another instance of the job is already on the queue and has not finished processing.');
        }

        return $this->jobHistoryRepository->save($jobHistory);
    }

    /** @param ?array<string, mixed> $params */
    public function changeStateToRunning(?JobHistory $jobHistory, ?array $params = null): ?JobHistory
    {
        return $this->changeState($jobHistory, JobHistoryState::Running, $params);
    }

    /** @param ?array<string, mixed> $params */
    public function changeStateToSucceeded(?JobHistory $jobHistory, ?array $params = null): ?JobHistory
    {
        return $this->changeState($jobHistory, JobHistoryState::Succeeded, $params);
    }

    /** @param ?array<string, mixed> $params */
    public function changeStateToFailed(?JobHistory $jobHistory, ?array $params = null): ?JobHistory
    {
        return $this->changeState($jobHistory, JobHistoryState::Failed, $params);
    }

    private function getLatest(JobHistory $jobHistory): ?JobHistory
    {
        return $this->jobHistoryRepository->getLatest($jobHistory->jobId(), $jobHistory->params());
    }

    /** @param ?array<string, mixed> $params */
    private function changeState(?JobHistory $jobHistory, JobHistoryState $state, ?array $params = null): ?JobHistory
    {
        if (! $jobHistory) {
            return null;
        }

        return $this->jobHistoryRepository->save($jobHistory->withState($state, $params));
    }
}
