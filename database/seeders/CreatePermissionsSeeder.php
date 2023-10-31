<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class CreatePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        #product
            DB::table('permissions')->insert([
                'name' => 'product_list',
                'display_name' => 'Product List',
                'group' => 'product',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'create_product',
                'display_name' => 'New Product',
                'group' => 'product',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'edit_product',
                'display_name' => 'Edit Product',
                'group' => 'product',
                'guard_name' => 'web',
            ]);
    
    
            DB::table('permissions')->insert([
                'name' => 'export_product',
                'display_name' => 'Export Product',
                'group' => 'product',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'import_product',
                'display_name' => 'Import Product',
                'group' => 'product',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'product_history',
                'display_name' => 'Product History',
                'group' => 'product',
                'guard_name' => 'web',
            ]);

        #transfer
            DB::table('permissions')->insert([
                'name' => 'transfer_list',
                'display_name' => 'Transfer List',
                'group' => 'transfer',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'create_transfer',
                'display_name' => 'New Transfer',
                'group' => 'transfer',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'edit_transfer',
                'display_name' => 'Edit Transfer',
                'group' => 'transfer',
                'guard_name' => 'web',
            ]);
    
    
            DB::table('permissions')->insert([
                'name' => 'export_transfer',
                'display_name' => 'Export Transfer',
                'group' => 'transfer',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'import_transfer',
                'display_name' => 'Import Transfer',
                'group' => 'transfer',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'transfer_history',
                'display_name' => 'Transfer History',
                'group' => 'transfer',
                'guard_name' => 'web',
            ]);

        #mr
            DB::table('permissions')->insert([
                'name' => 'mr_list',
                'display_name' => 'MR List',
                'group' => 'mr',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'create_mr',
                'display_name' => 'New MR',
                'group' => 'mr',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'edit_mr',
                'display_name' => 'Edit MR',
                'group' => 'mr',
                'guard_name' => 'web',
            ]);
    
    
            DB::table('permissions')->insert([
                'name' => 'export_mr',
                'display_name' => 'Export MR',
                'group' => 'mr',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'import_mr',
                'display_name' => 'Import MR',
                'group' => 'mr',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'mr_history',
                'display_name' => 'MR History',
                'group' => 'mr',
                'guard_name' => 'web',
            ]);
   
                
        #mrr
            DB::table('permissions')->insert([
                'name' => 'mrr_list',
                'display_name' => 'MRR List',
                'group' => 'mrr',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'create_mrr',
                'display_name' => 'New MRR',
                'group' => 'mrr',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'edit_mrr',
                'display_name' => 'Edit MRR',
                'group' => 'mrr',
                'guard_name' => 'web',
            ]);
    
    
            DB::table('permissions')->insert([
                'name' => 'export_mrr',
                'display_name' => 'Export MRR',
                'group' => 'mrr',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'import_mrr',
                'display_name' => 'Import MRR',
                'group' => 'mrr',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'mrr_history',
                'display_name' => 'MRR History',
                'group' => 'mrr',
                'guard_name' => 'web',
            ]);
        #department
            DB::table('permissions')->insert([
                'name' => 'department_list',
                'display_name' => 'Department List',
                'group' => 'department',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'create_department',
                'display_name' => 'New Department',
                'group' => 'department',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'edit_department',
                'display_name' => 'Edit Department',
                'group' => 'department',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'delete_department',
                'display_name' => 'Department History',
                'group' => 'Department',
                'guard_name' => 'web',
            ]);
        
        
        #adjustment
            DB::table('permissions')->insert([
                'name' => 'adjustment_list',
                'display_name' => 'Adjustment List',
                'group' => 'adjustment',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_adjustment',
                'display_name' => 'New Adjustment',
                'group' => 'adjustment',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_adjustment',
                'display_name' => 'Edit Adjustment',
                'group' => 'adjustment',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'adjustment_history',
                'display_name' => 'Adjustment History',
                'group' => 'adjustment',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'export_adjustment',
                'display_name' => 'Export Adjustment',
                'group' => 'adjustment',
                'guard_name' => 'web',
            ]);

        #supplier Return
            DB::table('permissions')->insert([
                'name' => 'supplier_return_list',
                'display_name' => 'Supplier Return List',
                'group' => 'supplier_return',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_supplier_return',
                'display_name' => 'New Supplier Return',
                'group' => 'supplier_return',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_supplier_return',
                'display_name' => 'Edit Supplier Return',
                'group' => 'supplier_return',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'supplier_return_history',
                'display_name' => 'Supplier Return History',
                'group' => 'supplier_return',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'export_supplier_return',
                'display_name' => 'Export Supplier Return',
                'group' => 'supplier_return',
                'guard_name' => 'web',
            ]);

        #warehouse
            DB::table('permissions')->insert([
                'name' => 'warehouse_list',
                'display_name' => 'Warehouse List',
                'group' => 'warehouse',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_warehouse',
                'display_name' => 'New Warehouse',
                'group' => 'warehouse',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_warehouse',
                'display_name' => 'Edit Warehouse',
                'group' => 'warehouse',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'delete_warehouse',
                'display_name' => 'Delete Warehouse',
                'group' => 'warehouse',
                'guard_name' => 'web',
            ]);

        #shelf
            DB::table('permissions')->insert([
                'name' => 'shelf_list',
                'display_name' => 'Shelf List',
                'group' => 'shelf',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_shelf',
                'display_name' => 'New Shelf',
                'group' => 'shelf',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_shelf',
                'display_name' => 'Edit Shelf',
                'group' => 'shelf',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'delete_shelf',
                'display_name' => 'Delete Shelf',
                'group' => 'shelf',
                'guard_name' => 'web',
            ]);

        #shelf_number
            DB::table('permissions')->insert([
                'name' => 'shelf_number_list',
                'display_name' => 'Shelf Number List',
                'group' => 'shelf_number',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_shelf_number',
                'display_name' => 'New Shelf Number',
                'group' => 'shelf',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_shelf_number',
                'display_name' => 'Edit Shelf Number',
                'group' => 'shelf_number',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'delete_shelf_number',
                'display_name' => 'Delete Shelf Number',
                'group' => 'shelf_number',
                'guard_name' => 'web',
            ]);

        #brand
            DB::table('permissions')->insert([
                'name' => 'brand_list',
                'display_name' => 'Brand List',
                'group' => 'brand',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_brand',
                'display_name' => 'New Brand',
                'group' => 'brand',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_brand',
                'display_name' => 'Edit Brand',
                'group' => 'brand',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'delete_brand',
                'display_name' => 'Delete Brand',
                'group' => 'brand',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'import_brand',
                'display_name' => 'Import Brand',
                'group' => 'brand',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'export_brand',
                'display_name' => 'Export Brand',
                'group' => 'brand',
                'guard_name' => 'web',
            ]);

        #commodity
            DB::table('permissions')->insert([
                'name' => 'commodity_list',
                'display_name' => 'Commodity List',
                'group' => 'commodity',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_commodity',
                'display_name' => 'New Commodity',
                'group' => 'commodity',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_commodity',
                'display_name' => 'Edit Commodity',
                'group' => 'commodity',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'delete_commodity',
                'display_name' => 'Delete Commodity',
                'group' => 'commodity',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'import_commodity',
                'display_name' => 'Import Commodity',
                'group' => 'commodity',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'export_commodity',
                'display_name' => 'Export Commodity',
                'group' => 'commodity',
                'guard_name' => 'web',
            ]);

        #unit
            DB::table('permissions')->insert([
                'name' => 'unit_list',
                'display_name' => 'Unit List',
                'group' => 'unit',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_unit',
                'display_name' => 'New Unit',
                'group' => 'unit',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_unit',
                'display_name' => 'Edit Unit',
                'group' => 'unit',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'delete_unit',
                'display_name' => 'Delete Unit',
                'group' => 'unit',
                'guard_name' => 'web',
            ]);

        #code
            DB::table('permissions')->insert([
                'name' => 'code_list',
                'display_name' => 'Code List',
                'group' => 'code',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_code',
                'display_name' => 'New Code',
                'group' => 'code',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_code',
                'display_name' => 'Edit Code',
                'group' => 'code',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'delete_code',
                'display_name' => 'Delete Code',
                'group' => 'code',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'export_code',
                'display_name' => 'Export Code',
                'group' => 'code',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'import_code',
                'display_name' => 'Import Code',
                'group' => 'code',
                'guard_name' => 'web',
            ]);

        #supplier
            DB::table('permissions')->insert([
                'name' => 'supplier_list',
                'display_name' => 'Supplier List',
                'group' => 'supplier',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_supplier',
                'display_name' => 'New Supplier',
                'group' => 'supplier',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_supplier',
                'display_name' => 'Edit Supplier',
                'group' => 'supplier',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'delete_supplier',
                'display_name' => 'Delete Supplier',
                'group' => 'supplier',
                'guard_name' => 'web',
            ]);

        #customer
            DB::table('permissions')->insert([
                'name' => 'customer_list',
                'display_name' => 'Customer List',
                'group' => 'customer',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_customer',
                'display_name' => 'New Customer',
                'group' => 'customer',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_customer',
                'display_name' => 'Edit Customer',
                'group' => 'customer',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'delete_customer',
                'display_name' => 'Delete Customer',
                'group' => 'customer',
                'guard_name' => 'web',
            ]);

        #user
            DB::table('permissions')->insert([
                'name' => 'user_list',
                'display_name' => 'User List',
                'group' => 'user',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_user',
                'display_name' => 'New User',
                'group' => 'user',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'edit_user',
                'display_name' => 'Edit User',
                'group' => 'user',
                'guard_name' => 'web',
            ]);
    
            DB::table('permissions')->insert([
                'name' => 'delete_user',
                'display_name' => 'Delete User',
                'group' => 'user',
                'guard_name' => 'web',
            ]);

        #permission
            DB::table('permissions')->insert([
                'name' => 'permission_list',
                'display_name' => 'Permission List',
                'group' => 'permission',
                'guard_name' => 'web',
            ]);

            DB::table('permissions')->insert([
                'name' => 'create_permission',
                'display_name' => 'New Permission',
                'group' => 'permission',
                'guard_name' => 'web',
            ]);
        #instock
            DB::table('permissions')->insert([
                'name' => 'instock_list',
                'display_name' => 'Instock ',
                'group' => 'instock',
                'guard_name' => 'web',
            ]);
      
    }
    
}
