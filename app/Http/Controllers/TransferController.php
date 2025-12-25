<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Services\TransferService;
use App\Models\User;
use Exception;
class TransferController extends Controller
{

    private TransferService $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function sendMoney(Request $request): JsonResponse {

        $validated = $request->validate([
            'wallet_number' => 'required|exists:wallets,wallet_number',
            'amount'        => 'required|numeric|min:1',
        ]);

        try {

            $sender = auth()->user();
            $receiverWallet = Wallet::where('wallet_number', $validated['wallet_number'])->firstOrFail();
            $this->transferService->execute($sender, $receiverWallet, (float)$validated['amount']);
            return response()->json(['status' => 'success', 'message' => 'Transfer başarılı.'], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }
}
