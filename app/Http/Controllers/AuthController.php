<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        // Cocokkan password
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect('/'); // redirect ke dashboard/home
        } else {
            // Jika gagal
            return back()->withErrors(['username' => 'Username atau password salah']);
        }

        // LDAP configuration
        $ldap_host = 'pdc.yarsi.ac.id';
        $ldap_port = '389';
        $ldap_basedn = 'dc=yarsi,dc=ac,dc=id';
        $username = $request->username;
        $password = $request->password;

        // Connect to LDAP server
        $ds = ldap_connect($ldap_host, $ldap_port);
        if (!$ds) {
            return back()->withErrors(['error' => 'Failed to connect to LDAP server']);
        }

        // Anonymous bind
        $ldap_bind = @ldap_bind($ds);
        if (!$ldap_bind) {
            ldap_close($ds);
            return back()->withErrors(['error' => 'Failed to bind to LDAP server']);
        }

        // Search for user
        $search_filter = "(uid=$username)";
        $search_result = @ldap_search($ds, $ldap_basedn, $search_filter);
        $entries = @ldap_get_entries($ds, $search_result);

        if ($entries['count'] > 0) {
            $user_dn = $entries[0]['dn'];
            $user_bind = @ldap_bind($ds, $user_dn, $password);

            if ($user_bind) {
                ldap_close($ds);

                // Retrieve user details
                $userDetails = [
                    'username' => $username,
                    'displayname' => $entries[0]['displayname'][0] ?? null,
                    'telephonenumber' => $entries[0]['telephonenumber'][0] ?? null,
                    'emailyarsi' => $entries[0]['mail'][0] ?? null,
                    'email' => $entries[0]['mailalternateaddress'][0] ?? null,
                    'serial_number' => $entries[0]['description'][0] ?? null,
                    'role' => $entries[0]['title'][0] ?? 'User',
                ];

                $studyProgram = null;
                $faculty = null;
                $prefix = substr($userDetails['serial_number'] ?? '', 0, 3);
                if ($userDetails['role'] === 'M') {
                    switch ($prefix) {
                        case '110':
                            $studyProgram = 'Kedokteran';
                            $faculty = "Kedokteran";
                            break;
                        case '111':
                            $studyProgram = 'Kedokteran Gigi';
                            $faculty = "Kedokteran Gigi";
                            break;
                        case '120':
                            $studyProgram = 'Manajemen';
                            $faculty = "Ekonomi dan Bisnis";
                            break;
                        case '121':
                            $studyProgram = 'Akuntansi';
                            $faculty = "Ekonomi dan Bisnis";
                            break;
                        case '130':
                            $studyProgram = 'Ilmu Hukum';
                            $faculty = "Hukum";
                            break;
                        case '140':
                            $studyProgram = 'Teknik Informatika';
                            $faculty = "Teknologi Informasi";
                            break;
                        case '150':
                            $studyProgram = 'Perpustakaan dan Sains Informasi';
                            $faculty = "Teknologi Informasi";
                            break;
                        case '160':
                            $studyProgram = 'Psikologi';
                            $faculty = "Psikologi";
                            break;
                        default:
                            $studyProgram = 'Undefined';
                    }
                }

                // Find or create a local user
                $user = User::updateOrCreate(
                    ['emailyarsi' => $userDetails['emailyarsi']],
                    [
                        'username' => $userDetails['username'],
                        'displayname' => $userDetails['displayname'],
                        'telephonenumber' => $userDetails['telephonenumber'],
                        'email' => $userDetails['email'],
                        'password' => Hash::make($password), // Store hashed password for future compatibility
                        'serial_number' => $userDetails['serial_number'],
                        'study_program' => $studyProgram,
                        'faculty' => $faculty,
                        'role' => $userDetails['role'],
                    ]
                );
                // Log the user in
                Auth::login($user);
                return redirect('/'); // redirect ke dashboard/home
            } else {
                ldap_close($ds);
                return redirect()->back()->withErrors(['message' => 'Invalid credentials']);
            }
        } else {
            ldap_close($ds);
            return redirect()->back()->withErrors(['message' => 'User not found']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
