<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    /**
     * Display all proposals
     */
    public function index()
    {
        $proposals = Proposal::with(['submitter', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('proposals.index', compact('proposals'));
    }

    /**
     * Display proposals awaiting approval
     */
    public function approval()
    {
        $proposals = Proposal::with(['submitter'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('proposals.approval', compact('proposals'));
    }
}
