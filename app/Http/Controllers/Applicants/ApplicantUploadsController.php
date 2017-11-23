<?php

namespace NTI\Http\Controllers\Applicants;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use NTI\Http\Controllers\Controller;

use NTI\Models\User;
use NTI\Models\Applicant;
use NTI\Models\Upload;

use Image;

class ApplicantUploadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
        $this->middleware('role:applicant');
    }

    public function index()
    {
        $applicant = Applicant::where('user_id', Auth::id())->first();

        $passport = Upload::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
                'object_name' => 'photo',
            ]
        )->latest()->first();

        return view('applicants.uploads', 
            [
                'applicant' => $applicant, 
                'passport' => $passport,
                'page' => 'uploads',
                ]);
    }

    public function save(Request $request, $type)
    {
        $appstate = 8;
        //dd($request->all());
        $applicant = Applicant::where('user_id', Auth::id())->first();

        $file = null !== $request->file('photo') ? $request->file('photo') : $request->all()['document'];
        
        if($file->isValid())
        {
            // resize image
            if($type == 'photo')
            {
                $img = Image::make($file);
                $img->resize(400, 400,  function ($constraint) {
                        // $constraint->aspectRatio();
                })->encode('jpg');
                
                $path = Storage::put('public/applicants/' . $applicant->id . '/photo.jpg', $img->__toString(), 'public');
                if($path == 1)
                    $path = 'public/applicants/' . $applicant->id . '/photo.jpg';
            } else {
                $path = Storage::putFile('public/applicants/' . $applicant->id, $file, 'public');
            }

            //
            
            // this line is for storage on the local filesystem
            //$path = str_replace('public/', '/storage/', $path); 

            $upload = new Upload;

            $upload->object_name = $type;

            $upload->param = 'applicant';

            $upload->val = $applicant->id;

            $upload->src = $path;

            $upload->save();

            if($type !== 'photo')
            {
                $applicant->application_state = $applicant->application_state < $appstate? $appstate : $applicant->application_state;

                $applicant->save();
            }

            if(env('APP_ENV') == 'production')
                //header_remove('X-Frame-Options');
                return response(['src' => env('AZURE_STORAGE_FILESHARE_URL') . '/' . $path, 'id' => $upload->id], 200);
            return response(['src' => Storage::url($path), 'id' => $upload->id], 200);
        }

        return response('invalid file',422);
    }

    public function continue(Request $request)
    {
        $appstate = 8;

        $applicant = Applicant::where('user_id', Auth::id())->first();

        $applicant->application_state = $applicant->application_state < $appstate? $appstate : $applicant->application_state;
        
        $applicant->save();

        return redirect()->route('review');
    }

    public function viewFile($id)
    {
        $file = Upload::findOrFail($id);

        $path = $file->src;

        return response()->file($path);
    }

    public function deleteFile($id)
    {
        $file = Upload::findOrFail($id);

        $path = $file->src;

        Storage::delete($path);

        return response('deleted', 200);
    }

}
