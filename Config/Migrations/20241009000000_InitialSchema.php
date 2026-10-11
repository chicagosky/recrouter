<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class InitialSchema extends BaseMigration
{
    public function change(): void
    {
        $sentinel = '9999-12-31 00:00:00';

        $this->table('groups')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('archived', 'datetime', ['default' => $sentinel])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('users')
            ->addColumn('group_id', 'integer', ['null' => true])
            ->addColumn('username', 'string', ['limit' => 255])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('firstname', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('lastname', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('hint', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('is_verified', 'string', ['limit' => 1, 'default' => 'N'])
            ->addColumn('verify_code', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('archived', 'datetime', ['default' => $sentinel])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addIndex(['username'])
            ->create();

        $this->table('countries')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('alpha_3', 'string', ['limit' => 8, 'null' => true])
            ->create();

        $this->table('states')
            ->addColumn('country_id', 'integer', ['null' => true])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('alpha_2', 'string', ['limit' => 8, 'null' => true])
            ->create();

        $this->table('priorities')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('contract_models')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('archived', 'datetime', ['default' => $sentinel])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('tags')
            ->addColumn('name', 'string', ['limit' => 255])
            ->create();

        $this->table('departments')
            ->addColumn('parent_id', 'integer', ['null' => true])
            ->addColumn('lft', 'integer', ['null' => true])
            ->addColumn('rght', 'integer', ['null' => true])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('workflows')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('archived', 'datetime', ['default' => $sentinel])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('workflow_step_types')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('icon', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('workflow_steps')
            ->addColumn('workflow_id', 'integer')
            ->addColumn('workflow_step_type_id', 'integer')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('sequence', 'integer', ['default' => 0])
            ->addColumn('archived', 'datetime', ['default' => $sentinel])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('workflow_step_users')
            ->addColumn('workflow_id', 'integer')
            ->addColumn('workflow_step_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->addColumn('archived', 'datetime', ['null' => true])
            ->create();

        $this->table('jobs')
            ->addColumn('user_id', 'integer', ['null' => true])
            ->addColumn('department_id', 'integer', ['null' => true])
            ->addColumn('priority_id', 'integer', ['null' => true])
            ->addColumn('workflow_id', 'integer', ['null' => true])
            ->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('skills', 'text', ['null' => true])
            ->addColumn('city', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('state', 'string', ['limit' => 64, 'null' => true])
            ->addColumn('country', 'string', ['limit' => 64, 'null' => true])
            ->addColumn('positions', 'integer', ['null' => true, 'default' => 1])
            ->addColumn('fill_by', 'datetime', ['null' => true])
            ->addColumn('archived', 'datetime', ['default' => $sentinel])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('job_ext_ids')
            ->addColumn('job_id', 'integer')
            ->addColumn('ext_name', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('ext_id', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('job_workflow_steps')
            ->addColumn('job_id', 'integer')
            ->addColumn('workflow_id', 'integer')
            ->addColumn('workflow_step_id', 'integer')
            ->addColumn('sequence', 'integer', ['default' => 0])
            ->addColumn('created', 'datetime', ['null' => true])
            ->create();

        $this->table('job_workflow_steps_users')
            ->addColumn('job_workflow_step_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->create();

        $this->table('candidates')
            ->addColumn('user_id', 'integer', ['null' => true])
            ->addColumn('firstname', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('lastname', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('email', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('phone', 'string', ['limit' => 64, 'null' => true])
            ->addColumn('phone_alt', 'string', ['limit' => 64, 'null' => true])
            ->addColumn('city', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('state', 'string', ['limit' => 64, 'null' => true])
            ->addColumn('country', 'string', ['limit' => 64, 'null' => true])
            ->addColumn('notes', 'text', ['null' => true])
            ->addColumn('file_name', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('xml', 'text', ['null' => true])
            ->addColumn('archived', 'datetime', ['default' => $sentinel])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('candidates_jobs')
            ->addColumn('candidate_id', 'integer')
            ->addColumn('job_id', 'integer')
            ->addColumn('email', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('hired', 'datetime', ['default' => $sentinel])
            ->addColumn('rejected', 'datetime', ['default' => $sentinel])
            ->addColumn('archived', 'datetime', ['default' => $sentinel])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('candidates_jobs_events')
            ->addColumn('candidates_job_id', 'integer')
            ->addColumn('job_workflow_step_id', 'integer', ['null' => true])
            ->addColumn('event_type_id', 'integer', ['null' => true])
            ->addColumn('notes', 'text', ['null' => true])
            ->addColumn('completed', 'datetime', ['null' => true, 'default' => $sentinel])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('candidates_jobs_emails')
            ->addColumn('candidates_job_id', 'integer')
            ->addColumn('candidates_jobs_event_id', 'integer', ['null' => true])
            ->addColumn('from_address', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('to_address', 'text', ['null' => true])
            ->addColumn('cc', 'text', ['null' => true])
            ->addColumn('subject', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('message', 'text', ['null' => true])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('surveys')
            ->addColumn('job_id', 'integer')
            ->addColumn('name', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('instructions', 'text', ['null' => true])
            ->addColumn('archived', 'datetime', ['default' => $sentinel])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('option_groups')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('option_choices')
            ->addColumn('option_group_id', 'integer')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('questions')
            ->addColumn('survey_id', 'integer')
            ->addColumn('option_group_id', 'integer', ['null' => true])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('subtext', 'text', ['null' => true])
            ->addColumn('type', 'string', ['limit' => 32, 'default' => '1'])
            ->addColumn('required', 'string', ['limit' => 8, 'null' => true])
            ->addColumn('archived', 'datetime', ['null' => true, 'default' => $sentinel])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modifed', 'datetime', ['null' => true])
            ->create();

        $this->table('answers')
            ->addColumn('question_id', 'integer')
            ->addColumn('candidates_job_id', 'integer', ['null' => true])
            ->addColumn('option_choice_id', 'integer', ['null' => true])
            ->addColumn('answer_text', 'text', ['null' => true])
            ->addColumn('answer_numeric', 'decimal', ['null' => true, 'precision' => 12, 'scale' => 2])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();

        $this->table('favorite_jobs')
            ->addColumn('user_id', 'integer')
            ->addColumn('job_id', 'integer')
            ->create();

        $this->table('favorite_candidates')
            ->addColumn('user_id', 'integer')
            ->addColumn('candidate_id', 'integer')
            ->create();

        $this->table('jobs_tags')
            ->addColumn('job_id', 'integer')
            ->addColumn('tag_id', 'integer')
            ->create();

        $this->table('candidates_tags')
            ->addColumn('candidate_id', 'integer')
            ->addColumn('tag_id', 'integer')
            ->create();

        $this->table('contract_models_jobs')
            ->addColumn('job_id', 'integer')
            ->addColumn('contract_model_id', 'integer')
            ->create();

        $this->table('candidates_contract_models')
            ->addColumn('candidate_id', 'integer')
            ->addColumn('contract_model_id', 'integer')
            ->create();
    }
}
