<?php

namespace App\Services;

use App\Models\Management\Account\Account;

class AccountService
{
    public function create(array $data)
    {
        return Account::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'type' => $data['type'],
            'category' => $data['category'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'balance' => 0
        ]);
    }

    public function update(Account $account, array $data)
    {
        $account->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'type' => $data['type'],
            'category' => $data['category'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $account;
    }
}
