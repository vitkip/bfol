<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // News
            'news.view', 'news.create', 'news.edit', 'news.delete', 'news.publish',
            // Events
            'events.view', 'events.create', 'events.edit', 'events.delete',
            // Pages
            'pages.view', 'pages.create', 'pages.edit', 'pages.delete',
            // Media
            'media.view', 'media.upload', 'media.delete',
            // Documents
            'documents.view', 'documents.upload', 'documents.delete',
            // Partners
            'partners.view', 'partners.create', 'partners.edit', 'partners.delete',
            // MOU
            'mou.view', 'mou.create', 'mou.edit', 'mou.delete',
            // Monk Programs
            'monk-programs.view', 'monk-programs.create', 'monk-programs.edit', 'monk-programs.delete',
            // Aid Projects
            'aid-projects.view', 'aid-projects.create', 'aid-projects.edit', 'aid-projects.delete',
            // Committee
            'committee.view', 'committee.create', 'committee.edit', 'committee.delete',
            // Contacts
            'contacts.view', 'contacts.reply', 'contacts.delete',
            // Slides & Banners
            'slides.manage', 'banners.manage',
            // Settings
            'settings.view', 'settings.edit',
            // Users
            'users.view', 'users.create', 'users.edit', 'users.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $roles = [
            'superadmin' => $permissions,
            'admin'      => array_filter($permissions, fn($p) => !str_starts_with($p, 'users.')),
            'editor'     => [
                'news.view', 'news.create', 'news.edit',
                'events.view', 'events.create', 'events.edit',
                'pages.view', 'pages.edit',
                'media.view', 'media.upload',
                'documents.view', 'documents.upload',
                'contacts.view',
            ],
            'viewer'     => [
                'news.view', 'events.view', 'pages.view',
                'media.view', 'documents.view', 'contacts.view',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions(array_values($rolePermissions));
        }
    }
}
