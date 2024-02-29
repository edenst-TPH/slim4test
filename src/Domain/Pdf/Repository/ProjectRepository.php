<?php

namespace App\Domain\Pdf\Repository;

use App\Factory\QueryFactory;
use DomainException;

final class ProjectRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function insertProject(array $project): int
    {
        return (int)$this->queryFactory->newInsert('projects', $this->toRow($project))
            ->execute()
            ->lastInsertId();
    }

    public function getProjectById(int $projectId): array
    {
        $query = $this->queryFactory->newSelect('projects');
        $query->select(
            [
                'id',
                'number',
                'name',
                'street',
                'postal_code',
                'city',
                'country',
                'email',
            ]
        );

        $query->where(['id' => $projectId]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(sprintf('Project not found: %s', $projectId));
        }

        return $row;
    }

    public function updateProject(int $projectId, array $project): void
    {
        $row = $this->toRow($project);

        $this->queryFactory->newUpdate('projects', $row)
            ->where(['id' => $projectId])
            ->execute();
    }

    public function existsProjectId(int $projectId): bool
    {
        $query = $this->queryFactory->newSelect('projects');
        $query->select('id')->where(['id' => $projectId]);

        return (bool)$query->execute()->fetch('assoc');
    }

    public function deleteProjectById(int $projectId): void
    {
        $this->queryFactory->newDelete('projects')
            ->where(['id' => $projectId])
            ->execute();
    }

    private function toRow(array $project): array
    {
        return [
            'number' => $project['number'],
            'name' => $project['name'],
            'street' => $project['street'],
            'postal_code' => $project['postal_code'],
            'city' => $project['city'],
            'country' => $project['country'],
            'email' => $project['email'],
        ];
    }
}
