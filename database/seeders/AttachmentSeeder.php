<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attachment;
use App\Models\Shipment;
use App\Models\Company;
use App\Models\Invoice;

class AttachmentSeeder extends Seeder
{
    public function run()
    {
        $shipments = Shipment::all();
        $companies = Company::all();
        $invoices = Invoice::all();

        $attachments = [
            // Shipment attachments
            [
                'attachment_code' => Attachment::generateCode(),
                'attachable_type' => Shipment::class,
                'attachable_id' => $shipments->first()?->id ?? 1,
                'file_name' => 'bill_of_lading_LGF202501001.pdf',
                'original_name' => 'Bill of Lading - LGF202501001.pdf',
                'file_path' => 'attachments/shipments/2024/01/bill_of_lading_LGF202501001.pdf',
                'file_type' => 'document',
                'mime_type' => 'application/pdf',
                'file_size' => 245760, // 240 KB
                'category' => 'bill_of_lading',
                'description' => 'Original bill of lading document',
                'uploaded_by_type' => 'App\Models\User',
                'uploaded_by_id' => 1,
                'is_public' => true,
                'is_required' => true,
            ],
            [
                'attachment_code' => Attachment::generateCode(),
                'attachable_type' => Shipment::class,
                'attachable_id' => $shipments->first()?->id ?? 1,
                'file_name' => 'commercial_invoice_LGF202501001.pdf',
                'original_name' => 'Commercial Invoice.pdf',
                'file_path' => 'attachments/shipments/2024/01/commercial_invoice_LGF202501001.pdf',
                'file_type' => 'document',
                'mime_type' => 'application/pdf',
                'file_size' => 156340, // 152 KB
                'category' => 'commercial_invoice',
                'description' => 'Commercial invoice for customs clearance',
                'uploaded_by_type' => 'App\Models\User',
                'uploaded_by_id' => 1,
                'is_public' => true,
                'is_required' => true,
            ],
            [
                'attachment_code' => Attachment::generateCode(),
                'attachable_type' => Shipment::class,
                'attachable_id' => $shipments->first()?->id ?? 1,
                'file_name' => 'container_photos_001.jpg',
                'original_name' => 'Container Loading Photos.jpg',
                'file_path' => 'attachments/shipments/2024/01/container_photos_001.jpg',
                'file_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 2048576, // 2 MB
                'category' => 'photos',
                'description' => 'Container loading verification photos',
                'uploaded_by_type' => 'App\Models\Employee',
                'uploaded_by_id' => 1,
                'is_public' => false,
                'is_required' => false,
            ],
            // Company attachments
            [
                'attachment_code' => Attachment::generateCode(),
                'attachable_type' => Company::class,
                'attachable_id' => $companies->first()?->id ?? 1,
                'file_name' => 'trade_license_ABC001.pdf',
                'original_name' => 'Trade License - ABC Trading.pdf',
                'file_path' => 'attachments/companies/2024/01/trade_license_ABC001.pdf',
                'file_type' => 'document',
                'mime_type' => 'application/pdf',
                'file_size' => 512000, // 500 KB
                'category' => 'license',
                'description' => 'Valid trade license certificate',
                'uploaded_by_type' => 'App\Models\User',
                'uploaded_by_id' => 1,
                'is_public' => false,
                'is_required' => true,
                'expires_at' => now()->addYears(2),
            ],
            // Invoice attachments
            [
                'attachment_code' => Attachment::generateCode(),
                'attachable_type' => Invoice::class,
                'attachable_id' => $invoices->first()?->id ?? 1,
                'file_name' => 'signed_invoice_INV202401001.pdf',
                'original_name' => 'Signed Invoice Copy.pdf',
                'file_path' => 'attachments/invoices/2024/01/signed_invoice_INV202401001.pdf',
                'file_type' => 'document',
                'mime_type' => 'application/pdf',
                'file_size' => 387420, // 378 KB
                'category' => 'signed_copy',
                'description' => 'Customer signed invoice copy',
                'uploaded_by_type' => 'App\Models\User',
                'uploaded_by_id' => 1,
                'is_public' => true,
                'is_required' => false,
            ]
        ];

        foreach ($attachments as $attachmentData) {
            Attachment::create($attachmentData);
        }

        $this->command->info('Attachments seeded successfully!');
    }
}
