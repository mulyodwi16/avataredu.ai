<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SecureSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sudah login, periksa session security
        if (Auth::check()) {
            $user = Auth::user();

            // Periksa IP address consistency untuk keamanan tambahan (opsional)
            $currentIp = $request->ip();
            $sessionIp = Session::get('login_ip');

            if (!$sessionIp) {
                // Set IP saat pertama kali login
                Session::put('login_ip', $currentIp);
                Session::put('login_user_agent', $request->userAgent());
            } else {
                // Periksa jika ada perubahan drastis dalam fingerprint
                $sessionUserAgent = Session::get('login_user_agent');

                // Jika user agent berbeda drastis, logout untuk keamanan
                if ($sessionUserAgent && $sessionUserAgent !== $request->userAgent()) {
                    $this->logSecurityEvent($user, $currentIp, $request->userAgent(), 'user_agent_mismatch');

                    Auth::logout();
                    Session::invalidate();
                    Session::regenerateToken();

                    return redirect()->route('login')
                        ->with('error', 'Session tidak valid. Silakan login ulang.');
                }
            }

            // Regenerate session ID secara berkala untuk keamanan
            if (
                !Session::has('last_regeneration') ||
                time() - Session::get('last_regeneration') > 300
            ) { // 5 menit
                Session::regenerate();
                Session::put('last_regeneration', time());
            }
        }

        return $next($request);
    }

    private function logSecurityEvent($user, $ip, $userAgent, $event)
    {
        \Log::warning('Security event detected', [
            'event' => $event,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'timestamp' => now()
        ]);
    }
}