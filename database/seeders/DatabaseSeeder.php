<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin / demo user
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@ssms.edu',
            'password' => Hash::make('password'),
        ]);

        // Sample subjects
        $subjects = [
            ['subject_code' => 'CS101',  'subject_name' => 'Introduction to Computing',       'description' => 'Fundamentals of computer science and programming concepts.',          'units' => 3, 'semester' => '1st Semester'],
            ['subject_code' => 'MATH201','subject_name' => 'Calculus I',                       'description' => 'Differential calculus, limits, and derivatives.',                     'units' => 5, 'semester' => '1st Semester'],
            ['subject_code' => 'ENG101', 'subject_name' => 'Technical Writing',                'description' => 'Writing for professional and technical communication.',                'units' => 3, 'semester' => '1st Semester'],
            ['subject_code' => 'CS201',  'subject_name' => 'Data Structures & Algorithms',    'description' => 'Study of data structures, sorting, searching, and algorithm analysis.','units' => 3, 'semester' => '2nd Semester'],
            ['subject_code' => 'MATH202','subject_name' => 'Calculus II',                      'description' => 'Integral calculus, sequences, and series.',                           'units' => 5, 'semester' => '2nd Semester'],
            ['subject_code' => 'CS301',  'subject_name' => 'Web Development',                  'description' => 'Full-stack web development with modern frameworks.',                  'units' => 3, 'semester' => '2nd Semester'],
            ['subject_code' => 'CS401',  'subject_name' => 'Database Management Systems',     'description' => 'Relational databases, SQL, and database design principles.',          'units' => 3, 'semester' => 'Summer'],
        ];

        foreach ($subjects as $subjectData) {
            Subject::create(array_merge($subjectData, ['user_id' => $admin->id]));
        }

        // Second user
        $user2 = User::create([
            'name'     => 'Maria Santos',
            'email'    => 'maria@ssms.edu',
            'password' => Hash::make('password'),
        ]);

        Subject::create([
            'user_id'      => $user2->id,
            'subject_code' => 'IT101',
            'subject_name' => 'Introduction to Information Technology',
            'description'  => 'Overview of IT concepts and applications.',
            'units'        => 3,
            'semester'     => '1st Semester',
        ]);
    }
}
