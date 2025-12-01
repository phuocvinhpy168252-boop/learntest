<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tìm user theo email
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email không tồn tại trong hệ thống.',
            ])->withInput($request->only('email'));
        }

        // Kiểm tra trạng thái tài khoản
        if ($user->trang_thai === 'vo_hieu_hoa') {
            return back()->withErrors([
                'email' => 'Tài khoản của bạn đã bị vô hiệu hóa.',
            ])->withInput($request->only('email'));
        }

        // Kiểm tra mật khẩu
        if (Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            // Chuyển hướng theo loại tài khoản
            switch ($user->loai_taikhoan) {
                case 'sinhvien':
                    return redirect()->route('sinhvien.dashboard');
                case 'admin':
                    return redirect('/admin');
                case 'giangvien':
                    return redirect()->route('giangvien.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login.form')->withErrors([
                        'email' => 'Loại tài khoản không hợp lệ.',
                    ]);
            }
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput($request->only('email'));
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'hoten' => 'required|string|max:255',
            'sdt' => 'required|string|max:20|unique:users,sdt',
            'email' => 'required|email|unique:users,email',
            'diachi' => 'nullable|string|max:255',
            'loai_taikhoan' => 'required|in:giangvien,sinhvien',
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();

        try {
            // Tạo mã người dùng tự động với logic đúng
            $prefix = $request->loai_taikhoan == 'giangvien' ? 'GV' : 'SV';
            
            // Tìm mã lớn nhất hiện có cho loại tài khoản này
            $lastUser = User::where('ma_nguoi_dung', 'like', $prefix . '%')
                          ->orderBy('ma_nguoi_dung', 'desc')
                          ->first();

            $nextNumber = 1;
            if ($lastUser && !empty($lastUser->ma_nguoi_dung)) {
                $lastNumber = intval(substr($lastUser->ma_nguoi_dung, 2));
                $nextNumber = $lastNumber + 1;
            }
            
            $maNguoiDung = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Kiểm tra xem mã đã tồn tại chưa (double check)
            $existingUser = User::where('ma_nguoi_dung', $maNguoiDung)->first();
            if ($existingUser) {
                // Nếu mã đã tồn tại, tìm mã tiếp theo
                $nextNumber++;
                $maNguoiDung = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            User::create([
                'ma_nguoi_dung' => $maNguoiDung,
                'hoten' => $request->hoten,
                'sdt' => $request->sdt,
                'email' => $request->email,
                'diachi' => $request->diachi,
                'loai_taikhoan' => $request->loai_taikhoan,
                'password' => Hash::make($request->password),
                'trang_thai' => 'hoat_dong',
            ]);

            DB::commit();

            return redirect()->route('login.form')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi đăng ký: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
    public function logoutGet(Request $request)
    {
        return $this->logout($request);
    }

    /**
     * Hàm tạo tài khoản admin mới
     */
    public function createAdminAccount()
    {
        DB::beginTransaction();

        try {
            // Xóa admin cũ nếu tồn tại
            User::where('email', 'a@gmail.com')->delete();

            // Tạo admin mới
            User::create([
                'ma_nguoi_dung' => 'AD001',
                'hoten' => 'Quản Trị Viên',
                'sdt' => '0987654321',
                'email' => 'a@gmail.com',
                'diachi' => 'Hệ thống',
                'loai_taikhoan' => 'admin',
                'password' => Hash::make('111111'),
                'trang_thai' => 'hoat_dong',
            ]);

            DB::commit();
            return "Đã tạo tài khoản admin: a@gmail.com / 111111";

        } catch (\Exception $e) {
            DB::rollBack();
            return "Lỗi khi tạo admin: " . $e->getMessage();
        }
    }

    /**
     * Hàm fix lỗi mã trùng lặp
     */
    public function fixDuplicateCodes()
    {
        // Tìm tất cả các mã trùng lặp
        $duplicates = DB::table('users')
            ->select('ma_nguoi_dung', DB::raw('COUNT(*) as count'))
            ->groupBy('ma_nguoi_dung')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $users = User::where('ma_nguoi_dung', $duplicate->ma_nguoi_dung)->get();
            
            // Giữ lại user đầu tiên, sửa các user sau
            for ($i = 1; $i < count($users); $i++) {
                $user = $users[$i];
                $prefix = substr($user->ma_nguoi_dung, 0, 2);
                $newNumber = $this->getNextAvailableNumber($prefix);
                $newCode = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
                
                $user->update(['ma_nguoi_dung' => $newCode]);
            }
        }

        return "Đã fix lỗi mã trùng lặp";
    }

    private function getNextAvailableNumber($prefix)
    {
        $lastUser = User::where('ma_nguoi_dung', 'like', $prefix . '%')
                      ->orderBy('ma_nguoi_dung', 'desc')
                      ->first();

        if ($lastUser) {
            return intval(substr($lastUser->ma_nguoi_dung, 2)) + 1;
        }

        return 1;
    }
}