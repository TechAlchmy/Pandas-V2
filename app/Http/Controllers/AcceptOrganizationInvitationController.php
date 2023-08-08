<?php

namespace App\Http\Controllers;

use App\Models\OrganizationInvitation;
use App\Models\User;
use Illuminate\Http\Request;

class AcceptOrganizationInvitationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, OrganizationInvitation $organizationInvitation)
    {
        $user = User::query()
            ->firstWhere('email', $organizationInvitation->email);

        $user->organization()->associate($organizationInvitation->organization)->save();

        if ($organizationInvitation->is_manager) {
            $user->managers()->create([
                'organization_id' => $organizationInvitation->organization->getKey(),
            ]);
        }

        $organizationInvitation->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Invitation accepted');
    }
}
