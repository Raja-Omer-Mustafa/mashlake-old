<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\User;
use Modules\Candidate\Models\Candidate;
use App\UserMeta;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\User\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class CandidateImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
         //   $validatorData= Validator::make($rows->toArray(), [
         //    '9' => [Rule::unique('users', 'email'), 'required', 'email'],
         // ]);
         //   if($validatorData->fails())
         //   {
         //    return redirect()->route('admin.index')->with('error','email already exist!');
         //   }


            $user = User::create([
                'instagram_id' => $row[0],
                'name' => $row[2],
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
                'role_id'  => 2,
                'password' => '$2y$10$7bgwSeheuZXfVxD7sLzmb.bZcUtzuEkTV4RYinisvUl1cohlsAmVe',

            ]);

            Candidate::create([

                'social_media' => $row[1],
                'website' => $row[16],
            ]);

            UserMeta::create([
                'user_id' => $user->id,
                'val' => $row[3],
            ]);

        }

    }
    public function startRow(): int
    {
        return 2;
    }
     
    
}
