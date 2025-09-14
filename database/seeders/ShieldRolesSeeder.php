<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class ShieldRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Roles base
        $super       = Role::firstOrCreate(['name' => 'super_admin',  'guard_name' => 'web']);
        $tenantAdmin = Role::firstOrCreate(['name' => 'tenant_admin', 'guard_name' => 'web']);
        $tenantDemo  = Role::firstOrCreate(['name' => 'tenant_demo',  'guard_name' => 'web']);

        // SUPER: todo
        $all = Permission::all();
        $super->syncPermissions($all);

        // ¿Tu tabla permissions tiene panel_id? (Shield reciente lo agrega)
        $hasPanel = Schema::hasColumn('permissions', 'panel_id');

        // Fragmentos del tenant para fallback SIN panel_id
        $tenantAllowed = [
            'rifa', 'order', 'payment::account',
            'brand::setting', 'home::setting', 'contact::setting',
            'footer::setting', 'legal::setting', 'faq::item',
            // páginas/widgets del tenant:
            'page_Reports', 'widget_',
        ];

        // 1) Permisos del panel tenant
        if ($hasPanel) {
            $tenantPerms = Permission::where('panel_id', 'tenant')->get();
        } else {
            // Fallback: tomar solo lo que claramente pertenece al tenant
            $tenantPerms = Permission::all()->filter(function ($p) use ($tenantAllowed) {
                $n = $p->name;
                if ($n === 'page_Reports' || str_starts_with($n, 'widget_')) return true;
                foreach ($tenantAllowed as $frag) {
                    if (in_array($frag, ['page_Reports','widget_'], true)) continue;
                    if (str_contains($n, $frag)) return true;
                }
                return false;
            })->values();
        }

        // 2) tenant_admin: TODO del tenant, PERO sin role/permission/user/tenant
        $excludeFragments = ['role', 'permission', 'user', 'tenant'];
        $tenantAdminPerms = $tenantPerms->reject(function ($p) use ($excludeFragments) {
            $n = $p->name;
            foreach ($excludeFragments as $frag) {
                if (str_contains($n, $frag)) return true;
            }
            return false;
        })->values();

        $tenantAdmin->syncPermissions($tenantAdminPerms);

        // 3) tenant_demo: SOLO LECTURA (view_*, page_*, widget_*)
        $demoPerms = $tenantPerms->filter(function ($p) {
            $n = $p->name;
            return str_starts_with($n, 'view')
                || str_starts_with($n, 'page_')
                || str_starts_with($n, 'widget_');
        })->values();

        $tenantDemo->syncPermissions($demoPerms);

        // 4) Asignaciones por defecto (ajusta emails/IDs)
        if ($gus = User::where('email', 'roottgus@gmail.com')->first()) {
            // Tú: control absoluto + administración de tenant
            $gus->syncRoles(['super_admin', 'tenant_admin']);
        }
        if ($seed = User::where('email', 'admin@example.com')->first()) {
            // Usuario seed como admin de tenant (o déjalo sin roles si no lo usas)
            $seed->syncRoles(['tenant_admin']);
        }

        // (Opcional) Si existía 'tenant_staff' y no lo usarás:
        // \Spatie\Permission\Models\Role::where('name', 'tenant_staff')->delete();
    }
}
