<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    private $apiKey;
    private $secretKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.bayarcash.api_key');
        $this->secretKey = config('services.bayarcash.secret_key');
        
        // Use sandbox or production API URL
        $this->apiUrl = config('services.bayarcash.sandbox', true) 
            ? 'https://console.bayarcash-sandbox.com/api/v2'
            : 'https://console.bayar.cash/api/v2';
        
        // Note: Middleware exclusion is handled at route level in routes/web.php
        // No need to set it here
    }
    
    /**
     * Generate checksum for payment intent according to Bayar.cash documentation
     */
    private function generateChecksum($data)
    {
        try {
            // According to docs, these fields must be in checksum:
            // payment_channel, order_number, amount, payer_name, payer_email
            $payload = [
                'payment_channel' => (int) $data['payment_channel'], // Cast to integer
                'order_number' => (string) trim($data['order_number']),
                'amount' => (string) trim($data['amount']),
                'payer_name' => (string) trim($data['payer_name']),
                'payer_email' => (string) trim($data['payer_email']),
            ];
            
            // Log payload for debugging
            Log::info('Checksum payload', $payload);
            
            ksort($payload);  // Sort by key
            $payloadString = implode('|', $payload);  // Concatenate with |
            
            Log::info('Checksum string', ['string' => $payloadString]);
            
            return hash_hmac('sha256', $payloadString, $this->secretKey);
        } catch (\Exception $e) {
            Log::error('Checksum generation error', [
                'error' => $e->getMessage(),
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Show payment page for member registration
     */
    public function showRegistrationPayment($memberId)
    {
        $user = auth()->user();
        $member = Member::findOrFail($memberId);
        
        // Check if user is authorized to pay for this member
        $memberIds = \App\Models\ParentStudent::where('parent_user_id', $user->id)
            ->pluck('member_id');
        
        if (!$memberIds->contains($memberId)) {
            abort(403, 'Unauthorized access');
        }

        // Check if already paid
        $existingInvoice = Invoice::where('member_id', $memberId)
            ->where('type', 'membership')
            ->where('status', 'paid')
            ->first();

        if ($existingInvoice) {
            return redirect()->route('parent.dashboard')
                ->with('info', 'Registration fee already paid.');
        }

        // Get or create invoice
        $invoice = $this->getOrCreateRegistrationInvoice($member);

        return view('parent.payment.registration', compact('member', 'invoice'));
    }

    /**
     * Create payment with Bayar.cash
     */
    public function createPayment(Request $request, $invoiceId)
    {
        // Validate payment channel selection
        $request->validate([
            'payment_channel' => 'required|integer|in:1,3,6',
        ]);
        
        $invoice = Invoice::findOrFail($invoiceId);
        $member = $invoice->member;
        $parent = auth()->user();

        try {
            // Get base URL from config (will use APP_URL from .env)
            $baseUrl = rtrim(config('app.url'), '/');
            
            // Get selected payment channel from form
            $paymentChannel = (int) $request->input('payment_channel', 1);
            
            // Prepare payment intent data with all required fields
            // According to Bayar.cash docs, these are required for checksum:
            // payment_channel (int), order_number, amount, payer_name, payer_email
            $paymentData = [
                'payment_channel' => $paymentChannel, // From user selection: 1=FPX, 3=Direct Debit, 6=DuitNow QR
                'order_number' => $invoice->invoice_number,
                'amount' => number_format($invoice->total_amount, 2, '.', ''),
                'payer_name' => $parent->name,
                'payer_email' => $parent->email,
                'payer_phone' => $member->phone ?? $parent->phone ?? '',
                'description' => $this->getInvoiceDescription($invoice, $member),
                'transaction_callback_url' => $baseUrl . '/parent/payment/callback',
                'return_url' => $baseUrl . '/parent/payment/return/' . $invoice->id,
            ];
            
            // Add portal_code if configured
            $portalCode = config('services.bayarcash.portal_code');
            
            Log::info('Portal Code Config Check', [
                'portal_code_from_config' => $portalCode,
                'is_null' => is_null($portalCode),
                'is_empty' => empty($portalCode),
                'env_value' => env('BAYARCASH_PORTAL_CODE'),
            ]);
            
            if ($portalCode) {
                // Try both field names - API might expect 'portal_key' instead of 'portal_code'
                $paymentData['portal_key'] = $portalCode;
                $paymentData['portal_code'] = $portalCode; // Also send this just in case
            } else {
                Log::warning('PORTAL_CODE is missing or empty!');
            }
            
            // Generate checksum
            $paymentData['checksum'] = $this->generateChecksum($paymentData);
            
            // Log FULL payment data being sent to API
            Log::info('Creating payment intent via Direct API', [
                'api_url' => $this->apiUrl,
                'full_payload' => $paymentData,  // Log everything untuk debug
            ]);

            // Create payment intent using Direct API
            $response = Http::withoutVerifying() // Disable SSL for sandbox
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl . '/payment-intents', $paymentData);

            // Log full response for debugging
            Log::info('Bayar.cash Direct API Response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->json(),
                'headers' => $response->headers()
            ]);

            // Check if request was successful
            if ($response->successful()) {
                $data = $response->json();
                
                // Extract payment URL and ID from response
                $paymentUrl = $data['url'] ?? 
                             $data['payment_url'] ?? 
                             $data['checkout_url'] ?? 
                             ($data['data']['url'] ?? null);
                             
                $paymentIntentId = $data['payment_intent_id'] ?? 
                                  $data['id'] ?? 
                                  ($data['data']['payment_intent_id'] ?? 
                                  ($data['data']['id'] ?? null));

                if ($paymentUrl) {
                    // Store transaction reference
                    $invoice->update([
                        'payment_gateway' => 'bayarcash',
                        'payment_reference' => $paymentIntentId,
                        'payment_url' => $paymentUrl,
                    ]);

                    Log::info('Redirecting to Bayar.cash checkout', [
                        'url' => $paymentUrl,
                        'payment_intent_id' => $paymentIntentId,
                        'invoice_id' => $invoice->id
                    ]);

                    // Redirect to Bayar.cash payment page
                    return redirect()->away($paymentUrl);
                } else {
                    Log::error('Payment URL not found in response', [
                        'response_data' => $data,
                        'invoice_number' => $invoice->invoice_number
                    ]);

                    return redirect()->back()
                        ->with('error', 'Payment URL not found in response. Please contact support.');
                }
            } else {
                // API returned an error
                $errorData = $response->json();
                
                // Format error message properly
                $errorMessage = 'Failed to create payment';
                
                if (isset($errorData['message']) && is_string($errorData['message'])) {
                    $errorMessage = $errorData['message'];
                } elseif (isset($errorData['error'])) {
                    // Handle array of errors
                    if (is_array($errorData['error'])) {
                        $errors = [];
                        foreach ($errorData['error'] as $field => $messages) {
                            if (is_array($messages)) {
                                $errors[] = ucfirst($field) . ': ' . implode(', ', $messages);
                            } else {
                                $errors[] = ucfirst($field) . ': ' . $messages;
                            }
                        }
                        $errorMessage = implode('. ', $errors);
                    } elseif (is_string($errorData['error'])) {
                        $errorMessage = $errorData['error'];
                    }
                }
                
                Log::error('Bayar.cash API Error', [
                    'status' => $response->status(),
                    'error' => $errorData,
                    'formatted_message' => $errorMessage,
                    'invoice_number' => $invoice->invoice_number
                ]);

                return redirect()->back()
                    ->with('error', 'Payment gateway error: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Payment creation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment return (after user completes payment)
     * This handles both GET and POST from Bayar.cash
     * NO AUTH REQUIRED - Public confirmation page
     */
    public function paymentReturn(Request $request, $invoiceId)
    {
        try {
            // Get invoice without authorization check (public confirmation page)
            $invoice = Invoice::withoutGlobalScopes()->findOrFail($invoiceId);
            $callbackData = $request->all();
            
            // Log return URL data for debugging
            Log::info('Payment return URL accessed', [
                'invoice_id' => $invoiceId,
                'method' => $request->method(),
                'callback_data' => $callbackData,
                'headers' => [
                    'content-type' => $request->header('Content-Type'),
                    'user-agent' => $request->header('User-Agent'),
                ]
            ]);
            
            // Verify checksum and update invoice if callback data present
            if (!empty($callbackData) && isset($callbackData['checksum'])) {
                $isValid = $this->verifyCallbackChecksum($callbackData);
                
                if ($isValid) {
                    Log::info('Callback checksum verified successfully');
                    
                    // Update invoice based on payment status
                    $this->updateInvoiceFromCallback($invoice, $callbackData);
                } else {
                    Log::warning('Invalid callback checksum', [
                        'invoice_id' => $invoiceId,
                        'data' => $callbackData
                    ]);
                }
            }
            
            // Refresh invoice to get latest status
            $invoice->refresh();
            
            // Auto-login parent user if payment is successful and not already authenticated
            if (!auth()->check() && $invoice->status === 'paid') {
                // Get the parent user from the member's parents relationship
                $member = $invoice->member;
                if ($member && $member->parents && $member->parents->count() > 0) {
                    $parentUser = $member->parents->first();
                    
                    // Login the parent user
                    auth()->login($parentUser);
                    
                    Log::info('Parent user auto-logged in after successful payment', [
                        'user_id' => $parentUser->id,
                        'invoice_id' => $invoice->id,
                        'member_id' => $member->id
                    ]);
                }
            }
            
            // Check if user is authenticated (for layout selection)
            $isAuthenticated = auth()->check();
            
            // Return proper HTML response (not JSON)
            // Use guest layout if not authenticated
            return response()
                ->view('parent.payment.return', [
                    'invoice' => $invoice,
                    'isAuthenticated' => $isAuthenticated
                ])
                ->header('Content-Type', 'text/html; charset=UTF-8');
                
        } catch (\Exception $e) {
            Log::error('Payment return error: ' . $e->getMessage(), [
                'invoice_id' => $invoiceId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('parent.dashboard')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    /**
     * Verify callback checksum according to Bayar.cash documentation
     * For v2 API: console.bayar.cash/api/v2
     */
    private function verifyCallbackChecksum($callbackData)
    {
        try {
            if (!isset($callbackData['checksum'])) {
                return false;
            }
            
            $callbackChecksum = $callbackData['checksum'];
            
            // Build payload according to Bayar.cash v2 documentation
            $payloadData = [
                'record_type' => $callbackData['record_type'] ?? '',
                'transaction_id' => $callbackData['transaction_id'] ?? '',
                'exchange_reference_number' => $callbackData['exchange_reference_number'] ?? '',
                'exchange_transaction_id' => $callbackData['exchange_transaction_id'] ?? '',
                'order_number' => $callbackData['order_number'] ?? '',
                'currency' => $callbackData['currency'] ?? '',
                'amount' => $callbackData['amount'] ?? '',
                'payer_name' => $callbackData['payer_name'] ?? '',
                'payer_email' => $callbackData['payer_email'] ?? '',
                'payer_bank_name' => $callbackData['payer_bank_name'] ?? '',
                'status' => $callbackData['status'] ?? '',
                'status_description' => $callbackData['status_description'] ?? '',
                'datetime' => $callbackData['datetime'] ?? '',
            ];
            
            ksort($payloadData);  // Sort by key
            $payloadString = implode('|', $payloadData);  // Concatenate with |
            
            $calculatedChecksum = hash_hmac('sha256', $payloadString, $this->secretKey);
            
            Log::info('Checksum verification', [
                'calculated' => $calculatedChecksum,
                'received' => $callbackChecksum,
                'match' => $calculatedChecksum === $callbackChecksum
            ]);
            
            return $calculatedChecksum === $callbackChecksum;
        } catch (\Exception $e) {
            Log::error('Checksum verification error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update invoice based on callback data
     */
    private function updateInvoiceFromCallback($invoice, $callbackData)
    {
        try {
            $status = $callbackData['status'] ?? null;
            
            // Bayar.cash status: 1=pending, 2=failed, 3=successful
            if ($status == 3 || $status == 'successful') {
                $invoice->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payment_method' => $callbackData['payer_bank_name'] ?? 'online',
                    'payment_reference' => $callbackData['transaction_id'] ?? null,
                ]);
                
                Log::info('Invoice marked as paid', [
                    'invoice_id' => $invoice->id,
                    'transaction_id' => $callbackData['transaction_id'] ?? null
                ]);
            } elseif ($status == 2 || $status == 'failed') {
                $invoice->update([
                    'status' => 'cancelled',
                ]);
                
                Log::info('Invoice marked as failed', [
                    'invoice_id' => $invoice->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update invoice from callback: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment callback (background webhook from Bayar.cash)
     */
    public function paymentCallback(Request $request)
    {
        try {
            $callbackData = $request->all();
            
            // Log callback for debugging
            Log::info('Bayar.cash callback received', [
                'data' => $callbackData,
                'ip' => $request->ip()
            ]);
            
            // Verify checksum
            if (isset($callbackData['checksum'])) {
                $isValid = $this->verifyCallbackChecksum($callbackData);
                
                if (!$isValid) {
                    Log::warning('Invalid callback checksum', [
                        'data' => $callbackData
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Invalid checksum'], 400);
                }
            } else {
                Log::warning('No checksum in callback data');
            }

            // Update invoice status
            if (isset($callbackData['order_number'])) {
                $invoice = Invoice::where('invoice_number', $callbackData['order_number'])->first();
                
                if ($invoice) {
                    $this->updateInvoiceFromCallback($invoice, $callbackData);
                } else {
                    Log::warning('Invoice not found for callback', [
                        'order_number' => $callbackData['order_number']
                    ]);
                }
            } else {
                Log::warning('No order_number in callback data', [
                    'data' => $callbackData
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Callback processed']);
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage(), [
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle payment webhook
     */
    public function paymentWebhook(Request $request)
    {
        return $this->paymentCallback($request);
    }

    /**
     * Get invoice description based on type
     */
    private function getInvoiceDescription($invoice, $member)
    {
        switch ($invoice->type) {
            case 'event':
                // Try to find the event registration
                $eventRegistration = \App\Models\EventRegistration::where('payment_invoice_id', $invoice->id)->first();
                if ($eventRegistration && $eventRegistration->event) {
                    return 'Event Registration: ' . $eventRegistration->event->name . ' - ' . $member->name;
                }
                return 'Event Registration Fee - ' . $member->name;
            
            case 'membership':
                return 'Membership Fee - ' . $member->name;
            
            case 'class':
                return 'Class Fee - ' . $member->name;
            
            case 'private':
                return 'Private Training Fee - ' . $member->name;
            
            default:
                return 'Payment for ' . $member->name;
        }
    }

    /**
     * Get or create registration invoice
     */
    private function getOrCreateRegistrationInvoice(Member $member)
    {
        $invoice = Invoice::where('member_id', $member->id)
            ->where('type', 'membership')
            ->whereIn('status', ['pending', 'draft'])
            ->first();

        if (!$invoice) {
            // Get registration fee from settings or use default
            $registrationFee = 150.00; // Default RM 150

            $invoice = Invoice::create([
                'invoice_number' => 'INV-REG-' . time() . '-' . $member->id,
                'member_id' => $member->id,
                'dojo_id' => currentDojo(),
                'type' => 'membership',
                'invoice_date' => now(),
                'due_date' => now()->addDays(7),
                'amount' => $registrationFee,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => $registrationFee,
                'status' => 'pending',
                'description' => 'Registration Fee for ' . $member->name,
            ]);
        }

        return $invoice;
    }
    
    /**
     * Display a listing of payments for the parent's children
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get all children IDs for this parent
        $memberIds = \App\Models\ParentStudent::where('parent_user_id', $user->id)
            ->pluck('member_id');
        
        // Get all invoices for all children
        $invoices = Invoice::whereIn('member_id', $memberIds)
            ->with(['member'])
            ->orderBy('due_date', 'desc')
            ->paginate(15);
        
        return view('parent.payments.index', compact('invoices'));
    }
    
    /**
     * Display the specified invoice
     */
    public function show($invoiceId)
    {
        $user = auth()->user();
        
        // Get all children IDs for this parent
        $memberIds = \App\Models\ParentStudent::where('parent_user_id', $user->id)
            ->pluck('member_id');
        
        // Find invoice that belongs to one of the parent's children
        $invoice = Invoice::with(['member', 'items'])
            ->whereIn('member_id', $memberIds)
            ->findOrFail($invoiceId);

        return view('parent.payments.show', compact('invoice'));
    }
}
