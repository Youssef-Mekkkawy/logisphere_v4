<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class AllStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $statuses = [
            // Shipment Statuses
            ['module' => 'shipment', 'status_code' => 'PENDING', 'status_name' => 'Pending', 'display_name' => 'Pending Processing', 'color' => '#f59e0b', 'icon' => '⏳', 'is_default' => true],
            ['module' => 'shipment', 'status_code' => 'CONFIRMED', 'status_name' => 'Confirmed', 'display_name' => 'Booking Confirmed', 'color' => '#3b82f6', 'icon' => '✅'],
            ['module' => 'shipment', 'status_code' => 'IN_TRANSIT', 'status_name' => 'In Transit', 'display_name' => 'In Transit', 'color' => '#8b5cf6', 'icon' => '🚢'],
            ['module' => 'shipment', 'status_code' => 'AT_PORT', 'status_name' => 'At Port', 'display_name' => 'Arrived at Port', 'color' => '#06b6d4', 'icon' => '🏠'],
            ['module' => 'shipment', 'status_code' => 'CUSTOMS', 'status_name' => 'Customs Clearance', 'display_name' => 'Customs Processing', 'color' => '#f97316', 'icon' => '📋'],
            ['module' => 'shipment', 'status_code' => 'DELIVERED', 'status_name' => 'Delivered', 'display_name' => 'Successfully Delivered', 'color' => '#10b981', 'icon' => '✅', 'is_final' => true],
            ['module' => 'shipment', 'status_code' => 'CANCELLED', 'status_name' => 'Cancelled', 'display_name' => 'Shipment Cancelled', 'color' => '#ef4444', 'icon' => '❌', 'is_final' => true],

            // Employee Statuses
            ['module' => 'employee', 'status_code' => 'ACTIVE', 'status_name' => 'Active', 'display_name' => 'Active Employee', 'color' => '#10b981', 'icon' => '✅', 'is_default' => true],
            ['module' => 'employee', 'status_code' => 'INACTIVE', 'status_name' => 'Inactive', 'display_name' => 'Inactive Employee', 'color' => '#6b7280', 'icon' => '⏸️'],
            ['module' => 'employee', 'status_code' => 'ON_LEAVE', 'status_name' => 'On Leave', 'display_name' => 'On Leave', 'color' => '#f59e0b', 'icon' => '🏖️'],
            ['module' => 'employee', 'status_code' => 'TERMINATED', 'status_name' => 'Terminated', 'display_name' => 'Employment Terminated', 'color' => '#ef4444', 'icon' => '❌', 'is_final' => true],

            // Company Statuses
            ['module' => 'company', 'status_code' => 'ACTIVE', 'status_name' => 'Active', 'display_name' => 'Active Client', 'color' => '#10b981', 'icon' => '✅', 'is_default' => true],
            ['module' => 'company', 'status_code' => 'INACTIVE', 'status_name' => 'Inactive', 'display_name' => 'Inactive Client', 'color' => '#6b7280', 'icon' => '⏸️'],
            ['module' => 'company', 'status_code' => 'SUSPENDED', 'status_name' => 'Suspended', 'display_name' => 'Account Suspended', 'color' => '#f59e0b', 'icon' => '⚠️'],
            ['module' => 'company', 'status_code' => 'BLACKLISTED', 'status_name' => 'Blacklisted', 'display_name' => 'Blacklisted', 'color' => '#ef4444', 'icon' => '🚫'],

            // Invoice Statuses
            ['module' => 'invoice', 'status_code' => 'DRAFT', 'status_name' => 'Draft', 'display_name' => 'Draft Invoice', 'color' => '#6b7280', 'icon' => '📝', 'is_default' => true],
            ['module' => 'invoice', 'status_code' => 'SENT', 'status_name' => 'Sent', 'display_name' => 'Invoice Sent', 'color' => '#3b82f6', 'icon' => '📤'],
            ['module' => 'invoice', 'status_code' => 'PAID', 'status_name' => 'Paid', 'display_name' => 'Fully Paid', 'color' => '#10b981', 'icon' => '💰', 'is_final' => true],
            ['module' => 'invoice', 'status_code' => 'OVERDUE', 'status_name' => 'Overdue', 'display_name' => 'Payment Overdue', 'color' => '#ef4444', 'icon' => '⚠️'],
            ['module' => 'invoice', 'status_code' => 'CANCELLED', 'status_name' => 'Cancelled', 'display_name' => 'Invoice Cancelled', 'color' => '#6b7280', 'icon' => '❌', 'is_final' => true],
        ];

        foreach ($statuses as $status) {
            DB::table('all_status')->insert(array_merge($status, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}
