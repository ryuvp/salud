<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('guard_name')->default('api');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description');
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->tinyInteger('category')->comment('0:section, 1:menu, 2:menu_link');
            $table->string('guard_name')->default('api');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned();
            $table->morphs('model');

            $table->foreign('role_id')->references('id')->on('roles')
                ->onDelete('cascade');
        });

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->bigInteger('permission_id')->unsigned();
            $table->morphs('model');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onDelete('cascade');
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->bigInteger('permission_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onDelete('cascade');
        });

        $roles = [
            ['name' => 'superadmin', 'guard_name' => 'api'],
            ['name' => 'administrator', 'guard_name' => 'api'],
            ['name' => 'editor', 'guard_name' => 'api'],
            ['name' => 'paciente', 'guard_name' => 'api'],
        ];
        foreach ($roles as $roleData) {
            Role::create($roleData);
        }

        $permissions = [
            ['name' => 'home', 'guard_name' => 'api', 'description' => 'inicio', 'route' => '', 'icon' => '', 'category' => 0, 'parent_id' => null],
            ['name' => 'home.dashboard', 'guard_name' => 'api', 'description' => 'panel principal', 'route' => '/dashboard', 'icon' => 'pi pi-home', 'category' => 2, 'parent_id' => 1],
            ['name' => 'manage', 'guard_name' => 'api', 'description' => 'administrar', 'route' => '', 'icon' => '', 'category' => 0, 'parent_id' => null],
            ['name' => 'manage.users', 'guard_name' => 'api', 'description' => 'usuarios', 'route' => '/users', 'icon' => 'pi pi-users', 'category' => 2, 'parent_id' => 3],
            ['name' => 'manage.roles', 'guard_name' => 'api', 'description' => 'roles', 'route' => '/roles', 'icon' => 'pi pi-lock', 'category' => 2, 'parent_id' => 3],
            ['name' => 'manage.permissions', 'guard_name' => 'api', 'description' => 'permisos', 'route' => '/permissions', 'icon' => 'pi pi-ban', 'category' => 2, 'parent_id' => 3],
            ['name' => 'profile', 'guard_name' => 'api', 'description' => 'perfil', 'route' => '', 'icon' => '', 'category' => 0, 'parent_id' => null],
            ['name' => 'profile.user', 'guard_name' => 'api', 'description' => 'mi perfil', 'route' => '', 'icon' => 'pi pi-user', 'category' => 2, 'parent_id' => 7],
            ['name' => 'patient', 'guard_name' => 'api', 'description' => 'pacientes', 'route' => '', 'icon' => '', 'category' => 0, 'parent_id' => null],
            ['name' => 'patient.register', 'guard_name' => 'api', 'description' => 'registro', 'route' => '/patients', 'icon' => 'pi pi-users', 'category' => 2, 'parent_id' => 9],
            ['name' => 'patient.follow', 'guard_name' => 'api', 'description' => 'seguimiento', 'route' => '/patients/follow', 'icon' => 'pi pi-pencil', 'category' => 2, 'parent_id' => 9],
            ['name' => 'patient.report', 'guard_name' => 'api', 'description' => 'reportes', 'route' => '/patients/report', 'icon' => 'pi pi-file', 'category' => 2, 'parent_id' => 9],
        ];
        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
        }

        $superadminRole = Role::where('name', 'superadmin')->first();
        $adminRole = Role::where('name', 'administrator')->first();
        $editorRole = Role::where('name', 'editor')->first();
        //$patientRole = Role::where('name', 'paciente')->first();

        $permissions = Permission::all();

        $superadminRole->givePermissionTo($permissions);
        $adminRole->givePermissionTo([
            'home',
            'home.dashboard',
            'manage',
            'manage.users',
            'profile',
            'profile.user',
            'patient',
            'patient.register',
            'patient.follow',
            'patient.report',
        ]);

        $editorRole->givePermissionTo([
            'home',
            'home.dashboard',
            'profile',
            'profile.user',
            'patient',
            'patient.register',
            'patient.follow',
            'patient.report',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
