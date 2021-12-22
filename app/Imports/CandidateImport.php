<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\User;
use Illuminate\Http\Request;
use Modules\Candidate\Models\Candidate;
use Modules\Media\Models\MediaFile;
use App\UserMeta;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Modules\User\Models\Role;
use Validator;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Modules\Media\Helpers\FileHelper;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
class CandidateImport implements ToCollection, WithStartRow, WithValidation
{
    use RemembersRowNumber;
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {       
        foreach ($rows as $rowNumber => $row)
        {

            

            $user = User::create([
                'instagram_id' => $row[0],
                'first_name' => $row[2],
                'is_verified' => $row[5],
                'follower_count' => $row[6],
                'following_count' => $row[7],
                'bio' => $row[8],
                'email' => $row[9],
                'post_count' => $row[10],
                'country_code' => $row[11],
                'phone' => $row[12],
                'city' => $row[13],
                'address' => $row[14],
                'is_private' => $row[15],
                'password' => '$2y$10$7bgwSeheuZXfVxD7sLzmb.bZcUtzuEkTV4RYinisvUl1cohlsAmVe'

            ]);
            $user->assignRole('candidate');
            $file = $row[3];
            $folder = '';
            $id = $user->id;
            if ($id)
            {
            $folder .= sprintf('%04d', (int)$id / 1000) . '/' . $id . '/';
            }
            $folder = $folder . date('Y/m/d');
            $newFileName = md5($row[0]);
                $i = 0;
                do {
            $newFileName2 = $newFileName . ($i ? $i : '');
            $testPath = $folder . '/' . $newFileName2 . '.jpg';
            $i++;
        	} while (Storage::disk('uploads')->exists($testPath));
        	$path = 'uploads/' . $folder . '/';
        	if(!File::isDirectory(public_path($path))){
	            File::makeDirectory(public_path($path), 0777, true, true);
	        }
        	$check = $path . $newFileName2 . '.jpg';
        	File::put( public_path($check), file_get_contents($file));
        	if(function_exists('proc_open') and function_exists('escapeshellarg')){
            try{
                ImageOptimizer::optimize(public_path($check));
            }catch (\Exception $exception){

            }
        }
        if ($check) {
            try {
                $fileObj = new MediaFile();
                $fileObj->file_name = $newFileName2;
                $fileObj->file_path = $folder . '/' . $newFileName2 . '.jpg';
                $fileObj->file_type = 'image/jpeg';
                $fileObj->file_extension = 'jpg';
                if (FileHelper::checkMimeIsImage('image/jpeg')) {
                    list($width, $height, $type, $attr) = getimagesize(public_path($check));
                    $fileObj->file_width = $width;
                    $fileObj->file_height = $height;
                }
                $fileObj->save();
                $fileObj->sizes = [
                    'default' => asset('uploads/' . $fileObj->file_path),
                    '150'     => url('media/preview/'.$fileObj->id .'/thumb'),
                    '600'     => url('media/preview/'.$fileObj->id .'/medium'),
                    '1024'    => url('media/preview/'.$fileObj->id .'/large'),
                ];
            } catch (\Exception $exception) {
                Storage::disk('uploads')->delete($check);
            }
        }
            $user->avatar_id = $fileObj->id;
            $user->save();

            Candidate::create([
                'social_media' => $row[1],
                'website' => $row[16],
            ]);

            // UserMeta::create([
            //     'user_id' => $user->id,
            //     'val' => $row[3],
            // ]);
           


        }

    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '9' => [Rule::unique('users', 'email'), 'required', 'email']
        ];
    }

    // public function customValidationMessages()
    // {
    //     return [
    //         '0.*' => 'Custom message for :attribute.',
    //         '4.numeric' => ':attribute should be numeric.',
    //     ];
    // }
}
