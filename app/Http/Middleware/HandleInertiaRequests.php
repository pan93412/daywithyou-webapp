<?php

namespace App\Http\Middleware;

use App\Models\State\CartState;
use App\Models\State\MessageState;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    public function __construct(
        private readonly CartService $cartService
    ) {}

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /**
         * @type CartState[] $cart
         */
        $cart = [];
        try {
            $cart = $this->cartService->list();
        } catch (\Exception $e) {
            Log::error('Error loading cart data: '.$e->getMessage(), [
                'exception' => $e,
            ]);
        }

        /**
         * @type MessageState|null $messageSession
         */
        $messageSession = null;
        try {
            $rawMessageSession = session(MessageState::$MESSAGE_SESSION_KEY);
            if ($rawMessageSession) {
                $messageSession = MessageState::fromArray($rawMessageSession);
            }
        } catch (\Exception $e) {
            Log::error('Error loading message data: '.$e->getMessage(), [
                'exception' => $e,
            ]);
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'message' => $messageSession?->toArray(),
            'cart' => $cart,
            'ziggy' => fn (): array => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
