<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateKMLRequest;
use App\Http\Requests\UpdateKMLRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\KML;
use App\Repositories\KMLRepository;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Storage;

class KMLController extends AppBaseController
{
    /** @var KMLRepository $kMLRepository*/
    private $kMLRepository;

    public function __construct(KMLRepository $kMLRepo)
    {
        $this->kMLRepository = $kMLRepo;
    }

    /**
     * Display a listing of the KML.
     */
    public function index(Request $request)
    {
        $kmls = $this->kMLRepository->paginate(10);

        return view('k_m_l_s.index')
            ->with('kmls', $kmls);
    }

    /**
     * Show the form for creating a new KML.
     */
    public function create()
    {
        return view('k_m_l_s.create');
    }

    /**
     * Store a newly created KML in storage.
     */
    public function store(CreateKMLRequest $request)
    {
        $input = $request->all();








        $kmlFile = $request->file('kml_file');




    //     if (array_key_exists("videos_names", $input)


        $docFileContent = file_get_contents($kmlFile);
        $extension = $kmlFile->extension();
       $file_name = $docFileContent;

    //    dd($kmlFile->filename());


    Storage::disk('public')->put('kml_files/'.$kmlFile->getClientOriginalName().'.'. $kmlFile->extension(),$file_name);





        KML::create([
            'kml_file' =>$kmlFile->getClientOriginalName().'.'. $kmlFile->extension()
        ]);




        // dd($kmlFile);


        Flash::success('K M L saved successfully.');

        return redirect(route('kmls.index'));
    }

    /**
     * Display the specified KML.
     */
    public function show($id)
    {
        $kML = $this->kMLRepository->find($id);

        if (empty($kML)) {
            Flash::error('K M L not found');

            return redirect(route('kMLS.index'));
        }

        return view('k_m_l_s.show')->with('kML', $kML);
    }

    /**
     * Show the form for editing the specified KML.
     */
    public function edit($id)
    {
        $kML = $this->kMLRepository->find($id);

        if (empty($kML)) {
            Flash::error('K M L not found');

            return redirect(route('kMLS.index'));
        }

        return view('k_m_l_s.edit')->with('kML', $kML);
    }

    /**
     * Update the specified KML in storage.
     */
    public function update($id, UpdateKMLRequest $request)
    {
        $kML = $this->kMLRepository->find($id);

        if (empty($kML)) {
            Flash::error('K M L not found');

            return redirect(route('kMLS.index'));
        }

        $kML = $this->kMLRepository->update($request->all(), $id);

        Flash::success('K M L updated successfully.');

        return redirect(route('kMLS.index'));
    }

    /**
     * Remove the specified KML from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $kML = $this->kMLRepository->find($id);

        if (empty($kML)) {
            Flash::error('K M L not found');

            return redirect(route('kMLS.index'));
        }

        $this->kMLRepository->delete($id);

        Flash::success('K M L deleted successfully.');

        return redirect(route('kMLS.index'));
    }
}
