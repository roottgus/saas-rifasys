<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ManualPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permisos = [
            "view_role","view_any_role","create_role","update_role","delete_role","delete_any_role",
            "view_tenant","view_any_tenant","create_tenant","update_tenant","restore_tenant",
            "restore_any_tenant","replicate_tenant","reorder_tenant","delete_tenant","delete_any_tenant",
            "force_delete_tenant","force_delete_any_tenant","view_user","view_any_user","create_user",
            "update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user",
            "delete_any_user","force_delete_user","force_delete_any_user","widget_QuickActionsWidget",
            "widget_StatsOverviewWidget","widget_RecentActivityWidget","widget_SalesChartWidget",
            "view_brand::setting","view_any_brand::setting","create_brand::setting","update_brand::setting",
            "restore_brand::setting","restore_any_brand::setting","replicate_brand::setting",
            "reorder_brand::setting","delete_brand::setting","delete_any_brand::setting",
            "force_delete_brand::setting","force_delete_any_brand::setting","view_contact::setting",
            "view_any_contact::setting","create_contact::setting","update_contact::setting",
            "restore_contact::setting","restore_any_contact::setting","replicate_contact::setting",
            "reorder_contact::setting","delete_contact::setting","delete_any_contact::setting",
            "force_delete_contact::setting","force_delete_any_contact::setting","view_faq::item",
            "view_any_faq::item","create_faq::item","update_faq::item","restore_faq::item",
            "restore_any_faq::item","replicate_faq::item","reorder_faq::item","delete_faq::item",
            "delete_any_faq::item","force_delete_faq::item","force_delete_any_faq::item","view_footer::setting",
            "view_any_footer::setting","create_footer::setting","update_footer::setting",
            "restore_footer::setting","restore_any_footer::setting","replicate_footer::setting",
            "reorder_footer::setting","delete_footer::setting","delete_any_footer::setting",
            "force_delete_footer::setting","force_delete_any_footer::setting","view_home::setting",
            "view_any_home::setting","create_home::setting","update_home::setting","restore_home::setting",
            "restore_any_home::setting","replicate_home::setting","reorder_home::setting","delete_home::setting",
            "delete_any_home::setting","force_delete_home::setting","force_delete_any_home::setting",
            "view_legal::setting","view_any_legal::setting","create_legal::setting","update_legal::setting",
            "restore_legal::setting","restore_any_legal::setting","replicate_legal::setting",
            "reorder_legal::setting","delete_legal::setting","delete_any_legal::setting",
            "force_delete_legal::setting","force_delete_any_legal::setting","view_order","view_any_order",
            "create_order","update_order","restore_order","restore_any_order","replicate_order","reorder_order",
            "delete_order","delete_any_order","force_delete_order","force_delete_any_order",
            "view_payment::account","view_any_payment::account","create_payment::account",
            "update_payment::account","restore_payment::account","restore_any_payment::account",
            "replicate_payment::account","reorder_payment::account","delete_payment::account",
            "delete_any_payment::account","force_delete_payment::account","force_delete_any_payment::account",
            "view_rifa","view_any_rifa","create_rifa","update_rifa","restore_rifa","restore_any_rifa",
            "replicate_rifa","reorder_rifa","delete_rifa","delete_any_rifa","force_delete_rifa",
            "force_delete_any_rifa","page_Reports","widget_WelcomeWidget","widget_TenantStatsWidget",
            "widget_RecentSalesWidget",
        ];

        foreach($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }
    }
}
