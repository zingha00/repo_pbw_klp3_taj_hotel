<!-- Room Modal -->
<div id="room-modal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop absolute inset-0 bg-black/50" onclick="closeModal('room-modal')"></div>
    <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl max-h-[90%] overflow-y-auto bg-white rounded-2xl shadow-2xl">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <h3 class="text-xl font-semibold text-slate-900" id="room-modal-title">Tambah Kamar Baru</h3>
            <button onclick="closeModal('room-modal')" class="p-2 hover:bg-slate-100 rounded-xl transition-colors">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="room-form" action="{{ route('admin.rooms.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="room-method" value="POST">
            <input type="hidden" name="id" id="room-id" value="">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Kamar</label>
                <input type="text" name="name" id="room-name" required
                    class="input-field w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none"
                    placeholder="Contoh: Deluxe Room 101">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tipe Kamar</label>
                    <select name="type" id="room-type" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-400">
                        <option value="">Pilih Tipe</option>
                        <option value="standard">Standard</option>
                        <option value="deluxe">Deluxe</option>
                        <option value="suite">Suite</option>
                        <option value="presidential">Presidential</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Kapasitas (Orang)</label>
                    <input type="number" name="capacity" id="room-capacity" required min="1"
                        class="input-field w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none"
                        placeholder="2">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Harga per Malam (Rp)</label>
                    <input type="number" name="price" id="room-price" required min="0"
                        class="input-field w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none"
                        placeholder="500000">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Jumlah Stok</label>
                    <input type="number" name="stock" id="room-stock" required min="0"
                        class="input-field w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none"
                        placeholder="5">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Deskripsi</label>
                <textarea name="description" id="room-description" rows="3"
                    class="input-field w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none resize-none"
                    placeholder="Deskripsi kamar..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fasilitas Kamar</label>
                <div class="grid grid-cols-2 gap-2">
                    <label
                        class="flex items-center gap-2 p-2 border border-slate-200 rounded-lg cursor-pointer hover:border-amber-400">
                        <input type="checkbox" name="facilities[]" value="ac" class="w-4 h-4">
                        <span class="text-sm">AC</span>
                    </label>
                    <label
                        class="flex items-center gap-2 p-2 border border-slate-200 rounded-lg cursor-pointer hover:border-amber-400">
                        <input type="checkbox" name="facilities[]" value="tv" class="w-4 h-4">
                        <span class="text-sm">TV</span>
                    </label>
                    <label
                        class="flex items-center gap-2 p-2 border border-slate-200 rounded-lg cursor-pointer hover:border-amber-400">
                        <input type="checkbox" name="facilities[]" value="wifi" class="w-4 h-4">
                        <span class="text-sm">WiFi</span>
                    </label>
                    <label
                        class="flex items-center gap-2 p-2 border border-slate-200 rounded-lg cursor-pointer hover:border-amber-400">
                        <input type="checkbox" name="facilities[]" value="minibar" class="w-4 h-4">
                        <span class="text-sm">Minibar</span>
                    </label>
                    <label
                        class="flex items-center gap-2 p-2 border border-slate-200 rounded-lg cursor-pointer hover:border-amber-400">
                        <input type="checkbox" name="facilities[]" value="bathtub" class="w-4 h-4">
                        <span class="text-sm">Bathtub</span>
                    </label>
                    <label
                        class="flex items-center gap-2 p-2 border border-slate-200 rounded-lg cursor-pointer hover:border-amber-400">
                        <input type="checkbox" name="facilities[]" value="balcony" class="w-4 h-4">
                        <span class="text-sm">Balkon</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="button" onclick="closeModal('room-modal')"
                    class="flex-1 px-4 py-3 border border-slate-200 rounded-xl font-medium hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 btn-primary px-4 py-3 text-slate-900 font-semibold rounded-xl">
                    Simpan Kamar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop absolute inset-0 bg-black/50" onclick="closeModal('confirm-modal')"></div>
    <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 id="confirm-title" class="text-xl font-semibold text-slate-900 mb-2">Konfirmasi Hapus</h3>
            <p id="confirm-message" class="text-slate-500">Apakah Anda yakin ingin menghapus data ini?</p>
        </div>

        <form id="delete-form" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeModal('confirm-modal')"
                    class="flex-1 px-4 py-3 border border-slate-200 rounded-xl font-medium hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-colors">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>