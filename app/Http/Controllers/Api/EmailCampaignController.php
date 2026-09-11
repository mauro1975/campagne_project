<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailCampaignResource;
use App\Models\EmailCampaign;
use Illuminate\Http\Request;

class EmailCampaignController extends Controller
{
    public function index()
    {
        return EmailCampaignResource::collection(EmailCampaign::latest()->paginate(20));
    }

    public function show(EmailCampaign $emailCampaign)
    {
        return new EmailCampaignResource($emailCampaign->load('logs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'subject'             => 'required|string|max:255',
            'body'                => 'required|string',
            'filter_product_ids'  => 'nullable|array',
            'filter_category_ids' => 'nullable|array',
            'min_orders'          => 'nullable|integer|min:0',
            'target_registered'   => 'boolean',
            'target_guests'       => 'boolean',
        ]);

        $data['status'] = 'draft';

        $campaign = EmailCampaign::create($data);

        return (new EmailCampaignResource($campaign))->response()->setStatusCode(201);
    }

    public function update(Request $request, EmailCampaign $emailCampaign)
    {
        $data = $request->validate([
            'name'                => 'string|max:255',
            'subject'             => 'string|max:255',
            'body'                => 'string',
            'status'              => 'in:draft,scheduled,sent',
            'filter_product_ids'  => 'nullable|array',
            'filter_category_ids' => 'nullable|array',
            'min_orders'          => 'nullable|integer|min:0',
            'target_registered'   => 'boolean',
            'target_guests'       => 'boolean',
        ]);

        $emailCampaign->update($data);

        return new EmailCampaignResource($emailCampaign);
    }

    public function destroy(EmailCampaign $emailCampaign)
    {
        $emailCampaign->delete();

        return response()->json(['message' => 'Campaign deleted.']);
    }
}
