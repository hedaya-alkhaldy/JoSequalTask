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
use Illuminate\Support\Facades\Validator;
use SimpleXMLElement;
use Ignaszak\Parcela\Parcela;
use Illuminate\Support\Facades\DB;


use function PHPUnit\Framework\countOf;

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

        return redirect(route('map'));


    }

    /**
     * Store a newly created KML in storage.
     */
    public function store(CreateKMLRequest $request)
    {
        $input = $request->all();
        $kmlFile = $request->file('kml_file');

        $docFileContent = file_get_contents($kmlFile);
        $extension = $kmlFile->extension();
        $file_name = $docFileContent;

        Storage::disk('public')->put('kml_files/' . $kmlFile->getClientOriginalName() . '.' . $kmlFile->extension(), $file_name);

        KML::create([
            'kml_file' => $kmlFile->getClientOriginalName() . '.' . $kmlFile->extension()
        ]);

        Flash::success('K M L saved successfully.');

        return redirect(route('kmls.index'));
    }

    /**
     * Display the specified KML.
     */
    public function show($id)
    {
        $kML = $this->kMLRepository->find($id);


        $test =  Storage::disk('public')->get('kml_files/' . $kML->kml_file);

        $xml      = new SimpleXMLElement($test);
        $value    = (string)$xml->Document->Placemark->Point->coordinates;
        $value2    = (string)$xml->Document->Placemark[1]->Polygon->outerBoundaryIs->LinearRing->coordinates;
        $value3    = (string)$xml->Document->Placemark[2]->LineString->coordinates;

        $point   = array();
        $args     = explode(",", $value);
        $point[] = array($args[0], $args[1], $args[2]);

        $Polygon   = array();
        $args     = explode("\n", $value2);

        $Polygon[] = array($args[0], $args[1], $args[2], $args[3], $args[4]);
        $polygonPoints = [];
        foreach ($Polygon   as $pi) {
            foreach ($pi   as $key => $p) {

                if ($key != 0) {
                    $args     = explode(',', $p);
                    $len = count($polygonPoints);

                    $supArray = array(trim($args[0]), $args[1], $args[2]);

                    $polygonPoints[$len] = $supArray;
                }
            }
        }


        $Lines   = array();
        $args     = explode("\n", $value3);
        $Lines[] = array($args[0], $args[1], $args[2], $args[3], $args[4]);
        $lineString = [];
        foreach ($Lines   as $pi) {
            foreach ($pi   as $key => $p) {

                if ($key != 0) {
                    $args     = explode(',', $p);
                    $len = count($lineString);

                    $supArray = array(trim($args[0]), $args[1], $args[2]);

                    $lineString[$len] = $supArray;
                }
            }
        }

        if (empty($kML)) {
            Flash::error('K M L not found');

            return redirect(route('kMLS.index'));
        }

        return view('k_m_l_s.show')->with(['kml' => $kML, 'point' => $point, 'polygonPoints' => $polygonPoints, 'lineString' => $lineString]);
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
        $kml = $this->kMLRepository->find($id);

        if (empty($kml)) {
            Flash::error('K M L not found');

            return redirect(route('kmls.index'));
        }

        DB::beginTransaction();
        try {

            if (Storage::disk('public')->exists($kml->kml_file)) {
                Storage::disk('public')->delete($kml->kml_file);
            }

            $this->kMLRepository->delete($id);


            DB::commit();


            Flash::success('KML deleted successfully.');
            return redirect(route('kmls.index'));


        } catch (\Exception $e) {

            Flash::success('KML File deleted failed.');


        return redirect(route('kmls.index'));
    }


}

    public function checkKmlFile(Request $request)
    {

        dd($request->all);

        $validation = Validator::make($request->all(), [
            'select_file' => 'required'
        ]);
        if ($validation->passes()) {
            $kml = $request->file('select_file');
            $new_name = rand() . '.' . $kml->getClientOriginalExtension();
            $kml->move(public_path('kml'), $new_name);
            return response()->json([
                'message'   => 'File Uploaded Successfully',
                'uploaded_file' => "kml/$new_name",
                'class_name'  => 'alert-success'
            ]);
        } else {
            return response()->json([
                'message'   => $validation->errors()->all(),
                'uploaded_file' => '',
                'class_name'  => 'alert-danger'
            ]);
        }
    }

    function action(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'select_file' => 'required'
        ]);
        if ($validation->passes()) {


            $kml = $request->file('select_file');
            $docFileContent = file_get_contents($kml);
            $extension = $kml->extension();
            $file_name = $docFileContent;



            Storage::disk('public')->put('kml_files/' . $kml->getClientOriginalName() . '.' . $kml->extension(), $file_name);
            KML::create([
                'kml_file' => $kml->getClientOriginalName() . '.' . $kml->extension()
            ]);

            $new_name = rand() . '.' . $kml->getClientOriginalExtension();

            $kml->move(public_path('kml'), $new_name);
            return response()->json([
                'message'   => 'File Uploaded and Saved Successfully',
                'uploaded_file' => "kml/$new_name",
                'class_name'  => 'alert-success'
            ]);
        } else {
            return response()->json([
                'message'   => $validation->errors()->all(),
                'uploaded_file' => '',
                'class_name'  => 'alert-danger'
            ]);
        }
    }


    function test(Request $request, $id)
    {

        $kml = $this->kMLRepository->find($id);


        $test =  Storage::disk('public')->get('kml_files/' . $kml->kml_file);

        return response()->json([
            'message'   => 'File Uploaded Successfully',
            'uploaded_file' => "kml/51809400.kml",
            'class_name'  => 'alert-success'
        ]);
    }
}
