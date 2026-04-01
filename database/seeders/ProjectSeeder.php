<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing projects first
        Project::truncate();

        $projects = [
            [
                'title'       => 'Portfolio Website',
                'description' => 'My personal portfolio built with Laravel and React. Clean, fast and fully custom.',
                'tech_stack'  => 'Laravel, React, Tailwind CSS',
                'live_url'    => 'https://yoursite.com',
                'github_url'  => 'https://github.com/yourhandle/portfolio',
                'image_url'   => null,
            ],
            [
                'title'       => 'Your Second Project',
                'description' => 'A short description of what this project does and what problem it solves.',
                'tech_stack'  => 'Laravel, MySQL',
                'live_url'    => 'https://project2.com',
                'github_url'  => 'https://github.com/yourhandle/project2',
                'image_url'   => null,
            ],
            [
                'title'       => 'Your Third Project',
                'description' => 'Another project description here.',
                'tech_stack'  => 'React, JavaScript',
                'live_url'    => null,
                'github_url'  => 'https://github.com/yourhandle/project3',
                'image_url'   => null,
            ],
            // keep adding as many as you have...
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}