<?php

namespace App\Http\Controllers\Api;

use App\Models\Frontend\StudentUser;
use App\Profile;
use Illuminate\Http\Request;
use Mockery\Undefined;

class ProfileController extends ApiController
{
    public function profileView()
    {
        return view('frontend.profile.index');
    }

    public function getProfile($studentID)
    {
        $student = StudentUser::find($studentID);

        if ($student) {
            $profile = Profile::where('user_id', $student->id)->first();
            if (!$profile) {
                $profile = Profile::create([
                    'user_id'           => $student->id,
                    'phone'             => $student->phone,
                    'public_email'      => $student->email
                ]);
            }
            return json_encode($profile);
        } else {
            return json_encode(['No student found']);
        }
    }

    public function update(Request $request)
    {

        $data = $request->all();
        $data['image'] = $this->saveImage($data);
        $profile = Profile::find($data['id']);
        $updated =  $profile->update($data);

        return $updated ?
            json_encode(["status" => "OK", 'updated' => true, 'data' => $request->all()]) :
            json_encode(["status" => "notOK", 'updated' => false, 'data' => "Not saved"]);
    }


    private function saveImage($profile)
    {

        $base64Image = $profile['image'];
        $old = Profile::find($profile['id']);
        $check =   explode(";base64,", $base64Image);

        if (isset($check[0]) && isset($check[1])) {

            define('UPLOAD_DIR', './storage/images/students/');
            
            if(!file_exists(UPLOAD_DIR)){
                mkdir(UPLOAD_DIR, 0777, true);
            }
            
            $image_parts = explode(";base64,", $base64Image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $imageName = uniqid() . '.' . $image_type;
            $file = UPLOAD_DIR . $imageName;
            
            file_put_contents($file, $image_base64);
            // remove old image
            if (file_exists(UPLOAD_DIR . ($old->image!='')?$old->image:'no')) {
                unlink(UPLOAD_DIR . $old->image);
            }
        } else {
            return $base64Image;
        }
        return $imageName;
    }
}
