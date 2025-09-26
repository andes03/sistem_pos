<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna dengan fitur pencarian dan filter berdasarkan role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        
        $query = User::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%");
        }
        
        if ($roleFilter && in_array($roleFilter, ['admin', 'user'])) {
            $query->where('role', $roleFilter);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(8)->withQueryString();

        // Jika permintaan adalah AJAX, kembalikan hanya partial view tabel
        if ($request->ajax()) {
            return view('user.partials.table', compact('users', 'search', 'roleFilter'));
        }

        return view('user.index', compact('users', 'search', 'roleFilter'));
    }

    /**
     * Menyimpan user baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:user,username',
                'nama' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:user,email',
                'password' => 'required|string|min:8|confirmed',
                'nomor_hp' => 'required|string|min:10|numeric|unique:user,nomor_hp',
                'alamat' => 'nullable|string',
                'jabatan' => 'nullable|string|max:255',
                'role' => 'required|in:user,admin',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username ini sudah digunakan. Silakan gunakan username lain.',
                'nama.required' => 'Nama lengkap wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal harus 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'nomor_hp.required' => 'Nomor HP wajib diisi.',
                'nomor_hp.min' => 'Nomor HP minimal 10 digit.',
                'nomor_hp.numeric' => 'Nomor HP harus berupa angka.',
                'nomor_hp.unique' => 'Nomor HP ini sudah terdaftar. Silakan gunakan nomor lain.',
                'role.required' => 'Role wajib diisi.',
                'role.in' => 'Role harus berupa user atau admin.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format gambar yang didukung adalah jpeg, png, jpg, gif, svg.',
                'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('user', 'public');
            }

            User::create([
                'username' => $validated['username'],
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'nomor_hp' => $validated['nomor_hp'],
                'alamat' => $validated['alamat'],
                'jabatan' => $validated['jabatan'],
                'role' => $validated['role'],
                'image' => $imagePath,
            ]);

            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('_form_type', 'add');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan user. Coba lagi nanti.')->withInput();
        }
    }

    /**
     * Memperbarui user yang sudah ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:user,username,' . $user->id,
                'nama' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:user,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'nomor_hp' => 'required|string|min:10|numeric|unique:user,nomor_hp,' . $user->id,
                'alamat' => 'nullable|string',
                'jabatan' => 'nullable|string|max:255',
                'role' => 'required|in:user,admin',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username ini sudah digunakan. Silakan gunakan username lain.',
                'nama.required' => 'Nama lengkap wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
                'password.min' => 'Password minimal harus 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'nomor_hp.required' => 'Nomor HP wajib diisi.',
                'nomor_hp.min' => 'Nomor HP minimal 10 digit.',
                'nomor_hp.numeric' => 'Nomor HP harus berupa angka.',
                'nomor_hp.unique' => 'Nomor HP ini sudah terdaftar. Silakan gunakan nomor lain.',
                'role.required' => 'Role wajib diisi.',
                'role.in' => 'Role harus berupa user atau admin.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format gambar yang didukung adalah jpeg, png, jpg, gif, svg.',
                'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            ]);

            $imagePath = $user->image;
            if ($request->hasFile('image')) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('user', 'public');
            }

            $updateData = [
                'username' => $validated['username'],
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'nomor_hp' => $validated['nomor_hp'],
                'alamat' => $validated['alamat'],
                'jabatan' => $validated['jabatan'],
                'role' => $validated['role'],
                'image' => $imagePath,
            ];

            // Hanya update password jika diisi
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with([
                '_form_type' => 'edit',
                'user_id_edit' => $user->id,
            ]);
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui user. Coba lagi nanti.')->withInput();
        }
    }

    /**
     * Menghapus user dari database.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $user->delete();
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');

        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Gagal menghapus user. User mungkin terkait dengan data lain.');
        }
    }

    /**
     * Menampilkan detail user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'nama' => $user->nama,
            'email' => $user->email,
            'nomor_hp' => $user->nomor_hp,
            'alamat' => $user->alamat ?? 'Tidak ada alamat',
            'jabatan' => $user->jabatan ?? 'Tidak ada jabatan',
            'role' => ucfirst($user->role),
            'image' => $user->image ? asset('storage/' . $user->image) : 'https://via.placeholder.com/200',
            'dibuat_pada' => $user->created_at->format('d M Y H:i'),
            'diperbarui_pada' => $user->updated_at->format('d M Y H:i'),
        ]);
    }
}
