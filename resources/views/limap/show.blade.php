<x-layout>
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-6">
           <div class="text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                    <a href="{{ route('limap.index') }}" class="bg-white/20 hover:bg-white/40 p-2 rounded-full transition"><i class="fas fa-arrow-left"></i></a>
                    <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Data 5P</h2>
                </div>
                <p class="text-red-50 text-sm md:text-base font-light ml-12">PIC: <strong class="font-bold">{{ $data->pic }}</strong></p>
           </div>
           <div class="flex gap-2">
               @if(Auth::check() && Auth::user()->role == 'admin')
                   <a href="{{ route('limap.edit', $data->id) }}" class="bg-white text-[#e92027] hover:bg-gray-50 px-6 py-3 rounded-full font-bold shadow-2xl flex items-center gap-2 transition">
                       <i class="fas fa-pen"></i> Edit
                   </a>
               @endif
           </div>
       </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 -mt-20 relative z-20 mb-12 space-y-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                <div class="bg-green-100 p-2 rounded-full text-green-600"><i class="fas fa-check"></i></div>
                <p class="text-sm font-bold text-green-800 flex-1">{{ session('success') }}</p>
                <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600"><i class="fas fa-times"></i></button>
            </div>
        @endif

        {{-- BAGIAN 1: GALERI GAMBAR --}}
        @php
            $categories = [
                'kesepakatan' => 'Kesepakatan', 'visi_misi' => 'Visi & Misi',
                'pembagian_area' => 'Pembagian Area', 'struktur' => 'Struktur',
                'jadwal_kegiatan' => 'Jadwal Kegiatan'
            ];
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($categories as $key => $label)
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4 text-[#e92027] uppercase tracking-wide">{{ $label }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if(!empty($data->$key))
                        @foreach($data->$key as $img)
                            <a href="{{ asset('storage/'.$img) }}" target="_blank" class="block h-52 sm:h-56 md:h-60 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition bg-white border border-gray-200 hover:border-[#e92027] group flex items-center justify-center p-2" title="Klik untuk membuka ukuran asli">
                                <img src="{{ asset('storage/'.$img) }}" class="max-w-full max-h-full w-auto h-auto object-contain group-hover:scale-105 transition duration-300 select-none">
                            </a>
                        @endforeach
                    @else
                        <div class="col-span-full text-center py-8 text-gray-400 text-sm italic">Belum ada gambar diunggah.</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- BAGIAN 2: KAIZEN PDF MANAGEMENT --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 md:p-8 mt-8" x-data="{ expandedYear: {{ date('Y') }}, expandedMonth: null }">
            <h3 class="text-2xl font-bold text-gray-800 border-b border-gray-100 pb-4 mb-6"><i class="fas fa-lightbulb text-amber-500 mr-2"></i> Dokumen Kaizen (PDF)</h3>

            @for($year = 2026; $year <= date('Y'); $year++)
            <div class="mb-4 border border-gray-200 rounded-2xl overflow-hidden">
                <button @click="expandedYear = expandedYear === {{ $year }} ? null : {{ $year }}" class="w-full bg-gray-50 px-6 py-4 flex justify-between items-center hover:bg-gray-100 transition">
                    <span class="font-bold text-lg text-gray-800">Tahun {{ $year }}</span>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform" :class="expandedYear === {{ $year }} ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="expandedYear === {{ $year }}" x-collapse class="p-4 bg-white border-t border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @php
                            $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                        @endphp

                        @foreach($months as $num => $monthName)
                            <div class="border border-gray-200 rounded-xl overflow-hidden" x-data="{ openUpload: false }">
                                <button @click="expandedMonth = expandedMonth === '{{$year}}-{{$num}}' ? null : '{{$year}}-{{$num}}'" class="w-full bg-red-50 px-4 py-3 flex justify-between items-center hover:bg-red-100 transition">
                                    <span class="font-bold text-sm text-[#e92027]">{{ $monthName }}</span>
                                    <i class="fas fa-chevron-down text-[#e92027] text-xs transition-transform" :class="expandedMonth === '{{$year}}-{{$num}}' ? 'rotate-180' : ''"></i>
                                </button>

                                <div x-show="expandedMonth === '{{$year}}-{{$num}}'" x-collapse class="p-4 bg-white space-y-3">
                                    @php
                                        $kaizens = $data->kaizens->where('tahun', $year)->where('bulan', $num);
                                    @endphp

                                    @forelse($kaizens as $file)
                                        <div class="flex items-center justify-between bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                            <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline truncate flex-1 flex items-center gap-2" title="{{ $file->original_name }}">
                                                <i class="fas fa-file-pdf text-red-500 text-lg"></i> <span class="truncate">{{ $file->original_name }}</span>
                                            </a>
                                            @if(Auth::check() && Auth::user()->role == 'admin')
                                                <form action="{{ route('limap.kaizen.destroy', $file->id) }}" method="POST" onsubmit="confirmDeleteKaizen(event, this)" hx-disable>
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-600 ml-2" title="Hapus PDF"><i class="fas fa-times-circle"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-[10px] text-gray-400 italic text-center">Kosong</p>
                                    @endforelse

                                    @if(Auth::check() && Auth::user()->role == 'admin')
                                        <div class="pt-3 border-t border-gray-100 mt-3">
                                            <button @click="openUpload = !openUpload" x-show="!openUpload" class="text-[10px] font-bold bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded-lg w-full hover:bg-gray-50 shadow-sm">+ Upload PDF</button>

                                            <form x-show="openUpload" x-collapse action="{{ route('limap.kaizen.store', $data->id) }}" method="POST" enctype="multipart/form-data" hx-disable class="bg-gray-50 p-3 rounded-lg border border-dashed border-gray-300">
                                                @csrf
                                                <input type="hidden" name="tahun" value="{{ $year }}">
                                                <input type="hidden" name="bulan" value="{{ $num }}">
                                                <input type="file" name="kaizen_files[]" multiple required accept="application/pdf" class="w-full text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-[#e92027] file:text-white mb-2">
                                                <div class="flex gap-2">
                                                    <button type="submit" class="flex-1 bg-[#e92027] text-white text-[10px] font-bold py-1.5 rounded hover:bg-red-700">Simpan</button>
                                                    <button type="button" @click="openUpload = false" class="flex-1 bg-white border border-gray-300 text-gray-600 text-[10px] font-bold py-1.5 rounded hover:bg-gray-100">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDeleteKaizen(event, form) {
            event.preventDefault();
            Swal.fire({
                title: 'Hapus File PDF Kaizen?',
                text: "Dokumen ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e92027',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Hapus File',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-layout>
