<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class SeedReferenceData extends BaseMigration
{
    public function up(): void
    {
        $now = date('Y-m-d H:i:s');
        $sentinel = '9999-12-31 00:00:00';

        $this->table('groups')->insert([
            ['name' => 'Administrators', 'archived' => $sentinel, 'created' => $now, 'modified' => $now],
            ['name' => 'Managers', 'archived' => $sentinel, 'created' => $now, 'modified' => $now],
            ['name' => 'Users', 'archived' => $sentinel, 'created' => $now, 'modified' => $now],
        ])->saveData();

        $this->table('workflow_step_types')->insert([
            ['id' => 1, 'name' => 'link', 'icon' => 'glyphicon glyphicon-link', 'created' => $now, 'modified' => $now],
            ['id' => 2, 'name' => 'hire', 'icon' => 'glyphicon glyphicon-thumbs-up', 'created' => $now, 'modified' => $now],
            ['id' => 3, 'name' => 'reject', 'icon' => 'glyphicon glyphicon-thumbs-down', 'created' => $now, 'modified' => $now],
            ['id' => 4, 'name' => 'notify', 'icon' => 'glyphicon glyphicon-envelope', 'created' => $now, 'modified' => $now],
            ['id' => 5, 'name' => 'assess', 'icon' => 'glyphicon glyphicon-list-alt', 'created' => $now, 'modified' => $now],
            ['id' => 7, 'name' => 'verify', 'icon' => 'glyphicon glyphicon-search', 'created' => $now, 'modified' => $now],
            ['id' => 8, 'name' => 'process', 'icon' => 'glyphicon glyphicon-cog', 'created' => $now, 'modified' => $now],
        ])->saveData();

        $this->table('priorities')->insert([
            ['name' => 'High', 'created' => $now, 'modified' => $now],
            ['name' => 'Medium', 'created' => $now, 'modified' => $now],
            ['name' => 'Low', 'created' => $now, 'modified' => $now],
        ])->saveData();

        $this->table('countries')->insert([
            ['name' => 'United States', 'alpha_3' => 'usa'],
        ])->saveData();

        $this->table('states')->insert([
            ['country_id' => 1, 'name' => 'California', 'alpha_2' => 'CA'],
            ['country_id' => 1, 'name' => 'Texas', 'alpha_2' => 'TX'],
            ['country_id' => 1, 'name' => 'New York', 'alpha_2' => 'NY'],
        ])->saveData();

        $this->table('departments')->insert([
            ['parent_id' => null, 'lft' => 1, 'rght' => 2, 'name' => 'Company', 'created' => $now, 'modified' => $now],
        ])->saveData();

        $this->table('users')->insert([
            [
                'group_id' => 1,
                'username' => 'admin@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'firstname' => 'Admin',
                'lastname' => 'User',
                'is_verified' => 'Y',
                'archived' => $sentinel,
                'created' => $now,
                'modified' => $now,
            ],
        ])->saveData();
    }

    public function down(): void
    {
        $this->execute('DELETE FROM users');
        $this->execute('DELETE FROM departments');
        $this->execute('DELETE FROM states');
        $this->execute('DELETE FROM countries');
        $this->execute('DELETE FROM priorities');
        $this->execute('DELETE FROM workflow_step_types');
        $this->execute('DELETE FROM groups');
    }
}
