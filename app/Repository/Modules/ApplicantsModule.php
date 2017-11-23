<?php
namespace NTI\Repository\Modules;

use NTI\Models\Applicant;
use NTI\Models\Transaction;
use NTI\Repository\Services\NTI as NTIService;
use NTI\Repository\Libraries\HTMLPDF as PDFService;

class ApplicantsModule
{
    public function __construct()
    {
    }

    public static function getFeeDefParams($applicant, $type)
    {
        if($type == 'tuition')
            $params['fee_id'] = NTIService::getFeeId()->tuitionFee;
        else 
            $params['fee_id'] = NTIService::getFeeId()->applicationFee;

        $params['specialization_id'] = @$applicant->first_choice;
        $params['level'] = @$applicant->entry_level;
        $params['semester'] = 1;
        if($type == 'tuition')
        {
            $params['category'] = $applicant->entry_level > 1 ? 'DE' : 'default';
        } else
        {
            $params['category'] = 'default';
        }
        
        return $params;
    }

    public static function getData($applicant)
    {
        $data['programme'] = NTIService::getInfo('programmes', 'id', $applicant->programme_id);
        $data['first_choice'] = NTIService::getInfo('specializations', 'id', $applicant->first_choice);
        $data['second_choice'] = NTIService::getInfo('specializations', 'id', $applicant->second_choice);
        $data['study_center'] = NTIService::getInfo('study_centers', 'id', $applicant->study_center_id);
        $data['soo'] = NTIService::getInfo('states', 'id', $applicant->soo);
        $data['soo_lga'] = NTIService::getInfo('lga', 'id', $applicant->soo_lga);
        $data['sor'] = NTIService::getInfo('states', 'id', $applicant->sor);
        $data['sor_lga'] = NTIService::getInfo('lga', 'id', $applicant->sor_lga);
        $data['academicSessionInfo'] = NTIService::getCurrentAcademicSessionInfo();

        return $data;
    }

    public static function getTransaction($applicant, $type, $academicSemesterId)
    {
        /*
        * This function fetches and returns the correct transaction and fee table for an applicant
        * based on current academic semester, selected programme and specialization 
        * as well as whether it is tuition or application form fees
        */
        $params = self::getFeeDefParams($applicant, $type);
        $fee_def = NTIService::getFeeDefinition($params['fee_id'], 
            @$applicant->programme_id, $params['specialization_id'], 
            @$params['level'], $params['semester'], $params['category']);
        $fee_type = NTIService::getInfo('fee_types', 'id', $params['fee_id']);
        $transaction = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => $params['fee_id'],// make sure its the transaction for admission or tuition fee
            //'fee_table_id' => $fee_table->id, // make sure the transaction matches the selected programme
            'semester_id' => $academicSemesterId // for current semester
        ])->latest()->first();
        return [
            'transaction' => $transaction,
            'fee_def' => $fee_def,
            'fee_type' => $fee_type,
        ];
    }

    public static function setMax($collection, $max)
    {
        /*
        * This function reduces the size of the array or collection to at most $max
        * anything above max is deleted.
        * Used to keep the number of records on the database for a particular applicant model eg education
        * background or work experience to a minimum so we don't have more records than we need.
        */

        if(count($collection) > $max)
        {
            $i = 1;
            foreach($collection as $col)
            {
                if($i > 2)
                {
                    $col->delete();
                }
                $i++;
            }
        }
        return true;
    }

    public static function echoPDF($resp)
    {
        //use this code to avoid storing a file on the server and just outputting the pdf file to browser
        $length = strlen($resp);
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');//<<<<
        header('Content-Disposition: attachment; mynti-receipt.pdf');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $length);
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');

        echo $resp;
        exit;
    }

    public static function uploadFile()
    {
        //'@' . asset('assets/img/png/logo.png');
       
        $file_name = realpath("assets/img/png/Polyangle.png"); 
        
        $resp = PDFService::uploadAsset($file_name);
        dd($resp);
    }

    public function testParams($type, $programme_id)
    {
        $applicant = json_decode(json_encode([
            'programme_id' => $programme_id,
            'first_choice' => 0,
            'entry_level' => 1,
        ]));
        $params = self::getFeeDefParams($applicant, $type);
        $fee_table = NTIService::getFeeDefinition($params['fee_id'], 
        $applicant->programme_id, $params['specialization_id'], 
        $params['level'], $params['semester'], $params['category']);
        dd($fee_table);
    }
    public static function redirectTo()
    {
        $applicant = Applicant::where('user_id', Auth::id())->first();

        switch ($applicant->application_step) {
            case 1:
            {
                return '/applicants/programme';
                break;
            }
            case 2:
            {
                return '/applicants/payments';
                break;
            }
            case 3:
            {
                return '/applicants/verify';
                break;
            }
            case 4:
            {
                return '/applicants/biodata';
                break;
            }
            case 5:
            {
                return '/applicants/certificates';
                break;
            }
            case 6:
            {
                return '/applicants/experience';
                break;
            }
            case 7:
            {
                return '/applicants/uploads';
                break;
            }
            default:
            {
                return '/applicants/review';
                break;
            }
        }
    }

    public function handleRedirect($id)
    {
        $applicant = Applicant::where('user_id', $id)->first();

        switch ($applicant->application_step) {
            case 1:
            {
                return redirect()->route('programme');
                break;
            }
            case 2:
            {
                return redirect()->route('payments');
                break;
            }
            case 3:
            {
                return redirect()->route('verify');
                break;
            }
            case 4:
            {
                return redirect()->route('biodata');
                break;
            }
            case 5:
            {
                return redirect()->route('certificates');
                break;
            }
            case 6:
            {
                return redirect()->route('experience');
                break;
            }
            case 7:
            {
                return redirect()->route('uploads');
                break;
            }
            default:
            {
                return redirect()->route('review');
                break;
            }
        }
    }
}
