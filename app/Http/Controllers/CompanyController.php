<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::paginate(20);
        return view('dashboard.companies', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Client,Supplier',
            'contact_person' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'country' => 'nullable|string',
            'address' => 'nullable|string',
            'service_type' => 'nullable|string'
        ]);

        Company::create($request->all());

        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully!');
    }

    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Client,Supplier',
            'contact_person' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'country' => 'nullable|string',
            'address' => 'nullable|string',
            'service_type' => 'nullable|string'
        ]);

        $company->update($request->all());

        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully!');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully!');
    }
}
