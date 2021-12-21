<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\User;
use Illuminate\Http\Request;
use Modules\Candidate\Models\Candidate;
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
                'password' => '$2y$10$7bgwSeheuZXfVxD7sLzmb.bZcUtzuEkTV4RYinisvUl1cohlsAmVe',

            ]);
            $user->assignRole('candidate');

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
