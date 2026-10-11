<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\DateTime;

class DashboardsController extends AppController
{
    public function index(): void
    {
        $this->viewBuilder()->setLayout('dashboard');
        $this->set('viewTitle', 'Dashboard');
        $jobs = $this->fetchTable('Jobs')->find('active')
            ->select(['id', 'department_id', 'positions', 'created'])
            ->all();
        $this->setCake2('jobs', $jobs);

        $candidates = $this->fetchTable('Candidates')->find('active')
            ->contain(['CandidatesJobs' => function ($q) {
                return $q->select(['id', 'candidate_id', 'hired', 'rejected'])
                    ->where(['CandidatesJobs.archived >' => DateTime::now()]);
            }])
            ->all();
        $this->setCake2('candidates', $candidates);
    }
}
