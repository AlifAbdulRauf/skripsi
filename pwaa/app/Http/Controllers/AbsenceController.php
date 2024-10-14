<?php
// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Absence;
// use Illuminate\Support\Facades\Auth;

// class AbsenceController extends Controller
// {
//     public function index()
//     {
//         $absences = Absence::where('doctor_id', Auth::id())->get();
//         return view('absences.index', compact('absences'));
//     }

//     public function create()
//     {
//         return view('absences.create');
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'dates' => 'required|array',
//             'dates.*' => 'required|date',
//             'reason' => 'nullable|string|max:255',
//         ]);
    
//         foreach ($request->dates as $date) {
//             Absence::create([
//                 'doctor_id' => Auth::id(),
//                 'date' => $date,
//                 'reason' => $request->reason,
//             ]);
//         }
    
//         return redirect()->route('absences.index')->with('success', 'Ketidakhadiran berhasil ditambahkan.');
//     }
    
//     public function update(Request $request, Absence $absence)
//     {
//         $request->validate([
//             'date' => 'required|date',
//             'reason' => 'nullable|string|max:255',
//         ]);
    
//         $absence->update($request->all());
    
//         return redirect()->route('absences.index')->with('success', 'Ketidakhadiran berhasil diperbarui.');
//     }
    

//     public function edit(Absence $absence)
//     {
//         return view('absences.edit', compact('absence'));
//     }

//     public function destroy(Absence $absence)
//     {
//         $absence->delete();

//         return redirect()->route('absences.index')->with('success', 'Ketidakhadiran berhasil dihapus.');
//     }
// }

