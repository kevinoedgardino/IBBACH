<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\EcclesiasticalInformation;
use App\Models\MinisterialInformation;
use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $errors = 0;
            DB::beginTransaction();

            // saving personal information values from request
            $personalInfo = new PersonalInformation();
            $personalInfo->name = $request->name;
            $personalInfo->last_name = $request->last_name;
            $personalInfo->email = $request->email;
            $personalInfo->phone = $request->phone;
            $personalInfo->address = $request->address;
            $personalInfo->nationality = $request->nationality;
            $personalInfo->birth_date = $request->birth_date;
            $personalInfo->marital_status = $request->marital_status;
            $personalInfo->mate_name = $request->mate_name;
            $personalInfo->secular_degree = $request->secular_degree;
            $personalInfo->current_ocupation = $request->current_ocupation;
            if ($personalInfo->save() <= 0) {
                $errors++;
            }

            // saving eclesiastical information values from request
            $ecclesiasticalInfo = new EcclesiasticalInformation();
            $ecclesiasticalInfo->is_pastor = $request->is_pastor;
            $ecclesiasticalInfo->is_member = $request->is_member;
            $ecclesiasticalInfo->pastor_phone = $request->pastor_phone;
            $ecclesiasticalInfo->church_name = $request->church_name;
            $ecclesiasticalInfo->church_address = $request->church_address;
            $ecclesiasticalInfo->church_phone = $request->church_phone;
            $ecclesiasticalInfo->district = $request->district;
            $ecclesiasticalInfo->pastor_name = $request->pastor_name;
            $ecclesiasticalInfo->licence = $request->licence;
            $ecclesiasticalInfo->reference_name_one = $request->reference_name_one;
            $ecclesiasticalInfo->reference_phone_one = $request->reference_phone_one;
            $ecclesiasticalInfo->reference_name_two = $request->reference_name_two;
            $ecclesiasticalInfo->reference_phone_two = $request->reference_phone_two;
            $ecclesiasticalInfo->christ_accepted = $request->christ_accepted;
            $ecclesiasticalInfo->christening_date = $request->christening_date;
            $ecclesiasticalInfo->time_being_member = $request->time_being_member;
            $ecclesiasticalInfo->privileges_held = $request->privileges_held;
            $ecclesiasticalInfo->denomination = $request->denomination;
            $ecclesiasticalInfo->study_reason = $request->study_reason;
            if ($ecclesiasticalInfo->save() <= 0) {
                $errors++;
            }

            // saving ministerial information values from request
            $ministerialInfo = new MinisterialInformation();
            $ministerialInfo->ministry_performed = $request->ministry_performed;
            $ministerialInfo->current_ministry = $request->current_ministry;
            $ministerialInfo->full_time = $request->full_time;
            $ministerialInfo->ministry_qualification = $request->ministry_qualification;
            $ministerialInfo->aspirated_ministry = $request->aspirated_ministry;
            $ministerialInfo->reason_aspiring_ministry = $request->reason_aspiring_ministry;
            $ministerialInfo->cicle_to_be_taken = $request->cicle_to_be_taken;
            $ministerialInfo->previous_institution = $request->previous_institution;
            $ministerialInfo->last_year_studied = $request->last_year_studied;
            $ministerialInfo->qualities_religious_worker = $request->qualities_religious_worker;
            if ($ministerialInfo->save() <= 0) {
                $errors++;
            }

            // saving applicant information and making relations with previous information tables
            $applicant = new Applicant();
            $applicant->registration_date = $request->registration_date;
            $applicant->personal_information_id = $personalInfo->id;
            $applicant->ecclesiastical_information_id = $ecclesiasticalInfo->id;
            $applicant->ministerial_information_id = $ministerialInfo->id;
            if ($applicant->save() <= 0) {
                $errors++;
            }

            // checking if there was an error
            if ($errors == 0) {
                DB::commit();
                return response()->json(['status'=>'OK', 'data'=>$applicant],201);
            }
            else {
                DB::rollBack();
                return response()->json(['status'=>'FAIL', 'data'=>$applicant],400);
            }

        }
        catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            $applicant = Applicant::join('personal_information','applicants.personal_information_id','=','personal_information.id')
            ->join('ecclesiastical_information','applicants.ecclesiastical_information_id','=','ecclesiastical_information.id')
            ->join('ministerial_information','applicants.ministerial_information_id','=','ministerial_information.id')
            ->select('applicants.id','personal_information.name')
            ->orderBy('id','asc')->get();
            return $applicant;
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
